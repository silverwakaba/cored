<?php

namespace App\Mail;

use App\Models\UserRequest;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;

class UserVerifyEmail extends Mailable implements ShouldQueue{
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
            subject: 'Verify your email address',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content() : Content{
        $datas = UserRequest::select('token')->where('id', '=', $this->mailID)->first();

        return new Content(
            view: 'mailer.verify',
            with: [
                'routeTo' => URL::temporarySignedRoute(
                    'fe.auth.verify-account', now()->addMinutes(60), ['id' => $datas->token]
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
