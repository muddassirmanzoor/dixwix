<?php

namespace App\Http\Controllers;

use App\Models\StripeInvoiceSchedule;
use App\Models\StripeInvoiceScheduleLog;
use Illuminate\Http\Request;

class StripeInvoiceScheduleController extends Controller
{
    private function ensureSuperAdmin()
    {
        // Super admin only (this app often uses user id 1 as super admin)
        if (auth()->id() !== 1) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $this->ensureSuperAdmin();

        $data = [
            'title' => 'Stripe Invoice Scheduler',
            'template' => 'admin.settings.stripe_invoice_scheduler',
        ];

        // Show only parent schedules (root recurring schedules).
        // Child "run schedules" are visible from the parent detail page.
        $query = StripeInvoiceSchedule::withTrashed()
            ->whereNull('parent_schedule_id')
            ->with(['activeRun', 'latestLog'])
            ->orderByDesc('id');
        
        // Filter: show deleted or active
        if ($request->has('show_deleted') && $request->show_deleted == '1') {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }
        
        // Show recent parent schedules
        $schedules = $query->limit(100)->get();

        return view('with_login_common', compact('data', 'schedules'));
    }

    public function show($id)
    {
        $this->ensureSuperAdmin();

        $schedule = StripeInvoiceSchedule::withTrashed()
            ->with(['items.user', 'parent', 'runs', 'activeRun', 'latestLog'])
            ->findOrFail($id);

        $data = [
            'title' => "Schedule #{$schedule->id} Details",
            'template' => 'admin.settings.stripe_invoice_scheduler_show',
        ];

        // Check if this is a parent schedule (recurring) or a run schedule (single execution)
        $isParentSchedule = is_null($schedule->parent_schedule_id);
        $activeSchedule = null;
        
        if ($isParentSchedule) {
            // Parent schedule: show preview for next run (if active and not completed)
            // Don't show logs - instead show the run schedules that were created
            $logs = collect(); // Don't show old logs
            $activeSchedule = ($schedule->is_active && $schedule->status === 'active') ? $schedule : ($schedule->activeRun ?? null);
            // Only show preview if schedule is active and not completed/failed
            $previewData = ($activeSchedule && !in_array($activeSchedule->status, ['completed', 'failed'])) ? $this->getNextRunPreview($activeSchedule) : null;
            
            // Get all run schedules (child schedules) for this parent
            $runSchedules = $schedule->runs()->orderByDesc('run_at')->get();
        } else {
            // Run schedule: show preview ONLY if pending/running/active, NOT if completed/failed
            $logs = collect(); // No logs for individual runs
            $runSchedules = collect(); // No child runs for run schedules
            $previewData = null;
            
            // Only show preview for schedules that haven't completed yet
            if (in_array($schedule->status, ['pending', 'running', 'active']) && !in_array($schedule->status, ['completed', 'failed'])) {
                $previewData = $this->getSchedulePreview($schedule);
            }
        }

        // Get point entries for this schedule (for both parent and run schedules)
        $entriesByUser = collect();
        
        // For completed schedules, get entries that were invoiced
        if (in_array($schedule->status, ['completed', 'failed'])) {
            // Try to get entries linked to this schedule
            $entries = \App\Models\Point::where('stripe_invoice_schedule_id', $schedule->id)
                ->where('type', 'debit')
                ->where('description', 'like', 'Charges paid for rental%')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // If no entries found and this is a parent schedule, check run schedules
            if ($entries->isEmpty() && $isParentSchedule) {
                $firstRun = $schedule->runs()->orderBy('run_at')->first();
                if ($firstRun) {
                    $entries = \App\Models\Point::where('stripe_invoice_schedule_id', $firstRun->id)
                        ->where('type', 'debit')
                        ->where('description', 'like', 'Charges paid for rental%')
                        ->with('user')
                        ->orderBy('created_at', 'desc')
                        ->get();
                }
            }
            
            $entriesByUser = $entries->groupBy('user_id');
            
            // Get admin commission points for this schedule
            $adminCommissions = \App\Models\Point::where('user_id', 1) // Admin user ID
                ->where('type', 'credit')
                ->where('description', 'like', '%Rental commission%')
                ->where('description', 'like', '%Schedule #' . $schedule->id . '%')
                ->with('throughUser')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // If no commissions found with exact schedule ID, try to find by invoice IDs from schedule items
            if ($adminCommissions->isEmpty() && $schedule->items->count() > 0) {
                $invoiceIds = $schedule->items->pluck('stripe_invoice_id')->filter();
                if ($invoiceIds->isNotEmpty()) {
                    $adminCommissions = \App\Models\Point::where('user_id', 1)
                        ->where('type', 'credit')
                        ->where('description', 'like', '%Rental commission%')
                        ->where(function($q) use ($invoiceIds) {
                            foreach ($invoiceIds as $invoiceId) {
                                $q->orWhere('description', 'like', '%Invoice: ' . $invoiceId . '%');
                            }
                        })
                        ->with('throughUser')
                        ->orderBy('created_at', 'desc')
                        ->get();
                }
            }
        } elseif (!$isParentSchedule && $schedule->range_from && $schedule->range_to) {
            // For pending/running schedules, get entries that will be processed
            $entries = \App\Models\Point::where('stripe_invoice_schedule_id', $schedule->id)
                ->where('type', 'debit')
                ->where('description', 'like', 'Charges paid for rental%')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $entriesByUser = $entries->groupBy('user_id');
            $adminCommissions = collect(); // No admin commissions for pending schedules
        } else {
            $adminCommissions = collect();
        }

        // Calculate total admin commission for this schedule
        $totalAdminCommission = $adminCommissions->sum('amount');
        $adminCommissionCount = $adminCommissions->count();

        return view('with_login_common', compact('data', 'schedule', 'logs', 'previewData', 'entriesByUser', 'isParentSchedule', 'runSchedules', 'activeSchedule', 'adminCommissions', 'totalAdminCommission', 'adminCommissionCount'));
    }

