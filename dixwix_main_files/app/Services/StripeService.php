<?php

namespace App\Services;

use Stripe\Refund;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Transfer;
use Stripe\Charge;
use Stripe\CustomerBalanceTransaction;
use Stripe\Invoice;
use Stripe\InvoiceItem;

use Stripe\Exception\ApiErrorException;
use App\Models\User;
use Stripe\PaymentMethod;
use Illuminate\Support\Str;
use function Carbon\settings;

//use App\Models\Transaction;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCustomer(User $user)
    {
        try {
             
            $customer = Customer::create([
                'email' => $user->email,
                'name' => $user->name,
                
            ]);

            $user->stripe_customer_id = $customer->id;
            $user->save();

            return $customer;
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function createPaymentIntentForPurchase($amount, $currency = 'usd', $customerId = null, $description = null, $metadata= [])
    {
        try {

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => $currency,
                'customer' => $customerId,
                'description' => $description,
                'metadata' => $metadata,
            ]);

            return $paymentIntent;
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function createPaymentIntentForConfirm($amount, $currency = 'usd', $customerId = null, $paymentMethodId = null, $description = null, $metadata = [])
    {
        try {
            // Log the parameters being sent to PaymentIntent::create
            \Log::info('Creating Payment Intent with parameters:', [
                'amount' => $amount * 100,
                'currency' => $currency,
                'customer' => $customerId,
                'payment_method' => $paymentMethodId,
                'description' => $description,
                'metadata' => $metadata,
            ]);

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => $currency,
                'customer' => $customerId,
                'payment_method' => $paymentMethodId,
                'confirm' => true,
                'description' => $description,
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
            ]);

            return $paymentIntent;
        } catch (ApiErrorException $e) {
            // Log the error message and any relevant details
            \Log::error('Payment Intent creation failed: ' . $e->getMessage(), [
                'amount' => $amount * 100,
                'currency' => $currency,
                'customer' => $customerId,
                'payment_method' => $paymentMethodId,
                'description' => $description,
                'metadata' => $metadata,
            ]);
            throw new \Exception($e->getMessage());
        }
    }

    public function retrievePaymentIntent($paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            return $paymentIntent;
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

  /*  public function redeemPoints(User $user, $points,$commission ,$paymentMethod, $description, $metadata)
    {
        //        $amount = calculateAmountFromCoins($points); // Example: 1 point = $0.10
        //        $amountCents = $amount * 100;

        $amount = $points / 100;
        $amountCents = $amount * 100;

        try {

            $paymentIntent = $this->createPaymentIntentForConfirm($amount, 'usd', $user->stripe_customer_id, $paymentMethod->stripe_payment_method_id, $description, $metadata);
            $refund = $this->refundPoints($paymentIntent->id, $amountCents);

            $user->reward_balance -= $points;
            $user->save();
            return $paymentIntent;
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }*/
    
  
   /* public function adminredeemPoints(User $user, $points, $paymentMethod, $description, $metadata)
    {
        //        $amount = calculateAmountFromCoins($points); // Example: 1 point = $0.10
        //        $amountCents = $amount * 100;

        $amount = $points / 100;
        $amountCents = $amount * 100;

        try {

            $paymentIntent = $this->createPaymentIntentForConfirm($amount, 'usd', $user->stripe_customer_id, $paymentMethod->stripe_payment_method_id, $description, $metadata);
            $refund = $this->refundPoints($paymentIntent->id, $amountCents);

          
            return $paymentIntent;
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }*/
  
  /*public function redeemPoints(User $user, $points, $commissionPercent, $paymentMethod, $description, $metadata)
  {
      $amount = $points / 100; // Example: 100 points = $1
      $amountCents = intval($amount * 100);
      $commissionAmountCents = intval($amountCents * $commissionPercent / 100);
      $netAmountCents = $amountCents - $commissionAmountCents;

      try {
          $paymentIntent = \Stripe\PaymentIntent::create([
              'amount' => $amountCents,
              'currency' => 'usd',
              'customer' => $user->stripe_customer_id,
              'payment_method' => $paymentMethod->stripe_payment_method_id,
              'confirm' => true,
              'application_fee_amount' => $commissionAmountCents,
              'transfer_data' => [
                  'destination' => $user->stripe_account_id,
              ],
              'description' => $description,
              'metadata' => array_merge($metadata, [
                  'points' => $points,
                  'commission_percent' => $commissionPercent,
                  'commission_amount' => $commissionAmountCents / 100,
                  'net_amount' => $netAmountCents / 100,
              ]),
          ]);
          $user->reward_balance -= $points;
            $user->save();

          return $paymentIntent;
      } catch (\Exception $e) {
          \Log::error('Stripe redeem error: ' . $e->getMessage());
          throw new \Exception($e->getMessage());
      }
  }*/
  
  public function redeemPoints(User $user, $points,$commissionPercent, $paymentMethod, $description, $metadata)
    {
        // Convert points to USD (example: 100 points = $1)
        $amount = $points / 100;
        $amountCents = $amount * 100;

        // Commission (10%)
       // $commissionPercent = 10;
        $commissionAmountCents = ($amountCents * $commissionPercent) / 100;
        $netAmountCents = $amountCents - $commissionAmountCents;

        try {
            // Merge commission info into metadata (will appear in Stripe Dashboard)
            $metadata = array_merge($metadata, [
                'user_id' => $user->id,
                'points' => $points,
                'commission_percent' => $commissionPercent,
                'commission_amount_usd' => $commissionAmountCents / 100,
                'net_amount_usd' => $netAmountCents / 100,
            ]);

            // ✅ Create PaymentIntent for the net amount (after commission)
            $paymentIntent = $this->createPaymentIntentForConfirm(
                $netAmountCents / 100,
                'usd',
                $user->stripe_customer_id,
                $paymentMethod->stripe_payment_method_id,
                $description,
                $metadata
            );

            // ✅ Refund (convert payment to payout style, as per your logic)
            $refund = $this->refundPoints($paymentIntent->id, $netAmountCents);

            // ✅ Deduct commission internally (log it for transparency)
            \Log::info('Commission deducted', [
                'user_id' => $user->id,
                'commission_usd' => $commissionAmountCents / 100,
                'original_amount_usd' => $amount,
            ]);

            // Optionally: store commission record in your own DB table
            // Commission::create([
            //     'user_id' => $user->id,
            //     'points' => $points,
            //     'commission_percent' => $commissionPercent,
            //     'commission_amount' => $commissionAmountCents / 100,
            //     'payment_intent_id' => $paymentIntent->id,
            // ]);

            // ✅ Deduct points from user’s in-app balance
            $user->reward_balance -= $points;
            $user->save();

            return $paymentIntent;

        } catch (ApiErrorException $e) {
            \Log::error('Stripe redeemPoints error: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
    


    public function savePaymentMethod(User $user, $paymentMethodId)
    {
        try {
            // Attach Payment Method to Customer
            $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
            $paymentMethod->attach(['customer' => $user->stripe_customer_id]);

            // Set Payment Method as Default
           Customer::update($user->stripe_customer_id, [
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethodId,
                ],
            ]);

            return $paymentMethod;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function onlySavePaymentMethod(User $user, $paymentMethodId)
    {
        try {
            // Attach Payment Method to Customer
            $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
            $paymentMethod->attach(['customer' => $user->stripe_customer_id]);

            return $paymentMethod;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function refundPoints($paymentIntentId, $amount)
    {
        $refund = Refund::create([
            'payment_intent' => $paymentIntentId,
            'amount' => $amount,
        ]);

        return $refund->id;
    }

   /* public function getCustomerBalance($customerId)
    {
        try {
            $customer = Customer::retrieve([
                'id' => $customerId,
                // 'expand' => ['cash_balance']
            ]);

            return [
                'balance' => $customer->metadata->cash_balance,
                // 'cash_balance' => $customer->cash_balance->available ?? [],
            ];
        } catch (\Exception $e) {
            \Log::error("Stripe Error for $customerId: " . $e->getMessage());
            return null;
        }
    }*/
  
public function getCustomerBalance($customerId)
{
    try {
        $customer = Customer::retrieve([
            'id' => $customerId,
            'expand' => ['cash_balance'], // expand balance info
        ]);

       return response()->json([
        'status' => 'success',
        'data'   => $customer
    ]);
    } catch (\Exception $e) {
        \Log::error("Stripe Error for $customerId: " . $e->getMessage());
        return null;
    }
}
  
  
 public function addCreditToCustomer($customerId,$amount,$currency)
  {

      try {
          $transaction = \Stripe\Customer::createBalanceTransaction(
              $customerId,
              [
                  'amount' => $amount * 100, // Stripe expects cents
                  'currency' => 'usd',
                  'description' => 'Manual test credit',
              ]
          );

          return json_encode($transaction);
      } catch (\Exception $e) {
          \Log::error("Stripe Balance Error: " . $e->getMessage());
          return back()->with('error', 'Failed to add credit: ' . $e->getMessage());
      }
  }
  public function getReceipt($chargeId)
  {    

      //dd('asdasd');
      Stripe::setApiKey(config('services.stripe.secret'));

      $charge = Charge::retrieve($chargeId);

      return $charge->receipt_url;
  }
  
  
  public function chargeUserCommission(User $user, $amount, $paymentMethod, $description = 'Rental commission deduction')
  {       
      try {
          // Convert amount to cents (Stripe works in the smallest currency unit)
          $amountCents = intval($amount * 100);

          // Create and confirm the payment — charges user directly
          $paymentIntent = PaymentIntent::create([
              'amount' => $amountCents,
              'currency' => 'usd',
              'customer' => $user->stripe_customer_id,
              'payment_method' => $paymentMethod->stripe_payment_method_id,
              'confirm' => true,
              'off_session' => true, // no user interaction needed
              'description' => $description,
              'metadata' => [
                  'user_id' => $user->id,
                  'reason' => 'rental_commission',
                  'amount_usd' => $amount,
              ],
          ]);

          \Log::info('Commission charged successfully', [
              'user_id' => $user->id,
              'payment_intent' => $paymentIntent->id,
              'amount' => $amount,
          ]);

          return $paymentIntent;
      } catch (\Exception $e) {
          \Log::error('Stripe commission charge failed: ' . $e->getMessage());
          throw new \Exception('Unable to charge commission: ' . $e->getMessage());
      }
  }

  /**
   * Create, finalize and send a Stripe Invoice for a customer.
   * Commission is the platform's share and must not be added as a line item when
   * it should stay in Dixwix balance; pass commission_amount => 0 and put platform
   * commission in metadata if needed.
   *
   * @param array $params
   *  - customer_id (string) Stripe customer id
   *  - currency (string) default 'usd'
   *  - rental_amount (float) total rental amount (in dollars)
   *  - commission_amount (float) optional; if 0, only rental is charged (commission retained by platform)
   *  - description (string) invoice description (appears in Stripe)
   *  - metadata (array) key/value
   */
  public function createFinalizeAndSendInvoice(array $params)
  {
      $customerId = $params['customer_id'] ?? null;
      if (!$customerId) {
          throw new \InvalidArgumentException('customer_id is required');
      }

      $currency = $params['currency'] ?? 'usd';
      $rentalAmount = (float) ($params['rental_amount'] ?? 0);
      $commissionAmount = (float) ($params['commission_amount'] ?? 0);
      $description = $params['description'] ?? 'Rental invoice';
      $metadata = $params['metadata'] ?? [];

      if ($rentalAmount <= 0 && $commissionAmount <= 0) {
          throw new \InvalidArgumentException('Invoice amount must be > 0');
      }

      try {
          // Create line items (InvoiceItem) before creating invoice
          if ($rentalAmount > 0) {
              InvoiceItem::create([
                  'customer' => $customerId,
                  'currency' => $currency,
                  'amount' => (int) round($rentalAmount * 100),
                  'description' => 'Rental charges',
                  'metadata' => $metadata,
              ]);
          }

          if ($commissionAmount > 0) {
              InvoiceItem::create([
                  'customer' => $customerId,
                  'currency' => $currency,
                  'amount' => (int) round($commissionAmount * 100),
                  'description' => 'Site commission',
                  'metadata' => $metadata,
              ]);
          }

          $invoice = Invoice::create([
              'customer'          => $customerId,
              'collection_method' => 'send_invoice',
              'days_until_due'    => 0,
              'description'       => $description,
              'metadata'          => $metadata,
              'auto_advance'      => true,
          ]);

          // finalize + send (instance methods to match installed stripe-php version)
          // Many versions expose finalizeInvoice()/sendInvoice as instance methods.
          if (method_exists($invoice, 'finalizeInvoice')) {
              $invoice->finalizeInvoice();
          } elseif (method_exists($invoice, 'finalize')) {
              // Fallback for older SDKs
              $invoice->finalize();
          }

          if (method_exists($invoice, 'sendInvoice')) {
              $invoice->sendInvoice();
          }

          return $invoice;
      } catch (ApiErrorException $e) {
          throw new \Exception($e->getMessage());
      }
  }

}
