<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- White Label: Título dinâmico --}}
        <title>{{ config('app.name', appName()) }} | {{ appName() }}</title>

        {{-- White Label: Meta tags para SEO --}}
        <meta name="description" content="{{ appMeta('description') }}">
        <meta name="keywords" content="{{ appMeta('keywords') }}">

        {{-- Open Graph / Facebook --}}
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ appMeta('title') }}">
        <meta property="og:description" content="{{ appMeta('description') }}">
        <meta property="og:image" content="{{ appMeta('image') }}">

        {{-- Twitter --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ appMeta('title') }}">
        <meta name="twitter:description" content="{{ appMeta('description') }}">
        <meta name="twitter:image" content="{{ appMeta('image') }}">

        {{-- White Label: Favicon dinâmico --}}
        <link rel="icon" type="image/x-icon" href="{{ appFavicon(true) }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=albert-sans:100,200,300,400,500,600,700,800,900|inter:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- White Label: CSS com variáveis de cores dinâmicas --}}
        {!! appColorsCss() !!}

        <!-- Styles -->
        @livewireStyles
        <wireui:scripts />

        <!-- CKEditor 5 (Mesmo do sistema de eventos) -->
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/decoupled-document/ckeditor.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    {{-- <body class="font-sans antialiased bg-gray-200"> --}}
    <body class="font-sans antialiased">

        <x-notifications position="top-right" />

        <x-dialog z-index="z-50" blur="md" align="center" />

        <x-jet-banner />

        <div class="flex flex-col min-h-screen">

            <div class="flex-grow px-6">

                @livewire('navigation.navigation-menu-pep-auth')

                <main >
                    {{ $slot }}
                </main>

            </div>

            <footer class="flex justify-center items-center gap-4 px-4 py-4 bg-gray-100 mt-auto">
                <div class="text-center text-sm text-gray-600">{{ appName() }} - Copyright © {{ now()->format('Y') }} - Todos os direitos reservados!</div>
            </footer>

        </div>

        @stack('modals')

        @livewireScripts
        {{-- @powerGridScripts --}}

    </body>
</html>
