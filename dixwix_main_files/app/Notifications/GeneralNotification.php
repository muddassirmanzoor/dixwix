<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(public array $data)
    {}

    public function via($notifiable)
    {
        $channels = [];

        // Always broadcast so Echo can catch it
        $channels[] = 'broadcast';

        if (app()->environment('local')) {
            $channels[] = 'database';
        } elseif (!empty($this->data['only_database']) && empty($this->data['only_email'])) {
            $channels[] = 'database';
        } elseif (!empty($this->data['only_email']) && empty($this->data['only_database'])) {
            $channels[] = 'mail';
        } else {
            $channels = array_merge($channels, ['mail', 'database']);
        }

        return $channels;
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title'   => $this->data['title'],
            'type'    => $this->data['type'],
            'subject' => $this->data['subject'],
            'message' => $this->data['message'],
            'url'     => $this->data['url'],
            'action'  => $this->data['action'],
        ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'title'   => $this->data['title'],
            'type'    => $this->data['type'],
            'subject' => $this->data['subject'],
            'message' => $this->data['message'],
            'url'     => $this->data['url'],
            'action'  => $this->data['action'],
        ];
    }

    public function toArray($notifiable)
    {
        return $this->data;
    }
}
