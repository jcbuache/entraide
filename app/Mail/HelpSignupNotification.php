<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HelpSignupNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $person;
    public $action;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $person, $action)
    {
        $this->user = $user;
        $this->person = $person;
        $this->action = $action;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notification d\'entraide',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.help_signup_notification',
            with: [
                'userName' => $this->user->name,
                'personName' => $this->person->name,
                'task' => $this->person->task,
                'action' => $this->action,
            ]
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
}
