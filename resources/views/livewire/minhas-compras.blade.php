<div class="min-h-screen bg-white">

    {{-- Navbar App-Version: Voltar + Nome Empresa + Sair --}}
    @if($isAppVersion)
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
                    {{ $appCustomerName ?? '' }}
                </span>

                {{-- Sair (se autenticado) --}}
                @if($authenticated)
                    <button
                        wire:click="sair"
                        class="px-3 py-2 text-xs border border-red-400 rounded-lg bg-white text-red-500 font-semibold active:bg-red-50 transition-colors flex items-center gap-1.5"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Sair</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Navbar Web (oculta no modo app-version e campanhas, pois esses layouts já têm nav próprio) --}}
    @if(!$isAppVersion && !$isCampanhasPage)
    <div class="bg-white sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 md:px-10 py-3 md:py-4">
            <div class="flex items-center justify-between">
                {{-- Logo --}}
                <a href="{{ $isCampanhasPage ? route('campanhas-home') : route('eventos-home') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                    <img src="{{ appLogo(true) }}" alt="{{ appName() }}" class="h-8 md:h-10">
                </a>

                {{-- Botões --}}
                <div class="flex items-center gap-3">
                    <a href="{{ $isCampanhasPage ? route('campanhas-home') : route('eventos-home') }}" class="px-4 py-2 text-sm font-medium text-blue-600 bg-white border-2 border-blue-600 hover:bg-blue-50 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class=""><span class="hidden sm:inline">Voltar às </span>{{ $isCampanhasPage ? 'Campanhas' : 'Eventos' }}</span>
                    </a>

                    @if($authenticated)
                        <button
                            wire:click="sair"
                            class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="hidden sm:inline">Sair</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="w-full max-w-7xl mx-auto">
        <x-jet-validation-errors />
    </div>

    <main class="{{ $isAppVersion ? 'px-4 pt-4 pb-8' : 'max-w-4xl mx-auto px-6 md:px-8 pt-6 pb-12' }}">

        @if(!$authenticated)
            {{-- Formulário de Consulta --}}
            <div class="max-w-xl mx-auto">
                <div class="text-center mb-6">
                    <h1 class="{{ $isAppVersion ? 'text-xl' : 'text-2xl md:text-3xl' }} font-bold text-gray-900 mb-1">
                        {{ $isCampanhasPage ? 'Minhas Doações' : 'Minhas Compras' }}
                    </h1>
                    <p class="text-sm text-gray-600">
                        {{ $isCampanhasPage ? 'Consulte suas adesões e contribuições em campanhas' : 'Consulte suas compras de eventos' }}
                    </p>
                </div>

                @if($errorMessage)
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm text-red-700">{{ $errorMessage }}</p>
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                    <form wire:submit.prevent="consultar" class="space-y-4">
                        {{-- CPF --}}
                        <div>
                            <x-inputs.maskable
                                wire:model.defer="doc_num"
                                label="CPF"
                                mask="###.###.###-##"
                                placeholder="000.000.000-00"
                            />
                        </div>

                        {{-- Data de nascimento: mesmos 3 dropdowns para campanhas e eventos --}}
                        <div>
                            @php
                                foreach (range(1,31) as $v) { $listaDd[str_pad($v,2,'0',STR_PAD_LEFT)] = str_pad($v,2,'0',STR_PAD_LEFT); }
                                foreach (range(1,12) as $v) { $listaMm[str_pad($v,2,'0',STR_PAD_LEFT)] = str_pad($v,2,'0',STR_PAD_LEFT); }
                                foreach (range(now()->format('Y'), now()->subYears(100)->format('Y')) as $aaaa) { $listaAaaa[$aaaa] = $aaaa; }
                            @endphp
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Data de Nascimento</label>
                            <div class="grid grid-cols-3 gap-2">
                                <x-native-select placeholder="Dia"  :options="$listaDd"   wire:model.defer="birth_date_dd" />
                                <x-native-select placeholder="Mês"  :options="$listaMm"   wire:model.defer="birth_date_mm" />
                                <x-native-select placeholder="Ano"  :options="$listaAaaa" wire:model.defer="birth_date_aaaa" />
                            </div>
                        </div>

                        {{-- Botão Consultar --}}
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="w-full px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg wire:loading.remove wire:target="consultar" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <svg wire:loading wire:target="consultar" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="consultar">Consultar</span>
                            <span wire:loading wire:target="consultar">Consultando...</span>
                        </button>
                    </form>
                </div>
            </div>
        @else
            {{-- Página de Boas-vindas e Listagem --}}

            {{-- Cabeçalho --}}
            <div class="mb-8">
                <h1 class="{{ $isAppVersion ? 'text-xl' : 'text-3xl md:text-4xl' }} font-bold text-gray-900 mb-2">
                    {{ $isCampanhasPage ? 'Minhas Doações' : 'Minhas Compras' }}
                </h1>
                <p class="text-gray-600">
                    Olá, <span class="font-semibold text-gray-900">{{ $buyer->name }}</span>!
                    {{ $isCampanhasPage ? 'Aqui estão todas as suas contribuições em campanhas.' : 'Aqui estão todas as suas compras de eventos.' }}
                </p>
            </div>

            {{-- Lista --}}
            @if($orders->count() > 0)
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900">
                        {{ $isCampanhasPage ? 'Suas Contribuições' : 'Suas Compras' }}
                        <span class="text-gray-400 font-normal text-base ml-2">({{ $orders->count() }})</span>
                    </h2>
                </div>

                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden">

                            @if($isCampanhasPage)
                                {{-- Card modo CAMPANHAS --}}
                                <div class="{{ $isAppVersion ? 'p-4' : 'p-6' }}">
                                    <div class="flex flex-col {{ $isAppVersion ? '' : 'md:flex-row md:items-center md:justify-between' }} gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-start gap-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                                                        @php
                                                            $campImg = null;
                                                            if (!empty($order->campaign->url_image_thumb)) {
                                                                $campImg = tenantAsset($order->campaign->url_image_thumb, true);
                                                            } elseif (!empty($order->campaign->url_image_banner)) {
                                                                $campImg = tenantAsset($order->campaign->url_image_banner, true);
                                                            }
                                                        @endphp
                                                        @if($campImg)
                                                            <img src="{{ $campImg }}" alt="{{ $order->campaign->name ?? '' }}" class="w-full h-full object-cover">
                                                        @else
                                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                                                        {{ $order->campaign->name ?? 'Campanha não encontrada' }}
                                                    </h3>
                                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                            </svg>
                                                            <span class="font-mono font-semibold">{{ $order->order_control }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-col {{ $isAppVersion ? '' : 'md:items-end' }} gap-2">
                                            <div class="text-right">
                                                <div class="text-sm text-gray-600">Valor Contribuído</div>
                                                <div class="text-2xl font-bold text-green-600">
                                                    {{ toMoney($order->amount_total, 'R$ ') }}
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                                    {{ $order->status === 'paid' ? 'bg-green-100 text-green-700' :
                                                       ($order->status === 'pending' ? 'bg-orange-100 text-orange-700' :
                                                       'bg-gray-100 text-gray-700') }}">
                                                    @if($order->status === 'paid') PAGO
                                                    @elseif($order->status === 'pending') PENDENTE
                                                    @else {{ strtoupper($order->status) }}
                                                    @endif
                                                </span>
                                                @if($order->campaign)
                                                    <a href="{{ campanhaUrl($order->campaign->customer_organization_slug, $order->campaign->slug, $order->id) }}"
                                                       class="px-3 py-1 text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                                        Ver Detalhes
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @else
                                {{-- Card modo EVENTOS --}}
                                <a href="{{ $isAppVersion ? route('app-version-minhas-compras-detalhes', $order->id) : route('minhas-compras-detalhes', $order->id) }}" class="block {{ $isAppVersion ? 'p-4' : 'p-6' }} hover:bg-gray-50 transition-colors">
                                    <div class="flex flex-col {{ $isAppVersion ? '' : 'md:flex-row md:items-center md:justify-between' }} gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-start gap-4">
                                                <div class="flex-shrink-0">
                                                    @if($order->event)
                                                        <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                                                            @php
                                                                $eventImage = null;
                                                                $isExternalEvent = !empty($order->event->referer_url)
                                                                    && rtrim($order->event->referer_url, '/') !== rtrim(config('domains.eventos'), '/');

                                                                $isInternalMediaPath = function ($path) {
                                                                    if (!is_string($path) || $path === '') {
                                                                        return false;
                                                                    }

                                                                    $internalPrefixes = [
                                                                        '/storage/',
                                                                        'storage/',
                                                                        'events/',
                                                                        'campaigns/',
                                                                        'customers/',
                                                                        'images_eventos/',
                                                                        'images_patrocinadores/',
                                                                        'images_customers_logo/',
                                                                    ];

                                                                    foreach ($internalPrefixes as $prefix) {
                                                                        if (str_starts_with($path, $prefix)) {
                                                                            return true;
                                                                        }
                                                                    }

                                                                    return false;
                                                                };

                                                                if ($isExternalEvent && ($order->event->url_image ?? false) && !$isInternalMediaPath($order->event->url_image)) {
                                                                    $eventImage = $order->event->referer_url . '/' . $order->event->url_image;
                                                                } elseif ($order->event->url_image) {
                                                                    $eventImage = tenantAsset($order->event->url_image, true);
                                                                }
                                                            @endphp
                                                            @if($eventImage)
                                                                <img src="{{ $eventImage }}" alt="{{ $order->event->event_name }}" class="w-full h-full object-cover">
                                                            @else
                                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    @if($order->event && $order->event->organizer)
                                                        <div class="text-xs font-medium text-gray-500 mb-0.5">
                                                            {{ $order->event->organizer->organizer_name_full }}
                                                        </div>
                                                    @endif
                                                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                                                        {{ $order->event->event_name ?? 'Evento não encontrado' }}
                                                    </h3>
                                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                            </svg>
                                                            <span class="font-mono font-semibold">{{ $order->order_control }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col {{ $isAppVersion ? '' : 'md:items-end' }} gap-2">
                                            <div class="text-right">
                                                <div class="text-sm text-gray-600">Valor Total</div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ toMoney($order->order_amount, 'R$ ') }}
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                                    {{ in_array($order->status, ['paid', 'approved']) ? 'bg-green-100 text-green-700' :
                                                       ($order->status === 'pending' ? 'bg-orange-100 text-orange-700' :
                                                       'bg-gray-100 text-gray-700') }}">
                                                    @if(in_array($order->status, ['paid', 'approved'])) PAGO
                                                    @elseif($order->status === 'pending') PENDENTE
                                                    @else {{ strtoupper($order->status) }}
                                                    @endif
                                                </span>
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endif

                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        {{ $isCampanhasPage ? 'Nenhuma contribuição encontrada' : 'Nenhuma compra encontrada' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ $isCampanhasPage ? 'Você ainda não realizou nenhuma contribuição em campanhas.' : 'Você ainda não realizou nenhuma compra de ingressos.' }}
                    </p>
                </div>
            @endif
        @endif

    </main>
</div>

