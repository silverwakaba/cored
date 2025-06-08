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
        <p><small>Since we send out email notification like this in large quantities, this email may end up in your spam mailbox. You can whitelist this email address so that you can receive notifications smoothly in the future.</small></p>
    </body>
</html>