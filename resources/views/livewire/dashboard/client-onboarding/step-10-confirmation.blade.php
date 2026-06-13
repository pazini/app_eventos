<!-- Step 10: Confirmação e Ativação -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-flag-checkered text-4xl text-green-500 mb-4"></i>
        <p class="text-gray-600">
            Revise todas as configurações e ative a aplicação do cliente.
        </p>
    </div>

    <!-- Resumo Final Completo -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-clipboard-check mr-2"></i>Resumo Completo da Configuração
        </h3>

        <div class="space-y-6">
            <!-- Informações da Empresa -->
            <div class="grid md:grid-cols-2 gap-6">
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-building text-blue-500 mr-2"></i>Empresa/Organização
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nome:</span>
                            <span class="font-medium">{{ $company_name ?? 'Não informado' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tipo:</span>
                            <span class="font-medium capitalize">{{ $company_type ?? 'empresa' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">E-mail:</span>
                            <span class="font-medium">{{ $company_email ?? 'Não informado' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Telefone:</span>
                            <span class="font-medium">{{ $company_phone ?? 'Não informado' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">CNPJ/CPF:</span>
                            <span class="font-medium">{{ $company_document ?? 'Não informado' }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 border border-gray-200 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-user-shield text-green-500 mr-2"></i>Administrador
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nome:</span>
                            <span class="font-medium">{{ $admin_name ?? 'Não informado' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">E-mail:</span>
                            <span class="font-medium">{{ $admin_email ?? 'Não informado' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Telefone:</span>
                            <span class="font-medium">{{ $admin_phone ?? 'Não informado' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Cargo:</span>
                            <span class="font-medium">{{ $admin_position ?? 'Não informado' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Senha:</span>
                            <span class="font-medium">{{ $password_type === 'generate' ? 'Gerada automaticamente' : 'Definida manualmente' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configurações e Limites -->
            <div class="grid md:grid-cols-3 gap-6">
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-cog text-purple-500 mr-2"></i>Configurações
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Timezone:</span>
                            <span class="font-medium">{{ $timezone ?? 'America/Sao_Paulo' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Moeda:</span>
                            <span class="font-medium">{{ $currency ?? 'BRL' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Notif. E-mail:</span>
                            <span class="font-medium">{{ $notification_email ? 'Sim' : 'Não' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Notif. SMS:</span>
                            <span class="font-medium">{{ $notification_sms ? 'Sim' : 'Não' }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 border border-gray-200 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-chart-line text-orange-500 mr-2"></i>Limites
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Campanhas:</span>
                            <span class="font-medium">{{ $max_campaigns ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Eventos:</span>
                            <span class="font-medium">{{ $max_events ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Usuários:</span>
                            <span class="font-medium">{{ $max_users ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 border border-gray-200 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-globe text-green-500 mr-2"></i>Domínio
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tipo:</span>
                            <span class="font-medium">
                                {{ $domain_type === 'custom' ? 'Personalizado' : 'Subdomínio' }}
                            </span>
                        </div>
                        @if ($domain_type === 'custom')
                            <div class="flex justify-between">
                                <span class="text-gray-600">URL:</span>
                                <span class="font-medium text-green-600">{{ $custom_domain ?? 'Não configurado' }}</span>
                            </div>
                        @else
                            <div class="flex justify-between">
                                <span class="text-gray-600">URL:</span>
                                <span class="font-medium text-green-600">{{ $subdomain }}.{{ config('app.domain', 'minhaPlataforma.com') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600">SSL:</span>
                            <span class="font-medium">{{ $force_https ? 'Forçado' : 'Opcional' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Branding e Pagamentos -->
            <div class="grid md:grid-cols-2 gap-6">
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-palette text-indigo-500 mr-2"></i>Branding
                    </h4>
                    <div class="space-y-3">
                        @if ($logo)
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gray-100 rounded overflow-hidden flex items-center justify-center">
                                    @if (is_string($logo))
                                        <img src="{{ Storage::url($logo) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                                    @else
                                        <img src="{{ $logo->temporaryUrl() }}" alt="Logo" class="max-w-full max-h-full object-contain">
                                    @endif
                                </div>
                                <span class="text-sm text-gray-600">Logo configurado</span>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Nenhum logo carregado</p>
                        @endif

                        @if ($favicon)
                            <p class="text-sm text-gray-600">✓ Favicon configurado</p>
                        @endif

                        @if ($banner)
                            <p class="text-sm text-gray-600">✓ Banner configurado</p>
                        @endif
                    </div>
                </div>

                <div class="p-4 border border-gray-200 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-credit-card text-emerald-500 mr-2"></i>Pagamentos
                    </h4>
                    @if ($payment_enabled)
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Gateway:</span>
                                <span class="font-medium capitalize">{{ $primary_gateway ?? 'Não configurado' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Taxa %:</span>
                                <span class="font-medium">{{ $processing_fee ?? 0 }}%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Taxa fixa:</span>
                                <span class="font-medium">R$ {{ number_format($fixed_fee ?? 0, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Parcelas:</span>
                                <span class="font-medium">{{ $installment_limit ?? 0 }}x</span>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Pagamentos desabilitados</p>
                    @endif
                </div>
            </div>

            <!-- Recursos Opcionais -->
            <div class="grid md:grid-cols-2 gap-6">
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-edit text-blue-500 mr-2"></i>Personalização
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tema textos:</span>
                            <span class="font-medium capitalize">{{ $text_theme ?? 'friendly' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tour guiado:</span>
                            <span class="font-medium">{{ $tour_enabled ? 'Habilitado' : 'Desabilitado' }}</span>
                        </div>
                        @if ($tour_enabled)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Steps tour:</span>
                                <span class="font-medium">{{ count(array_filter($tour_steps ?? [])) }} steps</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-4 border border-gray-200 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-flask text-orange-500 mr-2"></i>Dados de Teste
                    </h4>
                    @if ($create_demo_data)
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Campanhas demo:</span>
                                <span class="font-medium">{{ $demo_campaigns_count ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Eventos demo:</span>
                                <span class="font-medium">{{ $demo_events_count ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Usuários demo:</span>
                                <span class="font-medium">{{ $demo_users_count ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Volume transações:</span>
                                <span class="font-medium">R$ {{ number_format($demo_total_amount ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Nenhum dado de demonstração será criado</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Checklist de Ativação -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-tasks mr-2"></i>Checklist de Ativação
        </h3>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-900 mb-4">Configurações Obrigatórias</h4>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-{{ $company_name ? 'check text-green-500' : 'times text-red-500' }}"></i>
                        <span class="text-sm {{ $company_name ? 'text-gray-700' : 'text-red-600' }}">
                            Nome da empresa/organização
                        </span>
                    </div>

                    <div class="flex items-center space-x-3">
                        <i class="fas fa-{{ $company_email ? 'check text-green-500' : 'times text-red-500' }}"></i>
                        <span class="text-sm {{ $company_email ? 'text-gray-700' : 'text-red-600' }}">
                            E-mail de contato
                        </span>
                    </div>

                    <div class="flex items-center space-x-3">
                        <i class="fas fa-{{ $admin_name && $admin_email ? 'check text-green-500' : 'times text-red-500' }}"></i>
                        <span class="text-sm {{ $admin_name && $admin_email ? 'text-gray-700' : 'text-red-600' }}">
                            Dados do administrador
                        </span>
                    </div>

                    <div class="flex items-center space-x-3">
                        <i class="fas fa-{{ ($domain_type === 'custom' && $custom_domain) || ($domain_type === 'subdomain' && $subdomain) ? 'check text-green-500' : 'times text-red-500' }}"></i>
                        <span class="text-sm {{ ($domain_type === 'custom' && $custom_domain) || ($domain_type === 'subdomain' && $subdomain) ? 'text-gray-700' : 'text-red-600' }}">
                            Configuração de domínio
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-4">Configurações Opcionais</h4>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-{{ $logo ? 'check text-green-500' : 'minus text-gray-400' }}"></i>
                        <span class="text-sm text-gray-700">Logo personalizado</span>
                    </div>

                    <div class="flex items-center space-x-3">
                        <i class="fas fa-{{ $payment_enabled && $primary_gateway ? 'check text-green-500' : 'minus text-gray-400' }}"></i>
                        <span class="text-sm text-gray-700">Configuração de pagamentos</span>
                    </div>

                    <div class="flex items-center space-x-3">
                        <i class="fas fa-{{ $tour_enabled ? 'check text-green-500' : 'minus text-gray-400' }}"></i>
                        <span class="text-sm text-gray-700">Tour guiado</span>
                    </div>

                    <div class="flex items-center space-x-3">
                        <i class="fas fa-{{ $create_demo_data ? 'check text-green-500' : 'minus text-gray-400' }}"></i>
                        <span class="text-sm text-gray-700">Dados de demonstração</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximos Passos -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-list-ol mr-2"></i>Próximos Passos Após Ativação
        </h3>

        <div class="space-y-4">
            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-blue-600 font-medium text-sm">1</span>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Aplicação Criada</h4>
                    <p class="text-sm text-gray-600">Cliente criado no sistema com todas as configurações aplicadas</p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-green-600 font-medium text-sm">2</span>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Credenciais Enviadas</h4>
                    <p class="text-sm text-gray-600">
                        @if ($send_welcome_email)
                            E-mail de boas-vindas será enviado para {{ $admin_email }}
                        @else
                            Credenciais serão disponibilizadas manualmente
                        @endif
                    </p>
                </div>
            </div>

            @if ($domain_type === 'custom' && $custom_domain)
            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-orange-600 font-medium text-sm">3</span>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Configuração DNS</h4>
                    <p class="text-sm text-gray-600">
                        Cliente precisará configurar DNS para {{ $custom_domain }}
                    </p>
                </div>
            </div>
            @endif

            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-purple-600 font-medium text-sm">{{ $domain_type === 'custom' ? '4' : '3' }}</span>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Primeiro Acesso</h4>
                    <p class="text-sm text-gray-600">
                        Administrador faz login e
                        @if ($tour_enabled)
                            é apresentado ao tour guiado
                        @else
                            explora a plataforma livremente
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-red-600 font-medium text-sm">{{ $domain_type === 'custom' ? '5' : '4' }}</span>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Suporte Inicial</h4>
                    <p class="text-sm text-gray-600">Acompanhamento nos primeiros dias para dúvidas e ajustes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Finais -->
    <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-green-800 mb-6">
            <i class="fas fa-rocket mr-2"></i>Finalizar Configuração
        </h3>

        <div class="space-y-4">
            <!-- Validação Final -->
            @php
                $canActivate = $company_name && $company_email && $admin_name && $admin_email &&
                              (($domain_type === 'custom' && $custom_domain) || ($domain_type === 'subdomain' && $subdomain));
            @endphp

            @if (!$canActivate)
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                        <p class="text-sm font-medium text-yellow-800">Configurações obrigatórias pendentes</p>
                    </div>
                    <p class="mt-1 text-sm text-yellow-700">
                        Complete todas as configurações obrigatórias antes de ativar a aplicação.
                    </p>
                </div>
            @endif

            <!-- Opções de Ativação -->
            <div class="grid md:grid-cols-2 gap-4">
                <div class="p-4 border-2 {{ $canActivate ? 'border-green-300 bg-green-50' : 'border-gray-300 bg-gray-50' }} rounded-lg">
                    <div class="flex items-center space-x-3 mb-3">
                        <i class="fas fa-play text-green-600 text-xl"></i>
                        <h4 class="font-medium text-gray-900">Ativar Imediatamente</h4>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Cliente será criado e poderá acessar imediatamente
                    </p>
                    <button type="button" wire:click="createAndActivateClient"
                            {{ !$canActivate ? 'disabled' : '' }}
                            class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <i class="fas fa-rocket mr-2"></i>Criar e Ativar Cliente
                    </button>
                </div>

                <div class="p-4 border-2 border-blue-300 bg-blue-50 rounded-lg">
                    <div class="flex items-center space-x-3 mb-3">
                        <i class="fas fa-save text-blue-600 text-xl"></i>
                        <h4 class="font-medium text-gray-900">Salvar como Rascunho</h4>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Salvar configurações para ativação posterior
                    </p>
                    <button type="button" wire:click="saveDraft"
                            class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Salvar Rascunho
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div wire:loading wire:target="createAndActivateClient,saveDraft"
                 class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
                    <div class="flex items-center space-x-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                        <div>
                            <h4 class="font-medium text-gray-900">Processando...</h4>
                            <p class="text-sm text-gray-600">Criando aplicação do cliente</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações importantes -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800">Informações importantes sobre a ativação:</h4>
                <ul class="mt-2 text-sm text-blue-700 list-disc list-inside space-y-1">
                    <li>Após ativação, um novo customer será criado no sistema</li>
                    <li>O administrador receberá credenciais de acesso (se configurado)</li>
                    <li>Domínios personalizados requerem configuração DNS adicional</li>
                    <li>Todas as configurações podem ser alteradas posteriormente</li>
                </ul>
            </div>
        </div>
    </div>
</div>
