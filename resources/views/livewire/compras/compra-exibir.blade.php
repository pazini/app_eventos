<div class="min-h-screen bg-white">

    @php
        $colorPrimary   = $target->color_primary   ?? $target->color_default ?? '#6366f1';
        $colorSecondary = $target->color_secondary  ?? $target->color_default ?? '#8b5cf6';
        $colorDefault   = $target->color_default    ?? '#6366f1';
        $colorInverse   = $target->color_default_inverse ?? '#ffffff';
    @endphp

    {{-- LIVEWIRE - LOADER (não dispara no poll automático) --}}
    <div wire:loading.class.remove="hidden"
         wire:target="cancelarPedido,checkExpiration"
         class="hidden fixed inset-0 z-[999] flex items-center justify-center"
         style="background: rgba(255,255,255,0.80); backdrop-filter: blur(6px);">
        <div class="flex flex-col items-center gap-4">
            <img src="{{ asset('/assets/loader.v2.svg') }}" alt="" class="w-16 h-16">
            <span class="text-gray-500 text-sm uppercase tracking-widest font-light">Aguarde…</span>
        </div>
    </div>
    {{-- LIVEWIRE - LOADER FIM --}}

    @include('_includes.alertas_modal')

    {{-- HERO / HEADER --}}
    @php
        // Detect if event is from another instance
        $isExternalEvent = !empty($target->referer_url)
            && rtrim($target->referer_url, '/') !== rtrim(config('domains.eventos'), '/');

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
        if ($target->url_image_bg ?? false) {
            if ($isExternalEvent && !$isInternalMediaPath($target->url_image_bg)) {
                $urlImageBg = $target->referer_url . '/' . $target->url_image_bg;
            } else {
                $urlImageBg = str_starts_with($target->url_image_bg, '/storage/')
                    ? asset($target->url_image_bg)
                    : tenantAsset($target->url_image_bg, true);
            }
        }

        // Build event logo URL
        $urlImageLogo = null;
        if ($target->url_image_logo ?? false) {
            if ($isExternalEvent && !$isInternalMediaPath($target->url_image_logo)) {
                $urlImageLogo = $target->referer_url . '/' . $target->url_image_logo;
            } else {
                $urlImageLogo = str_starts_with($target->url_image_logo, '/storage/')
                    ? asset($target->url_image_logo)
                    : tenantAsset($target->url_image_logo, true);
            }
        }

        // Build customer logo URL (fallback)
        $urlCustomerLogo = null;
        if ($target->customer->url_image_logo ?? false) {
            $urlCustomerLogo = str_starts_with($target->customer->url_image_logo, '/storage/')
                ? asset($target->customer->url_image_logo)
                : tenantAsset($target->customer->url_image_logo, true);
        }

        // Build main event image URL
        $urlImage = null;
        if ($target->url_image ?? false) {
            if ($isExternalEvent && !$isInternalMediaPath($target->url_image)) {
                $urlImage = $target->referer_url . '/' . $target->url_image;
            } else {
                $urlImage = str_starts_with($target->url_image, '/storage/')
                    ? asset($target->url_image)
                    : tenantAsset($target->url_image, true);
            }
        }

        $eventDate   = $target->event_datetime_start ? \Carbon\Carbon::parse($target->event_datetime_start) : null;
        $eventIsPast = $eventDate && $eventDate->isPast();
    @endphp
    <style>@keyframes heroBgDrift{0%{transform:scale(1.08) translate(0%,0%)}25%{transform:scale(1.13) translate(-1.5%,-1%)}50%{transform:scale(1.10) translate(1%,-2%)}75%{transform:scale(1.14) translate(-0.5%,1%)}100%{transform:scale(1.08) translate(0%,0%)}}.hero-bg-animate{animation:heroBgDrift 24s ease-in-out infinite;will-change:transform;}</style>

    <section class="relative w-full overflow-hidden" style="min-height: 280px;">

        {{-- Hero background with event image --}}
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

        <div class="relative z-10 max-w-4xl mx-auto px-6 md:px-10 py-8 md:py-10 pb-20 md:pb-28">

            {{-- Logo + Date (desktop) + Status --}}
            <div class="flex items-center justify-between w-full gap-3 mb-6">
                <div class="flex items-center gap-4 min-w-0">
                    @if ($urlImageLogo)
                        <img class="h-12 md:h-14 w-auto drop-shadow-lg flex-shrink-0" src="{{ $urlImageLogo }}" alt="">
                    @elseif ($urlCustomerLogo)
                        <img class="h-12 md:h-14 w-auto drop-shadow-lg flex-shrink-0" src="{{ $urlCustomerLogo }}" alt="">
                    @else
                        <img class="h-12 md:h-14 w-auto drop-shadow-lg flex-shrink-0" src="{{ appLogo(true) }}" alt="{{ appName() }}">
                    @endif

                    {{-- Data do evento ao lado da logo (desktop) --}}
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

                <div class="flex-shrink-0">
                    <span class="inline-block px-5 py-2 text-xs font-semibold uppercase tracking-wider rounded-full shadow-lg" style="background-color: {{ $colorDefault }};color: {{ $colorInverse }};">{{ __($order->status ?? 'PEDIDO') }}</span>
                </div>
            </div>

            {{-- Location badge --}}
            @php
                $locationParts = array_filter([
                    $target->city ?? null,
                    $target->state ?? null,
                ]);
                $heroLocationText = implode(', ', $locationParts);
                if (!$heroLocationText && ($target->customer->customer_corporate_name ?? false)) {
                    $heroLocationText = $target->customer->customer_corporate_name;
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

            {{-- Event name --}}
            <h1 class="text-2xl md:text-4xl text-white font-extrabold uppercase tracking-tight drop-shadow-xl leading-tight">{{ $target->event_name ?? '--' }}</h1>

            {{-- Organizador + Descrição --}}
            @php
                $orgName = $target->customer->customer_corporate_name ?? null;
                $evtDesc = $target->event_description ?? null;
                $subLine = collect([$orgName, $evtDesc])->filter()->implode(' — ');
            @endphp
            @if ($subLine)
                <p class="text-base md:text-lg mt-2 font-medium text-white/60 uppercase tracking-wide leading-relaxed max-w-3xl">{{ $subLine }}</p>
            @endif

            {{-- Data do evento (mobile only) --}}
            @if ($eventDate && !$eventIsPast)
                <div class="mt-4 flex flex-col items-start gap-1 md:hidden">
                    <span class="text-white text-base font-bold drop-shadow-lg">
                        {{ $eventDate->locale('pt_BR')->isoFormat('D [de] MMMM [de] YYYY [·] HH[h]mm') }}
                    </span>
                    <span class="text-xs font-semibold drop-shadow" style="color: {{ $colorDefault }};">
                        {{ $eventDate->locale('pt_BR')->diffForHumans() }}
                    </span>
                </div>
            @endif

        </div>
    </section>

    {{-- BODY --}}
    <div class="w-full max-w-4xl mx-auto px-4 md:px-10 -mt-12 md:-mt-16 relative z-20">

        <div class="w-full rounded-2xl bg-white shadow-xl overflow-hidden" style="border: 1px solid {{ $colorPrimary }}18;">

            {{-- Card header --}}
            <div class="px-5 md:px-8 py-4 md:py-5" style="background: {{ $colorPrimary }}08; border-bottom: 1px solid {{ $colorPrimary }}15;">
                <div class="w-full grid grid-cols-1 md:grid-cols-12 items-center gap-2">

                    <div class="col-span-full md:col-span-6">
                        <div class="w-full text-center md:text-left uppercase text-xs tracking-widest font-light text-gray-400">LOCALIZADOR</div>
                        <div class="w-full text-center md:text-left uppercase text-xl md:text-2xl font-bold text-gray-800 -mt-0.5">{{ $order->order_control ?? null }}</div>
                    </div>

                    {{-- SE ORDER EXPIRAÇÃO --}}
                    @if ($order->reservation_expiration_date ?? false)

                        <div class="col-span-full md:col-span-6 flex flex-col items-center md:items-end">

                            {{-- MODAL EXPIRADO --}}
                            <x-modal.card blur wire:model.defer="reservation_expiration">
                                <div class="flex flex-col justify-center items-center px-6">
                                    <x-icon name="exclamation-circle" class="w-20 h-20 text-red-600" />
                                    <div class="w-full text-center text-3xl text-red-700 font-medium uppercase my-4">O tempo da reserva acabou</div>
                                    <div class="w-full text-center text-xl text-red-700 font-light px-8">Infelizmente, 30 minutos não foram suficientes para que você realizasse o pagamento</div>
                                    <div class="w-full text-center text-xl text-red-700 mt-4">
                                        <a href="{{ $order->channel_order }}" class="w-full text-blue-600 text-normal hover:underline">Clique aqui</a> para um novo pedido.
                                    </div>
                                </div>
                                <x-slot name="footer">
                                    <div class="flex justify-center gap-x-4">
                                        <x-button flat label="Fechar" x-on:click="close" />
                                    </div>
                                </x-slot>
                            </x-modal.card>
                            {{-- MODAL EXPIRADO - FIM --}}

                            @if (($reservation_expiration ?? false) || $order->reservation_expiration_date->format('YmdHi') < now()->format('YmdHi'))
                                {{-- EXPIRADO --}}
                                <div class="flex items-center gap-3 bg-red-950/90 border border-red-800/60 rounded-2xl px-5 py-3 shadow-lg">
                                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-red-800/50 flex-shrink-0">
                                        <svg class="w-5 h-5 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-[10px] tracking-widest uppercase font-semibold text-red-400">Reserva expirada</div>
                                        <div class="text-sm font-bold text-red-200 mt-0.5">{{ $order->reservation_expiration_date->format('d/m/Y \à\s H:i') }}</div>
                                    </div>
                                </div>
                            @else
                                {{-- COUNTDOWN ATIVO --}}
                                <div class="flex flex-col items-center gap-2">
                                    <div class="text-[10px] tracking-widest uppercase font-semibold text-gray-400">Sua reserva expira em</div>
                                    <div class="pep-cd-glow bg-slate-600 rounded-xl px-4 py-3 shadow-lg">
                                        <table class="border-separate border-spacing-0">
                                            <tr>
                                                <td id="cd-days-cell" class="text-center px-1">
                                                    <span id="cd-days" class="block w-10 text-center text-2xl font-black font-mono text-white leading-none tabular-nums">00</span>
                                                </td>
                                                <td class="text-center px-0">
                                                    <span id="cd-sep-days" class="pep-cd-blink block text-slate-300 font-black text-2xl leading-none select-none">:</span>
                                                </td>
                                                <td class="text-center px-1">
                                                    <span id="cd-hours" class="block w-10 text-center text-2xl font-black font-mono text-amber-400 leading-none tabular-nums">00</span>
                                                </td>
                                                <td class="text-center px-0">
                                                    <span class="pep-cd-blink block text-slate-300 font-black text-2xl leading-none select-none">:</span>
                                                </td>
                                                <td class="text-center px-1">
                                                    <span id="cd-minutes" class="block w-10 text-center text-2xl font-black font-mono text-amber-400 leading-none tabular-nums">00</span>
                                                </td>
                                                <td class="text-center px-0">
                                                    <span class="pep-cd-blink block text-slate-300 font-black text-2xl leading-none select-none">:</span>
                                                </td>
                                                <td class="text-center px-1">
                                                    <span id="cd-seconds" class="block w-10 text-center text-2xl font-black font-mono text-white leading-none tabular-nums">00</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="cd-days-label-cell" class="text-center px-1">
                                                    <span id="cd-days-label" class="block text-[9px] uppercase tracking-widest text-slate-300 font-light">dias</span>
                                                </td>
                                                <td></td>
                                                <td class="text-center px-1">
                                                    <span class="block text-[9px] uppercase tracking-widest text-slate-300 font-light">horas</span>
                                                </td>
                                                <td></td>
                                                <td class="text-center px-1">
                                                    <span class="block text-[9px] uppercase tracking-widest text-slate-300 font-light">min</span>
                                                </td>
                                                <td></td>
                                                <td class="text-center px-1">
                                                    <span class="block text-[9px] uppercase tracking-widest text-slate-300 font-light">seg</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <style>
                                    @keyframes pep-blink { 0%,100%{opacity:1} 50%{opacity:.2} }
                                    .pep-cd-blink { animation: pep-blink 1s step-start infinite; }
                                    @keyframes pep-glow {
                                        0%, 100% { box-shadow: 0 0 6px 1px rgba(251,191,36,0.25), 0 2px 8px rgba(0,0,0,0.3); }
                                        50%       { box-shadow: 0 0 18px 4px rgba(251,191,36,0.55), 0 2px 8px rgba(0,0,0,0.3); }
                                    }
                                    .pep-cd-glow { animation: pep-glow 2.5s ease-in-out infinite; }
                                </style>
                            @endif

                        </div>

                    @endif
                    <script>
                        (function () {
                            var duration = parseInt("{{ calculaSegundosDif($order->reservation_expiration_date ?? now(), now()) }}", 10);
                            if (duration < 1) return;

                            var elDays    = document.getElementById('cd-days');
                            var elHours   = document.getElementById('cd-hours');
                            var elMinutes = document.getElementById('cd-minutes');
                            var elSeconds   = document.getElementById('cd-seconds');
                            var elSepDays   = document.getElementById('cd-sep-days');
                            var elDaysLabel = document.getElementById('cd-days-label');

                            if (!elDays) return;

                            function pad(n) { return n < 10 ? '0' + n : '' + n; }

                            function tick() {
                                var d = Math.floor(duration / 86400);
                                var h = Math.floor((duration % 86400) / 3600);
                                var m = Math.floor((duration % 3600) / 60);
                                var s = Math.floor(duration % 60);

                                elDays.textContent    = pad(d);
                                elHours.textContent   = pad(h);
                                elMinutes.textContent = pad(m);
                                elSeconds.textContent = pad(s);

                                var hideDays = d === 0;
                                elDays.style.display      = hideDays ? 'none' : '';
                                elSepDays.style.display   = hideDays ? 'none' : '';
                                if (elDaysLabel) elDaysLabel.style.display = hideDays ? 'none' : '';

                                if (--duration < 0) {
                                    location.reload();
                                }
                            }

                            tick();
                            setInterval(tick, 1000);
                        })();
                    </script>
                    {{-- SE ORDER EXPIRAÇÃO FIM --}}

                </div>
            </div>

            {{-- Buyer info --}}
            <div class="px-5 md:px-8 py-5">

                <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-x-4 gap-y-1">

                    <div class="col-span-full md:col-span-7">
                        {!! setLabel('comprador', $order->buyer_name) !!}
                    </div>

                    <div class="col-span-full md:col-span-5">
                        {!! setLabel('buyer_doc', $order->buyer_doc_type . ' ' . (putMask($order->buyer_doc_num,$order->buyer_doc_type))) !!}
                    </div>

                    <div class="col-span-full md:col-span-7">
                        {!! setLabel('buyer_email', $order->buyer_email, true, false) !!}
                    </div>

                    <div class="col-span-full md:col-span-5">
                        {!! setLabel('buyer_contact', $order->buyer_contact_ddd . ' ' . $order->buyer_contact_num) !!}
                    </div>

                </div>

                {{-- SE HOUVER ITENS --}}
                @if ($order->itens ?? false)

                    <div class="my-4 border-t" style="border-color: {{ $colorPrimary }}15;"></div>

                    <div class="mb-4">
                        <div class="uppercase text-sm tracking-widest font-semibold text-gray-700">
                            @if(count($order->itens ?? []) > 1)
                                {{ $order->event->sales_label_item_multiple ?? 'PARTICIPANTES' }}
                            @else
                                {{ $order->event->sales_label_item ?? 'PARTICIPANTE' }}
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">

                        @foreach ($order->itens as $orderitem)
                            <div class="w-full rounded-xl px-4 md:px-5 py-3 transition-all duration-200" style="border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
                                <div class="uppercase text-sm md:text-base font-semibold text-gray-800">{{ $orderitem->user_name }}</div>
                                <div class="uppercase text-xs font-medium text-gray-500 mt-0.5">{{ $orderitem->item_description }}</div>
                            </div>
                        @endforeach

                    </div>

                @endif
                {{-- SE HOUVER ITENS FIM --}}

            </div>

            {{-- ALERTS --}}
            <div class="px-5 md:px-8">
                @include('_includes.alertas_exibir_compra')
            </div>

            {{-- SE PAGO E NAO PARCIAL - ACESSAR ONLINE --}}
            @if (in_array($order->status, listOrderStatusPaid()) && !in_array($order->status,listOrderStatusPaidParcial()))
                <div class="px-5 md:px-8 pb-5">
                    <x-button lg lime icon="ticket" href="{{ route('evento-vouchers', ['localizador' => $order->order_control, 'order_id' => $order->id]) }}" label="Clique aqui para acessar seus vouchers" class="w-full uppercase font-light shadow-md rounded-xl" />
                </div>
            @endif

        </div>

    </div>

    {{-- STATUS PAID --}}
    @if (in_array($order->status, listOrderStatusPaid()))

        <div id="div_status_paid" class="mt-6">

            <div class="w-full max-w-4xl mx-auto px-4 md:px-10">

                <div class="w-full rounded-2xl bg-white shadow-md overflow-hidden" style="border: 1px solid {{ $colorPrimary }}18;">

                    {{-- Header --}}
                    <div class="px-5 md:px-8 py-4" style="background: {{ $colorPrimary }}08; border-bottom: 1px solid {{ $colorPrimary }}15;">
                        <div class="w-full flex justify-between items-center gap-2">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-lg md:text-xl font-bold text-green-600 uppercase">COMPRA FINALIZADA</span>
                            </div>
                            @if ($order->channel_order ?? false)
                                <div class="w-auto">
                                    <x-button blue outline sm label="COMPRAR NOVAMENTE" href="{{ $order->channel_order }}" class="text-right md:text-center rounded-lg" />
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="px-5 md:px-8 py-5">

                        <div class="flex flex-col gap-2 mb-4">

                            {{-- SE CUPOM DESCONTO JÁ APLICADO --}}
                            @if ($order->code_promo_id ?? false)

                                <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3" style="background: {{ $colorPrimary }}06; border: 1px solid {{ $colorPrimary }}15;">
                                    <div class="text-sm md:text-lg uppercase text-left font-semibold text-gray-700">VALOR COMPRA</div>
                                    <div class="text-base md:text-xl uppercase text-right font-bold text-gray-800 whitespace-nowrap">{{ toMoney($order_amount_payment ?? ($order->order_amount ?? '---'), 'R$ ') }}</div>
                                </div>

                                <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3 bg-red-50 border border-red-200">
                                    <div class="text-sm md:text-lg uppercase text-left font-semibold text-red-600">{{ $order->code_promo_label ?? 'CUPOM APLICADO' }}</div>
                                    <div class="text-base md:text-xl uppercase text-right font-bold text-red-600 whitespace-nowrap">
                                        @if ($order->code_promo_price_less ?? false)
                                            <span>{{ toMoney($order->code_promo_price_less,'- R$ ') }}</span>
                                        @elseif ($order->code_promo_discount_amount ?? false)
                                            <span>{{ toMoney($order->code_promo_discount_amount,'- R$ ') }}</span>
                                        @else
                                            --
                                        @endif
                                    </div>
                                </div>

                                @if ($order->code_promo_price_new ?? false)
                                    <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3" style="background: {{ $colorPrimary }}06; border: 1px solid {{ $colorPrimary }}15;">
                                        <div class="text-sm md:text-lg uppercase text-left font-semibold text-gray-700">VALOR TOTAL</div>
                                        <div class="text-base md:text-xl uppercase text-right font-bold text-gray-800 whitespace-nowrap">
                                            <span>{{ toMoney($order->code_promo_price_new,'R$ ') }}</span>
                                        </div>
                                    </div>
                                @endif

                            @else

                                <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3" style="background: {{ $colorPrimary }}06; border: 1px solid {{ $colorPrimary }}15;">
                                    <div class="text-sm md:text-lg uppercase text-left font-semibold text-gray-700">VALOR COMPRA</div>
                                    <div class="text-base md:text-xl uppercase text-right font-bold text-gray-800 whitespace-nowrap">{{ toMoney($order_amount_payment ?? ($order->order_amount ?? '---'), 'R$ ') }}</div>
                                </div>

                            @endif

                        </div>

                        @php
                            $payments = $order->payments->whereIn('status', listPaymentStatusPaid());
                            $paymentParcial = (in_array($order->status,listOrderStatusPaidParcial())) ? true : false ;
                        @endphp

                        @if ($payments->count() ?? false)

                            <div class="mt-6 pt-4" style="border-top: 1px solid {{ $colorPrimary }}15;">
                                <div class="text-sm tracking-widest font-semibold uppercase text-gray-700 mb-4">
                                    @if ($payments->count() > 1)
                                        <span>PAGAMENTOS {{($paymentParcial ?? FALSE) ? '// PARCIAIS' : NULL}}</span>
                                    @else
                                        <span>PAGAMENTO {{($paymentParcial ?? FALSE) ? '// PARCIAL' : NULL}}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="divide-y" style="border-color: {{ $colorPrimary }}15;">
                                @foreach ($payments ?? [] as $payment)
                                    <div>@include('livewire.compras._includes.exibir-pagamentos')</div>
                                @endforeach
                            </div>

                        @endif

                    </div>

                </div>

            </div>

            {{-- DEMAIS PAGAMENTOS --}}
            @php
                $payments = $order->payments->whereNotIn('status', listPaymentStatusPaid());
            @endphp

            @if ($payments->count() ?? false)

                <div class="w-full max-w-4xl mx-auto px-4 md:px-10 mt-4">

                    <div class="w-full rounded-2xl bg-white shadow-md overflow-hidden" style="border: 1px solid {{ $colorPrimary }}18;">

                        <div class="px-5 md:px-8 py-4" style="background: {{ $colorPrimary }}08; border-bottom: 1px solid {{ $colorPrimary }}15;">
                            @if ($payments->count() > 1)
                                <div class="text-sm tracking-widest font-semibold uppercase text-gray-700">OUTROS PAGAMENTOS</div>
                            @else
                                <div class="text-sm tracking-widest font-semibold uppercase text-gray-700">OUTRO PAGAMENTO</div>
                            @endif
                        </div>

                        <div class="px-5 md:px-8 py-4 divide-y" style="border-color: {{ $colorPrimary }}15;">
                            @foreach ($payments ?? [] as $payment)
                            <div>@include('livewire.compras._includes.exibir-pagamentos')</div>
                            @endforeach
                        </div>

                    </div>

                </div>

            @endif

        </div>

    @elseif (in_array($order->status, listOrderStatusCancelada()))

        <div class="w-full max-w-4xl mx-auto px-4 md:px-10 mt-6">

            <div class="w-full rounded-2xl bg-white shadow-md overflow-hidden" style="border: 1px solid {{ $colorPrimary }}18;">

                <div class="px-5 md:px-8 py-5">

                    @php
                        $cancelDatetime = $order->order_cancel_datetime ?? $order->updated_at;
                        $cancelDateFormatted = $cancelDatetime ? \Carbon\Carbon::parse($cancelDatetime)->format('d/m/Y \à\s H:i') : null;
                        $isExpired = $order->status === 'expired_order';
                    @endphp

                    <div class="w-full text-center mb-3 text-red-700 bg-red-50 border border-red-200 p-3 rounded-xl">
                        @if ($isExpired)
                            <div class="font-semibold">Reserva expirada</div>
                            @if ($order->order_cancel_description ?? false)
                                <div class="text-sm mt-1">{{ $order->order_cancel_description }}</div>
                            @endif
                            @if ($cancelDateFormatted)
                                <div class="text-sm mt-1 opacity-75">Expirou em {{ $cancelDateFormatted }}</div>
                            @endif
                        @else
                            @if ($order->order_cancel_description ?? false)
                                <div class="font-semibold uppercase text-sm mt-1">{{ $order->order_cancel_description }}</div>
                            @else
                                <div class="font-semibold uppercase">Pedido cancelado</div>
                            @endif
                            @if ($cancelDateFormatted)
                                <div class="text-sm mt-1 opacity-75">Cancelado em {{ $cancelDateFormatted }}</div>
                            @endif
                        @endif
                    </div>

                    @if ($order->channel_order ?? false)
                        <div class="w-full text-center mt-3">
                            <a href="{{ $order->channel_order }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline font-medium transition-colors pt-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                                Comprar novamente
                            </a>
                        </div>
                    @endif

                </div>

            </div>

        </div>

    {{-- ABRE PAGAMENTO --}}
    @elseif (!in_array($order->status, listOrderStatusPaid()))
        <div class="mt-6">
            @livewire('compras.modulo-pagamento', ['orderId' => $order->id])
        </div>
    {{-- ELSE --}}
    @else
        COMPRA ELSE ANALISAR
    @endif
    {{-- STATUS PAID FIM --}}

    @if (!in_array($order->status,listOrderStatusNaoCancelar()))
        <div class="w-full max-w-4xl mx-auto px-4 md:px-10 mt-6">
            <div class="w-full text-center">
                <a
                    href="#"
                    title="Cancelar este pedido"
                    class="inline-block text-sm text-red-500 border border-transparent rounded-lg px-3 py-1.5 transition-all duration-200 hover:bg-red-50 hover:text-red-700 hover:border-red-200 uppercase"
                    onclick="confirm('Tem certeza que deseja cancelar este pedido? Será irreversível!') || event.stopImmediatePropagation()"
                    wire:click.prevent="cancelarPedido"
                >
                    Cancelar este Pedido
                </a>
            </div>
        </div>
    @endif

    {{-- Spacing bottom --}}
    <div class="pb-10"></div>

    {{-- MODAL ERROR --}}
    @if (session('error'))
        <x-modal.card blur wire:model.defer="error">

            <div class="flex flex-col justify-center items-center px-6">

                <div>
                    <img src="{{ asset('images/icones/icon-error-animate.gif') }}" alt="Erro na Conclusão" class="h-32">
                </div>

                <div class="w-full text-center text-2xl text-red-700 mx-1 font-medium">{{ __(session('error')) }}</div>

                @if (session('error_sub'))
                    <div class="w-full text-center text-xl text-red-700 mx-8">{{ __(session('error_sub')) }}</div>
                @endif

            </div>

            <x-slot name="footer">
                <div class="flex justify-center gap-x-4">
                    <x-button flat label="Fechar" x-on:click="close" />
                </div>
            </x-slot>

        </x-modal.card>
    @endif
    {{-- MODAL ERROR - FIM --}}

    {{-- MODAL CONCLUSAO ERROR --}}
    @if (session('conclusao_error'))
        <x-modal.card blur wire:model.defer="conclusao_error">

            <div class="flex flex-col justify-center items-center px-6">

                <div>
                    <img src="{{ asset('images/icones/icon-alert-animate.gif') }}" alt="Erro na Conclusão" class="h-32">
                </div>

                <div class="w-full text-center text-2xl text-red-700 mx-1 mb-2 font-medium">{{ __(session('conclusao_error')) }}</div>

                @if (session('conclusao_error_sub'))
                    <div class="w-full text-center text-xl text-red-700 mx-8">{{ __(session('conclusao_error_sub')) }}</div>
                @endif

            </div>

            <x-slot name="footer">
                <div class="flex justify-center gap-x-4">
                    <x-button flat label="Fechar" x-on:click="close" />
                </div>
            </x-slot>
        </x-modal.card>
    @endif
    {{-- MODAL CONCLUSAO ERROR - FIM --}}

    {{-- MODAL SUCCESS --}}
    @if (session('conclusao_success'))
        <x-modal.card blur wire:model="conclusao_success">

            <div class="flex flex-col justify-center items-center px-0 md:px-6">

                <div>
                    <img src="{{ asset('images/icones/icon-success-animate.gif') }}" alt="Sucesso" class="h-52 -mt-4">
                </div>

                <div class="w-full text-center text-2xl text-green-700 mx-1 font-medium -mt-10 uppercase">{{ __(session('conclusao_success')) }}</div>

                @if (session('conclusao_success_sub'))
                    <div class="w-full text-center text-xl text-green-700 mx-8">{{ __(session('conclusao_success_sub')) }}</div>
                @endif

                {{-- SE BOLETO PENDENTE --}}
                @if (($payment ?? false) && $payment->status == "pending_boleto" )

                    <div class="w-full py-2 px-4 mt-4 mb-1 uppercase text-center">
                        <div class="text-xs mb-2 text-gray-500 tracking-wider">CÓDIGO DE BARRAS</div>
                        <div class="w-full bg-gray-50 rounded-xl border border-gray-200 p-3">
                            <div class="text-sm font-mono font-medium text-gray-800">{{ $payment->pay_boleto_barcode ?? '---' }}</div>
                        </div>

                        <div class="flex justify-center gap-4 mt-4 capitalize">
                            <x-button outline flat primary class="w-1/2 md:w-1/3 rounded-lg" icon="clipboard" label="Copiar Código"   title="Copiar Código"   id="pay_boleto_barcode" onclick="copyToClipboard('pay_boleto_barcode','Código de barras copiado!')" data-clipboard-text="{{ $payment->pay_boleto_barcode ?? '---' }}" />
                            <x-button outline flat primary class="w-1/2 md:w-1/3 rounded-lg" icon="printer"   label="Imprimir Boleto" title="Imprimir Boleto" href="{{ $payment->pay_boleto_url ?? '#' }}" />
                        </div>
                    </div>

                @endif

                {{-- SE PIX PENDENTE --}}
                @if (($payment ?? false) && $payment->status == "pending_pix" )

                    <div class="w-full py-2 px-4 mt-4 mb-1 uppercase text-center">
                        <div class="text-xs mb-2 text-gray-500 tracking-wider">CHAVE COPIA e COLA</div>
                        <div class="w-full bg-gray-50 rounded-xl border border-gray-200 p-3 text-center break-words overflow-hidden">
                            <div class="text-xs font-mono font-medium text-gray-800">{{ $payment->pay_pix_key ?? 'CHAVE PIX AQUI' }}</div>
                        </div>

                        {{-- Poll automático a cada 10s enquanto PIX pendente --}}
                        <div wire:poll.10s="validarPagamento(false)" class="hidden"></div>
                        <div wire:loading wire:target="validarPagamento" class="flex items-center justify-center gap-1 text-xs text-gray-400 mt-2">
                            <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            <span>Verificando pagamento...</span>
                        </div>

                        <div class="flex justify-center gap-4 mt-4 capitalize">
                            <x-button outline flat primary class="w-full md:w-1/2 uppercase rounded-lg" icon="clipboard" label="Copiar Código"   title="Copiar Código"   id="pay_pix_key" onclick="copyToClipboard('pay_pix_key','Código PIX copiado!')" data-clipboard-text="{{ $payment->pay_pix_key ?? '---' }}" />
                            <x-button outline flat primary class="w-full md:w-1/2 uppercase rounded-lg" icon="check" label="Validar Pagamento"   title="Validar Pagamento" wire:click="validarPagamento" spinner="validarPagamento" />
                        </div>
                    </div>

                @endif

                <div class="flex flex-col justify-center items-center gap-2 pt-2 my-2">
                    <div class="text-sm font-normal">Pagamentos processados por</div>
                    <img src="{{ asset('assets/safe2pay-logo.png') }}" alt="Sucesso na Conclusão" class="h-10">
                </div>

            </div>

            <x-slot name="footer">
                <div class="flex justify-center gap-x-4">
                    <x-button flat label="Fechar" x-on:click="close" />
                </div>
            </x-slot>

        </x-modal.card>
    @endif
    {{-- MODAL SUCCESS - FIM --}}

    {{-- SCRIPS --}}
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
    <script type="text/javascript">
        function copyToClipboard(id,msg=false)
        {
            var Clipboard = new ClipboardJS('#' + id);
            if(msg)
            {
                alert(msg)
            }
        }
    </script>

</div>
