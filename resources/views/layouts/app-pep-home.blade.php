<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- White Label: Título e meta tags dinâmicos --}}
        <title>HOME | {{ appName() }}</title>
        <meta name="description" content="{{ appMeta('description') }}">
        <meta name="keywords" content="{{ appMeta('keywords') }}">

        {{-- Open Graph --}}
        <meta property="og:title" content="{{ appMeta('title') }}">
        <meta property="og:description" content="{{ appMeta('description') }}">
        <meta property="og:image" content="{{ appMeta('image') }}">

        {{-- White Label: Favicon dinâmico --}}
        <link rel="icon" type="image/x-icon" href="{{ appFavicon(true) }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=albert-sans:100,200,300,400,500,600,700,800,900|inter:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        {{-- White Label: CSS com cores dinâmicas --}}
        {!! appColorsCss() !!}

        <!-- Styles -->
        @livewireStyles
        <wireui:scripts />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="font-sans antialiased bg-white">

        <x-notifications z-index="z-50" />

        <x-dialog z-index="z-50" blur="md" align="center" />

        <x-jet-banner />

        <div class="flex flex-col min-h-screen">

            <main class="flex-1">
                {{ $slot }}
            </main>

            <footer class="mt-4 flex justify-center items-center gap-4 px-4 py-4 bg-gray-50 border-t border-gray-200">
                <div class="text-center text-xs uppercase tracking-widest text-gray-400">{{ appName() }} &middot; Copyright &copy; {{ now()->format('Y') }} &middot; Todos os direitos reservados</div>
            </footer>

        </div>

        @stack('modals')

        @livewireScripts
        @wireUiScripts
    </body>
</html>
