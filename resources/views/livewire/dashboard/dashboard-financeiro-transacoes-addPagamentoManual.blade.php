<div class="w-full max-w-7xl mx-auto mb-6">

    <div class="mb-6">
        <x-jet-validation-errors />
    </div>

@if ($order ?? false && $target ?? false)
    @php
        $isEditingNonManual = ($paymentId ?? false)
            && !in_array(($editingGatewaySlug ?? null), ['user_dashboard', 'manual', 'presencial'], true);
    @endphp

        {{-- HEADER MODERNO COM GRADIENTE --}}
        <div class="mb-4 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern-pagamento" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-pagamento)"/>
                </svg>
            </div>
            <div class="relative z-10 p-4">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-3">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <div class="p-1.5 bg-white/20 rounded backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-lg font-bold text-white">Modificar Pagamentos</h1>
                                <p class="text-white/90 text-xs mt-0.5">{{ $target->event_name }} - {{ $order->order_control ?? '--' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-button flat white xs icon="x" label="FECHAR" wire:click="fecharModificarPagamentos" class="hover:bg-white/20" />
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD: RESUMO DA COMPRA --}}
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-2">
                <h2 class="text-base font-semibold text-gray-800">Resumo da Compra</h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <div class="text-xs font-medium text-gray-500 mb-0.5">Localizador</div>
                        <div class="text-base font-bold text-gray-900">{{ $order->order_control ?? '--' }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 mb-0.5">Total da Compra</div>
                        <div class="text-lg font-bold text-gray-900">{{ toMoney($order->order_amount ?? 0, 'R$ ') }}</div>
                        @if ($order->code_promo_id ?? false)
                            <div class="text-xs text-red-600 mt-0.5">Desconto: - {{ toMoney($order->code_promo_discount_amount ?? 0, 'R$ ') }}</div>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 mb-0.5">Total para Pagamento</div>
                        <div class="text-lg font-bold text-green-600">{{ toMoney($order->order_amount_pay ?? 0, 'R$ ') }}</div>
                        @php
                            $statusClass = in_array($order->status ?? '--', listOrderStatusPaid())
                                ? 'bg-green-100 text-green-800'
                                : 'bg-yellow-100 text-yellow-800';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusClass }} mt-1">
                            {{ __($order->status ?? '--') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD: PAGAMENTOS EXISTENTES --}}
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-2 flex justify-between items-center">
                <h2 class="text-base font-semibold text-gray-800">Pagamentos Existentes</h2>
                <div class="flex gap-2">
                    <x-button primary xs label="Novo Pagamento" right-icon="plus" wire:click="$set('addPayManual',true)" />
                    <x-button primary xs label="Atualizar" right-icon="refresh" wire:click="addPagamentoManual" />
                </div>
            </div>
            <div class="p-4">
                @forelse ($orderPayments ?? [] as $payment)
                    @if ($payment->id == $paymentId)
                        @continue
                    @endif

                    <div class="bg-white border border-gray-200 rounded p-3 mb-2 hover:shadow-sm transition-shadow">
                        {{-- LINHA PRINCIPAL --}}
                        <div class="flex justify-between items-start mb-2">
                            {{-- ESQUERDA: TIPO E STATUS --}}
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-base font-bold text-gray-900 uppercase">{{ __($payment->pay_type ?? 'nd') }}</span>
                                    @php
                                        $statusClass = in_array($payment->status ?? '--', listOrderStatusPaid())
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-yellow-100 text-yellow-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusClass }}">
                                        {{ __($payment->status ?? '--') }}
                                    </span>
                                    @if($payment->status == 'paid' && $payment->pay_datetime)
                                        <span class="text-xs text-gray-500">{{ $payment->pay_datetime->format('d/m/Y H:i') }}</span>
                                    @endif
                                </div>

                                {{-- DETALHES DO PAGAMENTO --}}
                                <div class="text-xs text-gray-600">
                                    @if (in_array(strtoupper($payment->pay_type),['CREDIT_CARD','CREDIT_CARD']))
                                        <span class="uppercase">{{ $payment->pay_card_brand ?? null }} {{ $payment->pay_card_last ?? null }}</span>
                                    @endif
                                    @if (in_array(strtoupper($payment->pay_type),['BOLETO']))
                                        @if($payment->status != 'paid')
                                            <a href="{{ $payment->pay_boleto_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                Venc: {{ convertToDate($payment->pay_boleto_expiration_date) }}
                                            </a>
                                        @endif
                                    @endif
                                    @if (in_array(strtoupper($payment->pay_type),['TRANSFER_PIX']))
                                        <span class="text-gray-500">Chave: {{ $payment->pay_pix_key ?? 'NÃO INFORMADA' }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- DIREITA: VALORES --}}
                            <div class="text-right ml-4">
                                <div class="text-lg font-bold text-gray-900 mb-0.5">
                                    {{ toMoney($payment->value_paid, 'R$ ') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Valor: {{ toMoney($payment->value_liquid, 'R$ ') }} + Encargos: {{ toMoney($payment->value_fees, 'R$ ') }}
                                    @if($payment->pay_installments_number > 1)
                                        = {{ $payment->pay_installments_number }}x {{ toMoney($payment->pay_installment_value ?? $payment->value_paid, 'R$ ') }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- LINHA INFERIOR: DETALHES E AÇÕES --}}
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200 text-xs">
                            <div class="flex items-center gap-3 text-gray-500">
                                <span>NSU: {{ $payment->pay_nsu ?? '---' }}</span>
                                <span>Gateway: {{ __($payment->gateway_slug ?? 'nd') }}</span>
                                @if ($payment->gateway_sandbox ?? false)
                                    <span class="px-1.5 py-0.5 bg-red-100 text-red-700 rounded text-xs font-medium">MODO TESTE</span>
                                @endif
                            </div>
                            @if (
                                isAdmin()
                                || in_array($payment->gateway_slug ?? false, ['user_dashboard','manual','presencial'], true)
                            )
                                <x-button outline primary xs label="ALTERAR" wire:click="alterarPagamentoManual('{{ $payment->id }}')" />
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-900">Não possui pagamentos</p>
                        <p class="text-xs text-gray-500 mt-1">Adicione um novo pagamento usando o botão acima</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- MODAL: ADICIONAR/EDITAR PAGAMENTO --}}
        <x-modal max-width="4xl" wire:model.defer="addPayManual">
            <x-card title="{{ $paymentId ? 'ALTERAR PAGAMENTO' : 'NOVO PAGAMENTO' }}">
                <div class="mb-4">
                    <x-jet-validation-errors />
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-native-select label="* Tipo de Pagamento" wire:model="pay_type">
                                <option value="">Selecione</option>
                                <option value="CREDIT_CARD">CARTÃO CRÉDITO</option>
                                <option value="CARD_DEBIT">DÉBITO</option>
                                <option value="transfer_pix">PIX</option>
                                <option value="transfer_ted">TED</option>
                                <option value="transfer_doc">DOC</option>
                                <option value="transfer_bank">TRANSFERÊNCIA BANCÁRIA</option>
                                <option value="deposit_bank">DEPÓSITO BANCÁRIO</option>
                                <option value="dinheiro">DINHEIRO</option>
                                <option value="doacao">DOAÇÃO</option>
                            </x-native-select>
                        </div>

                        @if ($pay_type == 'CREDIT_CARD')
                            <div>
                                <x-native-select label="Bandeira do Cartão" wire:model.defer="pay_card_brand">
                                    <option value="">Selecione</option>
                                    <option value="master">MASTER</option>
                                    <option value="visa">VISA</option>
                                    <option value="elo">ELO</option>
                                    <option value="amex">AMEX</option>
                                    <option value="hipercard">HIPERCARD</option>
                                    <option value="dinners">DINNERS</option>
                                    <option value="outra">OUTRA</option>
                                </x-native-select>
                            </div>
                            <div>
                                <x-inputs.maskable
                                    placeholder="9999 (Opcional)"
                                    label="4 Últimos Dígitos"
                                    wire:model.defer="pay_card_last"
                                    mask="####"
                                />
                            </div>
                        @endif

                        @if ($pay_type == 'transfer_pix')
                            <div class="md:col-span-2">
                                <x-input label="Chave PIX Utilizada" wire:model.defer="pay_pix_key" />
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-inputs.currency
                                wire:model.defer="value_paid"
                                label="* Valor Pago"
                                hint="Ex: 1.234,56 = 1234,56"
                                thousands=""
                                decimal=","
                                precision="2"
                                emitFormatted="true"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $isEditingNonManual ? 'Data do Pagamento' : '* Data do Pagamento' }}</label>
                            <input
                                type="date"
                                autocomplete="off"
                                placeholder="Quando foi realizado"
                                wire:model.defer="pay_datetime"
                                name="pay_datetime"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                            <x-jet-input-error for="pay_datetime" />
                        </div>

                        <div>
                            <x-input label="NSU" placeholder="Nº Transação (Opcional)" wire:model.defer="pay_nsu" />
                        </div>
                    </div>

                    @if ($order->paymentsSlip->count() ?? 0)
                        <div>
                            <x-native-select label="Associar à Parcela do Carnê" wire:model.defer="slipPaymentSlipId">
                                <option value="">Nenhuma (pagamento avulso)</option>
                                @foreach ($order->paymentsSlip as $slipOption)
                                    <option value="{{ $slipOption->id }}">{{ $slipOption->installment_description }} - {{ dataData($slipOption->installment_date_due) }} - {{ toMoney($slipOption->installment_value, 'R$ ') }}</option>
                                @endforeach
                            </x-native-select>
                        </div>
                    @endif
                </div>

                <x-slot name="footer">
                    @if ($paymentId ?? false)
                        <div class="w-full flex justify-between gap-2">
                            <x-button
                                negative
                                label="REMOVER"
                                onclick="confirm('Confirma a remoção do pagamento?') || event.stopImmediatePropagation()"
                                wire:click="removerPagamentoManualSubmit('{{ $paymentId }}')"
                            />
                            <div class="flex gap-2">
                                <x-button flat label="CANCELAR" wire:click="$set('addPayManual',false)" />
                                <x-button primary label="ALTERAR" wire:click="addPagamentoManualSubmit('{{ $paymentId }}')" spinner />
                            </div>
                        </div>
                    @else
                        <div class="w-full flex justify-end gap-2">
                            <x-button flat label="CANCELAR" wire:click="$set('addPayManual',false)" />
                            <x-button primary label="ADICIONAR" wire:click="addPagamentoManualSubmit" spinner />
                        </div>
                    @endif
                </x-slot>
            </x-card>
        </x-modal>

    @endif

</div>
