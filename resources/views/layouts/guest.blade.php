<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex" />

        <title>勤怠管理システム</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <style>
        .login-logo-area{
            display: flex;
            align-items: center;
            gap: 3rem;
        }
        .app-name{
            font-weight: bold;
            font-size: 30px;
        }
        .logo-hgl{
            font-size: 35px;
            font-weight: bold;
        }
    </style>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">

            {{-- <div class="login-logo-area">
                <img class="" src="{{ asset('img/logo.png') }}" alt="">
                <p class="app-name">matthew</p>
            </div> --}}
            <div class="login-logo-area">
                <p class="logo-hgl">H.G.L</p>
                <p class="app-name">matthew</p>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                <div class="">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
