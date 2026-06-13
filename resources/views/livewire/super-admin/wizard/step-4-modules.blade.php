<!-- Step 4: Módulos Habilitados -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-puzzle-piece text-4xl text-indigo-500 mb-4"></i>
        <p class="text-gray-600">
            Escolha quais módulos estarão disponíveis nesta aplicação.
        </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Campanhas -->
        <div class="relative">
            <div class="bg-white rounded-lg border-2 transition-all duration-300 hover:shadow-lg
                @if ($features['campaigns']) border-green-500 bg-green-50 @else border-gray-200 @endif">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-4
                                @if ($features['campaigns']) bg-green-500 @else bg-gray-400 @endif">
                                <i class="fas fa-bullhorn text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="features.campaigns"
                                   class="w-5 h-5 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Campanhas</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Sistema completo de campanhas de arrecadação com metas, pagamentos e relatórios.
                    </p>
                    <div class="space-y-1 text-xs text-gray-500">
                        <p><i class="fas fa-check mr-1"></i>Criação de campanhas</p>
                        <p><i class="fas fa-check mr-1"></i>Gestão de apoiadores</p>
                        <p><i class="fas fa-check mr-1"></i>Pagamentos online</p>
                        <p><i class="fas fa-check mr-1"></i>Relatórios financeiros</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eventos -->
        <div class="relative">
            <div class="bg-white rounded-lg border-2 transition-all duration-300 hover:shadow-lg
                @if ($features['events']) border-blue-500 bg-blue-50 @else border-gray-200 @endif">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-4
                                @if ($features['events']) bg-blue-500 @else bg-gray-400 @endif">
                                <i class="fas fa-calendar-alt text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="features.events"
                                   class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Eventos</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Plataforma completa para gestão de eventos com ingressos e patrocínios.
                    </p>
                    <div class="space-y-1 text-xs text-gray-500">
                        <p><i class="fas fa-check mr-1"></i>Criação de eventos</p>
                        <p><i class="fas fa-check mr-1"></i>Venda de ingressos</p>
                        <p><i class="fas fa-check mr-1"></i>Gestão de patrocínios</p>
                        <p><i class="fas fa-check mr-1"></i>Check-in participantes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assinaturas -->
        <div class="relative">
            <div class="bg-white rounded-lg border-2 transition-all duration-300 hover:shadow-lg
                @if ($features['subscriptions']) border-teal-500 bg-teal-50 @else border-gray-200 @endif">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-4
                                @if ($features['subscriptions']) bg-teal-500 @else bg-gray-400 @endif">
                                <i class="fas fa-sync-alt text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="features.subscriptions"
                                   class="w-5 h-5 text-teal-600 bg-gray-100 border-gray-300 rounded focus:ring-teal-500 focus:ring-2">
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Assinaturas</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Gestão de planos recorrentes, cobrança e assinantes.
                    </p>
                    <div class="space-y-1 text-xs text-gray-500">
                        <p><i class="fas fa-check mr-1"></i>Produtos e planos</p>
                        <p><i class="fas fa-check mr-1"></i>Assinaturas recorrentes</p>
                        <p><i class="fas fa-check mr-1"></i>Ciclos e cobranças</p>
                        <p><i class="fas fa-check mr-1"></i>Relatórios financeiros</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics -->
        <div class="relative">
            <div class="bg-white rounded-lg border-2 transition-all duration-300 hover:shadow-lg
                @if ($features['analytics']) border-purple-500 bg-purple-50 @else border-gray-200 @endif">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-4
                                @if ($features['analytics']) bg-purple-500 @else bg-gray-400 @endif">
                                <i class="fas fa-chart-line text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="features.analytics"
                                   class="w-5 h-5 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Analytics</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Dashboard avançado com métricas e análises detalhadas de performance.
                    </p>
                    <div class="space-y-1 text-xs text-gray-500">
                        <p><i class="fas fa-check mr-1"></i>Dashboard analítico</p>
                        <p><i class="fas fa-check mr-1"></i>Métricas em tempo real</p>
                        <p><i class="fas fa-check mr-1"></i>Gráficos interativos</p>
                        <p><i class="fas fa-check mr-1"></i>Comparações históricas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Relatórios -->
        <div class="relative">
            <div class="bg-white rounded-lg border-2 transition-all duration-300 hover:shadow-lg
                @if ($features['reports']) border-orange-500 bg-orange-50 @else border-gray-200 @endif">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-4
                                @if ($features['reports']) bg-orange-500 @else bg-gray-400 @endif">
                                <i class="fas fa-file-alt text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="features.reports"
                                   class="w-5 h-5 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 focus:ring-2">
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Relatórios</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Geração de relatórios customizados e exportação de dados.
                    </p>
                    <div class="space-y-1 text-xs text-gray-500">
                        <p><i class="fas fa-check mr-1"></i>Relatórios personalizados</p>
                        <p><i class="fas fa-check mr-1"></i>Exportação PDF/Excel</p>
                        <p><i class="fas fa-check mr-1"></i>Agendamento automático</p>
                        <p><i class="fas fa-check mr-1"></i>Filtros avançados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Integrações -->
        <div class="relative">
            <div class="bg-white rounded-lg border-2 transition-all duration-300 hover:shadow-lg
                @if ($features['integrations']) border-red-500 bg-red-50 @else border-gray-200 @endif">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-4
                                @if ($features['integrations']) bg-red-500 @else bg-gray-400 @endif">
                                <i class="fas fa-plug text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="features.integrations"
                                   class="w-5 h-5 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 focus:ring-2">
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Integrações</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Conecte com sistemas externos e APIs de terceiros.
                    </p>
                    <div class="space-y-1 text-xs text-gray-500">
                        <p><i class="fas fa-check mr-1"></i>APIs externas</p>
                        <p><i class="fas fa-check mr-1"></i>Webhooks</p>
                        <p><i class="fas fa-check mr-1"></i>E-mail marketing</p>
                        <p><i class="fas fa-check mr-1"></i>Redes sociais</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Módulo Personalizado (Futuro) -->
        <div class="relative">
            <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <div class="p-6 text-center">
                    <div class="w-12 h-12 bg-gray-300 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-plus text-gray-500 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-500 mb-2">Mais Módulos</h3>
                    <p class="text-sm text-gray-400 mb-4">
                        Novos módulos serão adicionados em futuras atualizações.
                    </p>
                    <p class="text-xs text-gray-400">Em breve...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumo dos Módulos Selecionados -->
    @php
        $selectedFeatures = array_filter($features);
        $featureNames = [
            'campaigns' => 'Campanhas',
            'events' => 'Eventos',
            'subscriptions' => 'Assinaturas',
            'analytics' => 'Analytics',
            'reports' => 'Relatórios',
            'integrations' => 'Integrações'
        ];
    @endphp

    @if (count($selectedFeatures) > 0)
        <div class="bg-white rounded-lg border-2 border-indigo-200 p-6">
            <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-check-circle text-indigo-500 mr-2"></i>
                Módulos Selecionados ({{ count($selectedFeatures) }})
            </h4>

            <div class="grid md:grid-cols-2 gap-4">
                @foreach ($selectedFeatures as $feature => $enabled)
                    @if ($enabled)
                        <div class="flex items-center p-3 bg-indigo-50 rounded-lg">
                            <div class="w-8 h-8 bg-indigo-500 rounded flex items-center justify-center mr-3">
                                @if ($feature == 'campaigns')
                                    <i class="fas fa-bullhorn text-white text-sm"></i>
                                @elseif ($feature == 'events')
                                    <i class="fas fa-calendar-alt text-white text-sm"></i>
                                @elseif ($feature == 'analytics')
                                    <i class="fas fa-chart-line text-white text-sm"></i>
                                @elseif ($feature == 'reports')
                                    <i class="fas fa-file-alt text-white text-sm"></i>
                                @elseif ($feature == 'integrations')
                                    <i class="fas fa-plug text-white text-sm"></i>
                                @endif
                            </div>
                            <span class="font-medium text-indigo-800">{{ $featureNames[$feature] ?? $feature }}</span>
                        </div>
                    @endif
                @endforeach
            </div>

            @if (count($selectedFeatures) == 0)
                <p class="text-gray-500 text-center py-4">Nenhum módulo selecionado</p>
            @endif
        </div>
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-yellow-800">Atenção:</h4>
                    <p class="text-sm text-yellow-700 mt-1">
                        É recomendado selecionar pelo menos um módulo para que a aplicação tenha funcionalidades disponíveis.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Informações sobre Mudanças Futuras -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800">Flexibilidade:</h4>
                <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                    <li>Os módulos podem ser habilitados/desabilitados a qualquer momento</li>
                    <li>Clientes não verão módulos desabilitados no menu</li>
                    <li>Dados existentes são preservados ao desabilitar módulos</li>
                    <li>Novos módulos poderão ser adicionados sem afetar os existentes</li>
                </ul>
            </div>
        </div>
    </div>
</div>
