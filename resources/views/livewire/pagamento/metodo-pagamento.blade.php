<div class="w-full max-w-7xl mx-auto">
    <x-notifications position="top-right" />

    {{-- Header --}}
    <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern-payment-event" width="8" height="8" patternUnits="userSpaceOnUse">
                        <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern-payment-event)"/>
            </svg>
        </div>
        <div class="relative z-10 p-6 space-y-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex-1">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Método de Pagamentos</h1>
                            <p class="text-white/90 text-sm">Configure os métodos de pagamento para este evento</p>
                        </div>
                    </div>
                </div>
                <x-button flat white icon="reply" label="{{ appText('ui.back', 'VOLTAR') }}" href="{{ route('dashboard-evento') }}" class="hover:bg-white/20" />
            </div>

            {{-- Provedor Atual --}}
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                <div class="text-white/80 text-xs uppercase tracking-wide mb-2">Provedor Atual</div>
                <div class="text-white text-lg font-semibold">{{$target->gatewayPay->pay_gateway_label ?? 'Ainda Não Possui'}}</div>
                @if($target->gatewayPay->pay_gateway_description ?? null)
                    <div class="text-white/80 text-sm mt-1">{{$target->gatewayPay->pay_gateway_description}}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Erros --}}
    <div class="mb-6">
        <x-jet-validation-errors />
    </div>

    {{-- Formulário Principal --}}
    <div class="bg-white shadow-sm border-x border-b rounded-b-lg p-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Seleção de Gateway --}}
            <div class="lg:col-span-6">
                <x-native-select
                    label="{{ __('pay_gateway') }}"
                    wire:model="pay_gateway_id"
                    placeholder="Selecione um provedor de pagamento"
                    class="uppercase"
                >
                    <option value="">Selecione</option>
                    @foreach($customerPaymentGateways as $gateway)
                        <option value="{{ $gateway->id }}">
                            {{ $gateway->pay_gateway_label }}@if($gateway->pay_gateway_description) - {{ $gateway->pay_gateway_description }}@endif
                        </option>
                    @endforeach
                </x-native-select>
            </div>

            {{-- Modo Teste --}}
            <div class="lg:col-span-6 flex items-end justify-center lg:justify-end">
                @if (isAdmin())
                    <x-toggle label="MODO TESTE" left-label="ATIVO" lg wire:model="pay_sandbox" class="bg-green-600" />
                @endif
            </div>

            @if ($gateway ?? false)
                <div class="lg:col-span-12">
                    <hr class="my-6 border-gray-200">
                </div>

                <div class="lg:col-span-12">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Métodos de Pagamento Disponíveis</h2>
                </div>

                {{-- Boleto --}}
                @if ($gateway->pay_boleto ?? false)
                    <div class="lg:col-span-12">
                        <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
                            {{-- Header do Card --}}
                            <div class="bg-gray-50 px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                                <h3 class="text-base font-bold text-gray-900 uppercase">Boleto</h3>
                                <x-toggle md wire:model="pay_boleto" />
                            </div>
                            {{-- Body do Card --}}
                            @if($pay_boleto)
                                <div class="p-5">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div>
                                            <x-input label="{{ __('pay_boleto_date_end') }}" wire:model.defer="pay_boleto_date_end" type="date" />
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- PIX --}}
                @if ($gateway->pay_pix ?? false)
                    <div class="lg:col-span-12">
                        <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
                            {{-- Header do Card --}}
                            <div class="bg-gray-50 px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                                <h3 class="text-base font-bold text-gray-900 uppercase">PIX</h3>
                                <x-toggle md wire:model="pay_pix" />
                            </div>
                            {{-- Body do Card --}}
                            @if($pay_pix)
                                <div class="p-5">
                                    @if ($gateway->pay_slip_pix ?? false)
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            {{-- Habilitar Carnê --}}
                                            <div>
                                                <x-native-select
                                                    label="Habilitar Carnê"
                                                    wire:model="pay_slip_pix"
                                                    placeholder="Selecione"
                                                >
                                                    <option value="0">Não</option>
                                                    <option value="1">Sim</option>
                                                </x-native-select>
                                            </div>

                                            {{-- Parâmetros do Carnê (aparecem se habilitado) --}}
                                            @if ($pay_slip_pix ?? false)
                                                <div>
                                                    <x-native-select
                                                        label="Limite Parcelamento"
                                                        wire:model.defer="pay_slip_pix_installment_max_event_date_finish"
                                                        hint="Considerar para o cálculo"
                                                        placeholder="Selecione"
                                                    >
                                                        <option value="">Selecione</option>
                                                        <option value="0">Início do Evento</option>
                                                        <option value="1">Término do Evento</option>
                                                        <option value="999">Após o Evento</option>
                                                    </x-native-select>
                                                </div>
                                                <div>
                                                    <x-native-select
                                                        label="{{ __('pay_slip_pix_installment_max') }}"
                                                        wire:model.defer="pay_slip_pix_installment_max"
                                                        placeholder="Quantidade"
                                                        hint="Prevalece meses até o evento"
                                                        :options="range(1,$gateway->pay_slip_pix_installment_max ?? 1)"
                                                    />
                                                </div>
                                                <div>
                                                    <x-input label="{{ __('pay_slip_pix_installment_amount_min') }}" wire:model.defer="pay_slip_pix_installment_amount_min" prefix="R$ " type="number" min="{{toMoneyDot($gateway->pay_slip_pix_installment_amount_min ?? 10)}}" step="any" />
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Cartão de Crédito --}}
                @if ($gateway->pay_card_credit ?? false)
                    <div class="lg:col-span-12">
                        <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
                            {{-- Header do Card --}}
                            <div class="bg-gray-50 px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                                <h3 class="text-base font-bold text-gray-900 uppercase">Crédito</h3>
                                <x-toggle md wire:model="pay_card_credit" />
                            </div>
                            {{-- Body do Card --}}
                            @if ($pay_card_credit ?? false)
                                <div class="p-5">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div>
                                            <x-native-select
                                                label="{{ __('pay_card_credit_installment_max') }}"
                                                wire:model.defer="pay_card_credit_installment_max"
                                                placeholder="Quantidade"
                                                :options="range(1,$gateway->pay_card_credit_installment_max ?? 1)"
                                            />
                                        </div>
                                        <div>
                                            <x-input label="{{ __('pay_card_credit_installment_amount_min') }}" wire:model.defer="pay_card_credit_installment_amount_min" prefix="R$ " type="number" min="{{toMoneyDot($gateway->pay_card_credit_installment_amount_min ?? 10)}}" step="any" />
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Footer com Botões --}}
    @if ($gateway ?? false)
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg p-6 mt-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                @if (!$this->pay_sandbox)
                    <div class="w-full md:flex-1 bg-red-600 rounded-xl text-white font-semibold uppercase flex justify-center items-center px-6 py-4 text-sm text-center">
                        Pagamento ativo - As compras realizadas terão seus valores realmente processados
                    </div>
                @else
                    <div class="w-full md:flex-1 bg-amber-50 border border-amber-400 text-amber-700 rounded-xl font-semibold uppercase flex justify-center items-center px-6 py-4 text-sm text-center">
                        Pagamento em modo teste - Valores das compras não serão processados
                    </div>
                @endif

                <div class="w-full md:w-auto">
                    @if ($this->metodoAlterar ?? false)
                        <x-button lg positive spinner class="w-full md:w-auto" label="{{ appText('ui.update', 'ALTERAR') }}" onclick="confirm('Confirma a alteração do metodo de pagamento?') || event.stopImmediatePropagation()" wire:click="metodoPagamentoSubmit" />
                    @else
                        <x-button lg positive spinner class="w-full md:w-auto" label="{{ appText('ui.register', 'CADASTRAR') }}" onclick="confirm('Confirma o cadastro do metodo de pagamento?') || event.stopImmediatePropagation()" wire:click="metodoPagamentoSubmit" />
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
