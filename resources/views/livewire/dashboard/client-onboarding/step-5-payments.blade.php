<!-- Step 5: Configuração de Pagamentos -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-credit-card text-4xl text-emerald-500 mb-4"></i>
        <p class="text-gray-600">
            Configure os métodos de pagamento e gateways para este cliente.
        </p>
    </div>

    <!-- Status de Pagamento -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-toggle-on mr-2"></i>Status dos Pagamentos
        </h3>

        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <i class="fas fa-credit-card text-emerald-500 text-xl"></i>
                <div>
                    <p class="font-medium text-gray-900">Aceitar Pagamentos</p>
                    <p class="text-sm text-gray-600">Permitir que este cliente processe pagamentos</p>
                </div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model.defer="payment_enabled" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
            </label>
        </div>
    </div>

    <!-- Configuração de Gateways -->
    <div x-show="$wire.payment_enabled" x-transition class="space-y-6">
        <!-- Seleção de Gateway Principal -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
                <i class="fas fa-building mr-2"></i>Gateway de Pagamento Principal
            </h3>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- PagarMe -->
                <label class="relative">
                    <input type="radio" wire:model.defer="primary_gateway" value="pagarme" class="sr-only peer">
                    <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-emerald-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                        <div class="text-center space-y-3">
                            <div class="w-12 h-12 mx-auto bg-emerald-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-credit-card text-emerald-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">PagarMe</h4>
                                <p class="text-sm text-gray-600">Gateway brasileiro completo</p>
                            </div>
                            <div class="text-xs text-emerald-600 font-medium">
                                Cartão • PIX • Boleto
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Stripe -->
                <label class="relative">
                    <input type="radio" wire:model.defer="primary_gateway" value="stripe" class="sr-only peer">
                    <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                        <div class="text-center space-y-3">
                            <div class="w-12 h-12 mx-auto bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fab fa-stripe text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Stripe</h4>
                                <p class="text-sm text-gray-600">Gateway internacional</p>
                            </div>
                            <div class="text-xs text-blue-600 font-medium">
                                Cartão • Apple Pay • Google Pay
                            </div>
                        </div>
                    </div>
                </label>

                <!-- PayPal -->
                <label class="relative">
                    <input type="radio" wire:model.defer="primary_gateway" value="paypal" class="sr-only peer">
                    <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-yellow-300 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 transition-all">
                        <div class="text-center space-y-3">
                            <div class="w-12 h-12 mx-auto bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fab fa-paypal text-yellow-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">PayPal</h4>
                                <p class="text-sm text-gray-600">Pagamentos globais</p>
                            </div>
                            <div class="text-xs text-yellow-600 font-medium">
                                PayPal • Cartão
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Configuração do Gateway Selecionado -->
        @if ($primary_gateway)
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
                    <i class="fas fa-key mr-2"></i>Configuração - {{ ucfirst($primary_gateway) }}
                </h3>

                @if ($primary_gateway === 'pagarme')
                    <!-- PagarMe Configuration -->
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="pagarme_api_key" class="block text-sm font-medium text-gray-700 mb-2">
                                    API Key <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="pagarme_api_key" wire:model.defer="pagarme_api_key"
                                       placeholder="ak_live_..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('pagarme_api_key') border-red-500 @enderror">
                                @error('pagarme_api_key')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pagarme_encryption_key" class="block text-sm font-medium text-gray-700 mb-2">
                                    Encryption Key <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="pagarme_encryption_key" wire:model.defer="pagarme_encryption_key"
                                       placeholder="ek_live_..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('pagarme_encryption_key') border-red-500 @enderror">
                                @error('pagarme_encryption_key')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Ambiente -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Ambiente de Operação
                            </label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" wire:model.defer="pagarme_environment" value="sandbox" class="mr-2">
                                    <span class="text-sm">Sandbox (Teste)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" wire:model.defer="pagarme_environment" value="live" class="mr-2">
                                    <span class="text-sm">Produção (Live)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Métodos Habilitados -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Métodos de Pagamento Habilitados
                            </label>
                            <div class="grid md:grid-cols-3 gap-4">
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    <input type="checkbox" wire:model.defer="pagarme_methods" value="credit_card" class="mr-3">
                                    <i class="fas fa-credit-card text-emerald-500 mr-2"></i>
                                    <span class="text-sm">Cartão de Crédito</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    <input type="checkbox" wire:model.defer="pagarme_methods" value="pix" class="mr-3">
                                    <i class="fas fa-qrcode text-emerald-500 mr-2"></i>
                                    <span class="text-sm">PIX</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    <input type="checkbox" wire:model.defer="pagarme_methods" value="boleto" class="mr-3">
                                    <i class="fas fa-barcode text-emerald-500 mr-2"></i>
                                    <span class="text-sm">Boleto</span>
                                </label>
                            </div>
                        </div>
                    </div>

                @elseif ($primary_gateway === 'stripe')
                    <!-- Stripe Configuration -->
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="stripe_publishable_key" class="block text-sm font-medium text-gray-700 mb-2">
                                    Publishable Key <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="stripe_publishable_key" wire:model.defer="stripe_publishable_key"
                                       placeholder="pk_live_..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('stripe_publishable_key') border-red-500 @enderror">
                                @error('stripe_publishable_key')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="stripe_secret_key" class="block text-sm font-medium text-gray-700 mb-2">
                                    Secret Key <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="stripe_secret_key" wire:model.defer="stripe_secret_key"
                                       placeholder="sk_live_..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('stripe_secret_key') border-red-500 @enderror">
                                @error('stripe_secret_key')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="stripe_webhook_secret" class="block text-sm font-medium text-gray-700 mb-2">
                                Webhook Secret
                            </label>
                            <input type="password" id="stripe_webhook_secret" wire:model.defer="stripe_webhook_secret"
                                   placeholder="whsec_..."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <p class="mt-1 text-sm text-gray-500">
                                Necessário para receber notificações de pagamento
                            </p>
                        </div>
                    </div>

                @elseif ($primary_gateway === 'paypal')
                    <!-- PayPal Configuration -->
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="paypal_client_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Client ID <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="paypal_client_id" wire:model.defer="paypal_client_id"
                                       placeholder="AYSq3RDGsmBLJE-otTkBtM-jBRd1..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors @error('paypal_client_id') border-red-500 @enderror">
                                @error('paypal_client_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="paypal_client_secret" class="block text-sm font-medium text-gray-700 mb-2">
                                    Client Secret <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="paypal_client_secret" wire:model.defer="paypal_client_secret"
                                       placeholder="EGnHDxD_qRPdaLdZz8iCr8N7_MzF-YHPTkjs6NKY..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors @error('paypal_client_secret') border-red-500 @enderror">
                                @error('paypal_client_secret')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Ambiente de Operação
                            </label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" wire:model.defer="paypal_environment" value="sandbox" class="mr-2">
                                    <span class="text-sm">Sandbox (Teste)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" wire:model.defer="paypal_environment" value="live" class="mr-2">
                                    <span class="text-sm">Produção (Live)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Configurações de Taxa -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
                <i class="fas fa-percentage mr-2"></i>Taxas e Configurações
            </h3>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Taxa de Processamento -->
                <div>
                    <label for="processing_fee" class="block text-sm font-medium text-gray-700 mb-2">
                        Taxa de Processamento (%)
                    </label>
                    <div class="relative">
                        <input type="number" id="processing_fee" wire:model.defer="processing_fee"
                               min="0" max="10" step="0.01" placeholder="2.99"
                               class="w-full px-4 py-3 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('processing_fee') border-red-500 @enderror">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">%</span>
                        </div>
                    </div>
                    @error('processing_fee')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Taxa cobrada sobre cada transação
                    </p>
                </div>

                <!-- Taxa Fixa -->
                <div>
                    <label for="fixed_fee" class="block text-sm font-medium text-gray-700 mb-2">
                        Taxa Fixa (R$)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">R$</span>
                        </div>
                        <input type="number" id="fixed_fee" wire:model.defer="fixed_fee"
                               min="0" step="0.01" placeholder="0.39"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('fixed_fee') border-red-500 @enderror">
                    </div>
                    @error('fixed_fee')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Taxa fixa por transação
                    </p>
                </div>
            </div>

            <!-- Configurações de Prazo -->
            <div class="mt-6 grid md:grid-cols-3 gap-4">
                <div>
                    <label for="payment_deadline_days" class="block text-sm font-medium text-gray-700 mb-2">
                        Prazo Padrão (dias)
                    </label>
                    <input type="number" id="payment_deadline_days" wire:model.defer="payment_deadline_days"
                           min="1" max="365" placeholder="30"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                    <p class="mt-1 text-sm text-gray-500">
                        Prazo para pagamento de boletos/PIX
                    </p>
                </div>

                <div>
                    <label for="installment_limit" class="block text-sm font-medium text-gray-700 mb-2">
                        Máx. Parcelas
                    </label>
                    <input type="number" id="installment_limit" wire:model.defer="installment_limit"
                           min="1" max="24" placeholder="12"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                    <p class="mt-1 text-sm text-gray-500">
                        Parcelas permitidas no cartão
                    </p>
                </div>

                <div>
                    <label for="min_installment_value" class="block text-sm font-medium text-gray-700 mb-2">
                        Valor Mín. Parcela
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">R$</span>
                        </div>
                        <input type="number" id="min_installment_value" wire:model.defer="min_installment_value"
                               min="1" step="0.01" placeholder="10.00"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        Valor mínimo por parcela
                    </p>
                </div>
            </div>
        </div>

        <!-- Preview de Configuração -->
        <div class="bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                <i class="fas fa-eye mr-2"></i>Resumo da Configuração
            </h3>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <h4 class="font-medium text-gray-900 mb-3">Gateway Principal:</h4>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                            @if ($primary_gateway === 'pagarme')
                                <i class="fas fa-credit-card text-emerald-600"></i>
                            @elseif ($primary_gateway === 'stripe')
                                <i class="fab fa-stripe text-blue-600"></i>
                            @elseif ($primary_gateway === 'paypal')
                                <i class="fab fa-paypal text-yellow-600"></i>
                            @else
                                <i class="fas fa-question text-gray-400"></i>
                            @endif
                        </div>
                        <span class="font-medium">{{ $primary_gateway ? ucfirst($primary_gateway) : 'Não selecionado' }}</span>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <h4 class="font-medium text-gray-900 mb-3">Taxas:</h4>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span>Processamento:</span>
                            <span class="font-medium">{{ $processing_fee ?? '0' }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Taxa fixa:</span>
                            <span class="font-medium">R$ {{ number_format($fixed_fee ?? 0, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Máx. parcelas:</span>
                            <span class="font-medium">{{ $installment_limit ?? '0' }}x</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Connection -->
        @if ($primary_gateway && $this->hasRequiredCredentials())
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">Teste de Conexão</h4>
                        <p class="text-sm text-gray-600">Verificar se as credenciais estão funcionando</p>
                    </div>
                    <button type="button" wire:click="testConnection"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-plug mr-2"></i>Testar Conexão
                    </button>
                </div>

                @if ($connection_status)
                    <div class="mt-4 p-3 rounded-lg {{ $connection_status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-{{ $connection_status === 'success' ? 'check-circle' : 'exclamation-circle' }}"></i>
                            <span>{{ $connection_message }}</span>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Informações sobre pagamentos -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800">Informações sobre Pagamentos:</h4>
                <ul class="mt-2 text-sm text-blue-700 list-disc list-inside space-y-1">
                    <li>As credenciais são criptografadas e armazenadas com segurança</li>
                    <li>Você pode configurar múltiplos gateways se necessário</li>
                    <li>As taxas podem ser ajustadas posteriormente</li>
                    <li>Recomendamos testar em ambiente sandbox antes da produção</li>
                </ul>
            </div>
        </div>
    </div>
</div>
