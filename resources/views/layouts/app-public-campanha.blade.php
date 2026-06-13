<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Meta Tags Dinâmicas (Open Graph, Twitter Cards) -->
        @stack('meta')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @livewireStyles
        <wireui:scripts />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')

    </head>
    <body class="font-sans antialiased m-0 p-0" style="background-color: var(--campaign-color-primary, #3B82F6)15;">
        <x-dialog />
        <x-notifications />
        <main class="m-0 p-0">
            {{ $slot }}
        </main>
        @livewireScripts
        @wireUiScripts
    </body>
</html>

