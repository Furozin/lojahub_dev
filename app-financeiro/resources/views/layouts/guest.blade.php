<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="https://www.lojahub.com.br/images/favicon.ico" type="image/x-icon">

    <title>LojaHub | Finanças</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <a href="{{route('login')}}" class="flex items-center justify-center">
                <img class="h-16 w-auto mr-2" src="https://app.lojahub.com.br/assets/img/Logo.svg" alt="LojaHub - Finanças">
                <span class="text-2xl text-secondary-600">Finanças</span>
            </a>
        </div>

        <div class="mt-2 sm:mx-auto sm:w-full sm:max-w-sm">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
