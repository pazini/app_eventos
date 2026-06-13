<!-- Step 9: Configuração de Teste -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-flask text-4xl text-orange-500 mb-4"></i>
        <p class="text-gray-600">
            Configure dados de demonstração para que o cliente possa testar as funcionalidades.
        </p>
    </div>

    <!-- Status dos Dados de Teste -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-vial mr-2"></i>Dados de Demonstração
        </h3>

        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <i class="fas fa-database text-orange-500 text-xl"></i>
                <div>
                    <p class="font-medium text-gray-900">Criar Dados de Exemplo</p>
                    <p class="text-sm text-gray-600">Gera campanhas, eventos e usuários de demonstração</p>
                </div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model.defer="create_demo_data" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
            </label>
        </div>
    </div>

    <!-- Configuração dos Dados de Demonstração -->
    <div x-show="$wire.create_demo_data" x-transition class="space-y-6">

        <!-- Campanhas de Exemplo -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
                <i class="fas fa-bullhorn mr-2"></i>Campanhas de Demonstração
            </h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" wire:model.defer="demo_campaigns.education" checked class="text-purple-600">
                        <div>
                            <p class="font-medium text-gray-900">Campanha Educação</p>
                            <p class="text-sm text-gray-600">Arrecadação para construção de escola</p>
                        </div>
                    </div>
                    <div class="text-right text-sm">
                        <p class="text-purple-600 font-medium">Meta: R$ 50.000</p>
                        <p class="text-gray-500">75% alcançado</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" wire:model.defer="demo_campaigns.health" checked class="text-green-600">
                        <div>
                            <p class="font-medium text-gray-900">Campanha Saúde</p>
                            <p class="text-sm text-gray-600">Equipamentos médicos para hospital</p>
                        </div>
                    </div>
                    <div class="text-right text-sm">
                        <p class="text-green-600 font-medium">Meta: R$ 25.000</p>
                        <p class="text-gray-500">40% alcançado</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" wire:model.defer="demo_campaigns.environment" checked class="text-blue-600">
                        <div>
                            <p class="font-medium text-gray-900">Campanha Meio Ambiente</p>
                            <p class="text-sm text-gray-600">Plantio de árvores na região</p>
                        </div>
                    </div>
                    <div class="text-right text-sm">
                        <p class="text-blue-600 font-medium">Meta: R$ 15.000</p>
                        <p class="text-gray-500">90% alcançado</p>
                    </div>
                </div>

                <!-- Configurações das Campanhas -->
                <div class="grid md:grid-cols-3 gap-4 mt-6">
                    <div>
                        <label for="demo_campaigns_count" class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Campanhas
                        </label>
                        <select id="demo_campaigns_count" wire:model.defer="demo_campaigns_count"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                            <option value="1">1 campanha</option>
                            <option value="3" selected>3 campanhas</option>
                            <option value="5">5 campanhas</option>
                        </select>
                    </div>

                    <div>
                        <label for="demo_donations_per_campaign" class="block text-sm font-medium text-gray-700 mb-2">
                            Doações por Campanha
                        </label>
                        <select id="demo_donations_per_campaign" wire:model.defer="demo_donations_per_campaign"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                            <option value="10">10 doações</option>
                            <option value="25" selected>25 doações</option>
                            <option value="50">50 doações</option>
                        </select>
                    </div>

                    <div>
                        <label for="demo_campaign_status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status das Campanhas
                        </label>
                        <select id="demo_campaign_status" wire:model.defer="demo_campaign_status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                            <option value="active" selected>Ativas</option>
                            <option value="mixed">Mistas (ativas + encerradas)</option>
                            <option value="completed">Concluídas</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eventos de Exemplo -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
                <i class="fas fa-calendar-alt mr-2"></i>Eventos de Demonstração
            </h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-indigo-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" wire:model.defer="demo_events.conference" checked class="text-indigo-600">
                        <div>
                            <p class="font-medium text-gray-900">Conferência de Tecnologia</p>
                            <p class="text-sm text-gray-600">Evento de 2 dias sobre inovação</p>
                        </div>
                    </div>
                    <div class="text-right text-sm">
                        <p class="text-indigo-600 font-medium">150 ingressos</p>
                        <p class="text-gray-500">85% vendidos</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-pink-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" wire:model.defer="demo_events.workshop" checked class="text-pink-600">
                        <div>
                            <p class="font-medium text-gray-900">Workshop de Empreendedorismo</p>
                            <p class="text-sm text-gray-600">Capacitação para novos empreendedores</p>
                        </div>
                    </div>
                    <div class="text-right text-sm">
                        <p class="text-pink-600 font-medium">50 ingressos</p>
                        <p class="text-gray-500">60% vendidos</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" wire:model.defer="demo_events.networking" checked class="text-yellow-600">
                        <div>
                            <p class="font-medium text-gray-900">Networking de Negócios</p>
                            <p class="text-sm text-gray-600">Encontro de profissionais da área</p>
                        </div>
                    </div>
                    <div class="text-right text-sm">
                        <p class="text-yellow-600 font-medium">100 ingressos</p>
                        <p class="text-gray-500">45% vendidos</p>
                    </div>
                </div>

                <!-- Configurações dos Eventos -->
                <div class="grid md:grid-cols-3 gap-4 mt-6">
                    <div>
                        <label for="demo_events_count" class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Eventos
                        </label>
                        <select id="demo_events_count" wire:model.defer="demo_events_count"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                            <option value="1">1 evento</option>
                            <option value="3" selected>3 eventos</option>
                            <option value="5">5 eventos</option>
                        </select>
                    </div>

                    <div>
                        <label for="demo_tickets_per_event" class="block text-sm font-medium text-gray-700 mb-2">
                            Vendas por Evento
                        </label>
                        <select id="demo_tickets_per_event" wire:model.defer="demo_tickets_per_event"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                            <option value="20">20 ingressos</option>
                            <option value="50" selected>50 ingressos</option>
                            <option value="100">100 ingressos</option>
                        </select>
                    </div>

                    <div>
                        <label for="demo_event_dates" class="block text-sm font-medium text-gray-700 mb-2">
                            Período dos Eventos
                        </label>
                        <select id="demo_event_dates" wire:model.defer="demo_event_dates"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                            <option value="upcoming" selected>Próximos</option>
                            <option value="mixed">Passados + Próximos</option>
                            <option value="past">Já realizados</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usuários de Teste -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
                <i class="fas fa-users mr-2"></i>Usuários de Demonstração
            </h3>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Tipos de Usuários -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-4">Perfis de Usuário</h4>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <input type="checkbox" wire:model.defer="demo_users.admin" checked class="mr-3 text-blue-600">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900">Administradores</span>
                                <p class="text-sm text-gray-600">2 usuários com acesso total</p>
                            </div>
                            <i class="fas fa-user-shield text-blue-500"></i>
                        </label>

                        <label class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <input type="checkbox" wire:model.defer="demo_users.editor" checked class="mr-3 text-green-600">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900">Editores</span>
                                <p class="text-sm text-gray-600">3 usuários para criação/edição</p>
                            </div>
                            <i class="fas fa-user-edit text-green-500"></i>
                        </label>

                        <label class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <input type="checkbox" wire:model.defer="demo_users.viewer" checked class="mr-3 text-purple-600">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900">Visualizadores</span>
                                <p class="text-sm text-gray-600">5 usuários apenas leitura</p>
                            </div>
                            <i class="fas fa-user text-purple-500"></i>
                        </label>
                    </div>
                </div>

                <!-- Configurações dos Usuários -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-4">Configurações</h4>
                    <div class="space-y-4">
                        <div>
                            <label for="demo_users_count" class="block text-sm font-medium text-gray-700 mb-2">
                                Total de Usuários Demo
                            </label>
                            <select id="demo_users_count" wire:model.defer="demo_users_count"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                                <option value="5">5 usuários</option>
                                <option value="10" selected>10 usuários</option>
                                <option value="20">20 usuários</option>
                            </select>
                        </div>

                        <div>
                            <label for="demo_users_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Senha Padrão
                            </label>
                            <input type="text" id="demo_users_password" wire:model.defer="demo_users_password"
                                   value="demo123"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                            <p class="mt-1 text-sm text-gray-500">Todos os usuários demo terão esta senha</p>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div>
                                <span class="font-medium text-gray-900">Enviar credenciais por e-mail</span>
                                <p class="text-sm text-gray-600">Enviar dados de acesso para usuários demo</p>
                            </div>
                            <input type="checkbox" wire:model.defer="send_demo_credentials" class="text-yellow-600">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configurações de Pagamento de Teste -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
                <i class="fas fa-credit-card mr-2"></i>Transações de Demonstração
            </h3>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Status das Transações -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-4">Tipos de Transação</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" wire:model.defer="demo_payments.approved" checked class="text-green-600">
                                <div>
                                    <span class="font-medium text-gray-900">Pagamentos Aprovados</span>
                                    <p class="text-sm text-gray-600">85% do total</p>
                                </div>
                            </div>
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" wire:model.defer="demo_payments.pending" checked class="text-yellow-600">
                                <div>
                                    <span class="font-medium text-gray-900">Pagamentos Pendentes</span>
                                    <p class="text-sm text-gray-600">10% do total</p>
                                </div>
                            </div>
                            <i class="fas fa-clock text-yellow-500"></i>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" wire:model.defer="demo_payments.failed" checked class="text-red-600">
                                <div>
                                    <span class="font-medium text-gray-900">Pagamentos Rejeitados</span>
                                    <p class="text-sm text-gray-600">5% do total</p>
                                </div>
                            </div>
                            <i class="fas fa-times-circle text-red-500"></i>
                        </div>
                    </div>
                </div>

                <!-- Configurações das Transações -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-4">Configurações</h4>
                    <div class="space-y-4">
                        <div>
                            <label for="demo_total_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Volume Total de Transações
                            </label>
                            <select id="demo_total_amount" wire:model.defer="demo_total_amount"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                                <option value="10000">R$ 10.000</option>
                                <option value="50000" selected>R$ 50.000</option>
                                <option value="100000">R$ 100.000</option>
                            </select>
                        </div>

                        <div>
                            <label for="demo_payment_methods" class="block text-sm font-medium text-gray-700 mb-2">
                                Métodos de Pagamento Demo
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model.defer="demo_payment_methods.credit_card" checked class="mr-2 text-blue-600">
                                    <span class="text-sm">Cartão de Crédito (60%)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model.defer="demo_payment_methods.pix" checked class="mr-2 text-green-600">
                                    <span class="text-sm">PIX (25%)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model.defer="demo_payment_methods.boleto" checked class="mr-2 text-purple-600">
                                    <span class="text-sm">Boleto (15%)</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div>
                                <span class="font-medium text-gray-900">Usar gateway de teste</span>
                                <p class="text-sm text-gray-600">Simular transações sem processar pagamentos reais</p>
                            </div>
                            <input type="checkbox" wire:model.defer="use_test_gateway" checked class="text-blue-600">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumo dos Dados Demo -->
        <div class="bg-gradient-to-r from-orange-50 to-yellow-50 border border-orange-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-orange-800 mb-4">
                <i class="fas fa-chart-pie mr-2"></i>Resumo dos Dados de Demonstração
            </h3>

            <div class="grid md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                    <div class="text-2xl font-bold text-purple-600">{{ $demo_campaigns_count ?? 3 }}</div>
                    <p class="text-sm text-gray-600">Campanhas</p>
                    <p class="text-xs text-purple-600">R$ {{ number_format(($demo_total_amount ?? 50000) * 0.6, 0, ',', '.') }} arrecadado</p>
                </div>

                <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                    <div class="text-2xl font-bold text-indigo-600">{{ $demo_events_count ?? 3 }}</div>
                    <p class="text-sm text-gray-600">Eventos</p>
                    <p class="text-xs text-indigo-600">{{ ($demo_tickets_per_event ?? 50) * ($demo_events_count ?? 3) }} ingressos</p>
                </div>

                <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                    <div class="text-2xl font-bold text-green-600">{{ $demo_users_count ?? 10 }}</div>
                    <p class="text-sm text-gray-600">Usuários</p>
                    <p class="text-xs text-green-600">3 perfis diferentes</p>
                </div>

                <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                    <div class="text-2xl font-bold text-orange-600">{{ (($demo_donations_per_campaign ?? 25) * ($demo_campaigns_count ?? 3)) + (($demo_tickets_per_event ?? 50) * ($demo_events_count ?? 3)) }}</div>
                    <p class="text-sm text-gray-600">Transações</p>
                    <p class="text-xs text-orange-600">R$ {{ number_format($demo_total_amount ?? 50000, 0, ',', '.') }} total</p>
                </div>
            </div>

            <div class="mt-4 flex justify-center">
                <button type="button" wire:click="generateDemoPreview"
                        class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Gerar Preview dos Dados
                </button>
            </div>
        </div>
    </div>

    <!-- Informações sobre dados de teste -->
    <div class="bg-orange-50 border-l-4 border-orange-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-orange-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-orange-800">Sobre os dados de demonstração:</h4>
                <ul class="mt-2 text-sm text-orange-700 list-disc list-inside space-y-1">
                    <li><strong>Finalidade:</strong> Permitir que o cliente explore as funcionalidades</li>
                    <li><strong>Dados fictícios:</strong> Todas as informações são geradas automaticamente</li>
                    <li><strong>Remoção:</strong> Dados demo podem ser limpos a qualquer momento</li>
                    <li><strong>Gateway de teste:</strong> Nenhum pagamento real será processado</li>
                </ul>
            </div>
        </div>
    </div>
</div>
