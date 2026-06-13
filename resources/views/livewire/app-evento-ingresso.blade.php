<div class="min-h-screen bg-white">

    @if ($orderTickets ?? false)

        @php
            // Color variables
            $colorPrimary   = $event->color_primary   ?? $event->color_default ?? '#6366f1';
            $colorSecondary = $event->color_secondary  ?? $event->color_default ?? '#8b5cf6';
            $colorDefault   = $event->color_default    ?? '#6366f1';
            $colorInverse   = $event->color_default_inverse ?? '#ffffff';

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

            // Build event logo URL
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

            // Build customer logo URL (fallback)
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

            $eventDate   = $event->event_datetime_start ? \Carbon\Carbon::parse($event->event_datetime_start) : null;
            $eventIsPast = $eventDate && $eventDate->isPast();
        @endphp

        {{-- ═══════════════════════════════════════════════════════
             HERO
        ═══════════════════════════════════════════════════════ --}}
        <section class="relative w-full overflow-hidden" style="min-height: 240px;">

            {{-- Hero background --}}
            @if ($urlImageBg)
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $urlImageBg }}');"></div>
                <div class="absolute inset-0" style="background: linear-gradient(160deg, {{ $colorPrimary }}cc 0%, rgba(10,10,20,0.88) 100%);"></div>
            @elseif ($urlImage)
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat scale-105" style="background-image: url('{{ $urlImage }}'); filter: blur(2px) brightness(0.35);"></div>
                <div class="absolute inset-0" style="background: linear-gradient(160deg, {{ $colorPrimary }}88 0%, rgba(10,10,20,0.92) 100%);"></div>
            @else
                <div class="absolute inset-0" style="background: linear-gradient(135deg, {{ $colorPrimary }} 0%, {{ $colorSecondary }} 50%, rgba(10,10,20,1) 100%);"></div>
            @endif

            {{-- Decorative glow --}}
            <div class="absolute -top-20 -left-20 w-96 h-96 rounded-full opacity-20 blur-3xl pointer-events-none" style="background: {{ $colorPrimary }};"></div>
            <div class="absolute -bottom-10 -right-10 w-72 h-72 rounded-full opacity-15 blur-3xl pointer-events-none" style="background: {{ $colorSecondary }};"></div>

            <div class="relative z-10 max-w-4xl mx-auto px-5 md:px-10 py-6 md:py-8 pb-16 md:pb-24">

                {{-- Logo + Date (desktop) + Badge --}}
                <div class="flex items-center justify-between w-full gap-3 mb-5">
                    <div class="flex items-center gap-4 min-w-0">
                        @if ($urlImageLogo)
                            <img class="h-10 md:h-12 w-auto drop-shadow-lg flex-shrink-0" src="{{ $urlImageLogo }}" alt="">
                        @elseif ($urlCustomerLogo)
                            <img class="h-10 md:h-12 w-auto drop-shadow-lg flex-shrink-0" src="{{ $urlCustomerLogo }}" alt="">
                        @else
                            <img class="h-10 md:h-12 w-auto drop-shadow-lg flex-shrink-0" src="{{ appLogo(true) }}" alt="{{ appName() }}">
                        @endif

                        {{-- Data ao lado da logo (desktop) --}}
                        @if ($eventDate)
                            <div class="hidden md:flex flex-col min-w-0">
                                <span class="text-white text-sm font-bold drop-shadow-lg truncate">
                                    {{ $eventDate->locale('pt_BR')->isoFormat('D [de] MMMM [de] YYYY [·] HH[h]mm') }}
                                </span>
                                @if (!$eventIsPast)
                                    <span class="text-xs font-semibold drop-shadow" style="color: {{ $colorDefault }};">
                                        {{ $eventDate->locale('pt_BR')->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Contador de vouchers --}}
                    <span class="inline-flex items-center gap-1.5 px-3 md:px-4 py-2 text-xs font-semibold uppercase tracking-wider rounded-full shadow-lg flex-shrink-0"
                          style="background-color: {{ $colorDefault }}; color: {{ $colorInverse }};">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        {{ $orderTickets->count() }} {{ $event->event_tickets_nomenclature ?? 'VOUCHER' }}{{ $orderTickets->count() > 1 ? 'S' : '' }}
                    </span>
                </div>

                {{-- Location badge --}}
                @php
                    $locationParts = array_filter([$event->city ?? null, $event->state ?? null]);
                    $heroLocationText = implode(', ', $locationParts);
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

                {{-- Título do evento --}}
                <h1 class="text-2xl md:text-4xl text-white font-extrabold uppercase tracking-tight drop-shadow-xl leading-tight">{{ $event->event_name ?? '--' }}</h1>

                @if ($event->organizer->organizer_name_full ?? false)
                    <p class="text-sm md:text-base mt-2 font-medium text-white/60 uppercase tracking-wide">{{ $event->organizer->organizer_name_full }}</p>
                @endif

                {{-- Data (mobile) --}}
                @if ($eventDate)
                    <div class="mt-3 flex flex-col items-start gap-0.5 md:hidden">
                        <span class="text-white text-sm font-bold drop-shadow-lg">
                            {{ $eventDate->locale('pt_BR')->isoFormat('D [de] MMMM [de] YYYY [·] HH[h]mm') }}
                        </span>
                        @if (!$eventIsPast)
                            <span class="text-xs font-semibold drop-shadow" style="color: {{ $colorDefault }};">
                                {{ $eventDate->locale('pt_BR')->diffForHumans() }}
                            </span>
                        @endif
                    </div>
                @endif

            </div>
        </section>

        {{-- ═══════════════════════════════════════════════════════
             BODY — LOCALIZADOR + VOUCHERS
        ═══════════════════════════════════════════════════════ --}}
        <div class="w-full max-w-4xl mx-auto px-4 md:px-10 -mt-10 md:-mt-14 relative z-20">

            {{-- Card Localizador --}}
            <div class="w-full rounded-2xl bg-white shadow-xl overflow-hidden mb-6" style="border: 1px solid {{ $colorPrimary }}18;">
                <div class="px-5 md:px-8 py-4" style="background: {{ $colorPrimary }}08; border-bottom: 1px solid {{ $colorPrimary }}15;">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-3">
                        <div>
                            <div class="text-xs tracking-widest font-light text-gray-400 uppercase">LOCALIZADOR</div>
                            <div class="text-lg md:text-xl font-bold text-gray-800 uppercase -mt-0.5">{{ $order->order_control ?? '--' }}</div>
                        </div>
                        <div class="text-right min-w-0 flex-1">
                            <div class="text-xs tracking-widest font-light text-gray-400 uppercase">COMPRADOR</div>
                            <div class="text-sm md:text-base font-semibold text-gray-700 uppercase -mt-0.5 truncate">{{ $order->buyer_name ?? '--' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- VOUCHERS / INGRESSOS --}}
            <div class="flex flex-col gap-5 pb-10">

                @foreach ($orderTickets ?? [] as $ticketKey => $ticket)

                    <div class="w-full rounded-2xl bg-white shadow-md overflow-hidden transition-all duration-200" style="border: 1px solid {{ $colorPrimary }}18;">

                        {{-- Ticket header — tipo + status --}}
                        <div class="px-5 md:px-6 py-3 flex items-center justify-between gap-2" style="background: {{ $colorPrimary }}08; border-bottom: 1px solid {{ $colorPrimary }}15;">
                            <div class="flex items-center gap-2 min-w-0 w-full sm:w-auto">
                                <svg class="w-4 h-4 flex-shrink-0" style="color: {{ $colorPrimary }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                <span class="text-xs font-semibold uppercase tracking-widest text-gray-600">
                                    {{ $event->event_tickets_nomenclature ?? 'VOUCHER' }}
                                    <span class="font-bold" style="color: {{ $colorPrimary }};">{{ $ticket->type->ticket_name ?? null }}</span>
                                </span>
                            </div>
                            @if (in_array($ticket->ticket_status, ['utilizado']))
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold uppercase tracking-wider rounded-full bg-green-100 text-green-700 border border-green-200 flex-shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ __($ticket->ticket_status) }}
                                </span>
                            @elseif (in_array($ticket->ticket_status, ['disponivel']))
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold uppercase tracking-wider rounded-full shadow-sm flex-shrink-0"
                                      style="background-color: {{ $colorDefault }}22; color: {{ $colorPrimary }}; border: 1px solid {{ $colorDefault }}44;">
                                    {{ __($ticket->ticket_status) }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold uppercase tracking-wider rounded-full bg-gray-100 text-gray-600 border border-gray-200 flex-shrink-0">
                                    {{ __($ticket->ticket_status) }}
                                </span>
                            @endif
                        </div>

                        {{-- Ticket body --}}
                        <div class="px-5 md:px-6 py-4">
                            <div class="flex flex-col md:flex-row gap-4 md:gap-6">

                                {{-- Left: User info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs tracking-widest font-light text-gray-400 uppercase mb-1">UTILIZADOR</div>
                                    <div class="text-lg md:text-xl font-bold text-gray-800 uppercase break-words">{{ $ticket->user_name }}</div>

                                    <div class="flex flex-col gap-1.5 mt-3">
                                        @if ($ticket->user_email ?? false)
                                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                                <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                <span class="lowercase break-all">{{ $ticket->user_email }}</span>
                                            </div>
                                        @endif
                                        @if (($ticket->user_contact_ddd ?? false) || ($ticket->user_contact_num ?? false))
                                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                                <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                <span>({{ $ticket->user_contact_ddd ?? '--' }}) {{ $ticket->user_contact_num ?? '--' }}</span>
                                            </div>
                                        @endif
                                        @if ($ticket->user_birth_date ?? false)
                                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                                <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.75 1.75 0 003 16.5v2.25A1.25 1.25 0 004.25 20h15.5A1.25 1.25 0 0021 18.75v-3.204zM3.75 12h16.5M12 4v8"/></svg>
                                                <span>{{ $ticket->user_birth_date->format('d/m/Y') }} · {{ $ticket->user_birth_date->age }} anos</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Data do evento compacta --}}
                                    <div class="mt-3 pt-3" style="border-top: 1px solid {{ $colorPrimary }}10;">
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span class="font-medium">
                                                @if ($event->event_datetime_start)
                                                    {{ \Carbon\Carbon::parse($event->event_datetime_start)->format('d/m/Y - H:i') }}
                                                    @if ($event->event_datetime_finish)
                                                        @if (\Carbon\Carbon::parse($event->event_datetime_start)->format('d/m/Y') == \Carbon\Carbon::parse($event->event_datetime_finish)->format('d/m/Y'))
                                                            às {{ \Carbon\Carbon::parse($event->event_datetime_finish)->format('H:i') }}
                                                        @else
                                                            até {{ \Carbon\Carbon::parse($event->event_datetime_finish)->format('d/m/Y - H:i') }}
                                                        @endif
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Right: QR Code + Ticket Control --}}
                                <div class="flex flex-col items-center justify-center gap-2 flex-shrink-0">
                                    @if (in_array($ticket->ticket_status, ['disponivel']))
                                        <div class="p-3 rounded-xl bg-white shadow-sm" style="border: 2px solid {{ $colorPrimary }}30;">
                                            {{ \QrCode::size(120)->generate($ticket->ticket_control) }}
                                        </div>
                                    @else
                                        <div class="p-3 rounded-xl bg-gray-50 opacity-50" style="border: 2px dashed {{ $colorPrimary }}25;">
                                            {{ \QrCode::size(120)->generate($ticket->ticket_control) }}
                                        </div>
                                    @endif
                                    <div class="text-center">
                                        <div class="text-xxs tracking-widest font-light text-gray-400 uppercase">CÓDIGO</div>
                                        <div class="text-xs font-mono font-semibold text-gray-500 break-all max-w-[150px]">{{ $ticket->ticket_control }}</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Ticket footer — organizador --}}
                        <div class="px-5 md:px-6 py-2.5 text-center" style="background: {{ $colorPrimary }}05; border-top: 1px dashed {{ $colorPrimary }}20;">
                            <span class="text-xs text-gray-400 uppercase tracking-wider">{{ $event->organizer->organizer_name_full ?? null }}</span>
                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    @else

        {{-- ERROR / NOT FOUND --}}
        <div class="min-h-[60vh] flex flex-col items-center justify-center px-6 py-12">
            <img src="{{ asset('images/app/assets/404.gif') }}" class="w-48 md:w-64 mb-6" alt="">
            <x-jet-validation-errors />
        </div>

    @endif

</div>
