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
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
        <link rel="stylesheet" href="https://static.silverspoon.me/system/internal/template/arsha/02222025/vendor/bootstrap/css/bootstrap.min.css" integrity="sha256-2FMn2Zx6PuH5tdBQDRNwrOo60ts5wWPC9R8jK67b3t4=" crossorigin="anonymous">
        <link rel="stylesheet" href="https://static.silverspoon.me/system/internal/template/arsha/02222025/vendor/bootstrap-icons/bootstrap-icons.min.css" integrity="sha256-pdY4ejLKO67E0CM2tbPtq1DJ3VGDVVdqAR6j3ZwdiE4=" crossorigin="anonymous">
        <link rel="stylesheet" href="https://static.silverspoon.me/system/internal/template/arsha/02222025/vendor/glightbox/css/glightbox.min.css" integrity="sha256-bT9i1NF5afnHDpQ4z2cQBHJQGehoEj8uvClaAG+NXS0=" crossorigin="anonymous">
        <link rel="stylesheet" href="https://static.silverspoon.me/system/internal/template/arsha/02222025/vendor/swiper/swiper-bundle.min.css" integrity="sha256-dMpqrlRo28kkeQw7TSGaCJuQo0utU6D3yjpz5ztvWrg=" crossorigin="anonymous">
        <link rel="stylesheet" href="https://static.silverspoon.me/system/internal/template/arsha/02222025/css/main.min.css" integrity="sha256-LKXn37Nb+GCol2qihX8Eyt1tUAnLEH35qFN/VD20XvU=" crossorigin="anonymous">
    </head>
    <body class="index-page">
        <div id="app">
            <x-Arsha.MainComponent />
        </div>
        <script src="https://static.silverspoon.me/system/internal/template/arsha/02222025/vendor/bootstrap/js/bootstrap.bundle.min.js" integrity="sha256-5P1JGBOIxI7FBAvT/mb1fCnI5n/NhQKzNUuW7Hq0fMc=" crossorigin="anonymous"></script>
        <script src="https://static.silverspoon.me/system/internal/template/arsha/02222025/vendor/glightbox/js/glightbox.min.js" integrity="sha256-xnxZzfmAeTsCFsnenG6/aIQYg4girx5QsJQ546oon/M=" crossorigin="anonymous"></script>
        <script src="https://static.silverspoon.me/system/internal/template/arsha/02222025/vendor/swiper/swiper-bundle.min.js" integrity="sha256-mF8SJMDu7JnTZ6nbNeWORLIefrnORYMbFbTBCOQf2X8=" crossorigin="anonymous"></script>
        <script src="https://static.silverspoon.me/system/internal/template/arsha/02222025/vendor/waypoints/noframework.waypoints.js" integrity="sha256-yPSDPgPfPXLx/AcXtQJTgwDG9R0xbRgNAj/0fizu454=" crossorigin="anonymous"></script>
        <script src="https://static.silverspoon.me/system/internal/template/arsha/02222025/vendor/imagesloaded/imagesloaded.pkgd.min.js" integrity="sha256-htrLFfZJ6v5udOG+3kNLINIKh2gvoKqwEhHYfTTMICc=" crossorigin="anonymous"></script>
        <script src="https://static.silverspoon.me/system/internal/template/arsha/02222025/vendor/isotope-layout/isotope.pkgd.min.js" integrity="sha256-CBrpuqrMhXwcLLUd5tvQ4euBHCdh7wGlDfNz8vbu/iI=" crossorigin="anonymous"></script>
        <script src="https://static.silverspoon.me/system/internal/template/arsha/02222025/js/main.min.js" integrity="sha256-FIMS5D3Y2DsrQZbUisdL20E+GrgXBDPkSbWywUibuFk=" crossorigin="anonymous"></script>
        @stack('script')
    </body>
</html>