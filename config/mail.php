<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send all email
    | messages unless another mailer is explicitly specified when sending
    | the message. All additional mailers can be configured within the
    | "mailers" array. Examples of each type of mailer are provided.
    |
    */

    'default' => env('MAIL_MAILER', 'failover'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers that can be used
    | when delivering an email. You may specify which one you're using for
    | your mailers below. You may also add additional mailers if needed.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |            "postmark", "resend", "log", "array",
    |            "failover", "roundrobin"
    |
    */

    'mailers' => [
        // Debug SMTP via MailTrap
        'debug' => [
            'transport'     => 'smtp',
            'host'          => env('MAILER_DEBUG_HOST', 'sandbox.smtp.mailtrap.io'),
            'port'          => env('MAILER_DEBUG_PORT', 2525),
            'encryption'    => env('MAILER_DEBUG_ENCRYPTION', 'tls'),
            'username'      => env('MAILER_DEBUG_USERNAME'),
            'password'      => env('MAILER_DEBUG_PASSWORD', null),
            'timeout'       => null,
            'from'          => [
                'name'      => env('MAIL_FROM_NAME', 'Mailer Debug'),
                'address'   => env('MAIL_FROM_ADDRESS', 'email@debug.net'),
            ],
        ],

        // Custom SMTP via Owned Server (discontinued)
        'smtp' => [
            'transport'     => 'smtp',
            'host'          => env('MAILER_DEFAULT_HOST'),
            'port'          => env('MAILER_DEFAULT_PORT', 587),
            'encryption'    => env('MAILER_DEFAULT_ENCRYPTION', 'tls'),
            'username'      => env('MAILER_DEFAULT_USERNAME'),
            'password'      => env('MAILER_DEFAULT_PASSWORD', null),
            'timeout'       => null,
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers'   => [
                'debug',
            ],
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all emails sent by your application to be sent from
    | the same address. Here you may specify a name and address that is
    | used globally for all emails that are sent by your application.
    |
    */

    'from' => [
        'name'      => env('MAIL_FROM_NAME', 'Mailer Debug'),
        'address'   => env('MAIL_FROM_ADDRESS', 'email@debug.net'),
    ],

];