    private function getSchedulePreview(StripeInvoiceSchedule $schedule)
    {
        if (!$schedule->range_from || !$schedule->range_to) {
            return null;
        }

        $rangeFrom = $schedule->range_from;
        $rangeTo = $schedule->range_to;

        // Query exactly like the cron does
        $rows = \App\Models\Point::query()
            ->select([
                'user_id',
                \Illuminate\Support\Facades\DB::raw('SUM(amount) as subtotal_amount'),
                \Illuminate\Support\Facades\DB::raw('SUM(system_fee) as commission_amount'),
            ])
            ->where('type', 'debit')
            ->whereNotNull('user_id')
            ->whereBetween('created_at', [$rangeFrom, $rangeTo])
            ->where('description', 'like', 'Charges paid for rental%')
            ->whereNull('stripe_invoice_schedule_id')
            ->groupBy('user_id')
            ->get();

        $pointEntries = \App\Models\Point::query()
            ->where('type', 'debit')
            ->whereNotNull('user_id')
            ->whereBetween('created_at', [$rangeFrom, $rangeTo])
            ->where('description', 'like', 'Charges paid for rental%')
            ->whereNull('stripe_invoice_schedule_id')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $commissionRecord = \App\Models\Commission::first();
        $commissionPercent = $commissionRecord ? (float) $commissionRecord->commission : 5;

        $userTotals = [];
        if (!$rows->isEmpty() && !$pointEntries->isEmpty()) {
            foreach ($rows as $row) {
                $userId = (int) $row->user_id;
                $subtotal = (float) $row->subtotal_amount;
                $storedCommission = (float) ($row->commission_amount ?? 0);
                $commission = $storedCommission > 0 ? $storedCommission : (($subtotal * $commissionPercent) / 100);
                // Commission is DEDUCTED from renter's charge. Amount charged = rental - commission
                $total = $subtotal - $commission;

                $user = \App\Models\User::find($userId);
                
                $userTotals[$userId] = [
                    'user' => $user,
                    'subtotal' => $subtotal,
                    'commission' => $commission,
                    'total' => $total,
                    'entry_count' => $pointEntries->where('user_id', $userId)->count(),
                ];
            }
        }

        // Return preview data even if empty, so the preview box shows with "no entries" message
        return [
            'run_at' => $schedule->run_at,
            'next_run_at' => $schedule->run_at ?? $schedule->next_run_at ?? now(),
            'range_from' => $rangeFrom,
            'range_to' => $rangeTo,
            'recurring_days' => $schedule->recurring_days ?? 0,
            'user_totals' => $userTotals,
            'point_entries' => $pointEntries,
            'total_users' => count($userTotals),
            'total_entries' => $pointEntries->count(),
            'total_rental' => $pointEntries->sum('amount'),
            'total_commission' => $pointEntries->sum('system_fee'),
        ];
    }

