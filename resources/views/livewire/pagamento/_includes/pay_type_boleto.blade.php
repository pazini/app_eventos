@if (($pay_type ?? false) == 'boleto')

    <div class="animate-bounce col-span-full md:col-span-12 text-center bg-yellow-200 rounded shadow p-2">
        <span>{{ __('pending_boleto_sub') }}</span>
    </div>

    <div class="col-span-full md:col-span-12">
        @if (session('conclusao_error'))
            <div class="w-full mt-4">
                <div class="w-full mx-auto text-center bg-red-700 text-white px-1 py-2 shadow-md rounded-sm">
                    <h3 class="font-bold uppercase">
                        {{ __(session('conclusao_error')) }}
                    </h3>
                    @if (session('conclusao_error_sub'))
                        <h5 class="text-xs font-normal py-0 uppercase">
                            {{ __(session('conclusao_error_sub')) }}
                        </h5>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="col-span-full md:col-span-12">
        @if ($target->pay_sandbox ?? false)
            <x-button rounded positive label="GERAR BOLETO - MODO TESTE" class="w-full text-xl font-bold shadow"
                onclick="confirm('Confirma a geração do BOLETO para pagamento?') || event.stopImmediatePropagation()"
                wire:click="processarPagamento" />
        @else
            <x-button rounded positive label="GERAR BOLETO" class="w-full text-xl font-bold shadow"
                onclick="confirm('Confirma a geração do BOLETO para pagamento?') || event.stopImmediatePropagation()"
                wire:click="processarPagamento" />
        @endif
    </div>

@endif
