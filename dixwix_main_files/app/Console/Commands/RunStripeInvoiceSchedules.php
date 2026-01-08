<?php

namespace App\Console\Commands;

use App\Models\Point;
use App\Models\StripeInvoiceSchedule;
use App\Models\StripeInvoiceScheduleItem;
use App\Models\StripeInvoiceScheduleLog;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RunStripeInvoiceSchedules extends Command
{
    protected $signature = 'app:run-stripe-invoice-schedules';
    protected $description = 'Process due Stripe invoice schedules and send consolidated rental invoices per user.';

    public function handle(StripeService $stripeService): int
    {
        $lock = Cache::lock('stripe_invoice_schedules:lock', 55);
        if (! $lock->get()) {
            $this->info('Another run is in progress.');
            return self::SUCCESS;
        }

        try {
            $now = now();
            
            // Find active schedules that are due to run (can be parent or run schedules)
            $schedules = StripeInvoiceSchedule::where('is_active', true)
                ->where('status', 'active')
                ->where('next_run_at', '<=', $now)
                ->orderBy('next_run_at')
                ->limit(5)
                ->get();

            foreach ($schedules as $schedule) {
                $this->processRecurringSchedule($schedule, $stripeService, $now);
            }

            return self::SUCCESS;
        } finally {
            optional($lock)->release();
        }
    }

    protected function processRecurringSchedule(StripeInvoiceSchedule $schedule, StripeService $stripeService, $now): void
    {
        // Get recurring days from schedule (could be parent or run schedule)
        $recurringDays = (int) ($schedule->recurring_days ?? 30);
        $isParent = is_null($schedule->parent_schedule_id);
        
        if ($isParent) {
            $this->info("Processing parent schedule #{$schedule->id} (recurring every {$recurringDays} days)");
        } else {
            $this->info("Processing run schedule #{$schedule->id} (recurring every {$recurringDays} days)");
        }
        
        $schedule->update(['status' => 'running', 'error' => null]);

        if ($recurringDays < 1 || $recurringDays > 31) {
            $schedule->update(['status' => 'failed', 'error' => 'Invalid recurring_days (must be 1-31)']);
            $this->error("Invalid recurring_days: {$recurringDays}");
            
            // Log the failure
            StripeInvoiceScheduleLog::create([
                'schedule_id' => $isParent ? $schedule->id : $schedule->parent_schedule_id,
                'status' => 'failed',
                'run_at' => $now,
                'completed_at' => now(),
                'recurring_days' => $recurringDays,
                'error' => 'Invalid recurring_days (must be 1-31)',
            ]);
            return;
        }

        // Use schedule's range if it's a run schedule, otherwise calculate
        if ($schedule->range_from && $schedule->range_to) {
            $rangeFrom = $schedule->range_from;
            $rangeTo = $schedule->range_to;
        } else {
            $rangeTo = $now->copy();
            $rangeFrom = $now->copy()->subDays($recurringDays);
        }
        
        $this->info("Date range: {$rangeFrom->format('Y-m-d')} to {$rangeTo->format('Y-m-d')}");

        // This schedule IS the run schedule (no need to create another one)
        $runSchedule = $schedule;
        
        // Get parent schedule ID for logging (if this is a run schedule, use its parent)
        $parentScheduleId = $isParent ? $schedule->id : $schedule->parent_schedule_id;

        // Create log entry at start of processing (for backward compatibility)
        $log = StripeInvoiceScheduleLog::create([
            'schedule_id' => $parentScheduleId ?? $schedule->id,
            'status' => 'running',
            'run_at' => $now,
            'recurring_days' => $recurringDays,
            'range_from' => $rangeFrom,
            'range_to' => $rangeTo,
        ]);

        try {
            // Aggregate renter-side rental charges from points table
            // We rely on existing description string set in BookController:
            // 'Charges paid for rental {ITEM_ID}'
            $rows = Point::query()
                ->select([
                    'user_id',
                    DB::raw('SUM(amount) as subtotal_amount'),
                    DB::raw('SUM(system_fee) as commission_amount'),
                ])
                ->where('type', 'debit')
                ->whereNotNull('user_id')
                ->whereBetween('created_at', [$rangeFrom, $rangeTo])
                ->where('description', 'like', 'Charges paid for rental%')
                ->whereNull('stripe_invoice_schedule_id') // prevent double charging on overlapping schedules
                ->groupBy('user_id')
                ->get();

            $this->info("Found {$rows->count()} users with rental charges in date range");
            
            $processed = 0;
            $sent = 0;
            $skipped = 0;
            $failed = 0;

            // Get site commission percentage from DB (required)
            $commissionRecord = \App\Models\Commission::first();
            if (!$commissionRecord || !$commissionRecord->commission) {
                $schedule->update(['status' => 'failed', 'error' => 'Commission not set in database. Please set it in Settings → Site Rental Product Commission.']);
                $this->error('Commission not set in database');
                return;
            }
            $commissionPercent = (float) $commissionRecord->commission;
            $this->info("Using commission: {$commissionPercent}%");
            
            foreach ($rows as $row) {
                $processed++;
                $userId = (int) $row->user_id;
                $subtotal = (float) $row->subtotal_amount;
                
                // Use stored system_fee (commission) if available (already calculated with DB commission %)
                // Otherwise recalculate using current DB commission percentage
                $storedCommission = (float) ($row->commission_amount ?? 0);
                if ($storedCommission > 0) {
                    $commission = $storedCommission; // Already calculated with commission % when rental was created
                } else {
                    // Fallback: calculate commission using DB percentage if system_fee wasn't stored
                    $commission = ($subtotal * $commissionPercent) / 100;
                }
                
                $total = $subtotal + $commission;

                $item = StripeInvoiceScheduleItem::firstOrCreate(
                    ['schedule_id' => $runSchedule->id, 'user_id' => $userId], // Use runSchedule, not parent schedule
                    [
                        'log_id' => $log->id ?? null,
                        'subtotal_amount' => $subtotal,
                        'commission_amount' => $commission,
                        'total_amount' => $total,
                        'status' => 'pending',
                    ]
                );
                
                // Update log_id if item already existed
                if (!$item->log_id && isset($log)) {
                    $item->update(['log_id' => $log->id]);
                }

                // If already completed with a stripe invoice id, skip (idempotent)
                if ($item->stripe_invoice_id && $item->status === 'completed') {
                    $skipped++;
                    continue;
                }

                $user = User::find($userId);
                if (! $user || ! $user->stripe_customer_id) {
                    $item->update([
                        'status' => 'skipped',
                        'error' => 'Missing user or stripe_customer_id',
                    ]);
                    $skipped++;
                    continue;
                }

                $item->update([
                    'log_id' => $log->id ?? null,
                    'stripe_customer_id' => $user->stripe_customer_id,
                    'subtotal_amount' => $subtotal,
                    'commission_amount' => $commission,
                    'total_amount' => $total,
                    'status' => 'processing',
                    'error' => null,
                ]);

                try {
                    // Fetch point IDs to mark as invoiced ONLY after successful Stripe invoice creation
                    $pointIds = Point::query()
                        ->where('user_id', $userId)
                        ->where('type', 'debit')
                        ->whereBetween('created_at', [$rangeFrom, $rangeTo])
                        ->where('description', 'like', 'Charges paid for rental%')
                        ->whereNull('stripe_invoice_schedule_id')
                        ->pluck('id');

                    if ($pointIds->isEmpty()) {
                        $item->update([
                            'status' => 'skipped',
                            'error' => 'No uninvoiced rental points found (possibly already invoiced by another schedule)',
                        ]);
                        $skipped++;
                        continue;
                    }

                    $invoice = $stripeService->createFinalizeAndSendInvoice([
                        'customer_id' => $user->stripe_customer_id,
                        'currency' => 'usd',
                        'rental_amount' => $subtotal,
                        'commission_amount' => $commission,
                        'description' => 'Scheduled rental invoice',
                        'metadata' => [
                            'schedule_id' => (string) $schedule->id,
                            'user_id' => (string) $user->id,
                            'range_from' => $rangeFrom->toIso8601String(),
                            'range_to' => $rangeTo->toIso8601String(),
                        ],
                    ]);

                    // Mark included point rows as invoiced (idempotency / no double charge)
                    Point::whereIn('id', $pointIds)->update([
                        'stripe_invoice_schedule_id' => $runSchedule->id, // Use runSchedule ID
                        'stripe_invoiced_at' => now(),
                    ]);

                    $item->update([
                        'stripe_invoice_id' => $invoice->id ?? null,
                        'status' => 'completed',
                    ]);
                    $sent++;
                    $this->info("✓ Invoice sent for user #{$userId}: Rental=\$" . number_format($subtotal, 2) . " + Commission=\$" . number_format($commission, 2) . " = Total=\$" . number_format($total, 2) . " (Stripe ID: {$invoice->id})");
                } catch (\Throwable $e) {
                    Log::error('Stripe invoice schedule item failed', [
                        'schedule_id' => $schedule->id,
                        'user_id' => $userId,
                        'error' => $e->getMessage(),
                    ]);
                    $item->update([
                        'status' => 'failed',
                        'error' => $e->getMessage(),
                    ]);
                    $failed++;
                }
            }

            // Update the run schedule: mark as completed, save results, and create NEXT run schedule
            $resultSummary = [
                'processed_users' => $processed,
                'sent' => $sent,
                'skipped' => $skipped,
                'failed' => $failed,
            ];
            
            // Mark THIS schedule as completed (it has run)
            $runSchedule->update([
                'status' => 'completed',
                'is_active' => false, // No longer active
                'result_summary' => $resultSummary,
            ]);

            // Get the original parent schedule ID (for linking)
            $originalParentId = $isParent ? $schedule->id : $schedule->parent_schedule_id;
            $originalParent = $originalParentId ? StripeInvoiceSchedule::find($originalParentId) : null;

            // Create NEXT run schedule (this becomes the active one for next run)
            $nextRunAt = $now->copy()->addDays($recurringDays);
            $nextRangeTo = $nextRunAt->copy();
            $nextRangeFrom = $nextRunAt->copy()->subDays($recurringDays);
            
            $nextRunSchedule = StripeInvoiceSchedule::create([
                'parent_schedule_id' => $originalParentId, // Link to original parent (or NULL if no parent)
                'created_by' => $schedule->created_by,
                'recurring_days' => $recurringDays,
                'run_at' => $nextRunAt, // When it will run
                'next_run_at' => $nextRunAt, // Same as run_at for scheduling
                'range_from' => $nextRangeFrom,
                'range_to' => $nextRangeTo,
                'status' => 'active', // Active for next run
                'is_active' => true, // Active for cron to pick up
                'stripe_behavior' => $schedule->stripe_behavior ?? 'finalize_and_send',
            ]);

            // Update log entry with completion
            if (isset($log)) {
                $log->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                    'result_summary' => $resultSummary,
                ]);
            }
            
            // If this was a parent schedule (first run), mark it as completed
            if ($isParent) {
                $schedule->update([
                    'status' => 'completed',
                    'is_active' => false,
                    'last_run_at' => $now,
                ]);
            }
            
            $this->info("Schedule #{$runSchedule->id} completed: processed={$processed}, sent={$sent}, skipped={$skipped}, failed={$failed}");
            $this->info("Next run schedule #{$nextRunSchedule->id} created - will run on: {$nextRunAt->format('Y-m-d H:i:s')}");
        } catch (\Throwable $e) {
            $this->error("Run schedule #{$runSchedule->id} failed: {$e->getMessage()}");
            
            // Update run schedule with failure
            if (isset($runSchedule)) {
                $runSchedule->update([
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                ]);
            }
            
            // Keep parent schedule active (don't mark as failed, just log the error)
            $schedule->update(['error' => "Last run failed: {$e->getMessage()}"]);
            
            // Update log entry with failure
            if (isset($log)) {
                $log->update([
                    'status' => 'failed',
                    'completed_at' => now(),
                    'error' => $e->getMessage(),
                ]);
            } else {
                // Create log entry if it wasn't created earlier
                StripeInvoiceScheduleLog::create([
                    'schedule_id' => $schedule->id,
                    'status' => 'failed',
                    'run_at' => $now,
                    'completed_at' => now(),
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}


