<?php namespace App\Console\Commands;

use App\Models\Entries;
use App\Notifications\GeneralNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SendOverdueNotifications extends Command
{
    protected $signature = 'app:send-overdue-notifications';
    protected $description = 'Send overdue notifications to users';

    public function handle()
    {
        $today = Carbon::today()->toDateString();
        Log::info('Running SendOverdueNotifications command.');

        // Fetch expiring items
        $expiringItems = Entries::where('is_reserved', 1)
            ->where(function($query) {
                $query->where('state', 'returned')
                    ->orWhereNull('state');
            })
            ->where('due_date', '<=', $today)
            ->with(['book', 'reserver'])
            ->get();

        // Log the SQL query and bindings
        Log::info('SQL Query: ' . Entries::where('is_reserved', 1)
                ->where(function($query) {
                    $query->where('state', 'returned')
                        ->orWhereNull('state');
                })
                ->where('due_date', '<=', $today)
                ->toSql());
        Log::info('Bindings: ', [
            'is_reserved' => 1,
            'state' => 'returned',
            'due_date' => $today,
        ]);

        // Log the number of expiring items found
        Log::info('Number of expiring items: ' . $expiringItems->count());

        foreach ($expiringItems as $item) {
            $user = $item->reserver;
            $book = $item->book;

            $ownerId = DB::table('group')
                ->where('id', $book->group_id)
                ->value('created_by'); // this is the actual owner ID
            
            $owner = \App\Models\User::find($ownerId);

            if (!$user || !$book) {
                Log::warning('Missing user or Item for item ID: ' . $item->id);
                continue;
            }

            // Check if notification has already been sent today
            if ($item->notification_sent_at && Carbon::parse($item->notification_sent_at)->isToday()) {
                Log::info('Notification already sent for item ID: ' . $item->id);
                continue;  // Skip if notification has already been sent today
            }

            // Notify owner
            $ownerNotification = [
                'title' => 'Your Item is Overdue',
                'type' => 'item_overdue',
                'subject' => 'A Rented Item is Overdue',
                'message' => "The item '{$book->name}' you own is overdue and has not been returned yet.",
                'url' => route('show-group', $book->group->id),
                'action' => 'View Item',
            ];

            // Notify renter
            $notification = [
                'title' => 'Item Reservation Expiring Today',
                'type' => 'book_expiring_today',
                'subject' => 'Item Reservation Expiring Today',
                'message' => "Your reservation for the item {$book->name} is expiring today. Please take necessary action.",
                'url' => route('show-group', $book->group->id),
                'action' => 'View Item',
            ];

            try {
                Log::info('Sending notification to user: ' . $user->email);
                $user->notify(new GeneralNotification($notification)); // Send the notification

                Log::info('Sending notification to owner: ' . $owner->email);
                $owner->notify(new GeneralNotification($ownerNotification));

                // Mark the item as notified today
                $item->notification_sent_at = Carbon::now();
                $item->save();
                Log::info('Notification sent and item updated for item ID: ' . $item->id);
            } catch (\Exception $e) {
                Log::error('Failed to send notification for item ID: ' . $item->id . ' - Error: ' . $e->getMessage());
            }
        }

        Log::info('SendOverdueNotifications command completed.');
    }
}
