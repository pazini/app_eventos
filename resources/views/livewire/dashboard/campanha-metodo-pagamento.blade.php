<div class="w-full max-w-7xl mx-auto">
    <x-notifications position="top-right" />

    {{-- Header --}}
    <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern-payment" width="8" height="8" patternUnits="userSpaceOnUse">
                        <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern-payment)"/>
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
                            <p class="text-white/90 text-sm">Configure os métodos de pagamento para esta campanha</p>
                        </div>
                    </div>
                </div>
                <x-button flat white icon="reply" label="VOLTAR" href="{{ route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign_id]) }}" class="hover:bg-white/20" />
            </div>

            {{-- Provedor Atual --}}
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                <div class="text-white/80 text-xs uppercase tracking-wide mb-2">Provedor Atual</div>
                <div class="text-white text-lg font-semibold">{{ $campaign->gateway->pay_gateway_label ?? 'Ainda Não Possui' }}</div>
                @if($campaign->gateway->pay_gateway_description ?? null)
                    <div class="text-white/80 text-sm mt-1">{{ $campaign->gateway->pay_gateway_description }}</div>
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
            <div class="lg:col-span-8">
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
            <div class="lg:col-span-4 flex items-end justify-center lg:justify-end">
                <x-toggle label="MODO TESTE" left-label="ATIVO" lg wire:model="pay_sandbox" class="bg-emerald-600" />
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
                                    <div class="text-sm text-gray-600">Método de pagamento via boleto bancário habilitado.</div>
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
                                    <div class="text-sm text-gray-600">Método de pagamento via PIX habilitado.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- PIX DIRETO (Static PIX) --}}
                {{-- @if ($gateway->pay_pix ?? false)
                    <div class="lg:col-span-12 p-5 rounded-xl border border-purple-200 bg-purple-50/30">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <div class="w-full sm:w-auto">
                                <x-toggle label="PIX Direto (Estático)" md wire:model="pay_pix_direto" class="bg-white" />
                            </div>
                            <div class="text-xs text-gray-600">
                                PIX estático com QR Code permanente. Ideal para doações rápidas e anônimas.
                            </div>
                        </div>
                    </div>
                @endif --}}

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
                                            >
                                                @for($i = 1; $i <= ($gateway->pay_card_credit_installment_max ?? 1); $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </x-native-select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1 uppercase">{{ __('pay_card_credit_installment_amount_min') }}</label>
                                            <div
                                                class="flex items-stretch rounded-lg border border-gray-300 overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent bg-white"
                                                x-data="currencyField('{{ $pay_card_credit_installment_amount_min_input ?? '' }}')"
                                                x-init="init()"
                                            >
                                                <span class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 border-r border-gray-200 flex justify-center items-center">
                                                    R$
                                                </span>
                                                <input
                                                    type="text"
                                                    x-model="display"
                                                    x-on:input="handleInput($event.target.value)"
                                                    x-on:blur="updateModel()"
                                                    inputmode="decimal"
                                                    pattern="[0-9.,]*"
                                                    placeholder="0,00"
                                                    maxlength="18"
                                                    class="border-none rounded-lg flex-1 px-4 py-2 text-left text-base font-semibold text-gray-900 placeholder-gray-400 focus:outline-none"
                                                />
                                            </div>
                                            @error('pay_card_credit_installment_amount_min')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                            <input type="hidden" wire:model.live="pay_card_credit_installment_amount_min_input" id="pay_card_credit_installment_amount_min_input_hidden" />
                                        </div>
                                        <div>
                                            <x-native-select
                                                label="Quem paga os juros"
                                                wire:model.defer="pay_card_credit_installment_fee_payer"
                                                placeholder="Selecione"
                                            >
                                                <option value="campaign">Campanha (Valor fixo)</option>
                                                <option value="customer">Cliente (Valor aumenta)</option>
                                            </x-native-select>
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
                        Pagamento ativo - As contribuições realizadas terão seus valores realmente processados
                    </div>
                @else
                    <div class="w-full md:flex-1 bg-amber-50 border border-amber-400 text-amber-700 rounded-xl font-semibold uppercase flex justify-center items-center px-6 py-4 text-sm text-center">
                        Pagamento em modo teste - Valores das contribuições não serão processados
                    </div>
                @endif

                <div class="w-full md:w-auto">
                    @if ($this->metodoAlterar ?? false)
                        <x-button lg positive class="w-full md:w-auto" label="ALTERAR" onclick="confirm('Confirma a alteração do metodo de pagamento?') || event.stopImmediatePropagation()" wire:click="metodoPagamentoSubmit" />
                    @else
                        <x-button lg positive class="w-full md:w-auto" label="CADASTRAR" onclick="confirm('Confirma o cadastro do metodo de pagamento?') || event.stopImmediatePropagation()" wire:click="metodoPagamentoSubmit" />
                    @endif
                </div>
            </div>
        </div>
    @endif

    <br>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('currencyField', (initialValue) => ({
                display: '',
                rawValue: '',
                init() {
                    this.display = this.format(initialValue ?? '');
                    this.rawValue = this.getRawValue(initialValue ?? '');
                },
                handleInput(value) {
                    this.display = this.format(value);
                    this.rawValue = this.getRawValue(value);
                },
                updateModel() {
                    const digits = (this.rawValue || '').toString().replace(/\D/g, '');
                    const number = (parseInt(digits || '0', 10) / 100).toFixed(2);
                    const formattedValue = number.replace('.', ',');

                    const hiddenInput = document.getElementById('pay_card_credit_installment_amount_min_input_hidden');
                    if (hiddenInput) {
                        hiddenInput.value = formattedValue;
                        hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                        const livewireId = hiddenInput.closest('[wire\\:id]')?.getAttribute('wire:id');
                        if (livewireId && window.Livewire) {
                            const component = window.Livewire.find(livewireId);
                            if (component) {
                                component.set('pay_card_credit_installment_amount_min_input', formattedValue);
                            }
                        }
                    }
                },
                getRawValue(value) {
                    if (!value) return '';
                    return value.toString().replace(/\D/g, '');
                },
                format(value) {
                    const digits = (value || '').toString().replace(/\D/g, '');
                    const number = (parseInt(digits || '0', 10) / 100).toFixed(2);
                    const [intPart, decimalPart] = number.split('.');
                    const formattedInt = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    return `${formattedInt},${decimalPart}`;
                },
            }));
        });
    </script>
</div>

