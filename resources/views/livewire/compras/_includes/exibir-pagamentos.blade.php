<div class="w-full mb-3 rounded-xl bg-gray-50 border border-gray-100 overflow-hidden">

    {{-- CREDITO --}}
    @if (in_array(strtoupper($payment['pay_type'] ?? false), ['CREDIT_CARD','CARD_CREDIT','CREDIT','CARTAO-CREDITO','CARTAO_CREDITO']))

        <div class="w-full py-3 px-5 uppercase flex justify-between items-center text-base border-b border-gray-100">
            <div class="font-bold text-gray-600">CARTÃO DE CRÉDITO</div>
            <div class="text-green-500 font-bold text-sm">{{ __($payment['status']) }}</div>
        </div>

        <div class="w-full py-2.5 px-5 uppercase flex justify-between items-center text-sm border-b border-gray-100">
            <div class="text-gray-500 font-light">TRANSAÇÃO</div>
            <div class="text-gray-600">
                <span class="font-mono">{{ $payment->pay_nsu ?? '---' }}</span>
                @if (in_array($payment->pay_integration_type, ['sandbox']))
                    <span class="font-medium text-red-600 uppercase ml-1">{{$payment->pay_integration_type}}</span>
                @endif
            </div>
        </div>

        <div class="w-full py-2.5 px-5 uppercase flex justify-between items-center text-sm border-b border-gray-100">
            <div class="text-gray-500 font-light">DATA HORA</div>
            <div class="text-gray-600">{{ $payment->pay_datetime ? $payment->pay_datetime->format('d/m/Y H:i') : '---' }}</div>
        </div>

        @if ($payment['fee_percentage_used'] ?? false)
            <div class="w-full py-2.5 px-5 uppercase flex justify-between items-center text-sm border-b border-gray-100">
                <div class="text-gray-500 font-light">VALOR</div>
                <div class="text-gray-600 text-right">{{ toMoney($payment['value_liquid'],'R$ ') }}</div>
            </div>
            <div class="w-full py-2.5 px-5 uppercase flex justify-between items-center text-sm border-b border-gray-100">
                <div class="text-gray-500 font-light">ENCARGOS</div>
                <div class="text-gray-600 text-right">{{ toMoney($payment['value_fees'],'R$ ') }}</div>
            </div>
        @endif

        <div class="w-full py-2.5 px-5 flex justify-between items-center text-sm">
            <div class="text-gray-500 font-light uppercase">VALOR PAGO</div>
            <div class="flex-none md:flex justify-end items-center gap-2">
                <div class="text-gray-800 text-right uppercase font-semibold">{{ toMoney($payment['value_paid'] ?? 0,'R$ ') }}</div>
                <div class="text-gray-400 text-right text-xs">({{ $payment['pay_installments_number'] }}x {{ toMoney($payment['pay_installment_value'] ?? 0,'R$ ') }})</div>
            </div>
        </div>

        {{-- @dump($payment->toArray()) --}}

    {{-- PIX --}}
    @elseif (in_array(strtoupper($payment['pay_type'] ?? false), ['PIX','SLIP_PIX','TRANSFER_PIX']))

        <div class="w-full py-3 px-5 uppercase flex justify-between items-center text-base border-b border-gray-100">
            <div class="font-bold text-gray-600">VIA PIX</div>
            <div class="text-green-500 font-bold text-sm">{{ __($payment['status'] ?? 'SEM STATUS') }}</div>
        </div>

        <div class="w-full py-2.5 px-5 uppercase flex justify-between items-center text-sm border-b border-gray-100">
            <div class="text-gray-500 font-light">DATA HORA</div>
            <div class="text-gray-600">{{ $payment->pay_datetime ? $payment->pay_datetime->format('d/m/Y H:i') : '---' }}</div>
        </div>

        <div class="w-full py-2.5 px-5 uppercase flex justify-between items-center text-sm border-b border-gray-100">
            <div class="text-gray-500 font-light">TRANSAÇÃO</div>
            <div class="text-gray-600 font-mono">{{ $payment->pay_nsu ?? '---' }}</div>
        </div>

        <div class="w-full py-2.5 px-5 uppercase flex justify-between items-center text-sm">
            <div class="text-gray-500 font-light">VALOR PAGO</div>
            <div class="text-gray-800 font-semibold">{{ toMoney($payment->value_paid,'R$ ') }}</div>
        </div>

        {{-- @dump($payment->toArray()) --}}

    {{-- BOLETO --}}
    @elseif (in_array(strtoupper($payment['pay_type'] ?? false), ['BOLETO']))

        <div class="w-full py-3 px-5 uppercase flex justify-between items-center text-base border-b border-gray-100">
            <div class="font-bold text-gray-600">BOLETO</div>
            <div class="text-green-500 font-bold text-sm">{{ __($payment['status']) }}</div>
        </div>

        <div class="w-full py-2.5 px-5 uppercase flex justify-between items-center text-sm border-b border-gray-100">
            <div class="text-gray-500 font-light">VENCIMENTO</div>
            @php
                if($payment['pay_boleto_expiration_date'] ?? false)
                    $payment['pay_boleto_expiration_date'] = date_format($date=date_create($payment['pay_boleto_expiration_date']),"d/m/Y");
                else
                    $payment['pay_boleto_expiration_date'] = '---';
            @endphp
            <div class="text-gray-600">{{ __($payment['pay_boleto_expiration_date']) }}</div>
        </div>

        <div class="w-full py-2.5 px-5 uppercase flex justify-between items-center text-sm border-b border-gray-100">
            <div class="text-gray-500 font-light">DATA HORA</div>
            <div class="text-gray-600">{{ $payment->pay_datetime ? $payment->pay_datetime->format('d/m/Y H:i') : '---' }}</div>
        </div>

        <div class="w-full py-2.5 px-5 uppercase flex justify-between items-center text-sm border-b border-gray-100">
            <div class="text-gray-500 font-light">TRANSAÇÃO</div>
            <div class="text-gray-600 font-mono">{{ $payment->pay_nsu ?? '---' }}</div>
        </div>

        <div class="w-full py-2.5 px-5 uppercase flex justify-between items-center text-sm border-b border-gray-100">
            <div class="text-gray-500 font-light">VALOR PAGO</div>
            <div class="text-gray-800 font-semibold">{{ __($payment->paid_label) }}</div>
        </div>

        <div class="w-full py-2.5 px-5 uppercase flex flex-col md:flex-row justify-between items-center text-sm">
            <div class="text-gray-500 font-light">CÓDIGO DIGITÁVEL</div>
            <div class="text-gray-600 font-mono text-xs md:text-sm">{{ $payment->pay_boleto_barcode ?? '---' }}</div>
        </div>

    @else
        <div class="w-full py-3 px-5 uppercase flex flex-col md:flex-row justify-between items-center text-sm">
            <div>
                <span class="font-bold text-gray-600">PAGAMENTO NÃO INTERPRETADO</span>
                <span class="font-light text-gray-400 ml-1">{{$payment->id ?? 'SEM IDENTIFICADOR'}}</span>
            </div>
            <div class="text-gray-400">=-(</div>
        </div>
        @dump($payment)
    @endif

</div>
