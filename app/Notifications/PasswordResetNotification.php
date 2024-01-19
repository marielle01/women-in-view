<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class PasswordResetNotification extends Notification
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

        $this->user = $user;
        $this->resetPasswordLink = $resetPasswordLink;
        $this->token = $token;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject:'passwords reset',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

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
     * @return array<mixed>
     */
    public function attachments(): array
    {
        return [];
    }
}
