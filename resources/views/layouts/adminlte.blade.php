<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <title>@yield('title', 'Page Title') | {{ config('app.name', 'Cored') }}</title>
        <link rel="shortcut icon" href="https://static.silverspoon.me/system/internal/image/logo/silverspoon/logo-50px.webp">
        <link rel="apple-touch-icon" sizes="50x50" href="https://static.silverspoon.me/system/internal/image/logo/silverspoon/logo-50px.webp">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/fontawesome.min.css" integrity="sha256-TBe0l9PhFaVR3DwHmA2jQbUf1y6yQ22RBgJKKkNkC50=" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css" integrity="sha256-fxxvNo/vOD88AQfrGh88D74wgYex47k9+sa3bWmCelI=" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-bs4@1.11.5/css/dataTables.bootstrap4.min.css" integrity="sha256-lDWLG10paq84N0F/7818mEj3YW5d6LCSBmIj2LirkYo=" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.1/dist/sweetalert2.min.css" integrity="sha256-VJuwjrIWHWsPSEvQV4DiPfnZi7axOaiWwKfXaJnR5tA=" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" integrity="sha256-FdatTf20PQr/rWg+cAKfl6j4/IY3oohFAJ7gVC3M34E=" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" integrity="sha256-h7vy42BP4MtLE0udIyBuOEoB8nJI2iLaiOJEgO5Ykp0=" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css" integrity="sha256-rhU0oslUDWrWDxTY4JxI2a2OdRtG7YSf3v5zcRbcySE=" crossorigin="anonymous">
        @vite(['resources/css/adminlte3.css'])
    </head>
    <body class="hold-transition sidebar-mini">
        <div id="app">
            <x-Adminlte.MainComponent />
        </div>
        @vite(['resources/js/adminlte3.js', 'resources/js/echo.js'])
        <script src="https://hcaptcha.com/1/api.js" async defer></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jscroll@2.4.1/jquery.jscroll.min.js" integrity="sha256-sJRx4f+y7jaXUs1JgAMhYQujFu+g3+MMPjVzluCRGm8=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha256-fgLAgv7fyCGopR/gBNq2iW3ZKIdqIcyshnUULC4vex8=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/js/all.min.js" integrity="sha256-BAR0H3Qu2PCfoVr6CtZrcnbK3VKenmUF9C6IqgsNsNU=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/datatables@1.10.18/media/js/jquery.dataTables.min.js" integrity="sha256-3aHVku6TxTRUkkiibvwTz5k8wc7xuEr1QqTB+Oo5Q7I=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs4@1.11.5/js/dataTables.bootstrap4.min.js" integrity="sha256-2MzaecCGkwO775PvRJkqMTd4sR6cuRiQlkT2iUeCsSU=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.1/dist/sweetalert2.all.min.js" integrity="sha256-rTFZb1sYZydw8G8qAnFZ0aCxLsRoUNSR38mzRI1/OX4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" integrity="sha256-AFAYEOkzB6iIKnTYZOdUf9FFje6lOTYdwRJKwTN5mks=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js" integrity="sha256-u2yoem2HtOCQCnsp3fO9sj5kUrL+7hOAfm8es18AFjw=" crossorigin="anonymous"></script>
        @stack('script')
    </body>
</html>