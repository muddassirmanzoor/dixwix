<?php

namespace App\Http\Controllers;

use App\Mail\MailService;
use App\Models\CoinPackage;
use App\Models\Point;
use App\Models\Setting;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Http;
use App\Models\TransferRequest;
use App\Models\User;
use App\Models\Commission;
use App\Models\GiftoOrder;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RewardTransaction;
use Illuminate\Support\Facades\Mail;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use App\Services\GiftoGramService;
use function Symfony\Component\String\u;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    protected $stripeService;
    protected $giftoGramService;

    public function __construct(StripeService $stripeService, GiftoGramService $giftoGramService)
    {
        $this->stripeService = $stripeService;
        $this->giftoGramService = $giftoGramService;
    }

    public function ShowMyRewards(Request $request)
    {
        $rewards_prices = CoinPackage::orderBy('price', 'asc')->get();
        $user =  auth()->user();

        $reward_balance = $user->reward_balance;

        $payment_methods = $user->paymentMethods()->latest()->get();
        $payment_methods = collect($payment_methods)->sortByDesc('default')->values();

        $points = $request->get('points') ?? null;
        $price = $request->get('price') ?? null;
        $client_secret = $request->get('client_secret') ?? null;
        $payment_intent_id = $request->get('payment_intent_id') ?? null;

        $data = [
            'title' => 'My Rewards',
            'template' => 'reward.main',
        ];

        // Send Gift using GiftoGram Service
        //        $giftoGramResponse = app(GiftoGramService::class)->sendGift(
        //            "habibahmed001@gmail.com",
        //            100,
        //            "Enjoy your gift!"
        //        );
        //
        //        if (!$giftoGramResponse['success']) {
        //            return response()->json([
        //                'success' => false,
        //                'error' => $giftoGramResponse['error']
        //            ], 400);
        //        }
        //
        //        dd($giftoGramResponse);

        $funding = $this->giftoGramService->getFunding();
        //        dd($funding);

        //        $orders = $this->giftoGramService->getOrders();
        //        dd($orders);
        $campaigns = $this->giftoGramService->getCampaigns();
        //        dd($campaigns);

        $gifto_funds = isset($funding["data"]["data"]["credit_available"]) ? $funding["data"]["data"]["credit_available"] : null;

        $orders = GiftoOrder::where('user_id', Auth::user()->id)->latest()->get();

        $transferRequests = TransferRequest::where("from_user_id", Auth::user()->id)->orWhere('to_user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        $purchases = Point::with(["user", "package"])->where('user_id', Auth::user()->id)
            ->where('type', 'credit')
            ->where('description', 'like', '%Purchased points%')
            ->whereNotNull('package_id')
            ->orderBy('created_at', 'desc')
            ->get();
      
       $transfers = Point::with(['user', 'package'])
          ->where('type', 'debit')
          ->where(function ($q) {
                  $q->where('user_id', Auth::id())
                    ->where('description', 'like', '%Points gifted to%');
              })
          ->orderBy('created_at', 'desc')
          ->get();


        
        $earn_points = Point::with(['user', 'throughUser'])
        ->where('user_id', Auth::id())
        ->orderBy('id', 'desc')
        ->get();
        $transactions = RewardTransaction::where("user_id", Auth::user()->id)->orderBy('id', 'desc')->get();
        return view('with_login_common', compact('data', 'reward_balance', 'rewards_prices', 'points', 'earn_points', 'price', 'client_secret', 'payment_methods','payment_intent_id', 'gifto_funds', 'campaigns', 'orders', 'transferRequests', 'transactions', 'purchases','transfers'));
    }

    public function userEdit($id)
    {
        $transaction = TransferRequest::findOrFail($id);
        $data['title'] = 'Edit Redeem Request';
        $data['template'] = 'admin.reward.edit-points-request-user';

        return view('with_login_common', compact('data', 'transaction'));
    }

    public function userUpdate(Request $request, $id)
    {
        $transaction = TransferRequest::find($id);

        $request->validate([
            'coins' => 'required',
        ]);

        try {

            $transaction->points = $request->coins;

            $transaction->save();

            return redirect()->back()->with('success', 'Request updated successfully!');

        } catch (ApiErrorException $e) {
            return back()->with('error', 'Stripe error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to switch plan: ' . $e->getMessage());
        }
    }

    public function purchasePoints(Request $request)
    {   

        $validated = $request->validate([
            'points' => 'required|integer',
            'price' => 'required|numeric',
            'package_id' => 'required',
        ]);

        $points = $validated['points'];
        $price = $validated['price'];
        $package_id = $validated['package_id'];
        $user = auth()->user();

        $coinPackage = CoinPackage::find($package_id);

        $totalAmount = calculatePackageAmount($coinPackage);
        $systemFee = getSetting('system_fee');
        $description = "Purchase points";
        
                try {
            $paymentIntent =  $this->stripeService->createPaymentIntentForPurchase($totalAmount, 'usd', $user->stripe_customer_id, $description,  [
                'user_id' => auth()->id(),
                'points' => $points,
                'package_price' => $coinPackage->price,
                'system_fee' => '0',
                'package_id' => $package_id,
            ]);
                  
                 

            /******* Notification ********/
            if(isset($request->package_id)) {
                $user = Auth::user();
                $entryNotification = [
                    'only_database' => true,
                    'title'         => 'Your purchase Successfully 🎉',
                    'type'          => 'your_purchase_successfully',
                    'subject'       => 'Your purchase Successfully',
                    'message'       => "You're purchasing Successfully",
                    'action'        => 'Purchasing Successfully',
                    'user_id'       => Auth::user()->id,
                    'url'           => url("my-rewards?tabs=third#pageStarts"),
                ];
              
              
               
                try {
                    $user->notify(new GeneralNotification($entryNotification));
                    logger()->info('Notification sent successfully', ['user_id' => Auth::user()->id, 'book_id' => Auth::user()->id]);
                } catch (Exception $e) {
                    logger()->error('Failed to send notification', ['error' => $e->getMessage(), 'user_id' => Auth::user()->id, 'book_id' => Auth::user()->id]);
                    return json_encode(["success" => false, "message" => "Notification could not be sent."]);
                }

                $formData       = ["user_name"=>$user->name,"message" => "Dear Customer", "email" => "Your purchase Successfully"];
                $recipientEmail = Auth::user()->email;
             //   Mail::to($recipientEmail)->send(new MailService($formData));
              Mail::to($recipientEmail)->send(new MailService($formData));

            }
            /******* Notification ********/

            // return redirect()->route('my-rewards', [
            //     'points' => $points,
            //     'price' => $price,
            //     'client_secret' => $paymentIntent->client_secret,
            //     'payment_intent_id' => $paymentIntent->id,
            //     //                'tabs' => "third#pageStarts",
            //     'tabs' => "third",
            // ]);
            return response()->json([
                'redirect_url' => route('my-rewards', [
                    'points' => $points,
                    'price' => $price,
                    'client_secret' => $paymentIntent->client_secret,
                    'payment_intent_id' => $paymentIntent->id,
                    'tabs' => "third",
                ])
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function payWithSavedCard(Request $request)
    {
        try {
            $user = auth()->user();
            $paymentMethodId = $request->payment_method_id;
            $paymentIntentId = $request->payment_intent;

            $paymentMethod = $user->paymentMethods()->where('id', $paymentMethodId)->first();
            if (empty($paymentMethod)) {
                return redirect()->route('my-rewards')->withErrors('Payment method not found.');
            }

            $paymentIntent = $this->stripeService->retrievePaymentIntent($paymentIntentId);

            /******* Notification ********/
            if(isset($request->payment_method_id)) {
                $user = Auth::user();
                $entryNotification = [
                    'only_database' => true,
                    'title'         => 'Your purchase Successfully 🎉',
                    'type'          => 'your_purchase_successfully',
                    'subject'       => 'Your purchase Successfully',
                    'message'       => "You're purchasing Successfully",
                    'action'        => 'Purchasing Successfully',
                    'user_id'       => Auth::user()->id,
                    'url'           => url("my-rewards?tabs=third#pageStarts"),
                ];
                try {
                    $user->notify(new GeneralNotification($entryNotification));
                    logger()->info('Notification sent successfully', ['user_id' => Auth::user()->id, 'book_id' => Auth::user()->id]);
                } catch (Exception $e) {
                    logger()->error('Failed to send notification', ['error' => $e->getMessage(), 'user_id' => Auth::user()->id, 'book_id' => Auth::user()->id]);
                    return json_encode(["success" => false, "message" => "Notification could not be sent."]);
                }

                $formData       = ["user_name"=>$user->name,"message" => "Dear Customer", "email" => "Your purchase Successfully"];
                $recipientEmail = Auth::user()->email;
               // Mail::to($recipientEmail)->send(new MailService($formData));
              Mail::to($recipientEmail)->send(new MailService($formData));
              
            }
            /******* Notification ********/

            // Confirm the payment using the saved payment method
            $paymentIntent->confirm([
                'payment_method' => $paymentMethod->stripe_payment_method_id,
                'return_url' => route('payment-success'),
            ]);
          
            
            return response()->json([
                'success' => true,
                //'message' => 'Payment Succeeded',
                'payment_intent_id' => $paymentIntent->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function paymentSuccess(Request $request)
    {
        $paymentIntentId = $request->query('payment_intent');
//        $receiptUrl = $this->stripeService->getReceipt("ch_3SCauAHW1AJIhmNX0W6ZThTh");//
 //       dd($receiptUrl);

        try {
            DB::beginTransaction();

                        

            $paymentIntent = $this->stripeService->retrievePaymentIntent($paymentIntentId);
          //  $customer_balance = $this->stripeService->getCustomerBalance('cus_T1kB2ipC0qcCQc');
          //  $add_balance =  $this->addCreditToCustomer('cus_T1kB2ipC0qcCQc', 20000, 'usd');
            $userId = $paymentIntent->metadata->user_id;
            $points = $paymentIntent->metadata->points;
            $systemFee = $paymentIntent->metadata->system_fee;
            $packagePrice = $paymentIntent->metadata->package_price;
            $package_id = $paymentIntent->metadata->package_id;
            $amount = $paymentIntent->amount / 100;
            $admin =  User::find(1);
            $commission = Commission::first()->commission;
            $admin_commission = ($points * $commission )/ 100;
            $amount = pointsToDollars($points);
            //$admin_points = $admin->reward_balance + $admin_commission; 
          //  $admin->reward_balance += $admin_commission;
          //  $admin->save();  
            
          
            $admin = User::findOrFail('1');
            $user = User::findOrFail($userId);

            $description = "Purchased points via Stripe payment intent ID: {$paymentIntentId}";
            $existingRecord = Point::where('description', $description)
                ->where('amount', $amount)
                ->where('user_id', $userId)
                ->exists();

            if ($existingRecord) {
                DB::rollBack();
                return redirect()->route('my-rewards')->withErrors('Payment has already been processed.');
            }

            $purchase_point_rewards = getSetting('purchase_point_rewards');

            $reward_amount = calculateAmountFromCoins($purchase_point_rewards);
            Point::create([
                'user_id' => $user->id,
                'package_id' => $package_id,
                'type' => 'credit',
                'points' => $points  - $admin_commission,
                'price' => $packagePrice,
                'amount' => $amount - ($admin_commission/100),
                'system_fee' => $admin_commission/100,
                'description' => $description,
                'trans_type' => Point::TRANS_TYPE_PURCHASED,
            ]);

            $user->reward_balance += $points - $admin_commission;
            $user->save();
            
          //  $admindescription = "Admin Purchase Commission points have been transferred";
          //  $adminpaymentMethod = $admin->paymentMethods()->where('default', true)->first();
           // dd($adminpaymentResponse);
          //  $adminpaymentResponse = $this->stripeService->adminredeemPoints($admin,$admin_commission, $adminpaymentMethod, $admindescription, []);
             // dd($adminpaymentResponse);

          
         /*    Point::create([
                    'user_id' => $admin->id,
                    'through_user_id' => $user->id,
                    'type' => 'credit',
                    'points' => $admin_commission,
                    'data' => 'Admin commission',
                    'amount' => $admin_commission/100,
                    'description' => $admindescription,
                    'trans_type' => Point::TRANS_TYPE_REWARD,
                ]); */

//            if(getSetting('has_purchase_point_rewards')){
//                Point::create([
//                    'user_id' => $user->id,
//                    'type' => 'credit',
//                    'points' => $purchase_point_rewards,
//                    'amount' => $reward_amount,
//                    'description' => "Purchased points reward",
//                ]);
//
//                $user->reward_balance += $purchase_point_rewards;
//                $user->save();
//            }

            DB::commit();

            return redirect("/my-rewards?tabs=third#pageStarts")->with('success', 'Points Purchased Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('my-rewards')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }



    public function findUsers(Request $request)
    {
        $request->validate(['search_user' => 'required|string|min:1|max:255']);

        $users = User::where(function ($query) use ($request) {
            $query->where('name', 'LIKE', "%{$request->search_user}%")
                ->orWhere('email', 'LIKE', "%{$request->search_user}%");
        })
            ->where('id', '!=', auth()->id())
            ->whereHas('roles', function ($query) {
                $query->where('name', 'user');
            })
            ->get(['id', 'name', 'email']);

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function assignPoints(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|min:1',
        ]);
      
        $user = User::find($request->user_id);
        $admin = User::find(1);
        $authUser = auth()->user();
        $credit = $authUser->points()->where('type', 'credit')->sum('points');
        $debit = $authUser->points()->where('type', 'debit')->sum('points');
        $availablePoints = $credit - $debit;
        $transferCoinLimit = getSetting('user_transfer_coint_limit');
        if ($availablePoints < $request->points) {
            return response()->json([
                'success' => false,
                'message' => "You don't have enough points to gift.",
              //  'message' => $admin_commission,
            ]);
        }

        

        if ($request->points > $transferCoinLimit) {
               
            $checkExistsReq = $authUser->transferPointRequestFromUser()
                ->where('to_user_id',$request->user_id)
                ->where('status', TransferRequest::PENDING)
                ->exists();
            if($checkExistsReq){
                return response()->json([
                    'success' => false,
                    'message' => 'Your gift points request is already pending admin approval. You can\'t submit a new request until the previous one is completed.',
                ]);
            }else{       
              
                    TransferRequest::create([
                    'from_user_id' => $authUser->id,
                    'to_user_id' => $user->id,
                    'points' => $request->points,
                
               
                ]);
               
            /******* Notification ********/
            $entryNotification = [
                'only_database' => true,
                'title'         => 'Points transfer requested 🎉',
                'type'          => 'your_points_transfer_successfully',
                'subject'       => 'Points transfer successfully',
                'message'       => "Your points transfer request was submitted to admin successfully",
                'action'        => 'Points transfer requested successfully',
                'user_id'       => Auth::user()->id,
                'url'           => url("my-rewards?tabs=two#pageStarts"),
            ];
              $adminNotification = [
                'only_database' => true,
                'title'         => 'Points transfer requested 🎉',
                'type'          => 'your_points_transfer_successfully',
                'subject'       => 'Points transfer successfully',
                'message'       => "Your points transfer request was submitted to admin successfully",
                'action'        => 'Points transfer requested successfully',
                'user_id'       => Auth::user()->id,
                'url'           => 'https://www.dixwix.com/transfer-point-requests',

            ];
                    
            try {
                  
                $authUser->notify(new GeneralNotification($entryNotification));
                $admin->notify(new GeneralNotification($adminNotification));
               
                logger()->info('Notification sent successfully', ['user_id' => Auth::user()->id]);
              
            } catch (Exception $e) {
                logger()->error('Failed to send notification', ['error' => $e->getMessage(), 'user_id' => Auth::user()->id, 'book_id' => Auth::user()->id]);
                return json_encode(["success" => false, "message" => "Notification could not be sent."]);
            }

            $formData       = ["user_name"=>$user->name,"message" => "Dear Customer", "email" => "Points transfer requested successfully"];
            $recipientEmail = Auth::user()->email;
            //Mail::to($recipientEmail)->send(new MailService($formData));
             Mail::to($recipientEmail)->send(new MailService($formData));

                return response()->json([
                    'success' => true,
                    'message' => 'Your transfer request has been sent for admin approval.',
                ]);
            
            }

            
        }
      
        if ($request->points <= $transferCoinLimit) {
          
         // $sumOfPoints = TransferRequest::where("from_user_id", $authUser->id)->sum('points');
         // $sumOfPoints = $authUser->reward_balance - ($sumOfPoints + $request->points);
         // if($sumOfPoints < 0){
         //     return response()->json([
         //         'success' => false,
         //         'message' =>"You don't have sufficient points to send gift.",
         //     ]);
        //  }
          

            /******* Notification ********/
            $entryNotification = [
                'only_database' => true,
                'title'         => 'Points transfer successfully 🎉',
                'type'          => 'your_points_transfer_successfully',
                'subject'       => 'Points transfer successfully',
                'message'       => "Your points transfer successfully",
                'action'        => 'Points transfer successfully',
                'user_id'       => Auth::user()->id,
                'url'           => url("my-rewards?tabs=two#pageStarts"),
            ];
            try {
                $user->notify(new GeneralNotification($entryNotification));
                logger()->info('Notification sent successfully', ['user_id' => Auth::user()->id, 'book_id' => Auth::user()->id]);
            } catch (Exception $e) {
                logger()->error('Failed to send notification', ['error' => $e->getMessage(), 'user_id' => Auth::user()->id, 'book_id' => Auth::user()->id]);
                return json_encode(["success" => false, "message" => "Notification could not be sent."]);
            }

            $formData       = ["user_name"=>$user->name,"message" => "Dear Customer", "email" => "Points transfer successfully"];
            $recipientEmail = Auth::user()->email;
           // Mail::to($recipientEmail)->send(new MailService($formData));
           Mail::to($recipientEmail)->send(new MailService($formData));
            /******* Notification ********/

            // Directly transfer points if within limit
            $response =  $this->processPointTransfer($authUser, $user, $request->points);
           //             return response()->json(['success' => true, 'message' => 'Points gifted successfully!'. $response]);

            return response()->json(['success' => true, 'message' => 'Points gifted successfully!']);

          
          
        }

    }

    public function assignGiftoPoints(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|min:1',
        ]);

        $user = User::find($request->user_id);
        $authUser = auth()->user();

        $credit = $authUser->points()->where('type', 'credit')->sum('points');
        $debit = $authUser->points()->where('type', 'debit')->sum('points');
        $availablePoints = $credit - $debit;
//        $availablePoints = ($request->is_gifto == 1) ? (($credit - ($request->gifto_price * 100)) - $debit) : ($credit - $debit);
        $transferCoinLimit = getSetting('user_transfer_coint_limit_gifto');

        if ($availablePoints < $request->points) {
            return response()->json([
                'success' => false,
                'message' => "You don't have enough points to gift.",
            ]);
        }

        if ($request->points > $transferCoinLimit) {

            $checkExistsReq = $authUser->transferPointRequestFromUser()
                ->where('to_user_id',$request->user_id)
                ->where('status', TransferRequest::PENDING)
                ->exists();
            if($checkExistsReq){
                return response()->json([
                    'success' => false,
                    'message' => 'Your gift points request is already pending admin approval. You can\'t submit a new request until the previous one is completed.',
                ]);
            }


        }

        /******** Send Request To Gifto *******/
        $giftoGramResponse = app(GiftoGramService::class)->sendGift(
            $user->email,
            $request->points,
            $request->gifto_msg,
            getSetting('gifto_gram_uuid'),
            json_encode($request->selected_card),
            Auth::user()->name,
        );
        /******** Send Request To Gifto *******/

        /******** Store Order In DB ******/
        $order = new GiftoOrder();

        $order->user_id = Auth::user()->id;
        $order->userName = Auth::user()->name;
        $order->userEmail = $user->email;
        $order->points = $request->points;
        $order->giftoAmount = $request->points;
        $order->giftoMsg = $request->gifto_msg;
        $order->campaignUuid = getSetting('gifto_gram_uuid');
        $order->selectedCard = json_encode($request->selected_card);
        $order->cardPath = json_encode($request->card_path);

        $order->save();
        /******** Store Order In DB ******/
        // Directly transfer points if within limit
        $this->processPointTransfer($authUser, $user, $request->points);

        /******* Notification ********/
        $user = Auth::user();
        $entryNotification = [
            'only_database' => true,
            'title'         => 'Points gifted successfully 🎉',
            'type'          => 'your_purchase_successfully',
            'subject'       => 'Points gifted successfully',
            'message'       => "You're points gifted successfully",
            'action'        => 'Points gifted successfully',
            'user_id'       => Auth::user()->id,
            'url'           => url("my-rewards?tabs=one#pageStarts"),
        ];
        try {
            $user->notify(new GeneralNotification($entryNotification));
            logger()->info('Notification sent successfully', ['user_id' => Auth::user()->id, 'book_id' => Auth::user()->id]);
        } catch (Exception $e) {
            logger()->error('Failed to send notification', ['error' => $e->getMessage(), 'user_id' => Auth::user()->id, 'book_id' => Auth::user()->id]);
            return json_encode(["success" => false, "message" => "Notification could not be sent."]);
        }

        $formData       = ["user_name"=>$user->name,"message" => "Dear Customer", "email" => "Points gifted successfully"];
        $recipientEmail = Auth::user()->email;
       // Mail::to($recipientEmail)->send(new MailService($formData));
      Mail::to($recipientEmail)->send(new MailService($formData));
        /******* Notification ********/

        return response()->json(['success' => true, 'message' => 'Points gifted successfully!', 'gifto' => $giftoGramResponse]);
    }

    private function processPointTransfer($fromUser, $toUser, $points)
    {   
        $admin =  User::find(1);
            $commission = Commission::first()->commission;
            $admin_commission = ($points * $commission )/ 100;
            $amount = pointsToDollars($points);
            //$admin_points = $admin->reward_balance + $admin_commission; 
            $admin->reward_balance += $admin_commission;
            $admin->save();  
        $to_user_points = $points - $admin_commission;
        Point::create([
            'user_id' => $fromUser->id,
            'through_user_id' => $toUser->id, 
            'type' => 'debit',
            'points' => $points,
            'amount' => $points/100,
            'system_fee' => $admin_commission/100,
            'description' => "Points gifted to {$toUser->name}",
        ]);

        $fromUser->reward_balance -= $points;
        $fromUser->save();

        Point::create([
            'user_id' => $toUser->id,
            'through_user_id' => $fromUser->id,
            'type' => 'credit',
            'points' => $points,
            'amount' => $points/100,
            'system_fee' => $admin_commission/100,
            'trans_type' => Point::TRANS_TYPE_GIFT,
            'description' => "Gift received from {$fromUser->name}",
        ]);
      
        Point::create([
            'user_id' => $admin->id,
            'through_user_id' => $fromUser->id,
            'type' => 'credit',
            'points' => $admin_commission,
            'amount' => $admin_commission/100,
            'system_fee' => $admin_commission/100,
            'trans_type' => Point::TRANS_TYPE_GIFT,
            'description' => "Points transfer commission",
        ]);
      
           

        $toUser->reward_balance += $to_user_points;
        $toUser->save();
      
        $admin->reward_balance += $admin_commission;
        $admin->save();
    }


    public function destroy(string $id)
    {
        try {
            $transaction = TransferRequest::findOrFail($id);
            $transaction->delete();

            return redirect()->back()->with('success', 'Redeem request deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting Redeem request: '.$e->getMessage());
            return response()->json(['error' => 'Failed to delete Redeem request.'], 500);
        }
    }

}
