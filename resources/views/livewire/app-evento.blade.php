<div class="min-h-screen bg-white">

    {{-- LIVEWIRE - LOADER --}}
    <div wire:loading.class.remove="hidden" class="hidden fixed inset-0 z-[999] flex items-center justify-center" style="background: rgba(255,255,255,0.80); backdrop-filter: blur(6px);">
        <div class="flex flex-col items-center gap-4">
            <img src="{{ asset('/assets/loader.v2.svg') }}" alt="" class="w-16 h-16">
            <span class="text-gray-500 text-sm uppercase tracking-widest font-light">Aguarde…</span>
        </div>
    </div>
    {{-- LIVEWIRE - LOADER FIM --}}

    {{-- IMAGE URL LOGIC - Handle external events from other instances --}}
    @php
        // Detect if event is from another instance
        $isExternalEvent = !empty($event->referer_url)
            && rtrim($event->referer_url, '/') !== rtrim(config('domains.eventos'), '/');

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

        // Build background image URL
        $urlImageBg = null;
        if ($event->url_image_bg ?? false) {
            if ($isExternalEvent && !$isInternalMediaPath($event->url_image_bg)) {
                $urlImageBg = $event->referer_url . '/' . $event->url_image_bg;
            } else {
                $urlImageBg = str_starts_with($event->url_image_bg, '/storage/')
                    ? asset($event->url_image_bg)
                    : tenantAsset($event->url_image_bg, true);
            }
        }

        // Build event logo URL - if event has logo, show it (from origin if external)
        $urlImageLogo = null;
        if ($event->url_image_logo ?? false) {
            if ($isExternalEvent && !$isInternalMediaPath($event->url_image_logo)) {
                $urlImageLogo = $event->referer_url . '/' . $event->url_image_logo;
            } else {
                $urlImageLogo = str_starts_with($event->url_image_logo, '/storage/')
                    ? asset($event->url_image_logo)
                    : tenantAsset($event->url_image_logo, true);
            }
        }

        // Build customer logo URL (fallback) - if customer has logo, show it (external events won't have customer logo)
        $urlCustomerLogo = null;
        if ($event->customer->url_image_logo ?? false) {
            $urlCustomerLogo = str_starts_with($event->customer->url_image_logo, '/storage/')
                ? asset($event->customer->url_image_logo)
                : tenantAsset($event->customer->url_image_logo, true);
        }

        // Build main event image URL
        $urlImage = null;
        if ($event->url_image ?? false) {
            if ($isExternalEvent && !$isInternalMediaPath($event->url_image)) {
                $urlImage = $event->referer_url . '/' . $event->url_image;
            } else {
                $urlImage = str_starts_with($event->url_image, '/storage/')
                    ? asset($event->url_image)
                    : tenantAsset($event->url_image, true);
            }
        }

        $colorPrimary   = $event->color_primary   ?? $event->color_default ?? '#6366f1';
        $colorSecondary = $event->color_secondary  ?? $event->color_default ?? '#8b5cf6';
        $colorDefault   = $event->color_default    ?? '#6366f1';
        $colorInverse   = $event->color_default_inverse ?? '#ffffff';
    @endphp

    <style>@keyframes heroBgDrift{0%{transform:scale(1.08) translate(0%,0%)}25%{transform:scale(1.13) translate(-1.5%,-1%)}50%{transform:scale(1.10) translate(1%,-2%)}75%{transform:scale(1.14) translate(-0.5%,1%)}100%{transform:scale(1.08) translate(0%,0%)}}.hero-bg-animate{animation:heroBgDrift 24s ease-in-out infinite;will-change:transform;}</style>

    {{-- ═══════════════════════════════════════════════════════
         HERO
    ═══════════════════════════════════════════════════════ --}}
    <section class="relative w-full overflow-hidden" style="min-height: {{ $isAppVersion ? '260px' : '420px' }};">

        {{-- Hero background --}}
        @if ($urlImageBg)
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat hero-bg-animate" style="background-image: url('{{ $urlImageBg }}');"></div>
            <div class="absolute inset-0" style="background: linear-gradient(160deg, {{ $colorPrimary }}cc 0%, rgba(10,10,20,0.88) 100%);"></div>
        @elseif ($urlImage)
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat hero-bg-animate" style="background-image: url('{{ $urlImage }}'); filter: blur(2px) brightness(0.35);"></div>
            <div class="absolute inset-0" style="background: linear-gradient(160deg, {{ $colorPrimary }}88 0%, rgba(10,10,20,0.92) 100%);"></div>
        @else
            <div class="absolute inset-0" style="background: linear-gradient(135deg, {{ $colorPrimary }} 0%, {{ $colorSecondary }} 50%, rgba(10,10,20,1) 100%);"></div>
        @endif

        {{-- Decorative glow --}}
        <div class="absolute -top-20 -left-20 w-96 h-96 rounded-full opacity-20 blur-3xl pointer-events-none" style="background: {{ $colorPrimary }};"></div>
        <div class="absolute -bottom-10 -right-10 w-72 h-72 rounded-full opacity-15 blur-3xl pointer-events-none" style="background: {{ $colorSecondary }};"></div>

        {{-- Conteúdo do Hero --}}
        <div class="relative z-10 h-full flex flex-col justify-between {{ $isAppVersion ? 'px-4 py-5 pb-12' : 'max-w-7xl mx-auto px-6 md:px-10 py-6 md:py-10 pb-16 md:pb-28' }}">

            {{-- DATA DO EVENTO --}}
            @php
                $eventDate       = $event->event_datetime_start  ? \Carbon\Carbon::parse($event->event_datetime_start)  : null;
                $eventDateFinish = $event->event_datetime_finish ? \Carbon\Carbon::parse($event->event_datetime_finish) : null;
                $eventIsOngoing  = $eventDate && $eventDate->isPast() && (!$eventDateFinish || $eventDateFinish->isFuture());
                $eventIsPast     = $eventDate && $eventDate->isPast() && (!$eventIsOngoing);
            @endphp

            {{-- NAVBAR / LOGO + DATA + BUY BUTTON --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between w-full">
                <div class="flex items-center gap-4 min-w-0">
                    @if ($urlImageLogo)
                        <img class="{{ $isAppVersion ? 'h-10' : 'h-12 md:h-14' }} w-auto drop-shadow-lg flex-shrink-0" src="{{ $urlImageLogo }}" alt="">
                    @elseif ($urlCustomerLogo)
                        <img class="{{ $isAppVersion ? 'h-10' : 'h-12 md:h-14' }} w-auto drop-shadow-lg flex-shrink-0" src="{{ $urlCustomerLogo }}" alt="">
                    @elseif(!$isAppVersion)
                        <img class="h-12 md:h-14 w-auto drop-shadow-lg flex-shrink-0" src="{{ appLogo(true) }}" alt="{{ appName() }}">
                    @endif

                    {{-- Data viva ao lado da logo --}}
                    @if ($eventDate && !$eventIsPast)
                        <div class="hidden md:flex flex-col min-w-0">
                            <span class="text-white text-sm md:text-base font-bold drop-shadow-lg truncate">
                                {{ $eventDate->locale('pt_BR')->isoFormat('D [de] MMMM [de] YYYY [·] HH[h]mm') }}
                            </span>
                            <span class="text-xs font-semibold drop-shadow" style="color: {{ $colorDefault }};">
                                {{ $eventDate->locale('pt_BR')->diffForHumans() }}
                            </span>
                        </div>
                    @endif
                </div>

                <style>
                    @keyframes eventPastPulse {
                        0%, 100% { box-shadow: 0 0 0 0 rgba(234,88,12,0.7), 0 4px 20px rgba(234,88,12,0.45); }
                        50%       { box-shadow: 0 0 0 10px rgba(234,88,12,0), 0 4px 20px rgba(234,88,12,0.45); }
                    }
                    @keyframes eventOngoingPulse {
                        0%, 100% { box-shadow: 0 0 0 0 rgba(22,163,74,0.7), 0 4px 20px rgba(22,163,74,0.45); }
                        50%       { box-shadow: 0 0 0 10px rgba(22,163,74,0), 0 4px 20px rgba(22,163,74,0.45); }
                    }
                    @keyframes btnCompraPulse {
                        0%, 100% { box-shadow: 0 0 0 0 var(--btn-glow), 0 4px 20px var(--btn-glow); }
                        50%       { box-shadow: 0 0 0 10px transparent, 0 4px 20px var(--btn-glow); }
                    }
                    .badge-evento-passado { animation: eventPastPulse    2.2s ease-in-out infinite; }
                    .badge-evento-ongoing { animation: eventOngoingPulse 2.2s ease-in-out infinite; }
                    .btn-comprar-agora    { animation: btnCompraPulse    2.2s ease-in-out infinite; }
                </style>

                @if ($eventIsPast)
                    <span class="badge-evento-passado self-start md:self-auto flex-shrink-0 inline-flex items-center gap-2 {{ $isAppVersion ? 'px-5 py-2 text-xs' : 'px-6 py-2.5 text-sm' }} font-bold uppercase tracking-wider rounded-full"
                          style="background: linear-gradient(135deg, #ea580c 0%, #f97316 100%); color: #ffffff;">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        EVENTO JÁ REALIZADO
                    </span>
                @elseif ($eventIsOngoing)
                    <span class="badge-evento-ongoing self-start md:self-auto flex-shrink-0 inline-flex items-center {{ $isAppVersion ? 'px-5 py-2 text-xs' : 'px-6 py-2.5 text-sm' }} font-bold uppercase tracking-wider rounded-full"
                          style="background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); color: #ffffff;">
                        EM ANDAMENTO
                    </span>
                @else
                    <a href="#comprar"
                       class="btn-comprar-agora self-start md:self-auto flex-shrink-0 inline-flex items-center {{ $isAppVersion ? 'px-4 py-1.5 text-xs' : 'px-4 py-1.5 text-xs md:px-6 md:py-2.5 md:text-sm' }} font-semibold uppercase tracking-wider rounded-full transition-all duration-200 hover:opacity-90 hover:scale-105 active:scale-95"
                       style="--btn-glow: {{ $colorDefault }}99; background-color: {{ $colorDefault }}; color: {{ $colorInverse }};">
                        COMPRAR AGORA MESMO
                    </a>
                @endif
            </div>

            {{-- TÍTULO E DESCRIÇÃO --}}
            <div class="w-full {{ $isAppVersion ? 'mt-6 text-center' : 'mt-8 md:mt-12' }}">

                @php
                    $locationParts = array_filter([
                        $event->city ?? null,
                        $event->state ?? null,
                    ]);
                    $heroLocationText = implode(', ', $locationParts);
                    if (!$heroLocationText && ($event->organizer->organization->organization_name ?? false)) {
                        $heroLocationText = $event->organizer->organization->organization_name;
                    }
                @endphp
                @if ($heroLocationText)
                    <div class="inline-flex items-center gap-1.5 mb-3 px-3 py-1 rounded-full text-xs font-medium uppercase tracking-widest"
                         style="background: {{ $colorPrimary }}33; color: {{ $colorInverse }}; border: 1px solid {{ $colorPrimary }}55;">
                        <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $heroLocationText }}
                    </div>
                @endif

                <h1 class="{{ $isAppVersion ? 'text-2xl' : 'text-3xl md:text-5xl lg:text-6xl' }} font-extrabold text-white leading-tight uppercase tracking-tight drop-shadow-xl">
                    {{ $event->event_name ?? '--' }}
                </h1>

                {{-- ORGANIZADOR + DESCRIÇÃO (mesma linha, separados por —) --}}
                @php
                    $orgName = $event->organizer->organizer_name_full ?? ($event->customer->name_corporate ?? null);
                    $evtDesc = $event->event_description ?? null;
                    $subLine = collect([$orgName, $evtDesc])->filter()->implode(' — ');
                @endphp
                @if ($subLine)
                    <p class="{{ $isAppVersion ? 'text-sm mt-2' : 'text-base md:text-lg mt-3' }} font-medium text-white/60 uppercase tracking-wide leading-relaxed max-w-4xl">
                        {{ $subLine }}
                    </p>
                @endif

                {{-- DATA DO EVENTO (mobile only - desktop shows next to logo) --}}
                @if ($eventDate && !$eventIsPast)
                    <div class="mt-4 flex flex-col items-center gap-1 md:hidden">
                        <span class="text-white text-base font-bold drop-shadow-lg text-center">
                            {{ $eventDate->locale('pt_BR')->isoFormat('D [de] MMMM [de] YYYY [·] HH[h]mm') }}
                        </span>
                        <span class="text-xs font-semibold drop-shadow" style="color: {{ $colorDefault }};">
                            {{ $eventDate->locale('pt_BR')->diffForHumans() }}
                        </span>
                    </div>
                @endif
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
         IMAGEM PRINCIPAL DO EVENTO (thumbnail)
    ═══════════════════════════════════════════════════════ --}}
    @if ($urlImage)
        <div id="image_thumbnail" class="relative z-10 w-full flex justify-center {{ $isAppVersion ? 'px-3 -mt-6' : 'max-w-5xl mx-auto px-6 md:px-10 -mt-10 md:-mt-16' }}">
            <div class="w-full rounded-2xl overflow-hidden shadow-xl ring-1 ring-gray-200"
                 style="box-shadow: 0 25px 50px rgba(0,0,0,0.15);">
                <img class="w-full h-auto block" src="{{ $urlImage }}" alt="{{ $event->event_name }}"
                     style="background-color: {{ $colorDefault }};">
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════
         BODY PRINCIPAL
    ═══════════════════════════════════════════════════════ --}}
    <main class="{{ $isAppVersion ? 'px-3 pb-8 pt-6' : 'max-w-5xl mx-auto px-6 md:px-10 pb-16 pt-8 md:pt-10' }}">

        <div id="div_comprar_agora" class="w-full flex flex-col">

            @if ($ticketTypeSelected ?? false)

                {{-- LOTE SELECIONADO --}}
                @php
                    // LISTA DDD
                    $listaDdd = ['11','12','13','14','15','16','17','18','19','21','22','24','27','28','31','32','33','34','35','37','38','41','42','43','44','45','46','47','48','49','51','53','54','55','61','62','63','64','65','66','67','68','69','71','73','74','75','77','79','81','82','83','84','85','86','87','88','89','91','92','93','94','95','96','97','98','99'];

                    // LISTA DIAS
                    foreach (range(1,31) as $key => $value)
                    {
                        $key   = str_pad($value , 2 , '0' , STR_PAD_LEFT);
                        $value = str_pad($value , 2 , '0' , STR_PAD_LEFT);
                        //
                        $listaDd[$key] = $value;
                    }

                    // LISTA MES
                    foreach (range(1,12) as $key => $value)
                    {
                        $key   = str_pad($value , 2 , '0' , STR_PAD_LEFT);
                        $value = str_pad($value , 2 , '0' , STR_PAD_LEFT);
                        //
                        $listaMm[$key] = $value;
                    }

                    // LISTA ANO NASCIMENTO
                    foreach (range(now()->format('Y'),now()->subYear(100)->format('Y')) as $aaaa)
                    {
                        $listaAaaa[$aaaa] = $aaaa;
                    }

                    // LISTA ANO CARD
                    foreach (range(now()->format('Y'),now()->addYear(10)->format('Y')) as $aaaa)
                    {
                        $listaCardAaaa[$aaaa] = $aaaa;
                    }
                @endphp

                {{-- ─── CHECKOUT CONTAINER ─── --}}
                <div id="comprar_ingresso" class="w-full {{ $isAppVersion ? 'mb-4' : 'max-w-2xl mx-auto mb-8' }}">

                    {{-- HEADER DO CHECKOUT --}}
                    <div class="w-full flex items-start justify-between gap-3 mt-2 mb-5">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: {{ $colorPrimary }};">
                                {{ $ticketTypeSelected->sales_label_btn ?? 'COMPRAR' }}
                            </div>
                            <div class="{{ $isAppVersion ? 'text-xl' : 'text-2xl md:text-3xl' }} font-bold text-gray-800 leading-tight uppercase">
                                {{ $ticketTypeSelected->sales_label_title ?? $ticketTypeSelected->ticket_name ?? 'INGRESSO' }}
                            </div>
                        </div>
                        <button wire:click="cancelTicketType" type="button"
                                class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium uppercase tracking-wider transition-all duration-200 hover:bg-gray-100 text-gray-400 border border-gray-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            cancelar
                        </button>
                    </div>

                    {{-- CARD: INGRESSO SELECIONADO --}}
                    <div class="w-full flex items-center justify-between rounded-2xl px-5 py-4 mb-3 bg-white"
                         style="border: 1px solid #e2e8f0; box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                        <div class="flex-1 min-w-0">
                            @if ($ticketTypeSelected->ticket_name ?? false)
                                <div class="text-xs font-medium uppercase tracking-wider mb-0.5" style="color: {{ $colorPrimary }};">
                                    {{ $ticketTypeSelected->sales_label_title ?? null }}
                                </div>
                                <div class="font-bold text-gray-800 {{ $isAppVersion ? 'text-base' : 'text-lg md:text-xl' }} uppercase truncate">
                                    {{ $ticketTypeSelected->ticket_name }}
                                </div>
                            @else
                                <div class="font-bold text-gray-800 {{ $isAppVersion ? 'text-base' : 'text-lg md:text-xl' }} uppercase truncate">
                                    {{ $ticketTypeSelected->sales_label_title ?? 'INGRESSO' }}
                                </div>
                            @endif
                            @if ($ticketTypeSelected->ticket_description ?? false)
                                <div class="text-gray-400 text-xs mt-0.5 truncate">{{ $ticketTypeSelected->ticket_description }}</div>
                            @endif
                        </div>
                        <div class="flex-shrink-0 text-right ml-4">
                            <div class="text-xs text-gray-500 font-semibold mb-0.5 uppercase tracking-widest">por ingresso</div>
                            <div class="flex items-baseline gap-1">
                                <span class="{{ $isAppVersion ? 'text-sm' : 'text-base' }} font-bold" style="color: {{ $colorPrimary }};">R$</span>
                                <span class="{{ $isAppVersion ? 'text-2xl' : 'text-3xl md:text-4xl' }} font-extrabold leading-none text-gray-800">{{ toMoney($ticketTypeSelected->price ?? 0) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- AVISO NÃO REEMBOLSÁVEL --}}
                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl mb-5 text-xs font-medium uppercase tracking-wide bg-red-50 border border-red-100 text-red-500">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        Valor não reembolsável após 7 dias
                    </div>

                    {{-- ─── FORMULÁRIO ─── --}}
                    <div class="w-full space-y-5">

                        {{-- CARD: DADOS DO COMPRADOR --}}
                        <div class="rounded-2xl overflow-hidden bg-white" style="border: 1px solid #e2e8f0; box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                            <div class="px-5 py-3 flex items-center gap-3"
                                 style="background: {{ $colorPrimary }}0d; border-bottom: 1px solid #e2e8f0;">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                                     style="background: {{ $colorPrimary }}; color: {{ $colorInverse }};">1</div>
                                <div>
                                    <div class="font-bold text-gray-700 uppercase text-sm tracking-wide">Dados do comprador</div>
                                    <div class="text-gray-400 text-xs">Informe os dados abaixo</div>
                                </div>
                            </div>
                            <div class="p-5 space-y-4">
                                <div class="w-full {{ $isAppVersion ? 'flex flex-col gap-4' : 'flex gap-4' }}">
                                    <div class="w-full {{ $isAppVersion ? '' : 'md:w-1/2' }}">
                                        <x-input
                                            label="* Nome"
                                            placeholder=""
                                            id="comprador_nome"
                                            wire:model.defer="comprador_nome"
                                            class="rounded capitalize"
                                            required
                                        />
                                    </div>
                                    <div class="w-full {{ $isAppVersion ? '' : 'md:w-1/2' }}">
                                        <x-input
                                            label="* Sobrenome"
                                            placeholder=""
                                            wire:model.defer="comprador_sobrenome"
                                            class="rounded capitalize"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="w-full {{ $isAppVersion ? 'flex flex-col gap-4' : 'flex gap-4' }}">
                                    <div class="w-full {{ $isAppVersion ? '' : 'md:w-1/2' }}">
                                        <x-input
                                            label="* Email"
                                            type="email"
                                            class="lowercase"
                                            wire:model.defer="comprador_email"
                                            required
                                        />
                                    </div>
                                    <div class="w-full {{ $isAppVersion ? '' : 'md:w-1/2' }}">
                                        <x-inputs.maskable
                                            label="* CPF"
                                            mask="###.###.###-##"
                                            placeholder="____ . ____ . ____ - ___"
                                            wire:model.defer="comprador_cpf"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="w-full {{ $isAppVersion ? 'flex flex-col gap-4' : 'flex gap-4' }}">
                                    <div class="w-full {{ $isAppVersion ? '' : 'md:w-1/2' }}">
                                        <div class="{{ setClass('divContentLabel') }}">* Data Nascimento</div>
                                        <div class="w-full flex mt-1">
                                            <div class="w-1/3">
                                                <x-native-select
                                                    placeholder="DIA"
                                                    :options="$listaDd ?? []"
                                                    wire:model.defer="comprador_nascimento_dd"
                                                    class="rounded-r-none"
                                                    required />
                                            </div>
                                            <div class="w-1/3">
                                                <x-native-select
                                                    placeholder="MÊS"
                                                    :options="$listaMm ?? []"
                                                    wire:model.defer="comprador_nascimento_mm"
                                                    class="rounded-none"
                                                    required />
                                            </div>
                                            <div class="w-1/3">
                                                <x-native-select
                                                    placeholder="ANO"
                                                    :options="$listaAaaa ?? []"
                                                    wire:model.defer="comprador_nascimento_aaaa"
                                                    class="rounded-l-none"
                                                    required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full {{ $isAppVersion ? '' : 'md:w-1/2' }}">
                                        <div class="{{ setClass('divContentLabel') }}">* Telefone</div>
                                        <div class="w-full flex mt-1">
                                            <div class="w-1/2">
                                                <x-native-select
                                                    placeholder="DDD"
                                                    :options="$listaDdd ?? []"
                                                    wire:model.defer="comprador_celular_ddd"
                                                    class="rounded-r-none"
                                                    required />
                                            </div>
                                            <div class="w-1/2">
                                                <x-inputs.maskable
                                                    mask="['####-####','#####-####']"
                                                    placeholder="Número"
                                                    wire:model.defer="comprador_celular_num"
                                                    class="rounded-l-none"
                                                    required
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CARD: QUANTIDADE DE INGRESSOS --}}
                        <div class="rounded-2xl overflow-hidden bg-white" style="border: 1px solid #e2e8f0; box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                            <div class="px-5 py-3 flex items-center gap-3"
                                 style="background: {{ $colorPrimary }}0d; border-bottom: 1px solid #e2e8f0;">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                                     style="background: {{ $colorPrimary }}; color: {{ $colorInverse }};">2</div>
                                <div>
                                    <div class="font-bold text-gray-700 uppercase text-sm tracking-wide">Quantidade</div>
                                    <div class="text-gray-400 text-xs">Selecione a quantidade</div>
                                </div>
                            </div>
                            <div class="p-5">
                                <x-native-select
                                    id="comprador_ingressos_qtd"
                                    wire:model="comprador_ingressos_qtd"
                                    class="rounded-xl uppercase"
                                    required >
                                        <option value="0">Selecione a quantidade</option>
                                        @foreach ($listaParticipantes as $listaParticipanteKey => $listaParticipanteItem)
                                        <option value="{{ $listaParticipanteKey }}" class="uppercase">{{ $listaParticipanteItem['label'] }}</option>
                                        @endforeach
                                </x-native-select>

                                {{-- CARDS DOS PARTICIPANTES --}}
                                @if ($comprador_ingressos_qtd ?? false)
                                    <div class="flex flex-col mt-5 gap-4">
                                        @foreach (range(1, $comprador_ingressos_qtd ?? 1) as $participanteKey => $participanteInput)
                                            @php
                                                $label = '* nome do ' . (!in_array($event->sales_label_item,['casal']) ? $event->sales_label_item : 'participante');
                                            @endphp
                                            <div class="rounded-xl p-4 bg-gray-50" style="border: 1px solid {{ $colorPrimary }}10;">
                                                <div class="flex items-center gap-2 mb-3 pb-2" style="border-bottom: 1px solid {{ $colorPrimary }}10;">
                                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                                                         style="background: {{ $colorPrimary }}15; color: {{ $colorPrimary }}; border: 1px solid {{ $colorPrimary }}30;">
                                                        {{ $participanteInput }}
                                                    </div>
                                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                        DADOS {{ $event->sales_label_item ?? 'Participante' }}
                                                        @if(($comprador_ingressos_qtd ?? 1) > 1) {{ $participanteInput }} @endif
                                                    </span>
                                                </div>
                                                <div class="w-full">
                                                    <x-input
                                                        label="{{ $label }}"
                                                        wire:model.defer="participantes.{{ $participanteInput }}"
                                                        placeholder="Nome Completo"
                                                        class="w-full uppercase"
                                                    />
                                                </div>

                                                {{-- FIELDS QUESTIONS --}}
                                                @foreach (collect($event_questions_item ?? [])->sortBy('input_order') as $questions_key => $questions_item)
                                                    @php
                                                        if(in_array($ticketTypeSelected->id, $questions_item['input_hidden_lotes'] ?? []))
                                                        {
                                                            continue;
                                                        }

                                                        $name        = 'participantes_questions.' . $participanteInput . '.' . $questions_key;
                                                        $label       = $questions_item['input_label'] ?? $questions_key;
                                                        $placeholder = $questions_item['input_placeholder'] ?? '';
                                                        $type        = $questions_item['input_type'] ?? 'text';
                                                        $required    = false;
                                                        $options     = $questions_item['input_type_options'] ?? [];

                                                        if(!empty($options)) asort($options);

                                                        if($questions_item['input_required'] ?? false)
                                                        {
                                                            $label    = '* ' . $label;
                                                        }
                                                    @endphp

                                                    <div class="w-full mt-3">
                                                        @if ($type == 'text')
                                                            <x-input
                                                                label="{{ $label }}"
                                                                wire:model.defer="{{ $name }}"
                                                                placeholder="{{ $placeholder }}"
                                                                class="w-full uppercase"
                                                            />
                                                        @elseif ($type == 'select')
                                                            <x-native-select
                                                                label="{{ $label }}"
                                                                wire:model.defer="{{ $name }}"
                                                                title="{{ $placeholder }}"
                                                                class="w-full uppercase"
                                                            >
                                                                <option value="">---</option>
                                                                @foreach ($options ?? [] as $option_item)
                                                                    <option value="{{ $option_item }}">{{ $option_item }}</option>
                                                                @endforeach
                                                            </x-native-select>
                                                        @else
                                                            <x-input
                                                                label="{{ $label }}"
                                                                wire:model.defer="{{ $name }}"
                                                                placeholder="{{ $placeholder }}"
                                                                class="w-full uppercase"
                                                            />
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- CARD: RESUMO E FINALIZAR --}}
                        @if ($comprador_ingressos_qtd ?? false)

                            @if (session('conclusao_error'))
                                <div class="flex items-start gap-3 px-5 py-4 rounded-2xl bg-red-50 border border-red-200">
                                    <svg class="w-5 h-5 flex-shrink-0 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                    </svg>
                                    <div>
                                        <div class="text-red-700 font-bold uppercase text-sm">{{ __(session('conclusao_error')) }}</div>
                                        @if (session('conclusao_error_sub'))
                                            <div class="text-red-500 text-xs mt-0.5 uppercase">{{ __(session('conclusao_error_sub')) }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if ($this->orderPrice ?? false)

                                {{-- RESUMO DE VALOR --}}
                                <div class="rounded-2xl overflow-hidden bg-white" style="border: 1px solid #e2e8f0; box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                                    <div class="px-5 py-3 flex items-center gap-3"
                                         style="background: {{ $colorPrimary }}0d; border-bottom: 1px solid #e2e8f0;">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                                             style="background: {{ $colorPrimary }}; color: {{ $colorInverse }};">3</div>
                                        <div class="font-bold text-gray-700 uppercase text-sm tracking-wide">Resumo da compra</div>
                                    </div>
                                    <div class="p-5">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="text-gray-500 text-xs uppercase tracking-wide mb-0.5">Total</div>
                                                <div class="text-xs text-gray-400">** Encargos e descontos serão calculados no pagamento</div>
                                            </div>
                                            <div class="text-right">
                                                <div class="{{ $isAppVersion ? 'text-2xl' : 'text-3xl md:text-4xl' }} font-extrabold"
                                                     style="color: {{ $colorPrimary }};">
                                                    {{ toMoney($this->orderPrice ?? 0, 'R$ ') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- BOTÃO PRÓXIMA ETAPA --}}
                                <button
                                    wire:click="concluirCompra()"
                                    type="button"
                                    class="w-full flex items-center justify-center gap-3 rounded-2xl {{ $isAppVersion ? 'py-4 text-base' : 'py-5 text-lg md:text-xl' }} font-extrabold uppercase tracking-wider transition-all duration-200 hover:opacity-90 hover:scale-[1.01] active:scale-[0.99] shadow-lg"
                                    style="background: linear-gradient(135deg, {{ $colorPrimary }}, {{ $colorSecondary }}); color: {{ $colorInverse }}; box-shadow: 0 8px 30px {{ $colorPrimary }}55;">
                                    <span>PRÓXIMA ETAPA</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </button>

                            @else

                                {{-- BOTÃO CONCLUIR (sem valor) --}}
                                <button
                                    onclick="confirm('Confirma o pedido?') || event.stopImmediatePropagation()"
                                    wire:click="concluirCompra()"
                                    type="button"
                                    class="w-full flex items-center justify-center gap-3 rounded-2xl {{ $isAppVersion ? 'py-4 text-base' : 'py-5 text-lg md:text-xl' }} font-extrabold uppercase tracking-wider transition-all duration-200 hover:opacity-90 hover:scale-[1.01] active:scale-[0.99] shadow-lg"
                                    style="background: linear-gradient(135deg, {{ $colorPrimary }}, {{ $colorSecondary }}); color: {{ $colorInverse }}; box-shadow: 0 8px 30px {{ $colorPrimary }}55;">
                                    CONCLUIR
                                </button>

                            @endif

                        @endif

                    </div>{{-- /space-y-5 --}}

                    @php
                        $numWhatsapp = false;
                        if(($event->organizer->owner_phone_country ?? false) && ($event->organizer->owner_phone_ddd ?? false) && ($event->organizer->owner_phone_num ?? false))
                        {
                            $numWhatsapp  = $event->organizer->owner_phone_country.$event->organizer->owner_phone_ddd.$event->organizer->owner_phone_num;
                            $linkWhatsapp = "https://api.whatsapp.com/send?phone=" .  $numWhatsapp . "&text=Fazendo contato sobre o evento " . $event->event_name . '.';
                        }
                    @endphp

                    {{-- WHATSAPP FOOTER --}}
                    @if ($numWhatsapp ?? false)
                        <div class="mt-8 flex items-center justify-center gap-3 px-4 py-4 rounded-2xl text-sm bg-green-50 border border-green-100">
                            <svg class="w-5 h-5 flex-shrink-0" fill="#25D366" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            <span class="text-gray-500">Precisa de ajuda? Fale conosco pelo
                                <a href="{{ $linkWhatsapp }}" target="_blank" class="font-bold" style="color: #25D366;">WhatsApp</a>
                            </span>
                        </div>
                    @endif

                </div>{{-- /comprar_ingresso --}}

                <script>
                    const element = document.getElementById("div_comprar_agora");
                          element.scrollIntoView();
                          document.getElementById("comprador_nome").focus();
                </script>


            @else

                {{-- ═══════════════════════════════════════════════════════
                     PÁGINA DO EVENTO (sem ingresso selecionado)
                ═══════════════════════════════════════════════════════ --}}

                {{-- ─── SEÇÃO 1: SOBRE O EVENTO ─── --}}
                @if ($event->event_about ?? false)
                    <section id="session_sobre-ooevento" class="w-full mb-10">
                        <div class="rounded-2xl overflow-hidden bg-white" style="border: 1px solid #e2e8f0; box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                            <div class="px-5 py-3 flex items-center gap-3" style="background: {{ $colorPrimary }}0d; border-bottom: 1px solid #e2e8f0;">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $colorPrimary }};">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-bold text-gray-700 uppercase text-xs tracking-widest">Sobre o Evento</span>
                            </div>
                            <div class="p-5 md:p-6">
                                <div class="prose max-w-none {{ $isAppVersion ? 'text-sm' : 'text-sm md:text-base' }} text-gray-600 leading-relaxed">
                                    {!! $event->event_about !!}
                                </div>
                            </div>
                        </div>
                    </section>
                @endif

                {{-- ─── SEÇÃO 2: INGRESSOS / LOTES ─── --}}
                <section id="comprar" class="w-full mb-10">

                    {{-- HEADER --}}
                    <div class="flex items-center gap-4 mb-6">
                        <svg class="w-9 h-9 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $colorPrimary }};">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                        <div>
                            <h2 class="{{ $isAppVersion ? 'text-lg' : 'text-xl md:text-2xl' }} font-extrabold text-gray-800 uppercase tracking-tight">
                                {{ $event->sales_label ?? 'Ingressos' }}
                            </h2>
                            <p class="text-xs mt-0.5 text-gray-400">Escolha seu ingresso e garanta sua vaga</p>
                        </div>
                    </div>

                    {{-- ERRO DE SESSÃO --}}
                    @if (session('conclusao_error'))
                        <div class="flex items-start gap-3 px-5 py-4 rounded-2xl mb-5 bg-red-50 border border-red-200">
                            <svg class="w-5 h-5 flex-shrink-0 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                            <div>
                                <div class="text-red-700 font-bold uppercase text-sm">{{ __(session('conclusao_error')) }}</div>
                                @if (session('conclusao_error_sub'))
                                    <div class="text-red-500 text-xs mt-0.5 uppercase">{{ __(session('conclusao_error_sub')) }}</div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- GRID DE LOTES (3 colunas desktop, 1 mobile) --}}
                    <div class="grid grid-cols-1 {{ $isAppVersion ? 'gap-3' : 'md:grid-cols-2 lg:grid-cols-3 gap-4' }}">

                        @forelse ($ticketTypes ?? [] as $ticketTypeId => $ticketTypeValues)
                            @php
                                $isSoldOut = ($ticketTypeValues->esgotado ?? false);
                                $isClosed  = ($ticketTypeValues->loteFechado ?? false);
                                $isBlocked = $isSoldOut || $isClosed;
                            @endphp

                            <div class="relative rounded-2xl overflow-hidden transition-all duration-300 bg-white flex flex-col {{ $isBlocked ? 'opacity-60' : 'hover:-translate-y-1' }}"
                                 style="border: 1px solid {{ $isBlocked ? '#e5e7eb' : '#e2e8f0' }}; box-shadow: {{ $isBlocked ? '0 1px 4px rgba(0,0,0,0.06)' : '0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06)' }};">

                                {{-- Barra superior com cor do evento --}}
                                <div class="h-1 flex-shrink-0" style="background: linear-gradient(90deg, {{ $colorPrimary }}, {{ $colorSecondary }});"></div>

                                <div class="{{ $isAppVersion ? 'p-4' : 'p-5 md:p-6' }} flex flex-col flex-1">

                                    {{-- INFO DO LOTE --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                            @if ($ticketTypeValues->sales_label_title ?? false)
                                                <span class="text-xs font-semibold uppercase tracking-widest" style="color: {{ $colorPrimary }};">
                                                    {{ $ticketTypeValues->sales_label_title }}
                                                </span>
                                            @endif

                                            {{-- BADGE STATUS --}}
                                            @if($isSoldOut)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-red-50 border border-red-200 text-red-500">
                                                    Esgotado
                                                </span>
                                            @elseif($isClosed)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-red-50 border border-red-200 text-red-500">
                                                    Encerrado
                                                </span>
                                            @endif
                                        </div>

                                        @if ($ticketTypeValues->ticket_name ?? false)
                                            <h3 class="font-extrabold text-gray-800 {{ $isAppVersion ? 'text-lg' : 'text-xl' }} uppercase leading-tight">
                                                {{ $ticketTypeValues->ticket_name }}
                                            </h3>
                                        @else
                                            <h3 class="font-extrabold text-gray-800 {{ $isAppVersion ? 'text-lg' : 'text-xl' }} uppercase leading-tight">
                                                {{ $ticketTypeValues->sales_label_title ?? 'INGRESSO' }}
                                            </h3>
                                        @endif

                                        @if ($ticketTypeValues->ticket_description ?? false)
                                            <p class="text-gray-600 text-sm mt-1 leading-relaxed">
                                                {{ $ticketTypeValues->ticket_description }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- PREÇO --}}
                                    <div class="pt-3 mt-auto border-t border-gray-100">
                                        <div class="text-gray-500 text-[10px] uppercase tracking-widest mb-1 font-semibold">Valor</div>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-sm font-bold" style="color: {{ $isBlocked ? '#9ca3af' : $colorPrimary }};">R$</span>
                                            <span class="{{ $isAppVersion ? 'text-2xl' : 'text-2xl md:text-3xl' }} font-extrabold leading-none {{ $isBlocked ? 'text-gray-400' : 'text-gray-800' }}">
                                                {{ number_format((int) ($ticketTypeValues->price ?? '0') / 100, 2, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        {{-- BOTÃO --}}
                                        @if ($isSoldOut || $isClosed)
                                            <div class="w-full flex items-center justify-center gap-1.5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-gray-100 text-gray-400 border border-gray-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                {{ $isSoldOut ? 'ESGOTADO' : 'ENCERRADO' }}
                                            </div>
                                        @else
                                            <button
                                                onclick="scrollTo()"
                                                wire:click="setTicketType('{{ $ticketTypeValues->id }}')"
                                                type="button"
                                                class="w-full flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-bold uppercase tracking-wide transition-all duration-200 hover:opacity-90 hover:scale-[1.02] active:scale-[0.98]"
                                                style="background-color: {{ $colorDefault }}; color: {{ $colorInverse }}; box-shadow: 0 4px 16px {{ $colorDefault }}33;">
                                                {{ $ticketTypeValues->sale_label_btn ?? 'COMPRAR' }}
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                </div>
                            </div>

                        @empty

                            <div class="col-span-full rounded-2xl p-10 text-center bg-white border border-dashed border-gray-200">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.069A1 1 0 0121 8.834v6.332a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <div class="text-gray-400 font-semibold uppercase text-sm tracking-wide">Ainda está indisponível</div>
                                <div class="text-gray-300 text-xs mt-1 uppercase tracking-wider">Volte mais tarde</div>
                            </div>

                        @endforelse

                    </div>

                    {{-- TEXTO RODAPÉ DO EVENTO --}}
                    @if ($event->event_text_footer ?? false)
                        <div class="mt-5 rounded-xl px-4 py-3 text-center bg-white border border-gray-100">
                            <p class="text-gray-500 text-sm leading-relaxed">{{ $event->event_text_footer }}</p>
                        </div>
                    @endif

                </section>

                {{-- ─── SEÇÃO 3: NOTIFICAÇÃO ─── --}}
                @if ($event->notification_text_1 ?? false)
                    <section id="session_notificacao" class="w-full mb-10">
                        <div class="rounded-2xl px-5 py-5 text-center bg-red-50 border border-red-100">
                            <div class="{{ $isAppVersion ? 'text-lg' : 'text-xl md:text-2xl' }} font-extrabold uppercase text-red-600 mb-1.5">
                                {{ $event->notification_text_1 }}
                            </div>
                            @if ($event->notification_text_2 ?? false)
                                <div class="{{ $isAppVersion ? 'text-xs' : 'text-sm' }} font-medium text-red-400">
                                    {{ $event->notification_text_2 }}
                                </div>
                            @endif
                        </div>
                    </section>
                @endif

                {{-- ─── SEÇÃO 4: LOCALIZAÇÃO ─── --}}
                @if ($event->google_maps_iframe ?? false)
                    @php
                        $iframe_google_maps = str_replace('width="600"','width="100%"', $event->google_maps_iframe);

                        // Montar endereço completo
                        $addressParts = array_filter([
                            trim(($event->address ?? '') . ($event->address_number ? ', ' . $event->address_number : '')),
                            $event->address_complement ?? null,
                        ]);
                        $addressLine1 = implode(' — ', $addressParts);

                        $cityParts = array_filter([
                            $event->city_neighborhood ?? null,
                            $event->city ?? null,
                            $event->state ?? null,
                        ]);
                        $addressLine2 = implode(', ', $cityParts);
                        if ($event->zip_code ?? false) {
                            $addressLine2 .= ' · CEP ' . $event->zip_code;
                        }

                        $hasAddress = !empty($addressLine1) || !empty($addressLine2);
                    @endphp
                    <section id="session_maps" class="w-full mb-10">
                        <div class="rounded-2xl overflow-hidden bg-white" style="border: 1px solid #e2e8f0; box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                            {{-- Header --}}
                            <div class="px-5 py-3 flex items-center gap-3" style="background: {{ $colorPrimary }}0d; border-bottom: 1px solid #e2e8f0;">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $colorPrimary }};">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="font-bold text-gray-700 uppercase text-xs tracking-widest">Localização</span>
                            </div>

                            {{-- Endereço do evento --}}
                            @if ($hasAddress)
                                <div class="px-5 py-4" style="border-bottom: 1px solid {{ $colorPrimary }}10;">
                                    @if ($addressLine1)
                                        <p class="text-gray-700 {{ $isAppVersion ? 'text-sm' : 'text-base' }} font-semibold leading-snug">
                                            {{ $addressLine1 }}
                                        </p>
                                    @endif
                                    @if ($addressLine2)
                                        <p class="text-gray-500 text-sm mt-0.5">{{ $addressLine2 }}</p>
                                    @endif
                                    @if ($event->address_reference ?? false)
                                        <p class="text-gray-400 text-xs mt-1 italic">Ref: {{ $event->address_reference }}</p>
                                    @endif
                                </div>
                            @endif

                            {{-- Mapa --}}
                            <div class="w-full">
                                {!! $iframe_google_maps !!}
                            </div>
                        </div>
                    </section>
                @endif

            @endif

        </div>{{-- /div_comprar_agora --}}

    </main>



    <script>
        function scrollTo() {
            const el = document.getElementById("image_thumbnail") || document.getElementById("div_comprar_agora");
            if(el) el.scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</div>