    public function store(Request $request)
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'recurring_days' => 'required|integer|min:1|max:31',
        ]);

        $recurringDays = (int) $validated['recurring_days'];
        $now = now();
        $nextRunAt = $now->copy()->addDays($recurringDays);

        StripeInvoiceSchedule::create([
            'created_by' => auth()->id(),
            'recurring_days' => $recurringDays,
            'run_at' => $nextRunAt, // first run date
            'next_run_at' => $nextRunAt,
            'last_run_at' => null,
            'range_from' => null, // Calculated dynamically in cron
            'range_to' => null,   // Calculated dynamically in cron
            'status' => 'active', // recurring schedules are active, not pending
            'is_active' => true,
            'stripe_behavior' => 'finalize_and_send',
        ]);

        return back()->with('success', "Recurring schedule created: will run every {$recurringDays} days. First run: {$nextRunAt->format('Y-m-d H:i:s')}");
    }

    public function cancel($id)
    {
        $this->ensureSuperAdmin();

        $schedule = StripeInvoiceSchedule::findOrFail($id);
        if (!$schedule->is_active || $schedule->status !== 'active') {
            return back()->with('error', 'Only active recurring schedules can be deactivated.');
        }

        $schedule->update(['is_active' => false, 'status' => 'cancelled']);
        return back()->with('success', 'Recurring schedule deactivated.');
    }

    public function destroy($id)
    {
        $this->ensureSuperAdmin();

        $schedule = StripeInvoiceSchedule::withTrashed()->findOrFail($id);
        
        // If already soft deleted, force delete
        if ($schedule->trashed()) {
            $schedule->forceDelete();
            return back()->with('success', 'Schedule permanently deleted.');
        }
        
        // If deleting a parent schedule, also stop any active child run schedules.
        // Soft deletes do not cascade, so without this orphaned active children can keep running via cron.
        if (is_null($schedule->parent_schedule_id)) {
            StripeInvoiceSchedule::where('parent_schedule_id', $schedule->id)
                ->where('is_active', true)
                ->where('status', 'active')
                ->update([
                    'is_active' => false,
                    'status' => 'cancelled',
                    'error' => 'Cancelled because parent schedule was deleted.',
                ]);
        }

        // If active, deactivate first, then soft delete
        if ($schedule->is_active && $schedule->status === 'active') {
            $schedule->update(['is_active' => false, 'status' => 'cancelled']);
        }

        $schedule->delete(); // Soft delete
        return back()->with('success', 'Schedule deleted (soft delete). You can restore it if needed.');
    }

    public function restore($id)
    {
        $this->ensureSuperAdmin();

        $schedule = StripeInvoiceSchedule::withTrashed()->findOrFail($id);
        
        if (!$schedule->trashed()) {
            return back()->with('error', 'Schedule is not deleted.');
        }

        $schedule->restore();
        return back()->with('success', 'Schedule restored successfully.');
    }

    public function logs($id)
    {
        $this->ensureSuperAdmin();

        $schedule = StripeInvoiceSchedule::withTrashed()->findOrFail($id);
        
        $logs = StripeInvoiceScheduleLog::where('schedule_id', $schedule->id)
            ->orderByDesc('run_at')
            ->paginate(50);

        $data = [
            'title' => "Schedule #{$schedule->id} - Run Logs",
            'template' => 'admin.settings.stripe_invoice_scheduler_logs',
        ];

        return view('with_login_common', compact('data', 'schedule', 'logs'));
    }

    private function getNextRunPreview(StripeInvoiceSchedule $schedule)
    {
        if (!$schedule->is_active || $schedule->status !== 'active' || !$schedule->next_run_at) {
            return null;
        }

        $now = now();
        $recurringDays = (int) ($schedule->recurring_days ?? 30);
        
        if ($recurringDays < 1 || $recurringDays > 31) {
            return null;
        }

        // Calculate the date range that will be used (same logic as cron)
        $rangeTo = $now->copy();
        $rangeFrom = $now->copy()->subDays($recurringDays);

        // Query exactly like the cron does
        $rows = \App\Models\Point::query()
            ->select([
                'user_id',
                \Illuminate\Support\Facades\DB::raw('SUM(amount) as subtotal_amount'),
                \Illuminate\Support\Facades\DB::raw('SUM(system_fee) as commission_amount'),
            ])
            ->where('type', 'debit')
            ->whereNotNull('user_id')
            ->whereBetween('created_at', [$rangeFrom, $rangeTo])
            ->where('description', 'like', 'Charges paid for rental%')
            ->whereNull('stripe_invoice_schedule_id') // Only uninvoiced entries
            ->groupBy('user_id')
            ->get();

        // Get detailed point entries for preview
        $pointEntries = \App\Models\Point::query()
            ->where('type', 'debit')
            ->whereNotNull('user_id')
            ->whereBetween('created_at', [$rangeFrom, $rangeTo])
            ->where('description', 'like', 'Charges paid for rental%')
            ->whereNull('stripe_invoice_schedule_id')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get commission percentage
        $commissionRecord = \App\Models\Commission::first();
        $commissionPercent = $commissionRecord ? (float) $commissionRecord->commission : 5;

        // If no uninvoiced entries found, don't show preview (cron already processed everything)
        if ($rows->isEmpty() || $pointEntries->isEmpty()) {
            return null;
        }

        // Calculate totals per user
        $userTotals = [];
        foreach ($rows as $row) {
            $userId = (int) $row->user_id;
            $subtotal = (float) $row->subtotal_amount;
            $storedCommission = (float) ($row->commission_amount ?? 0);
            $commission = $storedCommission > 0 ? $storedCommission : (($subtotal * $commissionPercent) / 100);
            $total = $subtotal + $commission;

            $user = \App\Models\User::find($userId);
            
            $userTotals[$userId] = [
                'user' => $user,
                'subtotal' => $subtotal,
                'commission' => $commission,
                'total' => $total,
                'entry_count' => $pointEntries->where('user_id', $userId)->count(),
            ];
        }

        return [
            'next_run_at' => $schedule->next_run_at,
            'range_from' => $rangeFrom,
            'range_to' => $rangeTo,
            'recurring_days' => $recurringDays,
            'user_totals' => $userTotals,
            'point_entries' => $pointEntries,
            'total_users' => count($userTotals),
            'total_entries' => $pointEntries->count(),
            'total_rental' => $pointEntries->sum('amount'),
            'total_commission' => $pointEntries->sum('system_fee'),
        ];
    }
}


