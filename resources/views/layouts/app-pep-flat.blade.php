<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ProEventPay') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=albert-sans:100,200,300,400,500,600,700,800,900|inter:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @livewireStyles
        <wireui:scripts />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="font-sans antialiased" data-theme="lemonade">

        <x-jet-banner />

        <div class="min-h-screen bg-white">

            <main class="p-6">
                <div class="p-6 border shadow rounded-sm">
                    {{ $slot }}
                </div>
            </main>

        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
