<div class="min-h-screen bg-white">

    <div class="w-full max-w-7xl mx-auto">
        <x-jet-validation-errors />
    </div>


    @if ($campaignList->count())

        <main class="max-w-7xl mx-auto px-6 md:px-8 pt-6 pb-12">

            {{-- Seção Principal - Cards Grandes (Destaque) --}}
            @if($campaignList->take(3)->count() > 0)
                <section class="mb-16">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Campanhas em Destaque</h2>
                        <div class="flex items-center gap-3">
                            @if($campaignList->count() > 3)
                                <a href="#todas" class="text-sm font-medium text-gray-600 hover:text-gray-900">Ver todas</a>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                        @foreach($campaignList->take(3) as $campaign)
                            @php
                                // Função helper para gerar URL completa da imagem
                                $getImageUrl = function($url) {
                                    if (empty($url)) return null;
                                    // Verifica se já é URL completa
                                    if (substr($url, 0, 7) === 'http://' || substr($url, 0, 8) === 'https://') {
                                        return $url;
                                    }
                                    // Se começa com /storage/, usa asset diretamente
                                    if (substr($url, 0, 9) === '/storage/') {
                                        return asset($url);
                                    }
                                    // Se começa com storage/, adiciona /
                                    if (substr($url, 0, 8) === 'storage/') {
                                        return asset('/' . $url);
                                    }
                                    // Se começa com /, assume que é um caminho absoluto
                                    if (substr($url, 0, 1) === '/') {
                                        return asset($url);
                                    }
                                    // Caso padrão: adiciona storage/
                                    return tenantAsset($url,true);
                                };

                                $urlImage = null;

                                // 1. Prioridade: Imagem específica da campanha (banner ou thumb)
                                if (!empty($campaign->url_image_banner)) {
                                    $urlImage = $getImageUrl($campaign->url_image_banner);
                                } elseif (!empty($campaign->url_image_thumb)) {
                                    $urlImage = $getImageUrl($campaign->url_image_thumb);
                                }

                                // 2. Se não tem imagem da campanha, usa thumbnail padrão do app
                                if (!$urlImage) {
                                    $urlImage = appDefaultThumb(true);
                                    // Verifica se realmente existe uma thumb padrão (não é a padrão do sistema)
                                    if ($urlImage === asset('images/default-thumb.png')) {
                                        $urlImage = null; // Reset para tentar próxima opção
                                    }
                                }

                                // 3. Se não tem thumb padrão do app, usa logo do cliente
                                if (!$urlImage && $campaign->customer && !empty($campaign->customer->url_image_logo)) {
                                    $urlImage = $getImageUrl($campaign->customer->url_image_logo);
                                }

                                // 4. Última opção: logo principal do app
                                $isDefaultLogo = false;
                                if (!$urlImage) {
                                    $urlImage = appLogo(true);
                                    $isDefaultLogo = true;
                                }

                                // Determinar se deve usar estilo de logo (para logos e thumbs padrão)
                                $useLogoStyle = !(!empty($campaign->url_image_banner) || !empty($campaign->url_image_thumb));
                            @endphp

                            <a href="{{ campanhaUrl($campaign->customer_organization_slug, $campaign->slug) }}" class="group block">
                                <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 shadow">
                                    {{-- Imagem Grande --}}
                                    <div class="relative h-64 md:h-80 bg-gray-100 overflow-hidden {{ $useLogoStyle ? 'flex items-center justify-center' : '' }}">
                                        <img
                                            src="{{ $urlImage }}"
                                            alt="{{ $campaign->name }}"
                                            class="{{ $useLogoStyle ? 'max-w-[70%] max-h-[70%] w-auto h-auto object-contain' : 'w-full h-full object-cover' }} transition-transform duration-700 group-hover:scale-110"
                                            onerror="this.onerror=null; this.src='{{ appLogo(true) }}'; this.className='max-w-[70%] max-h-[70%] w-auto h-auto object-contain transition-transform duration-700 group-hover:scale-110';"
                                        />

                                        {{-- Badge Status --}}
                                        @if($campaign->datetime_finish && $campaign->datetime_finish < now())
                                            <div class="absolute top-3 right-3 md:top-4 md:right-4 bg-white/90 backdrop-blur-sm px-2.5 md:px-3 py-1 md:py-1.5 rounded-full text-xs font-semibold shadow uppercase text-gray-700">
                                                Finalizada
                                            </div>
                                        @elseif($campaign->status === 'active')
                                            <div class="absolute top-3 right-3 md:top-4 md:right-4 bg-green-500 text-white px-2.5 md:px-3 py-1 md:py-1.5 rounded-full text-xs font-semibold shadow uppercase">
                                                Ativa
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Conteúdo na Parte Inferior --}}
                                    <div class="p-4 md:p-6">

                                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-1 md:mb-1 line-clamp-2 group-hover:text-gray-700 transition-colors capitalize">
                                            {{ $campaign->name }}
                                        </h3>

                                        {{-- Organizador --}}
                                        <div class="space-y-1 md:space-y-1">
                                            @if($campaign->organizer && $campaign->organizer->organizer_name_full)
                                                <div class="text-xs md:text-sm text-gray-600">
                                                    <span class="capitalize font-medium truncate">{{ Str::limit($campaign->organizer->organizer_name_full, 100) }}</span>
                                                </div>
                                            @elseif($campaign->organizer && $campaign->organizer->organizer_name)
                                                <div class="text-xs md:text-sm text-gray-600">
                                                    <span class="capitalize font-medium">{{ Str::limit($campaign->organizer->organizer_name, 100) }}</span>
                                                </div>
                                            @else
                                                <div class="text-xs md:text-sm text-gray-600">
                                                    <span class="capitalize font-medium">Organizador</span>
                                                </div>
                                            @endif
                                        </div>

                                        @if($campaign->datetime_finish)
                                            <div class="flex items-center gap-2 text-xs md:text-sm text-gray-500 mt-3 md:mt-4 pt-3 md:pt-4 border-t border-gray-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>Até {{ \Carbon\Carbon::parse($campaign->datetime_finish)->format('d/m/Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif


            {{-- Seção Todas as Campanhas --}}
            @if($campaignList->skip(3)->count() > 0)
                <section id="todas">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Outras Campanhas</h2>
                        <span class="text-sm text-gray-500">{{ $campaignList->count() }} campanhas</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                        @foreach($campaignList->skip(3) as $campaign)
                            @php
                                // Função helper para gerar URL completa da imagem
                                $getImageUrl = function($url) {
                                    if (empty($url)) return null;
                                    // Verifica se já é URL completa
                                    if (substr($url, 0, 7) === 'http://' || substr($url, 0, 8) === 'https://') {
                                        return $url;
                                    }
                                    // Se começa com /storage/, usa asset diretamente
                                    if (substr($url, 0, 9) === '/storage/') {
                                        return asset($url);
                                    }
                                    // Se começa com storage/, adiciona /
                                    if (substr($url, 0, 8) === 'storage/') {
                                        return asset('/' . $url);
                                    }
                                    // Se começa com /, assume que é um caminho absoluto
                                    if (substr($url, 0, 1) === '/') {
                                        return asset($url);
                                    }
                                    // Caso padrão: adiciona storage/
                                    return asset('storage/' . $url);
                                };

                                $urlImage = null;

                                // 1. Prioridade: Imagem específica da campanha (banner ou thumb)
                                if (!empty($campaign->url_image_banner)) {
                                    $urlImage = $getImageUrl($campaign->url_image_banner);
                                } elseif (!empty($campaign->url_image_thumb)) {
                                    $urlImage = $getImageUrl($campaign->url_image_thumb);
                                }

                                // 2. Se não tem imagem da campanha, usa thumbnail padrão do app
                                $isDefaultThumb = false;
                                if (!$urlImage) {
                                    $urlImage = appDefaultThumb(true);
                                    // Verifica se realmente existe uma thumb padrão (não é a padrão do sistema)
                                    if ($urlImage !== asset('images/default-thumb.png')) {
                                        $isDefaultThumb = true;
                                    } else {
                                        $urlImage = null; // Reset para tentar próxima opção
                                    }
                                }

                                // 3. Se não tem thumb padrão do app, usa logo do cliente
                                if (!$urlImage && $campaign->customer && !empty($campaign->customer->url_image_logo)) {
                                    $urlImage = $getImageUrl($campaign->customer->url_image_logo);
                                }

                                // 4. Última opção: logo principal do app
                                $isDefaultLogo = false;
                                if (!$urlImage) {
                                    $urlImage = appLogo(true);
                                    $isDefaultLogo = true;
                                }

                                // Determina se deve usar estilo de logo (centralizado e proporcional)
                                $useLogoStyle = $isDefaultLogo || $isDefaultThumb;
                            @endphp

                            <a href="{{ campanhaUrl($campaign->customer_organization_slug, $campaign->slug) }}" class="group block">
                                <div class="bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5">
                                    {{-- Imagem --}}
                                    <div class="relative h-64 bg-gray-100 overflow-hidden {{ $useLogoStyle ? 'flex items-center justify-center' : '' }}">
                                        <img
                                            src="{{ $urlImage }}"
                                            alt="{{ $campaign->name }}"
                                            class="{{ $useLogoStyle ? 'max-w-[70%] max-h-[70%] w-auto h-auto object-contain' : 'w-full h-full object-cover' }} transition-transform duration-500 group-hover:scale-105"
                                            onerror="this.onerror=null; this.src='{{ appLogo(true) }}'; this.className='max-w-[70%] max-h-[70%] w-auto h-auto object-contain transition-transform duration-500 group-hover:scale-105';"
                                        />
                                        @if($campaign->datetime_finish && $campaign->datetime_finish < now())
                                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-full text-xs font-medium text-gray-700">
                                                Finalizada
                                            </div>
                                        @elseif($campaign->status === 'active')
                                            <div class="absolute top-3 right-3 bg-green-500 text-white px-2.5 py-1 rounded-full text-xs font-semibold">
                                                Ativo
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Conteúdo na Parte Inferior --}}
                                    <div class="p-4 md:p-5">
                                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-2 md:mb-3 line-clamp-2 group-hover:text-gray-700 transition-colors">
                                            {{ $campaign->name }}
                                        </h3>

                                        {{-- Organizador --}}
                                        <div class="space-y-1 md:space-y-1.5">
                                            @if($campaign->organizer && $campaign->organizer->organizer_name_full)
                                                <div class="text-xs md:text-sm text-gray-600">
                                                    <span class="capitalize font-medium">{{ Str::limit($campaign->organizer->organizer_name_full, 100) }}</span>
                                                </div>
                                            @elseif($campaign->organizer && $campaign->organizer->organizer_name)
                                                <div class="text-xs md:text-sm text-gray-600">
                                                    <span class="capitalize font-medium">{{ Str::limit($campaign->organizer->organizer_name, 100) }}</span>
                                                </div>
                                            @else
                                                <div class="text-xs md:text-sm text-gray-600">
                                                    <span class="capitalize font-medium">Organizador</span>
                                                </div>
                                            @endif
                                        </div>

                                        @if($campaign->datetime_finish)
                                            <div class="flex items-center gap-2 text-xs md:text-sm text-gray-500 mt-3 md:mt-4 pt-3 md:pt-4 border-t border-gray-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>Até {{ \Carbon\Carbon::parse($campaign->datetime_finish)->format('d/m/Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>

    @else
        <div class="max-w-7xl mx-auto px-6 md:px-8 py-24">
            <div class="text-center">
                <svg class="w-24 h-24 text-gray-200 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                <h3 class="text-3xl font-bold text-gray-900 mb-3">
                    @if($search || $filterCustomer)
                        Nenhuma campanha encontrada
                    @else
                        Nenhuma campanha disponível
                    @endif
                </h3>
                <p class="text-gray-500 text-lg">
                    @if($search || $filterCustomer)
                        Tente ajustar os filtros de busca
                    @else
                        Volte em breve para conhecer nossas campanhas
                    @endif
                </p>
            </div>
        </div>
    @endif

    @if ($userUuid ?? false)
        <div class="fixed bottom-4 right-4 bg-white border border-gray-200 rounded-lg p-3 shadow-lg max-w-xs">
            <div class="flex items-center gap-2">
                <p class="text-xs text-gray-600 font-mono truncate flex-1" id="userUuid">{{ $userUuid }}</p>
                <button
                    onclick="copyToClipboard('{{ $userUuid }}')"
                    class="flex-shrink-0 bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs font-medium transition-colors duration-200"
                    id="copyBtn"
                    title="Copiar UUID"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <script>
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    const btn = document.getElementById('copyBtn');
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                    btn.classList.remove('bg-gray-100', 'hover:bg-gray-200');
                    btn.classList.add('bg-green-100', 'text-green-700');

                    setTimeout(function() {
                        btn.innerHTML = originalHTML;
                        btn.classList.remove('bg-green-100', 'text-green-700');
                        btn.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
                    }, 1500);
                });
            }
        </script>
    @endif

</div>
