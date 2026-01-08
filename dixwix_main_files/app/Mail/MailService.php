<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailService extends Mailable
{
    use Queueable, SerializesModels;

    public $formData;

    public $emailbody;

    /**
     * Create a new message instance.
     *
     * @param array $formData
     */
    public function __construct($formData)
    {
        $this->formData = $formData;
  
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Message from Dix Wix Website',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emailbody',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        // Safely map the incoming form data to the Blade view variables.
        // Some callers provide "user_name", others only "name" or omit it entirely.
        $userName      = $this->formData['user_name'] ?? ($this->formData['name'] ?? 'User');
        $emailMessage  = $this->formData['message'] ?? null;
        $customerEmail = $this->formData['email'] ?? null;

        return $this->subject('Mail from dixwix App')
            ->view('emailbody', [
                'user_name'      => $userName,
                'email_message'  => $emailMessage,
                'customer_email' => $customerEmail,
            ]);
    }
}
