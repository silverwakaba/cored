<!DOCTYPE html>
<html>
    <head>
        <title>{{ config('app.name', 'vTual') }} Mailer</title>
    </head>
    <body>
        <p>Greetings from {{ config('app.name', 'vTual') }}.</p>
        <br />
        @yield('content')
        <br />
        <p>Best regards,<br />The {{ config('app.name', 'vTual') }} Team</p>
        <hr />
        <p><strong><small>* Since we send out email notification like this in large quantities, this email may end up in your spam mailbox. You can whitelist this email address so that you can receive notifications smoothly in the future.</small></strong></p>
        <p><strong><small>** This message may contain confidential and/or privileged information. If you are not the addressee or authorized to receive this for the addressee, you must not use, copy, disclose or take any action based on this message or any information herein. If you have received this communication in error, please delete it from your system.</small></strong></p>
    </body>
</html>