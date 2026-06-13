<div class="min-h-screen bg-white">

    @php
        $colorPrimary   = $target->color_primary   ?? $target->color_default ?? '#6366f1';
        $colorSecondary = $target->color_secondary  ?? $target->color_default ?? '#8b5cf6';
        $colorDefault   = $target->color_default    ?? '#6366f1';
        $colorInverse   = $target->color_default_inverse ?? '#ffffff';

        // Logo do evento
        $urlImageLogo = null;
        if ($target->url_image_logo ?? false)
            $urlImageLogo = tenantAsset($target->url_image_logo, true);
        elseif ($target->customer->url_image_logo ?? false)
            $urlImageLogo = tenantAsset($target->customer->url_image_logo, true);

        // BG do evento
        $urlImageBg = null;
        if ($target->url_image_bg ?? false)
            $urlImageBg = tenantAsset($target->url_image_bg, true);
        elseif ($target->url_image ?? false)
            $urlImageBg = tenantAsset($target->url_image, true);

        // Logo do patrocinador
        $sponsorLogo = null;
        if ($order->buyer_url_logo ?? false)
            $sponsorLogo = str_starts_with($order->buyer_url_logo, '/storage/')
                ? asset($order->buyer_url_logo)
                : tenantAsset($order->buyer_url_logo, true);
    @endphp

    {{-- LIVEWIRE - LOADER (apenas para ações pesadas, não para o poll) --}}
    <div wire:loading.class.remove="hidden"
         wire:target="processarPagamento,checkExpiration"
         class="hidden fixed inset-0 z-[999] flex items-center justify-center"
         style="background: rgba(255,255,255,0.80); backdrop-filter: blur(6px);">
        <div class="flex flex-col items-center gap-4">
            <img src="{{ asset('/assets/loader.v2.svg') }}" alt="" class="w-16 h-16">
            <span class="text-gray-500 text-sm uppercase tracking-widest font-light">Aguarde...</span>
        </div>
    </div>
    {{-- LIVEWIRE - LOADER FIM --}}

    {{-- ═══════════════════════════════════════
        HERO / HEADER
    ════════════════════════════════════════ --}}
    <style>
        @keyframes heroBgDrift {
            0%   { transform: scale(1.08) translate(0%, 0%); }
            25%  { transform: scale(1.13) translate(-1.5%, -1%); }
            50%  { transform: scale(1.10) translate(1%, -2%); }
            75%  { transform: scale(1.14) translate(-0.5%, 1%); }
            100% { transform: scale(1.08) translate(0%, 0%); }
        }
        .hero-bg-animate {
            animation: heroBgDrift 24s ease-in-out infinite;
            will-change: transform;
        }
    </style>

    <section class="relative w-full overflow-hidden" style="min-height: 260px;">

        {{-- Background --}}
        @if ($urlImageBg)
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat hero-bg-animate" style="background-image: url('{{ $urlImageBg }}'); filter: blur(2px) brightness(0.35);"></div>
            <div class="absolute inset-0" style="background: linear-gradient(160deg, {{ $colorPrimary }}88 0%, rgba(10,10,20,0.92) 100%);"></div>
        @else
            <div class="absolute inset-0" style="background: linear-gradient(135deg, {{ $colorPrimary }} 0%, {{ $colorSecondary }} 50%, rgba(10,10,20,1) 100%);"></div>
        @endif

        {{-- Glow --}}
        <div class="absolute rounded-full pointer-events-none" style="top:-5rem;left:-5rem;width:24rem;height:24rem;opacity:0.2;filter:blur(60px);background:{{ $colorPrimary }};"></div>
        <div class="absolute rounded-full pointer-events-none" style="bottom:-2.5rem;right:-2.5rem;width:18rem;height:18rem;opacity:0.15;filter:blur(60px);background:{{ $colorSecondary }};"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-6 md:px-10" style="padding-top:2rem;padding-bottom:7rem;">

            {{-- Logo evento + Status --}}
            <div class="flex items-center justify-between w-full gap-3 mb-6">
                <div class="flex items-center gap-4 min-w-0">
                    @if ($urlImageLogo)
                        <img class="h-12 md:h-14 w-auto drop-shadow-lg flex-shrink-0" src="{{ $urlImageLogo }}" alt="">
                    @else
                        <img class="h-12 md:h-14 w-auto drop-shadow-lg flex-shrink-0" src="{{ appLogo(true) }}" alt="{{ appName() }}">
                    @endif
                </div>
                <div class="flex-shrink-0">
                    <span class="inline-block px-5 py-2 text-xs font-semibold uppercase tracking-wider rounded-full shadow-lg"
                          style="background-color: {{ $colorDefault }};color: {{ $colorInverse }};">{{ __($order->status ?? 'PEDIDO') }}</span>
                </div>
            </div>

            {{-- Localizacao --}}
            @php
                $heroCidade = collect([$target->city ?? null, $target->state ?? null])->filter()->implode(', ');
            @endphp
            @if ($heroCidade)
                <div class="inline-flex items-center gap-1.5 mb-3 px-3 py-1 rounded-full text-xs font-medium uppercase tracking-widest"
                     style="background: {{ $colorPrimary }}33; color: {{ $colorInverse }}; border: 1px solid {{ $colorPrimary }}55;">
                    <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $heroCidade }}
                </div>
            @endif

            {{-- Nome do evento --}}
            <h1 class="text-2xl md:text-4xl text-white font-extrabold uppercase leading-tight" style="letter-spacing:-0.02em;">{{ $target->event_name ?? '--' }}</h1>

            @if ($target->event_description ?? false)
                <p class="text-base md:text-lg mt-1 font-medium uppercase tracking-wide leading-relaxed" style="color:rgba(255,255,255,0.6);">{{ $target->event_description }}</p>
            @endif

            {{-- ═══ BANNER DO PLANO DE PATROCÍNIO ═══ --}}
            <style>.pln-banner-row{display:flex;align-items:center;justify-content:space-between;gap:0.75rem;}@media(max-width:639px){.pln-banner-row{flex-direction:column;align-items:flex-start;gap:0.25rem;}}</style>
            <div style="margin-top:1.5rem;display:block;width:100%;background:rgba(255,255,255,0.12);border:2px solid rgba(255,255,255,0.28);border-radius:1rem;padding:1rem 1.5rem;">
                <div style="color:rgba(255,255,255,0.55);font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.18em;margin-bottom:0.3rem;">PLANO DE PATROCÍNIO</div>
                <div class="pln-banner-row">
                    <div>
                        <div style="color:#ffffff;font-size:clamp(1.3rem,3.5vw,2rem);font-weight:900;text-transform:uppercase;letter-spacing:0.01em;line-height:1.1;">
                            {{ $order->plano->name ?? $order->order_description ?? '--' }}
                        </div>
                        @if ($order->plano->description ?? false)
                            <div style="color:rgba(255,255,255,0.5);font-size:0.75rem;font-weight:400;text-transform:uppercase;letter-spacing:0.08em;margin-top:0.2rem;">{{ $order->plano->description }}</div>
                        @endif
                    </div>
                    <div style="flex-shrink:0;">
                        <span style="color:rgba(255,255,255,0.9);font-size:clamp(1rem,2.5vw,1.4rem);font-weight:700;">{{ toMoney($order->order_amount ?? 0, 'R$ ') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════
        CARD PRINCIPAL
    ════════════════════════════════════════ --}}
    <div class="w-full max-w-4xl mx-auto px-4 md:px-10 relative z-20" style="margin-top:-3.5rem;">

        <div class="w-full rounded-2xl bg-white shadow-xl overflow-hidden" style="border: 1px solid {{ $colorPrimary }}18;">

            {{-- Card Header: PATROCINIO + Logo patrocinador --}}
            <div class="px-5 md:px-8 py-4 md:py-5 flex items-center justify-between gap-4" style="background: {{ $colorPrimary }}08; border-bottom: 1px solid {{ $colorPrimary }}15;">
                <div>
                    <div class="uppercase text-xs tracking-widest font-light text-gray-400">PATROCINIO</div>
                    <div class="uppercase text-xl md:text-2xl font-bold text-gray-800 -mt-0.5">PAGAMENTO</div>
                </div>
                @if ($sponsorLogo)
                    <img src="{{ $sponsorLogo }}" class="h-14 w-auto object-contain rounded border shadow-sm flex-shrink-0" alt="Logo patrocinador" />
                @endif
            </div>

            {{-- Dados do Plano + Patrocinador --}}
            <div class="px-5 md:px-8 py-5">

                {{-- Plano --}}
                <div class="mb-4">
                    {!! setLabel('PLANO', $order->order_description ?? null) !!}
                </div>

                <div class="my-3 border-t" style="border-color: {{ $colorPrimary }}15;"></div>

                {{-- Dados do patrocinador --}}
                <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-x-4 gap-y-1">

                    <div class="col-span-full md:col-span-7">
                        {!! setLabel('PATROCINADOR', mb_strtoupper($order->buyer_name ?? '--')) !!}
                    </div>

                    <div class="col-span-full md:col-span-5">
                        {!! setLabel($order->buyer_doc_type ?? 'DOC', putMask($order->buyer_doc_num, $order->buyer_doc_type)) !!}
                    </div>

                    <div class="col-span-full md:col-span-7">
                        {!! setLabel('buyer_email', $order->buyer_email, true, false) !!}
                    </div>

                    <div class="col-span-full md:col-span-5">
                        {!! setLabel('buyer_contact', ($order->buyer_contact_ddd ?? '') . ' ' . ($order->buyer_contact_num ?? '')) !!}
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ═══════════════════════════════════════
        STATUS: PAGO
    ════════════════════════════════════════ --}}
    @if (in_array($order->status, listOrderStatusPaid()))

        <div class="w-full max-w-4xl mx-auto px-4 md:px-10 mt-6">

            <div class="w-full rounded-2xl bg-white shadow-md overflow-hidden" style="border: 1px solid {{ $colorPrimary }}18;">

                <div class="px-5 md:px-8 py-4" style="background: {{ $colorPrimary }}08; border-bottom: 1px solid {{ $colorPrimary }}15;">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-lg md:text-xl font-bold text-green-600 uppercase">PATROCINIO CONFIRMADO</span>
                    </div>
                </div>

                <div class="px-5 md:px-8 py-5">

                    <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3 mb-4" style="background: {{ $colorPrimary }}06; border: 1px solid {{ $colorPrimary }}15;">
                        <div class="text-sm md:text-lg uppercase text-left font-semibold text-gray-700">VALOR INVESTIDO</div>
                        <div class="text-base md:text-xl uppercase text-right font-bold text-gray-800 whitespace-nowrap">{{ toMoney($order->order_amount ?? 0, 'R$ ') }}</div>
                    </div>

                    @php $paidPayments = $order->payments->whereIn('status', listPaymentStatusPaid()); @endphp

                    @if ($paidPayments->count())
                        <div class="mt-4 pt-4" style="border-top: 1px solid {{ $colorPrimary }}15;">
                            <div class="text-sm tracking-widest font-semibold uppercase text-gray-700 mb-3">
                                {{ $paidPayments->count() > 1 ? 'PAGAMENTOS' : 'PAGAMENTO' }}
                            </div>
                            <div class="flex flex-col gap-2">
                                @foreach ($paidPayments as $paidItem)
                                    <div class="rounded-xl px-4 py-3" style="border: 1px solid #e2e8f0;">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-3">
                                                <div class="text-sm font-semibold uppercase text-gray-700">{{ __($paidItem->pay_type ?? '---') }}</div>
                                                @if ($paidItem->pay_datetime ?? false)
                                                    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($paidItem->pay_datetime)->format('d/m/Y') }}</div>
                                                @endif
                                                <div class="text-xs font-mono text-gray-400">{{ $paidItem->pay_nsu ?? '' }}</div>
                                            </div>
                                            <div class="text-base font-bold text-green-600">{{ $paidItem->paid_label ?? toMoney($paidItem->value_paid ?? 0, 'R$ ') }}</div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1 uppercase">{{ __($paidItem->status ?? '') }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    {{-- ═══════════════════════════════════════
        STATUS: CANCELADO
    ════════════════════════════════════════ --}}
    @elseif (in_array($order->status, listOrderStatusCancelada()))

        <div class="w-full max-w-4xl mx-auto px-4 md:px-10 mt-6">
            <div class="w-full rounded-2xl bg-white shadow-md overflow-hidden" style="border: 1px solid {{ $colorPrimary }}18;">
                <div class="px-5 md:px-8 py-5">
                    <div class="w-full text-center mb-3 text-red-700 bg-red-50 border border-red-200 p-4 rounded-xl">
                        @if ($order->order_cancel_description ?? false)
                            <div class="font-semibold uppercase text-sm">{{ $order->order_cancel_description }}</div>
                        @else
                            <div class="font-semibold uppercase">Pedido cancelado</div>
                        @endif
                        @if ($order->order_cancel_datetime ?? false)
                            <div class="text-xs mt-1 opacity-75">Cancelado em {{ $order->order_cancel_datetime->format('d/m/Y H:i') }}</div>
                        @endif
                    </div>
                    @if ($order->channel_order ?? false)
                        <div class="w-full text-center mt-3">
                            <a href="{{ $order->channel_order }}" class="text-blue-600 hover:underline font-medium text-sm transition-colors">
                                Realizar nova adesao
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    {{-- ═══════════════════════════════════════
        STATUS: AGUARDANDO PAGAMENTO
    ════════════════════════════════════════ --}}
    @elseif (!in_array($order->status, listOrderStatusNaoAbrePagamento()) || in_array($order->status, ['pending_pix', 'pending_boleto', 'pending_slip_pix']))

        <div id="formasPagamento" class="w-full max-w-4xl mx-auto px-4 md:px-10 mt-6">

            <div class="w-full rounded-2xl bg-white shadow-md overflow-hidden" style="border: 1px solid {{ $colorPrimary }}18;">

                {{-- Header --}}
                <div class="px-5 md:px-8 py-4" style="background: {{ $colorPrimary }}08; border-bottom: 1px solid {{ $colorPrimary }}15;">
                    <div class="uppercase text-sm tracking-widest font-semibold text-gray-700">PAGAMENTO</div>
                </div>

                <div class="px-5 md:px-8 py-5">

                    {{-- Valor do investimento --}}
                    <div class="flex flex-col gap-2 mb-6">
                        <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3 bg-gray-50" style="border: 1px solid #e2e8f0;">
                            <div class="text-xs uppercase tracking-widest font-semibold text-gray-500">VALOR DO INVESTIMENTO</div>
                            <div class="text-lg md:text-2xl uppercase text-right font-bold text-gray-900 whitespace-nowrap">{{ toMoney($this->order_amount ?? 0, 'R$ ') }}</div>
                        </div>
                    </div>

                    {{-- Formas de pagamento disponiveis --}}
                    @if ($this->forma_pagamento_disponivel ?? false)

                        <div class="w-full text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">ESCOLHA UMA FORMA DE PAGAMENTO</div>

                        <div
                            x-data="{ openTab: '{{ $this->pay_type ?? '' }}' }"
                            class="flex flex-col gap-2"
                            id="formaPagamentoSelecionada"
                        >
                            @foreach ($this->forma_pagamento_disponivel as $formaPagamentoItem)
                                @php
                                    $slug = $formaPagamentoItem['slug'];
                                    $payConfig = match($slug) {
                                        'boleto'      => ['label' => 'BOLETO',            'icon' => asset('images/icones/logo-boleto.png')],
                                        'card_credit' => ['label' => 'CARTAO DE CREDITO', 'icon' => asset('images/icones/logo-credit.png')],
                                        'pix'         => ['label' => 'PIX',               'icon' => asset('images/icones/logo-pix.png')],
                                        default       => null,
                                    };
                                @endphp

                                @if($payConfig !== null)
                                    <div
                                        class="rounded-xl overflow-hidden transition-all duration-200"
                                        :style="openTab === '{{ $slug }}'
                                            ? 'border: 1px solid {{ $colorPrimary }}; box-shadow: 0 2px 8px rgba(0,0,0,0.08);'
                                            : 'border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,0.04);'"
                                    >
                                        {{-- Cabecalho da forma --}}
                                        <button
                                            type="button"
                                            wire:click="$set('pay_type','{{ $slug }}')"
                                            x-on:click="openTab = openTab === '{{ $slug }}' ? '' : '{{ $slug }}'"
                                            class="w-full flex items-center justify-between gap-3 px-4 py-3 md:py-4 focus:outline-none transition-colors duration-200"
                                            :style="openTab === '{{ $slug }}'
                                                ? 'background: {{ $colorPrimary }}0d; border-left: 4px solid {{ $colorPrimary }};'
                                                : 'background: white; border-left: 4px solid transparent;'"
                                        >
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $payConfig['icon'] }}" alt="{{ $payConfig['label'] }}" class="h-6 md:h-8 w-auto object-contain flex-shrink-0">
                                                <div class="text-left leading-tight">
                                                    <div
                                                        class="text-sm md:text-base font-bold uppercase tracking-wide transition-colors duration-200"
                                                        :style="openTab === '{{ $slug }}' ? 'color: {{ $colorPrimary }};' : 'color: #1f2937;'"
                                                    >{{ $payConfig['label'] }}</div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <svg x-show="openTab === '{{ $slug }}'" class="w-4 h-4" :style="'color: {{ $colorPrimary }};'" fill="currentColor" viewBox="0 0 20 20" style="display:none;">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <svg x-show="openTab !== '{{ $slug }}'" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </div>
                                        </button>

                                        {{-- Corpo da forma de pagamento --}}
                                        <div
                                            x-show="openTab === '{{ $slug }}'"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0"
                                            x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100"
                                            x-transition:leave-end="opacity-0"
                                            style="display: none; border-top: 1px solid #e2e8f0;"
                                        >
                                            <div class="p-4 md:p-6 bg-white">
                                                @switch($slug)
                                                    @case('card_credit')
                                                        @if($this->pay_type === 'card_credit')
                                                            @include('livewire.pagamento._includes.pay_type_card_credit')
                                                        @endif
                                                        @break
                                                    @case('boleto')
                                                        @if($this->pay_type === 'boleto')
                                                            @include('livewire.pagamento._includes.pay_type_boleto')
                                                        @endif
                                                        @break
                                                    @case('pix')
                                                        @if($this->pay_type === 'pix')
                                                            @php
                                                                $currentPayment = (isset($payment) && in_array($payment->status ?? '', ['pending_pix'])) ? $payment : null;
                                                                $pixValido      = false;
                                                                $isLegado       = (bool)$currentPayment;
                                                            @endphp
                                                            {{-- Polling: valida pagamento a cada 10s enquanto PIX estiver pendente --}}
                                                            @if ($currentPayment && !in_array($currentPayment->status ?? 'pending', ['paid', 'approved', 'autorizado', 'captured', 'pix_expired']))
                                                                <div wire:poll.10s="validarPagamento(false)" class="hidden"></div>
                                                                <div wire:loading wire:target="validarPagamento" class="flex items-center justify-center gap-2 text-xs text-gray-400 py-1">
                                                                    <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                                                    </svg>
                                                                    <span>Verificando pagamento...</span>
                                                                </div>
                                                            @endif
                                                            @include('livewire.pagamento._includes.pay_type_pix')
                                                        @endif
                                                        @break
                                                @endswitch
                                            </div>
                                        </div>

                                    </div>
                                @endif
                            @endforeach

                        </div>

                    @else
                        <div class="w-full text-center font-bold text-red-600 border border-red-300 bg-red-50 p-4 rounded-xl">
                            FORMAS DE PAGAMENTO INDISPONIVEIS
                        </div>
                    @endif

                    {{-- Tracking discreto --}}
                    <div class="mt-6 pt-4" style="border-top: 1px solid {{ $colorPrimary }}10;">
                        <div class="text-center text-xs text-gray-400 space-y-1">
                            <div class="flex items-center justify-center gap-2">
                                <span>Localizador: <span class="font-mono font-semibold text-gray-500">{{ $order->order_control ?? 'N/A' }}</span></span>
                            </div>
                            <div class="text-[10px] text-gray-400"><span class="font-mono">{{ $order->id ?? 'N/A' }}</span></div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    {{-- ═══════════════════════════════════════
        STATUS: OUTROS (nao abre pagamento)
    ════════════════════════════════════════ --}}
    @else

        <div class="w-full max-w-4xl mx-auto px-4 md:px-10 mt-6">
            <div class="w-full rounded-2xl bg-white shadow-md overflow-hidden" style="border: 1px solid {{ $colorPrimary }}18;">
                <div class="px-5 md:px-8 py-5 text-center">
                    <div class="uppercase font-semibold text-gray-600 mb-2">{{ __($order->status ?? '') }}</div>
                    @if ($order->channel_order ?? false)
                        <a href="{{ $order->channel_order }}" class="text-blue-600 hover:underline text-sm">Realizar nova adesao</a>
                    @endif
                </div>
            </div>
        </div>

    @endif

    {{-- Espaco inferior --}}
    <div class="pb-10"></div>

    {{-- ═════════════ SCRIPTS ═════════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
    <script>
        function copyToClipboard(id, msg) {
            var Clipboard = new ClipboardJS('#' + id);
            if (msg) { alert(msg); }
        }
        function scrolToFormasPagamentoSelecionada() {
            var el = document.getElementById('formaPagamentoSelecionada');
            if (el) el.scrollIntoView({ behavior: 'smooth' });
        }
        @if ($this->pay_type)
            scrolToFormasPagamentoSelecionada();
        @endif
    </script>

</div>
