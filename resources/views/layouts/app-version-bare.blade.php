<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- White Label: Título e meta tags dinâmicos --}}
        <title>{{ appName() }}</title>
        <meta name="description" content="{{ appMeta('description') }}">
        <link rel="icon" type="image/x-icon" href="{{ appFavicon(true) }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=albert-sans:100,200,300,400,500,600,700,800,900|inter:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        {{-- White Label: CSS com cores dinâmicas --}}
        {!! appColorsCss() !!}

        @livewireStyles
        <wireui:scripts />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Estilos específicos do app-version --}}
        <style>
            body.app-version {
                background-color: #1a1a1a;
                min-height: 100vh;
            }

            .app-container {
                max-width: 480px;
                margin: 0 auto;
                background-color: white;
                min-height: 100vh;
                position: relative;
            }

            .app-version button:active,
            .app-version a:active {
                opacity: 0.7;
                transform: scale(0.98);
            }

            .app-version button,
            .app-version a {
                min-height: 44px;
                min-width: 44px;
            }
        </style>
    </head>
    <body class="app-version">
        <div class="app-container font-sans text-gray-900 antialiased">
            {{-- Sem navigation - o conteúdo define seu próprio header --}}
            <main>
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>
