<?php

namespace App\Mail\Core;

use App\Models\Core\UserRequest;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;

class UserResetPassword extends Mailable implements ShouldQueue{
    use Queueable, SerializesModels;

    public $mailID;

    /**
     * Create a new message instance.
     */
    public function __construct($mailID){
        $this->mailID = $mailID;
    }

    /**
     * Get the message envelope.
     */
    public function envelope() : Envelope{
        return new Envelope(
            subject: 'Reset your password',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content{
        // Force app url
        config(['app.url' => config('app.url')]);

        // Get request
        $datas = UserRequest::select('token')->where('id', '=', $this->mailID)->first();

        // Response
        return new Content(
            view: 'mailer.reset',
            with: [
                'routeTo' => URL::temporarySignedRoute(
                    'fe.auth.reset-password-tokenized', now()->addMinutes(60), ['token' => $datas->token]
                ),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments() : array{
        return [];
    }
}






