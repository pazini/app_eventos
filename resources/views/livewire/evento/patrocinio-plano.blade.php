<div class="w-full max-w-7xl mx-auto">
    <x-notifications position="top-right" />

    {{-- Header --}}
    <div class="mb-6 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 rounded-lg relative overflow-hidden shadow-md">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern-sponsorship" width="8" height="8" patternUnits="userSpaceOnUse">
                        <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern-sponsorship)"/>
            </svg>
        </div>
        <div class="relative z-10 p-6 space-y-4">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex-1">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">
                                @if ($this->sponsorship_plan ?? false)
                                    Alterar Plano de Patrocínio
                                @else
                                    Novo Plano de Patrocínio
                                @endif
                            </h1>
                            <p class="text-white/90 text-sm">{{ $target->event_name }} &mdash; {{ formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) }}</p>
                        </div>
                    </div>
                </div>
                <x-button flat white icon="reply" label="VOLTAR" href="{{ route('dashboard-evento') }}" class="hover:bg-white/20" />
            </div>

            @if ($this->sponsorship_plan ?? false)
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                    <div class="text-white/80 text-xs uppercase tracking-wide mb-1">Plano Atual</div>
                    <div class="text-white text-lg font-semibold">{{ $this->sponsorship_plan->name }}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- Erros --}}
    <div class="mb-6">
        @include('_includes.alertas')
        <x-jet-validation-errors />
    </div>

    {{-- Dados do Plano --}}
    <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Dados do Plano</h2>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4">
                <x-input label="Nome do Plano" wire:model.defer="name" placeholder="Ex: Plano Ouro" />
            </div>
            <div class="lg:col-span-4">
                <x-input label="Valor" wire:model.defer="price" prefix="R$ " class="w-full pl-10" type="number" min="1" step="any" />
            </div>
            <div class="lg:col-span-4">
                <x-input label="Data Limite" type="date" wire:model.lazy="data_finish" />
            </div>
        </div>
    </div>

    {{-- Métodos de Pagamento Disponíveis --}}
    @if ($target->gatewayPay ?? false)
        <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Métodos de Pagamento Disponíveis</h2>

            <div class="grid grid-cols-1 gap-6">

                {{-- Cartão de Crédito --}}
                @if ($target->gatewayPay->pay_card_credit ?? false)
                    <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
                        <div class="bg-gray-50 px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-base font-bold text-gray-900 uppercase">Cartão de Crédito</h3>
                            <x-toggle md wire:model="pay_credit" />
                        </div>
                        @if ($pay_credit)
                            <div class="p-5">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <x-native-select label="Máx. Parcelas" wire:model.defer="installments_max"
                                            :options="range(1, $target->gatewayPay->pay_card_credit_installment_max ?? 1)"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="bg-blue-50 px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                                    <h3 class="text-base font-bold text-gray-900 uppercase">Repassar Juros ao Patrocinador</h3>
                                    <x-toggle md wire:model="installments_fees_pay" />
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Boleto --}}
                @if ($target->gatewayPay->pay_boleto ?? false)
                    <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
                        <div class="bg-gray-50 px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-base font-bold text-gray-900 uppercase">Boleto</h3>
                            <x-toggle md wire:model="pay_boleto" />
                        </div>
                        @if ($pay_boleto)
                            <div class="p-5">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <x-input label="Data Limite do Boleto" wire:model.defer="pay_boleto_date_max" type="date" />
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- PIX --}}
                @if ($target->gatewayPay->pay_pix ?? false)
                    <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
                        <div class="bg-gray-50 px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-base font-bold text-gray-900 uppercase">PIX</h3>
                            <x-toggle md wire:model.defer="pay_pix" />
                        </div>
                    </div>
                @endif

            </div>

            @if (!($target->gatewayPay->pay_card_credit ?? false) && !($target->gatewayPay->pay_boleto ?? false) && !($target->gatewayPay->pay_pix ?? false))
                <div class="text-center text-gray-500 py-8">
                    <p class="text-sm">Nenhum método de pagamento disponível no gateway configurado para este evento.</p>
                </div>
            @endif
        </div>
    @else
        <div class="bg-amber-50 border border-amber-300 rounded-lg p-6 mb-6 text-center">
            <p class="text-amber-700 font-semibold">Nenhum gateway de pagamento configurado para este evento.</p>
            <p class="text-amber-600 text-sm mt-1">Configure um método de pagamento no evento antes de criar um plano de patrocínio.</p>
        </div>
    @endif

    {{-- Descrição --}}
    <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Descrição</h2>
        <x-textarea label="Descrição" wire:model.defer="description" rows="5" />
    </div>

    {{-- Footer com Botões --}}
    <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                @if ($this->sponsorship_plan ?? false)
                    <x-button lg red flat label="Remover"
                        onclick="confirm('ATENÇÃO - Tem certeza que deseja remover este plano?') || event.stopImmediatePropagation()"
                        wire:click="patrocinioRemove('{{ $this->sponsorship_plan->id }}')"
                    />
                @endif
            </div>
            <div>
                @if ($this->sponsorship_plan ?? false)
                    <x-button lg positive label="ALTERAR"
                        onclick="confirm('Confirma a alteração?') || event.stopImmediatePropagation()"
                        wire:click="patrocinioSubmit"
                    />
                @else
                    <x-button lg positive label="CADASTRAR"
                        onclick="confirm('Confirma o cadastro?') || event.stopImmediatePropagation()"
                        wire:click="patrocinioSubmit"
                    />
                @endif
            </div>
        </div>
    </div>

</div>
