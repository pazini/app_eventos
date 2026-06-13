<div>

    {{-- LIVEWIRE - LOADER (não dispara no poll automático) --}}
    <div wire:loading.class.remove="hidden"
         wire:target="processarPagamento,paymentReset"
         class="hidden fixed inset-0 z-[999] flex items-center justify-center" style="background: rgba(255,255,255,0.80); backdrop-filter: blur(6px);">
        <div class="flex flex-col items-center gap-4">
            <img src="{{ asset('/assets/loader.v2.svg') }}" alt="" class="w-16 h-16">
            <span class="text-gray-500 text-sm uppercase tracking-widest font-light">Aguarde…</span>
        </div>
    </div>
    {{-- LIVEWIRE - LOADER FIM --}}

    @include('_includes.alertas_modal')

    @if ($order ?? false)

        @php
            $colorPrimary   = $order->event->color_primary   ?? $order->event->color_default ?? '#6366f1';
            $colorSecondary = $order->event->color_secondary  ?? $order->event->color_default ?? '#8b5cf6';
            $colorDefault   = $order->event->color_default    ?? '#6366f1';
            $colorInverse   = $order->event->color_default_inverse ?? '#ffffff';
        @endphp

        <div id="formasPagamento" class="w-full max-w-4xl mx-auto px-4 md:px-10">

            <div class="w-full rounded-2xl bg-white shadow-md overflow-hidden" style="border: 1px solid {{ $colorPrimary }}18;">

                {{-- Header --}}
                <div class="px-5 md:px-8 py-4" style="background: {{ $colorPrimary }}08; border-bottom: 1px solid {{ $colorPrimary }}15;">
                    <div class="uppercase text-sm tracking-widest font-semibold text-gray-700">PAGAMENTO</div>
                </div>

                <div class="px-5 md:px-8 py-5">

                <div class="flex flex-col gap-2 mb-4">

                    <div class="col-span-full flex flex-col gap-2">

                        <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3 bg-gray-50" style="border: 1px solid #e2e8f0;">
                            <div class="text-xs uppercase tracking-widest font-semibold text-gray-500">VALOR</div>
                            <div class="text-lg md:text-2xl uppercase text-right font-bold text-gray-900 whitespace-nowrap">{{ toMoney($this->order_amount_payment ?? 0, 'R$ ') }}</div>
                        </div>

                    </div>

                    {{-- SE CUPOM DESCONTO JÁ APLICADO --}}
                    @if ($order->code_promo_id ?? false)

                        <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3 bg-red-50 border border-red-200">
                            <div>
                                <div class="text-sm md:text-lg uppercase text-left font-semibold text-red-600">{{ $order->code_promo_label ?? 'CUPOM APLICADO' }}</div>
                            </div>
                            <div>
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
                        </div>

                        @if ($order->code_promo_price_new ?? false)

                            <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3 bg-gray-50" style="border: 1px solid #e2e8f0;">
                                <div>
                                    <div class="text-xs uppercase tracking-widest font-semibold text-gray-500">TOTAL PARA PAGAMENTO</div>
                                </div>
                                <div>
                                    <div class="text-lg md:text-2xl uppercase text-right font-bold text-gray-900 whitespace-nowrap">
                                        <span>{{ toMoney($order->code_promo_price_new,'R$ ') }}</span>
                                    </div>
                                </div>
                            </div>

                        @endif

                    @endif

                    {{-- SE PAGAMENTO PARCIAL --}}
                    @php
                        $valueOrderAmount  = (($order->code_promo_id ?? false) && ($order->code_promo_price_new)) ? ($order->code_promo_price_new ?? 0) : ($order->order_amount ?? 0);
                        $valueOrderPaid    = $order->order_amount_pay ?? 0;
                        $valueOrderPending = $valueOrderAmount - $valueOrderPaid;
                    @endphp
                    @if(($order->order_amount_pay > 0) && ($valueOrderPending > 0))

                        <div x-data="{ open: false }" class="rounded-xl px-4 md:px-5 py-3 bg-green-50 border border-green-200">
                            <div class="w-full flex justify-between items-center gap-2">
                                <div class="text-sm md:text-lg uppercase text-left font-semibold text-green-700">PAGAMENTOS REALIZADOS <button @click="open = !open" class="text-xs text-blue-500 font-light hover:underline ml-1">Exibir</button></div>
                                <div class="text-base md:text-xl uppercase text-right font-bold text-green-700 whitespace-nowrap">- {{ toMoney($valueOrderPaid ?? 0, 'R$ ') }}</div>
                            </div>
                            <div x-show="open" x-transition.duration.500ms.opacity.scale class="mt-2">

                                {{-- @forelse ($order->payments->whereIn('status',listPaymentStatusPaid()) ?? [] as $orderPaymentKey => $orderPaymentItem) --}}
                                @forelse ($order->payments ?? [] as $orderPaymentKey => $orderPaymentItem)
                                    <div class="mt-1 py-1.5 px-4 bg-white rounded-lg border border-gray-100 shadow-sm" title="{{$orderPaymentKey + 1}} // {{$orderPaymentItem->id}}">
                                        <div class="w-full flex justify-between items-center">
                                            <div class="flex-none md:flex items-center gap-2 text-sm">
                                                <div class="uppercase font-light text-gray-500">{{dataData($orderPaymentItem->pay_datetime)}}</div>
                                                <div class="uppercase font-medium text-gray-700">{{__($orderPaymentItem->pay_type ?? '---')}}</div>
                                                <div class="uppercase text-gray-400 text-xs">{{$orderPaymentItem->status ?? null}}</div>
                                                <div class="uppercase text-gray-400 text-xs font-mono">{{$orderPaymentItem->pay_nsu ?? '---'}}</div>
                                            </div>
                                            <div class="text-sm font-semibold text-gray-800">
                                                @if ($orderPaymentItem->pay_value_paid < $orderPaymentItem->value_liquid)
                                                    {{toMoney($orderPaymentItem->pay_value_paid ?? 0,'R$ ')}}
                                                @else
                                                    {{toMoney($orderPaymentItem->value_liquid ?? 0,'R$ ')}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                <div class="mt-1 py-1.5 px-4 bg-white rounded-lg border border-gray-100 shadow-sm text-sm text-gray-500">PAGAMENTOS NÃO LOCALIZADOS</div>
                                @endforelse
                            </div>
                        </div>
                        <div class="col-span-full flex flex-col gap-2">
                            <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3 bg-gray-50" style="border: 1px solid #e2e8f0;">
                                <div class="text-xs uppercase tracking-widest font-semibold text-gray-500">PENDENTE</div>
                                <div class="text-lg md:text-2xl uppercase text-right font-bold text-gray-900 whitespace-nowrap">{{ toMoney($valueOrderPending ?? 0, 'R$ ') }}</div>
                            </div>
                        </div>

                    {{-- SE NAO POSSUI CIUPOM APLICADO --}}
                    @elseif(!$order->code_promo_id ?? false)

                        {{-- FRAME CUPOM DESCONTO --}}
                        <div class="w-full grid grid-cols-1 md:grid-cols-12 mt-4 md:mt-6 px-5 py-4 rounded-xl bg-gray-50" style="border: 1px solid #e2e8f0;">

                            <div class="col-span-full md:col-span-6">
                                <div class="uppercase text-sm md:text-base font-semibold text-gray-700">POSSUI UM CUPOM?</div>
                                <div class="uppercase text-xs font-light -mt-0.5 mb-2 text-gray-400">Informe aqui e clique em aplicar</div>
                            </div>

                            <div class="col-span-full md:col-span-6">

                                @if ($code_promo_label ?? false)
                                    <x-input wire:model.defer="ticket_code_promo" class="w-full bg-gray-100 cursor-not-allowed uppercase" readonly>
                                        <x-slot name="append">
                                            <div class="absolute inset-y-0 right-0 flex items-center p-0.5">
                                                <x-button wire:click="removeCupom" class="h-full shadow uppercase" label="Remover" negative squared />
                                            </div>
                                        </x-slot>
                                    </x-input>
                                @else
                                    <x-input placeholder="CUPOM" wire:model.defer="ticket_code_promo" class="w-full py-1 md:py-2 px-2 text-sm md:text-base uppercase">
                                        <x-slot name="append">
                                            <div class="absolute inset-y-0 right-0 flex items-center p-0.5">
                                                <x-button wire:click="aplicarCupom" class="h-full uppercase text-sm md:text-base p-1" label="APLICAR" primary squared />
                                            </div>
                                        </x-slot>
                                    </x-input>
                                @endif

                            </div>

                            @if (session('ticket_code_promo_erro'))
                                <div class="col-span-full mt-3 py-1.5 text-center bg-red-50 text-red-700 border border-red-200 rounded-lg">
                                    <span class="text-sm font-bold uppercase">{{ __(session('ticket_code_promo_erro')) }}</span>
                                </div>
                            @endif

                            @if (session('ticket_code_promo_sucesso'))
                                <div class="col-span-full mt-3 py-1.5 text-center bg-green-50 text-green-700 border border-green-200 rounded-lg">
                                    <span class="text-sm font-bold uppercase">{{ __(session('ticket_code_promo_sucesso')) }}</span>
                                </div>
                            @endif

                            {{-- SE DESCONTO --}}
                            @if ($this->code_promo_discount_amount ?? false)
                                <div class="col-span-full mt-3 flex justify-between items-center px-4 py-2 bg-white rounded-lg border border-gray-100 text-gray-600">
                                    <div class="uppercase text-sm font-semibold flex items-center gap-2">
                                        <div>DESCONTO</div>
                                        @if ($code_promo_label ?? false)
                                            <div class="font-normal text-green-700 uppercase">{{ __($this->code_promo_label) }}</div>
                                        @endif
                                    </div>
                                    <div class="font-bold text-lg">{{ toMoney($this->code_promo_discount_amount ?? 0 ,'- ') }}</div>
                                </div>
                            @endif

                        </div>

                        <div class="text-gray-400 text-xs font-normal mx-2 mt-2 mb-4">* Se você não possuir nenhum cupom de desconto, simplesmente deixe em branco.</div>

                    @endif

                </div>

                {{-- SE AINDA NÃO POSSUI PAGAMENTO CADASTRADO --}}
                @if ((!$payment ?? false) || (($payment ?? false) && !in_array($payment->status,listPaymentStatusPaidCanceled()) ))

                    <div class="w-full mt-4 pt-4" style="border-top: 1px solid #e2e8f0;">

                        @include('_includes.alertas_forma_pagamento')

                        @if ($formaPagamentoDisponivel ?? false)

                            <div class="w-full text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3 mt-2">ESCOLHA UMA FORMA DE PAGAMENTO</div>

                            {{-- ACCORDION DE FORMAS DE PAGAMENTO --}}
                            <div
                                x-data="{ openTab: '{{ $this->payType ?? '' }}' }"
                                class="flex flex-col gap-2"
                                id="formaPagamentoSelecionada"
                            >
                                @foreach ($this->formaPagamentoDisponivel as $formaPagamentoItem)
                                    @php
                                        $slug = $formaPagamentoItem['slug'];
                                        $payConfig = match($slug) {
                                            'boleto'      => ['label' => 'BOLETO',            'sub' => null,        'icon' => asset('images/icones/logo-boleto.png')],
                                            'card_credit' => ['label' => 'CARTÃO DE CRÉDITO', 'sub' => null,        'icon' => asset('images/icones/logo-credit.png')],
                                            'pix'         => ['label' => 'PIX',               'sub' => null,        'icon' => asset('images/icones/logo-pix.png')],
                                            'slip_pix'    => ['label' => 'CARNÊ PIX',          'sub' => 'COM JUROS', 'icon' => asset('images/icones/logo-slip-pix.png')],
                                            'slip_boleto' => ['label' => 'CARNÊ BOLETO',       'sub' => 'COM JUROS', 'icon' => asset('images/icones/logo-slip-pix.png')],
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

                                            {{-- Cabeçalho --}}
                                            <button
                                                type="button"
                                                wire:click="$set('payType','{{ $slug }}')"
                                                x-on:click="openTab = openTab === '{{ $slug }}' ? '' : '{{ $slug }}'"
                                                class="w-full flex items-center justify-between gap-3 px-4 py-3 md:py-4 focus:outline-none transition-colors duration-200"
                                                :style="openTab === '{{ $slug }}'
                                                    ? 'background: {{ $colorPrimary }}0d; border-left: 4px solid {{ $colorPrimary }};'
                                                    : 'background: white; border-left: 4px solid transparent;'"
                                            >
                                                <div class="flex items-center gap-3">
                                                    <img
                                                        src="{{ $payConfig['icon'] }}"
                                                        alt="{{ $payConfig['label'] }}"
                                                        class="h-6 md:h-8 w-auto object-contain flex-shrink-0"
                                                    >
                                                    <div class="text-left leading-tight">
                                                        <div
                                                            class="text-sm md:text-base font-bold uppercase tracking-wide transition-colors duration-200"
                                                            :style="openTab === '{{ $slug }}' ? 'color: {{ $colorPrimary }};' : 'color: #1f2937;'"
                                                        >{{ $payConfig['label'] }}</div>
                                                        @if($payConfig['sub'])
                                                            <div class="text-xs font-light text-gray-400 mt-0.5">{{ $payConfig['sub'] }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2 flex-shrink-0">
                                                    {{-- Check quando selecionado --}}
                                                    <svg
                                                        x-show="openTab === '{{ $slug }}'"
                                                        class="w-4 h-4"
                                                        :style="'color: {{ $colorPrimary }};'"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        style="display:none;"
                                                    >
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{-- Chevron quando fechado --}}
                                                    <svg
                                                        x-show="openTab !== '{{ $slug }}'"
                                                        class="w-4 h-4 text-gray-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    >
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </div>
                                            </button>

                                            {{-- Corpo --}}
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
                                                            @if($this->payType === 'card_credit')
                                                                @include('livewire.pagamento._includes.pay_type_card_credit')
                                                            @endif
                                                            @break
                                                        @case('boleto')
                                                            @if($this->payType === 'boleto')
                                                                @include('livewire.pagamento._includes.pay_type_boleto')
                                                            @endif
                                                            @break
                                                        @case('pix')
                                                            @if($this->payType === 'pix')
                                                                {{-- Poll automático a cada 10s enquanto PIX pendente --}}
                                                                @if ($this->pixValido ?? false)
                                                                    <div wire:poll.10s="paymentCheckProcessed" class="hidden"></div>
                                                                    <div wire:loading wire:target="paymentCheckProcessed" class="flex items-center justify-center gap-1 text-xs text-gray-400 pb-2">
                                                                        <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                                                        <span>Verificando pagamento...</span>
                                                                    </div>
                                                                @endif
                                                                @include('livewire.pagamento._includes.pay_type_pix')
                                                            @endif
                                                            @break
                                                        @case('slip_pix')
                                                            @if($this->payType === 'slip_pix')
                                                                @include('livewire.pagamento._includes.pay_type_slip_pix')
                                                            @endif
                                                            @break
                                                        @default
                                                    @endswitch
                                                </div>
                                            </div>

                                        </div>
                                    @endif
                                @endforeach

                            </div>

                        @else
                            <div class="w-full text-center font-bold text-red-600 border border-red-300 bg-red-50 p-4 rounded-xl">FORMAS DE PAGAMENTO INDISPONÍVEIS</div>
                        @endif

                    </div>

                @endif

                @if($order->buyer_email == "proeventpay@gmail.com")
                    <x-button warning label="DEBUG - VALIDAR ORDER" wire:click="validaOrder('{{$order->id}}',false)" class="w-full mt-4" />
                @endif

                {{-- Tracking da Order (Footer Discreto) --}}
                <div class="mt-6 pt-4" style="border-top: 1px solid {{ $colorPrimary }}10;">
                    <div class="text-center text-xs text-gray-400 space-y-1">
                        <div class="flex items-center justify-center gap-2">
                            <span>Localizador: <span class="font-mono font-semibold text-gray-500">{{ $order->order_control ?? 'N/A' }}</span></span>
                        </div>
                        <div class="text-[10px] text-gray-400">
                            <span class="font-mono">{{ $order->id ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

            </div>
            </div>

        </div>

    @elseif($orderControl ?? false)

        <div class="w-full max-w-4xl mx-auto px-4 md:px-10 text-center">
            <x-button lg orange icon="refresh" href="{{ route('compra-exibir', ['localizador' => $orderControl]) }}" label="Clique aqui para atualizar" class="w-full uppercase font-bold rounded-xl" />
        </div>

    @else

        <div class="w-full max-w-4xl mx-auto px-4 md:px-10">
            <div class="border border-red-300 bg-red-50 p-4 text-center rounded-xl">
                <span class="text-red-600 font-semibold">PEDIDO NÃO LOCALIZADO</span>
            </div>
        </div>

    @endif

    {{-- SCRIPS --}}
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
    <script>
        function scrollToFormasPagamento() {
            document.getElementById('formasPagamento').scrollIntoView({
                behavior: 'smooth'
            });
        }
        function scrolToFormasPagamentoSelecionada() {
            document.getElementById('formaPagamentoSelecionada').scrollIntoView({
                behavior: 'smooth'
            });
        }
        function copyToClipboard(id,msg=false)
        {
            var Clipboard = new ClipboardJS('#' + id);
            if(msg) {alert(msg)}
        }

        if ('{{$payType}}' !== false)
        {
            // ROLA TELA ATE FRAME PAGAMENTO
            scrolToFormasPagamentoSelecionada();
        }
        else
        {
            // ROLA TELA ATE FRAME PAGAMENTO
            scrollToFormasPagamento();
        }

    </script>

</div>

