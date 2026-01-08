<?php

namespace App\Http\Controllers;
use App\Notifications\GeneralNotification;

use App\Models\Point;
use App\Models\TransferRequest;
use App\Models\User;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferRequestController extends Controller
{
    public function index()
    {
        $data['title'] = 'Transfer Point Requests';
        $data['template'] = 'admin.reward.transfer-req-list';
        $transferRequests = TransferRequest::orderBy('created_at', 'desc')->get();

        return view('with_login_common', compact('data', 'transferRequests'));
    }


    public function MyTransfers()
    {
        $data['title'] = 'My Transfer Point Requests';
        $data['template'] = 'admin.reward.user.transfer-req-list';
        //$transferRequests = TransferRequest::where("from_user_id", Auth::user()->id)->orderBy('created_at', 'desc')->get();
       dd(
            TransferRequest::where(function ($query) {
                $query->where('from_user_id', Auth::id())
                      ->orWhere('to_user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->toSql()
        );
  
        return view('with_login_common', compact('data', 'transferRequests'));
    }

    public function edit($id)
    {
        $transferRequest = TransferRequest::findOrFail($id);
        $data['title'] = 'Edit Transfer Point Request';
        $data['template'] = 'admin.reward.edit-transfer-request';

        return view('with_login_common', compact('data', 'transferRequest'));
    }

    public function update(Request $request, $id){

        $request->validate([
            'status' => 'required|in:1,2',
        ]);

        $transferRequest = TransferRequest::find($id);
        $fromUser = User::findOrFail($transferRequest->from_user_id);
        $toUser = User::findOrFail($transferRequest->to_user_id);

        if ($request->status == TransferRequest::APPROVED) {

            if ($fromUser->reward_balance < $transferRequest->points) {
                $entryNotification = [
                'only_database' => true,
                'title'         => 'Points are not enough to be transferred.',
                'type'          => 'your_points_transfer_rejected',
                'subject'       => 'Points transfer rejected',
                'message'       => "You does not have enough points to approve this request.",
                'action'        => 'Points transfer rejected.',
                'user_id'       => Auth::user()->id,
                'url'           => url("my-rewards?tabs=two#pageStarts"),
            ];
            try {
                //$toUser->notify(new GeneralNotification($entryNotification));
                $fromUser->notify(new GeneralNotification($entryNotification));

                logger()->info('Notification sent successfully', ['user_id' => $fromUser->id, 'book_id' => $fromUser->id]);
            } catch (Exception $e) {
                logger()->error('Failed to send notification', ['error' => $e->getMessage(), 'user_id' => $fromUser->id, 'book_id' => $fromUser->id]);
                return json_encode(["success" => false, "message" => "Notification could not be sent."]);
            }
                return redirect()->back()->with('error', "User does not have enough points to approve this request.");
            }

            // Process the point transfer
            $this->processPointTransfer($fromUser, $toUser, $transferRequest->points);
            $entryNotification = [
                'only_database' => true,
                'title'         => 'Points transfer successfully 🎉',
                'type'          => 'your_points_transfer_successfully',
                'subject'       => 'Points transfer successfully',
                'message'       => "Your points transfer was successfull",
                'action'        => 'Points transfer successfully',
                'user_id'       => Auth::user()->id,
                'url'           => url("my-rewards?tabs=two#pageStarts"),
            ];
            $recieveNotification = [
                'only_database' => true,
                'title'         => 'Points recieved successfully 🎉',
                'type'          => 'your_points_recieved_successfully',
                'subject'       => 'Points recieved.',
                'message'       => "Points transfered to you waere recieved",
                'action'        => 'Points transfer successfully',
                'user_id'       => Auth::user()->id,
                'url'           => url("my-rewards?tabs=two#pageStarts"),
            ];
            try {
                $toUser->notify(new GeneralNotification($recieveNotification));
                $fromUser->notify(new GeneralNotification($entryNotification));

                logger()->info('Notification sent successfully', ['user_id' => $fromUser->id, 'book_id' => $fromUser->id]);
            } catch (Exception $e) {
                logger()->error('Failed to send notification', ['error' => $e->getMessage(), 'user_id' => $fromUser->id, 'book_id' => $fromUser->id]);
                return json_encode(["success" => false, "message" => "Notification could not be sent."]);
            }
            $transferRequest->status = $request->status;
            $transferRequest->approved_by = auth()->user()->id;
            $transferRequest->approved_at = now();
            $transferRequest->save();

            return redirect()->route('transfer-requests')->with('success', 'Point Transfer request updated successfully.');
        }
        
        if ($request->status == TransferRequest::REJECTED) {

                $entryNotification = [
                'only_database' => true,
                'title'         => 'Points transfer rejected.',
                'type'          => 'your_points_transfer_rejected',
                'subject'       => 'Points transfer rejected',
                'message'       => "Your points transfer was rejected by admin.",
                'action'        => 'Points transfer rejected',
                'user_id'       => Auth::user()->id,
                'url'           => url("my-rewards?tabs=two#pageStarts"),
            ];
            try {
                  //$toUser->notify(new GeneralNotification($entryNotification));
                  $fromUser->notify(new GeneralNotification($entryNotification));

                  logger()->info('Notification sent successfully', ['user_id' => Auth::user()->id, 'book_id' => Auth::user()->id]);
                } catch (Exception $e) {
                    logger()->error('Failed to send notification', ['error' => $e->getMessage(), 'user_id' => Auth::user()->id, 'book_id' => Auth::user()->id]);
                    return json_encode(["success" => false, "message" => "Notification could not be sent."]);
                }
        }
        $transferRequest->status = $request->status;
        $transferRequest->approved_by = auth()->user()->id;
        $transferRequest->approved_at = now();
        $transferRequest->save();

        return redirect()->route('transfer-requests')->with('success', 'Point Transfer request updated successfully.');
    }

    private function processPointTransfer($fromUser, $toUser, $points)
    {
        $admin = User::find(1);
        $commission = $commission = Commission::first()->commission;
        $admin_commission = ( $points * $commission ) /100 ;
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
            'trans_type' => Point::TRANS_TYPE_GIFT,
            'description' => "Points transfer commission",
        ]);

        $toUser->reward_balance += $to_user_points;
        $toUser->save();
      
        $admin->reward_balance += $admin_commission;
        $admin->save();
       }
}
