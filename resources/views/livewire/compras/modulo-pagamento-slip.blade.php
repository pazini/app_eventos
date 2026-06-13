<div>

    @php
        $colorPrimary   = $order->event->color_primary   ?? $order->event->color_default ?? '#6366f1';
        $colorSecondary = $order->event->color_secondary  ?? $order->event->color_default ?? '#8b5cf6';
        $colorDefault   = $order->event->color_default    ?? '#6366f1';
        $colorInverse   = $order->event->color_default_inverse ?? '#ffffff';
    @endphp

    {{-- LIVEWIRE - LOADER --}}
    <div wire:loading.class.remove="hidden" class="hidden fixed inset-0 z-[999] flex items-center justify-center" style="background: rgba(255,255,255,0.80); backdrop-filter: blur(6px);">
        <div class="flex flex-col items-center gap-4">
            <img src="{{ asset('/assets/loader.v2.svg') }}" alt="" class="w-16 h-16">
            <span class="text-gray-500 text-sm uppercase tracking-widest font-light">Aguarde…</span>
        </div>
    </div>
    {{-- LIVEWIRE - LOADER FIM --}}

    @include('_includes.alertas_modal')

    @if ($order ?? false)

        <div id="formasPagamento" class="w-full max-w-4xl mx-auto px-4 md:px-10 mb-6">

            @if ($order->paymentsSlip->count())

                <div class="w-full rounded-2xl bg-white shadow-md overflow-hidden" style="border: 1px solid {{ $colorPrimary }}18;">

                    @if ($slipPayment ?? false)

                        {{-- PAGAMENTO SELECIONADO header --}}
                        <div class="px-5 md:px-8 py-4" style="background: {{ $colorPrimary }}08; border-bottom: 1px solid {{ $colorPrimary }}15;">
                            <div class="uppercase text-xs tracking-widest font-light text-gray-400">REALIZAR</div>
                            <div class="text-xl md:text-2xl font-bold text-gray-800 -mt-0.5 uppercase">PAGAMENTO</div>
                        </div>

                        {{-- DIV ONDE O SCROLL VAI PARAR --}}
                        <div wire:key="slip-{{$slipPayment->id}}" class="px-5 md:px-8 py-5">

                            <div class="w-full rounded-xl px-4 md:px-5 py-4 mb-4" style="background: {{ $colorPrimary }}06; border: 1px solid {{ $colorPrimary }}15;">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                                    <div>
                                        <div class="uppercase text-lg md:text-xl font-semibold text-gray-800">{{$slipPayment->installment_description ?? ('# PARCELA ' . ($slipKey + 1))}}</div>
                                        <div class="flex items-center gap-1.5 mt-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            @if ($slipPayment->paid_datetime ?? false)
                                                <div class="uppercase text-sm font-light text-gray-500">{{$slipPayment->installment_date_due ? dataData($slipPayment->installment_date_due,ago:true) : 'SEM DATA VENCIMENTO'}}</div>
                                            @else
                                                <div class="uppercase text-sm font-light text-gray-500">{{$slipPayment->installment_date_due ? dataData($slipPayment->installment_date_due,ago:true) : 'SEM DATA VENCIMENTO'}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0" title="{{$slipPayment->status}}">
                                        <div class="{{setClass('payment_' . $slipPayment->status)}} text-sm md:text-base font-semibold">{{__($slipPayment->status)}}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Valor da parcela --}}
                            <div class="w-full flex justify-between items-center gap-2 rounded-xl px-4 md:px-5 py-3 mb-4" style="background: {{ $colorPrimary }}06; border: 1px solid {{ $colorPrimary }}15;">
                                <div class="text-sm md:text-base uppercase font-semibold text-gray-700">VALOR PARCELA</div>
                                <div class="text-base md:text-xl uppercase font-bold text-gray-800 whitespace-nowrap">{{ toMoney($slipPayment->installment_value ?? 0,'R$ ') }}</div>
                            </div>

                            {{-- Forma de pagamento --}}
                            <div class="w-full rounded-xl bg-white border p-4 md:p-5 shadow-sm" style="border-color: {{ $colorPrimary }}20;">

                                @include('_includes.alertas_forma_pagamento')

                                {{-- PAGO --}}
                                @if (in_array($slipPayment->status,listPaymentStatusPaid()))
                                    <div class="flex items-center gap-2 text-green-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div class="font-medium uppercase text-sm">ESTE PAGAMENTO JÁ FOI REALIZADO EM</div>
                                        <div class="font-light text-sm">{{dataData($slipPayment->paid_datetime)}}</div>
                                    </div>
                                {{-- PIX --}}
                                @elseif (in_array(($slipPayment->installment_pay_type ?? false),['pix','slip_pix']))
                                    @include('livewire.pagamento._includes.pay_type_pix')
                                {{-- BOLETO --}}
                                @elseif (in_array(($slipPayment->installment_pay_type ?? false),['boleto']))
                                    @include('livewire.pagamento._includes.pay_type_boleto')
                                @else
                                    <div class="text-gray-500 text-sm uppercase">NÃO POSSUI MÉTODO PAGAMENTO ASSOCIADO</div>
                                @endif

                            </div>

                        </div>

                        {{-- Separador --}}
                        <div class="mx-5 md:mx-8" style="border-top: 1px solid {{ $colorPrimary }}15;"></div>

                    @endif

                    {{-- CARNÊ ONLINE header --}}
                    <div class="px-5 md:px-8 py-4" style="background: {{ $colorPrimary }}08; border-bottom: 1px solid {{ $colorPrimary }}15;">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span class="text-lg md:text-xl font-bold text-gray-800 uppercase">CARNÊ ONLINE</span>
                        </div>
                    </div>

                    <div class="px-5 md:px-8 py-5">

                        @if (!$slipPayment ?? false)
                            @include('_includes.alertas_forma_pagamento')
                        @endif

                        {{-- DEMAIS PARCELAS --}}
                        @php
                            $orderPaymentsSlip = $order->paymentsSlip->sortBy('slip_installment');
                        @endphp
                        <div class="flex flex-col gap-3">
                        @foreach ($orderPaymentsSlip ?? [] as $slipKey => $slipItem)
                            @php
                                //
                                $slipStatus       = null;
                                $slipStatusSufixo = null;
                                $classStatus      = null;
                                $classBorder      = null;
                                $classIcon        = null;
                                //
                                $slipStatus = $slipItem->status;
                                $diasAtraso = (now()->format('Ymd') - dataCarbon($slipItem->installment_date_due,'Ymd'));

                                //
                                if(in_array($slipItem->status,listPaymentStatusPaid()))
                                {
                                    $classStatus = 'bg-green-50';
                                    $classBorder = 'border-green-300';
                                    $classIcon   = 'text-green-500';
                                }
                                elseif(($slipItem->installment_date_due ?? false) && ($diasAtraso > 0))
                                {
                                    $slipStatus       = 'em_atraso';
                                    $slipStatusSufixo = $diasAtraso . ' dia(s)';
                                    $classStatus      = 'bg-red-50';
                                    $classBorder      = 'border-red-300';
                                    $classIcon        = 'text-red-500';
                                }
                                elseif(in_array($slipItem->status,['aguardando_pagamento']))
                                {
                                    $classStatus = 'bg-blue-50';
                                    $classBorder = 'border-blue-300';
                                    $classIcon   = 'text-blue-500';
                                }
                                else
                                {
                                    $classStatus = 'bg-gray-50';
                                    $classBorder = 'border-gray-200';
                                    $classIcon   = 'text-gray-400';
                                }
                            @endphp
                            <div x-data="{ open: false }" class="w-full rounded-xl border shadow-sm overflow-hidden transition-all duration-200 {{$classStatus}} {{$classBorder}}">
                                <button @click="open = !open" class="w-full px-4 md:px-5 py-3">
                                    <div class="w-full flex flex-col md:flex-row justify-between items-start md:items-center gap-1">
                                        <div class="text-left">
                                            <div class="uppercase text-sm md:text-base font-semibold text-gray-800">{{$slipItem->installment_description ?? 'SEM DESCRIÇÃO'}}</div>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <svg class="w-3.5 h-3.5 {{ $classIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                @if ($slipItem->paid_datetime ?? false)
                                                    <div class="uppercase text-xs font-light text-green-600">{{dataData($slipItem->paid_datetime,ago:true)}}</div>
                                                @else
                                                    <div class="uppercase text-xs font-light text-gray-500">{{$slipItem->installment_date_due ? dataData($slipItem->installment_date_due,ago:true) : 'SEM DATA VENCIMENTO'}}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2" title="{{$slipStatus}}">
                                            <div class="{{setClass('payment_' . $slipStatus)}} text-xs md:text-sm font-semibold">{{__($slipStatus)}} {{$slipStatusSufixo ?? null}}</div>
                                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </button>

                                <div x-show="open" x-transition.duration.500ms.opacity.scale class="px-4 md:px-5 pb-4">
                                    @if (in_array($slipItem->status,listPaymentStatusPaid()))
                                        @forelse ($slipItem->payments as $payment)
                                            @include('livewire.compras._includes.exibir-pagamentos')
                                        @empty
                                            <div class="text-sm text-gray-500 uppercase">NÃO ENCONTRAMOS PAGAMENTOS PARA ESSA MENSALIDADE</div>
                                        @endforelse
                                    @else
                                        <div class="flex flex-col gap-0 rounded-xl bg-white border shadow-sm overflow-hidden" style="border-color: {{ $colorPrimary }}20;">
                                            <div class="w-full px-4 py-3 uppercase flex justify-between items-center text-sm md:text-base" style="border-bottom: 1px solid {{ $colorPrimary }}10;">
                                                <div class="font-semibold text-gray-700">VALOR PARCELA</div>
                                                <div class="font-bold text-gray-800">{{ toMoney($slipItem->installment_value ?? 0,'R$ ') }}</div>
                                            </div>

                                            <div class="w-full px-4 py-3 uppercase flex justify-between items-center text-sm md:text-base" style="border-bottom: 1px solid {{ $colorPrimary }}10;">
                                                <div class="font-semibold text-gray-700">VENCIMENTO</div>
                                                <div class="font-bold text-gray-800">{{$slipItem->installment_date_due ? dataData($slipItem->installment_date_due) : 'SEM DATA VENCIMENTO'}}</div>
                                            </div>

                                            <div class="w-full px-4 py-3 uppercase flex justify-between items-center text-sm md:text-base">
                                                <div class="font-semibold text-gray-700">SITUAÇÃO</div>
                                                <div class="font-bold text-gray-800">{{__($slipItem->status)}}</div>
                                            </div>
                                        </div>
                                        <div class="w-full flex justify-center md:justify-end items-center gap-4 mt-4">
                                            @if (!in_array($slipItem->status,listPaymentStatusPaid()))
                                                <x-button blue rounded label="SELECIONAR PARA PAGAR" wire:click="selecionaSlipPayment('{{$slipItem->id}}')" class="font-semibold px-6 shadow-md rounded-xl" onclick="scrollToFormasPagamento()" />
                                            @elseif (($slipPayment ?? FALSE) && $slipItem->id != $slipPayment->id)
                                                <x-button blue rounded label="SELECIONAR PARA PAGAR" wire:click="selecionaSlipPayment('{{$slipItem->id}}')" class="font-semibold px-6 shadow-md rounded-xl" onclick="scrollToFormasPagamento()" />
                                            @endif
                                        </div>
                                    @endif
                                </div>

                            </div>

                        @endforeach
                        </div>

                    </div>

                </div>

            @else

                <div class="w-full rounded-xl bg-red-50 border border-red-200 p-4 text-center animate-bounce">
                    <span class="text-red-700 font-normal">Este carnê não possui parcelas cadastradas. Procure o organizador do evento informe o localizador</span>
                    <span class="text-red-700 font-bold">{{$order->order_control}}</span>
                </div>

            @endif

        </div>

        {{-- VALIDAR PAGAMENTO --}}
        <div class="w-full max-w-4xl mx-auto px-4 md:px-10 mb-6">
            <x-button green wire:click="paymentCheckProcessed(true)" spinner class="w-full rounded-xl shadow-md" label="VALIDAR PAGAMENTO FORÇADO" title="VALIDAR PAGAMENTO FORÇADO" />
        </div>

    @elseif($orderControl ?? false)

        <div class="w-full max-w-4xl mx-auto px-4 md:px-10 text-center">
            <x-button lg blue icon="refresh" href="{{ route('compra-exibir', ['localizador' => $orderControl]) }}" label="Clique aqui para atualizar" class="w-full uppercase font-semibold rounded-xl shadow-md" />
        </div>

    @else

        <div class="w-full max-w-4xl mx-auto px-4 md:px-10">
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-center">
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

        // ROLA TELA ATE FRAME PAGAMENTO
        scrollToFormasPagamento();
    </script>

</div>
