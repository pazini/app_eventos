<div class="min-h-screen bg-white">

    <div class="w-full max-w-7xl mx-auto px-4">
        <x-jet-validation-errors />
    </div>

    @if ($eventList->count())

        <main class="{{ $isAppVersion ? 'px-4 pt-5 pb-10' : 'max-w-7xl mx-auto px-6 md:px-8 pt-8 pb-16' }}">

            <section>
                {{-- ─── CABEÇALHO DA SEÇÃO ─── --}}
                <div class="flex flex-col {{ $isAppVersion ? '' : 'md:flex-row md:items-center md:justify-between' }} items-start gap-4 mb-8">

                    <div>
                        <h2 class="{{ $isAppVersion ? 'text-xl' : 'text-2xl md:text-3xl' }} font-extrabold text-gray-800 uppercase tracking-tight">
                            Eventos em Destaque
                        </h2>
                        <span class="text-sm font-normal mt-0.5 block text-gray-400">
                            {{ $totalEvents }} {{ $totalEvents == 1 ? 'evento disponível' : 'eventos disponíveis' }}
                        </span>
                    </div>

                    {{-- SELECT ORDENAÇÃO --}}
                    <div class="flex items-center gap-3 {{ $isAppVersion ? 'w-full' : 'w-full md:w-auto' }}">
                        <div class="relative inline-flex items-center {{ $isAppVersion ? 'w-full' : '' }}">
                            <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
                            </svg>
                            <select
                                wire:model="sortBy"
                                class="appearance-none pl-9 pr-8 py-2 text-sm font-semibold focus:outline-none cursor-pointer transition-colors bg-white border border-gray-200 rounded-xl text-gray-700 {{ $isAppVersion ? 'w-full' : '' }}"
                            >
                                <option value="event_datetime_start_asc">Mais Próximos</option>
                                <option value="event_datetime_start">Mais Distantes</option>
                                <option value="event_name_asc">Nome (A-Z)</option>
                                <option value="event_name_desc">Nome (Z-A)</option>
                                <option value="created_at">Recém Cadastrados</option>
                            </select>
                            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- ─── GRID DE EVENTOS ─── --}}
                <div class="grid {{ $isAppVersion ? 'grid-cols-1 gap-4' : 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6' }}">
                    @foreach($eventList as $event)
                        @php
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

                            $urlImage = null;

                            if ($event->url_image_thumbnail ?? false) {
                                $urlImage = tenantAsset($event->url_image_thumbnail, true);
                            }
                            if (!$urlImage && ($event->url_image ?? false)) {
                                $urlImage = tenantAsset($event->url_image, true);
                            }
                            if (!$urlImage) {
                                $defaultThumb = appDefaultThumb(true);
                                if ($defaultThumb && $defaultThumb !== asset('images/default-thumb.png')) {
                                    $urlImage = $defaultThumb;
                                }
                            }
                            if (!$urlImage && $event->customer && ($event->customer->url_image_logo ?? false)) {
                                $urlImage = tenantAsset($event->customer->url_image_logo, true);
                            }

                            if ($isExternalEvent) {
                                if (($event->url_image_thumbnail ?? false) && !$isInternalMediaPath($event->url_image_thumbnail)) {
                                    $urlImage = $event->referer_url . '/' . $event->url_image_thumbnail;
                                }
                                if (!$urlImage && ($event->url_image ?? false) && !$isInternalMediaPath($event->url_image)) {
                                    $urlImage = $event->referer_url . '/' . $event->url_image;
                                }
                            }

                            $useLogoStyle = false;
                            if (!$urlImage) {
                                $urlImage = appLogo(true);
                                $useLogoStyle = true;
                            }

                            $isFinished = $event->event_datetime_finish && $event->event_datetime_finish < now();
                            $isOngoing  = $event->event_datetime_start && $event->event_datetime_start <= now() && !$isFinished;
                            $isFuture   = $event->event_datetime_start && $event->event_datetime_start > now();

                            $cardColor  = $event->color_primary ?? $event->color_default ?? '#6366f1';
                        @endphp

                        <a href="{{ $isAppVersion ? route('app-version-evento', $event->event_slug) : eventoUrl($event->event_slug) }}"
                           class="group block h-full">
                            <div class="relative rounded-2xl overflow-hidden h-full flex flex-col transition-all duration-300
                                        {{ $isAppVersion ? 'active:scale-[0.98]' : 'hover:-translate-y-1.5 hover:shadow-2xl' }}"
                                 style="background: #fff;
                                        border: 1px solid #e5e7eb;
                                        box-shadow: 0 2px 12px rgba(0,0,0,0.06);">

                                {{-- IMAGEM --}}
                                <div class="relative {{ $isAppVersion ? 'h-48' : 'h-60 md:h-72' }} flex-shrink-0 overflow-hidden
                                            {{ $useLogoStyle ? 'flex items-center justify-center' : '' }}"
                                     style="background: #f9fafb;">
                                    <img
                                        src="{{ $urlImage }}"
                                        alt="{{ $event->event_name }}"
                                        class="{{ $useLogoStyle ? 'max-w-[60%] max-h-[60%] w-auto h-auto object-contain opacity-70' : 'w-full h-full object-cover' }} transition-transform duration-700 group-hover:scale-105"
                                        onerror="this.onerror=null; this.src='{{ appLogo(true) }}'; this.className='max-w-[60%] max-h-[60%] w-auto h-auto object-contain opacity-70 transition-transform duration-700 group-hover:scale-105';"
                                    />
                                    {{-- Overlay sutil na imagem --}}
                                    @if(!$useLogoStyle)
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                    @endif

                                    {{-- BADGES --}}
                                    @if($isFinished)
                                        <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs font-semibold uppercase bg-gray-700/80 text-white backdrop-blur-sm">Realizado</div>
                                    @elseif($isOngoing)
                                        <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs font-semibold uppercase"
                                             style="background: rgba(249,115,22,0.9); color: #fff;">Em Andamento</div>
                                    @elseif($isFuture)
                                        <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs font-semibold uppercase"
                                             style="background: rgba(99,102,241,0.9); color: #fff;">Em Breve</div>
                                    @endif

                                    {{-- Indicador de cor do evento --}}
                                    <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gray-200"></div>
                                </div>

                                {{-- CONTEÚDO --}}
                                <div class="p-4 md:p-5 flex-1 flex flex-col">
                                    <h3 class="font-bold text-gray-800 uppercase {{ $isAppVersion ? 'text-base' : 'text-lg md:text-xl' }} mb-2 line-clamp-2 group-hover:opacity-80 transition-opacity capitalize flex-shrink-0 leading-snug">
                                        {{ $event->event_name }}
                                    </h3>

                                    <div class="flex-1"></div>

                                    @if($event->event_datetime_start && !$isFinished)
                                        <div class="flex items-center gap-2 mb-3 flex-shrink-0">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-gray-700 text-sm font-medium uppercase">
                                                {{ \Carbon\Carbon::parse($event->event_datetime_start)->locale('pt_BR')->isoFormat('D [de] MMM [·] HH[h]mm') }}
                                            </span>
                                            <span class="text-xs font-normal text-gray-400 uppercase">
                                                ({{ \Carbon\Carbon::parse($event->event_datetime_start)->locale('pt_BR')->diffForHumans() }})
                                            </span>
                                        </div>
                                    @endif

                                    @if($event->organizer && $event->organizer->organizer_name_full)
                                        @php
                                            $org = $event->organizer;
                                            $locationParts = array_filter([
                                                $event->city ?? ($org->organization->organization_name ?? null),
                                                $event->state ?? null,
                                            ]);
                                            $locationText = implode(', ', $locationParts);
                                            if (!$locationText) {
                                                $locationText = $org->organization->organization_name ?? null;
                                            }
                                        @endphp
                                        <div class="pt-3 flex-shrink-0 border-t border-gray-100"
                                             title="{{ $org->organizer_name_full }}">
                                            {{-- Local do evento com pin --}}
                                            @if($locationText)
                                                <div class="flex items-center gap-1.5 mb-1">
                                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                         stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    <span class="text-sm font-bold text-gray-700 uppercase truncate">{{ $locationText }}</span>
                                                </div>
                                            @endif
                                            {{-- Organizador --}}
                                            <p class="text-[11px] uppercase font-medium truncate leading-tight text-gray-400 {{ $locationText ? 'pl-5' : '' }}">{{ $org->organizer_name_full }}</p>
                                        </div>
                                    @elseif($event->customer && $event->customer->name_corporate)
                                        <div class="pt-3 flex-shrink-0 border-t border-gray-100"
                                             title="{{ $event->customer->name_corporate }}">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                     stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <span class="text-sm font-bold text-gray-700 uppercase truncate">{{ Str::limit($event->customer->name_corporate, 40) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- ─── BOTÃO CARREGAR MAIS ─── --}}
                @if($this->hasMore)
                    <div class="flex justify-center mt-12">
                        <button
                            wire:click="loadMore"
                            wire:loading.attr="disabled"
                            wire:target="loadMore"
                            class="inline-flex items-center gap-2 px-8 py-3 rounded-full font-semibold text-sm uppercase tracking-wide transition-all duration-200 hover:opacity-90 hover:scale-105 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed bg-white border border-gray-200 text-gray-700 shadow-sm"
                        >
                            <span wire:loading.remove wire:target="loadMore">Carregar Mais Eventos</span>
                            <span wire:loading wire:target="loadMore" class="flex items-center gap-2" style="display:none;">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Carregando...
                            </span>
                        </button>
                    </div>
                @endif
            </section>
        </main>

    @else
        <div class="{{ $isAppVersion ? 'px-6 py-20' : 'max-w-7xl mx-auto px-6 md:px-8 py-32' }}">
            <div class="text-center">
                <div class="w-20 h-20 rounded-full mx-auto mb-6 flex items-center justify-center bg-gray-100 border border-gray-200">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="{{ $isAppVersion ? 'text-xl' : 'text-2xl md:text-3xl' }} font-bold text-gray-800 mb-3">
                    @if($search || $filterCustomer)
                        Nenhuma informação encontrada
                    @else
                        Nenhuma informação disponível
                    @endif
                </h3>
                <p class="{{ $isAppVersion ? 'text-sm' : 'text-base' }} text-gray-400">
                    @if($search || $filterCustomer)
                        Tente ajustar os filtros de busca
                    @else
                        Volte em breve, teremos novidades
                    @endif
                </p>
            </div>
        </div>
    @endif

</div>
