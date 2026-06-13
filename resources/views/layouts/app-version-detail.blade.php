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
            /* Modo app-version: sempre mobile, centrado, fundo escuro nas laterais */
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

            /* Remove efeitos hover e adiciona efeitos active para mobile */
            .app-version button:active,
            .app-version a:active {
                opacity: 0.7;
                transform: scale(0.98);
            }

            /* Touch targets mínimos de 44x44px */
            .app-version button,
            .app-version a {
                min-height: 44px;
                min-width: 44px;
            }

            /* Força layout mobile removendo md: breakpoints via CSS */
            .app-version .app-container .force-mobile-flex {
                display: flex !important;
                flex-direction: column !important;
            }
        </style>
    </head>
    <body class="app-version">
        <div class="app-container font-sans text-gray-900 antialiased">
            {{-- Header simples: Voltar + Nome da Empresa + Compras --}}
            <div class="bg-white sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm">
                <div class="max-w-[480px] mx-auto px-4 py-3">
                    <div class="flex items-center justify-between">
                        {{-- Voltar --}}
                        <a
                            href="{{ route('app-version-home') }}"
                            class="flex items-center gap-1 text-sm text-gray-600 active:text-gray-900 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            <span class="font-medium">Voltar</span>
                        </a>

                        {{-- Nome da empresa --}}
                        <span class="text-sm font-bold text-gray-900 uppercase truncate mx-3 flex-1 text-center">
                            {{ session('app_customer_name', '') }}
                        </span>

                        {{-- Botão Compras --}}
                        <a
                            href="{{ route('app-version-minhas-compras') }}"
                            class="px-3 py-2 text-xs border border-green-500 rounded-lg bg-white text-green-600 font-semibold active:bg-green-50 transition-colors flex items-center gap-1.5"
                        >
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                            <span>Compras</span>
                        </a>
                    </div>
                </div>
            </div>

            <main>
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>
