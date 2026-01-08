<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable; // ✅ ADD THIS
use Illuminate\Support\Str;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;

class UpdateStripeCashBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels; // ✅ ADD Dispatchable here

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        $formattedCashBalance = number_format($this->user->reward_balance / 100, 2);
        $customerId = (string) $this->user->stripe_customer_id;

        if (Str::startsWith($customerId, 'cus_')) {
            try {
                $stripe = new StripeClient(config('services.stripe.secret'));

                $stripe->customers->update($customerId, [
                    'metadata' => [
                        'cash_balance' => 'USD ' . $formattedCashBalance,
                    ],
                ]);
                Log::info('Stripe updated successfully for user ' . $this->user->id);
                
                $this->user->touch();
            } catch (\Exception $e) {
                Log::error('Stripe update failed for user ' . $this->user->id . ': ' . $e->getMessage());
            }
        } else {
            Log::warning('Invalid Stripe customer ID format for user ' . $this->user->id);
        }
    }
}
