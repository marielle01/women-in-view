<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected User $user;
    protected string $resetPasswordLink;

    protected string $token;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $resetPasswordLink, string $token)
    {
        // Initialize the properties with the provided values during instantiation.
        $this->user = $user;
        $this->resetPasswordLink = $resetPasswordLink;
        $this->token = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Return a new Envelope instance with the email subject.
        return new Envelope(
            subject: 'Password Reset Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Return a new Content instance with the view for the email content and additional data to be passed to the view.
        return new Content(
            view: 'Api.Mails.resetPassword',
            with: [
                'user' => $this->user,
                'resetPasswordLink' => $this->resetPasswordLink,
                'token' => $this->token,
            ],
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
