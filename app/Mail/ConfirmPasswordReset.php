<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmPasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Return a new Envelope instance with the email subject.
        return new Envelope(
            subject: 'Confirm Password Reset',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Return a new Content instance with the view for the email content.
        return new Content(
            view: 'Api.Mails.ConfirmResetPassword',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Return an empty array, indicating that there are no attachments.
        return [];
    }
}
