<div class="p-6">
    <form wire:submit.prevent="saveSettings">
        <div class="space-y-8">
            {{-- Módulos da Aplicação --}}
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Módulos Habilitados</h3>
                <p class="text-sm text-gray-600 mb-6">Selecione quais funcionalidades estarão disponíveis nesta aplicação.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Campanhas --}}
                    <div class="relative flex items-start p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-colors">
                        <div class="flex items-center h-5">
                            <input type="checkbox" wire:change="updateFeatures('campaigns', $event.target.checked)"
                                   @if($features['campaigns'] ?? false) checked @endif
                                   id="feature_campaigns"
                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="feature_campaigns" class="font-medium text-gray-900 cursor-pointer">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Campanhas
                                </div>
                            </label>
                            <p class="text-gray-500">Campanhas de arrecadação e crowdfunding</p>
                        </div>
                    </div>

                    {{-- Eventos --}}
                    <div class="relative flex items-start p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-colors">
                        <div class="flex items-center h-5">
                            <input type="checkbox" wire:change="updateFeatures('events', $event.target.checked)"
                                   @if($features['events'] ?? false) checked @endif
                                   id="feature_events"
                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="feature_events" class="font-medium text-gray-900 cursor-pointer">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Eventos
                                </div>
                            </label>
                            <p class="text-gray-500">Gestão de eventos e venda de ingressos</p>
                        </div>
                    </div>

                    {{-- Assinaturas --}}
                    <div class="relative flex items-start p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-colors">
                        <div class="flex items-center h-5">
                            <input type="checkbox" wire:change="updateFeatures('subscriptions', $event.target.checked)"
                                   @if($features['subscriptions'] ?? false) checked @endif
                                   id="feature_subscriptions"
                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="feature_subscriptions" class="font-medium text-gray-900 cursor-pointer">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6M6 18a6 6 0 0110.95-3M18 6a6 6 0 00-10.95 3"></path>
                                    </svg>
                                    Assinaturas
                                </div>
                            </label>
                            <p class="text-gray-500">Planos recorrentes e gestão de assinantes</p>
                        </div>
                    </div>

                    {{-- Analytics --}}
                    <div class="relative flex items-start p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-colors">
                        <div class="flex items-center h-5">
                            <input type="checkbox" wire:change="updateFeatures('analytics', $event.target.checked)"
                                   @if($features['analytics'] ?? false) checked @endif
                                   id="feature_analytics"
                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="feature_analytics" class="font-medium text-gray-900 cursor-pointer">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Analytics
                                </div>
                            </label>
                            <p class="text-gray-500">Relatórios e análises de performance</p>
                        </div>
                    </div>

                    {{-- Relatórios --}}
                    <div class="relative flex items-start p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-colors">
                        <div class="flex items-center h-5">
                            <input type="checkbox" wire:change="updateFeatures('reports', $event.target.checked)"
                                   @if($features['reports'] ?? false) checked @endif
                                   id="feature_reports"
                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="feature_reports" class="font-medium text-gray-900 cursor-pointer">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Relatórios
                                </div>
                            </label>
                            <p class="text-gray-500">Exportação de relatórios avançados</p>
                        </div>
                    </div>

                    {{-- Integrações --}}
                    <div class="relative flex items-start p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-colors">
                        <div class="flex items-center h-5">
                            <input type="checkbox" wire:change="updateFeatures('integrations', $event.target.checked)"
                                   @if($features['integrations'] ?? false) checked @endif
                                   id="feature_integrations"
                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="feature_integrations" class="font-medium text-gray-900 cursor-pointer">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    Integrações
                                </div>
                            </label>
                            <p class="text-gray-500">APIs e integrações com terceiros</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Configurações de E-mail --}}
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações de E-mail</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="email_from_name" class="block text-sm font-medium text-gray-700">Nome do Remetente</label>
                        <input type="text" wire:model.defer="email_from_name" id="email_from_name"
                               class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="Ex: ProEventPay">
                    </div>

                    <div>
                        <label for="email_from_address" class="block text-sm font-medium text-gray-700">E-mail Remetente</label>
                        <input type="email" wire:model.defer="email_from_address" id="email_from_address"
                               class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="Ex: noreply@proeventpay.com.br">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="email_reply_to" class="block text-sm font-medium text-gray-700">E-mail de Resposta</label>
                        <input type="email" wire:model.defer="email_reply_to" id="email_reply_to"
                               class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="Ex: contato@proeventpay.com">
                    </div>
                </div>
            </div>

            {{-- SEO e Meta Tags --}}
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">SEO e Meta Tags</h3>
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700">Título SEO</label>
                        <input type="text" wire:model.defer="meta_title" id="meta_title"
                               class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="Ex: ProEventPay - Plataforma de Eventos">
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700">Descrição SEO</label>
                        <textarea wire:model.defer="meta_description" id="meta_description" rows="3"
                                  class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                  placeholder="Descrição que aparecerá nos resultados de busca..."></textarea>
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700">Palavras-chave</label>
                        <input type="text" wire:model.defer="meta_keywords" id="meta_keywords"
                               class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="eventos, ingressos, campanhas, fundraising">
                        <p class="mt-1 text-sm text-gray-500">Separadas por vírgula</p>
                    </div>
                </div>
            </div>

            {{-- Limites da Aplicação --}}
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Limites da Aplicação</h3>
                <p class="text-sm text-gray-600 mb-6">Defina os limites de uso para esta aplicação.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Storage --}}
                    <div>
                        <label for="limit_storage" class="block text-sm font-medium text-gray-700">Storage (MB)</label>
                        <input type="number" wire:model.defer="limits.storage_mb" id="limit_storage" min="1" max="100000"
                               class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="5120">
                        <p class="mt-1 text-xs text-gray-500">Limite de armazenamento</p>
                    </div>

                    {{-- Campanhas por cliente --}}
                    <div>
                        <label for="limit_campaigns" class="block text-sm font-medium text-gray-700">Campanhas/Cliente</label>
                        <input type="number" wire:model.defer="limits.campaigns_per_customer" id="limit_campaigns" min="1" max="1000"
                               class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="50">
                        <p class="mt-1 text-xs text-gray-500">Limite por cliente</p>
                    </div>

                    {{-- Eventos por cliente --}}
                    <div>
                        <label for="limit_events" class="block text-sm font-medium text-gray-700">Eventos/Cliente</label>
                        <input type="number" wire:model.defer="limits.events_per_customer" id="limit_events" min="1" max="1000"
                               class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="50">
                        <p class="mt-1 text-xs text-gray-500">Limite por cliente</p>
                    </div>

                    {{-- Usuários por cliente --}}
                    <div>
                        <label for="limit_users" class="block text-sm font-medium text-gray-700">Usuários/Cliente</label>
                        <input type="number" wire:model.defer="limits.users_per_customer" id="limit_users" min="1" max="100"
                               class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="10">
                        <p class="mt-1 text-xs text-gray-500">Limite por cliente</p>
                    </div>
                </div>

                {{-- Preview dos limites --}}
                <div class="mt-6 bg-white rounded-lg p-4 border border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Resumo dos Limites</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div class="text-center">
                            <div class="text-lg font-semibold text-blue-600">{{ number_format($limits['storage_mb'] ?? 5120) }} MB</div>
                            <div class="text-gray-500">Storage Total</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-semibold text-green-600">{{ number_format($limits['campaigns_per_customer'] ?? 50) }}</div>
                            <div class="text-gray-500">Campanhas/Cliente</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-semibold text-purple-600">{{ number_format($limits['events_per_customer'] ?? 50) }}</div>
                            <div class="text-gray-500">Eventos/Cliente</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-semibold text-orange-600">{{ number_format($limits['users_per_customer'] ?? 10) }}</div>
                            <div class="text-gray-500">Usuários/Cliente</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Erros --}}
        @error('settings')
            <div class="mt-6 bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ $message }}</p>
                    </div>
                </div>
            </div>
        @enderror

        {{-- Ações da aplicação --}}
        <div class="mt-8 bg-white border border-gray-200 rounded-lg p-4">
            <div class="w-full flex justify-between items-center gap-4">
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $app_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700' }}">
                            {{ $app_active ? 'Aplicação ativa' : 'Aplicação inativa' }}
                        </span>
                        <span class="text-sm text-gray-600 hidden sm:inline">Defina o status antes de salvar.</span>
                    </div>

                    {{-- Toggle de ativação --}}
                </div>

                {{-- Botões de ação --}}
                <div class="flex flex-wrap justify-end gap-3 items-center">
                    <button wire:click="toggleAppActive"
                            wire:confirm="Confirma alteração do status da aplicação?"
                            class="inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md
                            {{ $app_active
                                ? 'border-red-300 text-red-700 bg-red-50 hover:bg-red-100'
                                : 'border-green-300 text-green-700 bg-green-50 hover:bg-green-100' }}
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        @if($app_active)
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Desativar
                        @else
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Ativar
                        @endif
                    </button>
                    <button type="button" wire:click="loadAppData"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg wire:loading wire:target="saveSettings" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="saveSettings">Salvar Configurações</span>
                        <span wire:loading wire:target="saveSettings">Salvando...</span>
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

{{-- Exclusão da aplicação --}}
<div class="mt-10">
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-gray-900">Excluir Aplicação White Label</h3>
                <p class="text-sm text-gray-600">Operação irreversível. Só é permitido quando não houver dados vinculados e a aplicação esteja inativa.</p>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-sm">
                    <div class="p-3 rounded-md border bg-gray-50">
                        <div class="text-gray-500">Clientes</div>
                        <div class="font-semibold text-gray-900">{{ $deleteSummary['customers'] ?? 0 }}</div>
                    </div>
                    <div class="p-3 rounded-md border bg-gray-50">
                        <div class="text-gray-500">Gateways</div>
                        <div class="font-semibold text-gray-900">{{ $deleteSummary['gateways'] ?? 0 }}</div>
                    </div>
                    <div class="p-3 rounded-md border bg-gray-50">
                        <div class="text-gray-500">Campanhas</div>
                        <div class="font-semibold text-gray-900">{{ $deleteSummary['campaigns'] ?? 0 }}</div>
                    </div>
                    <div class="p-3 rounded-md border bg-gray-50">
                        <div class="text-gray-500">Eventos</div>
                        <div class="font-semibold text-gray-900">{{ $deleteSummary['events'] ?? 0 }}</div>
                    </div>
                </div>

                @if($deleteBlocked || $app_active)
                    <div class="text-sm text-red-600 flex items-start space-x-2">
                        <svg class="h-4 w-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Remova os vínculos e garanta que a aplicação esteja desativada para liberar a exclusão.</span>
                    </div>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <button type="button"
                        wire:click="refreshDeleteSummary"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Atualizar checagem
                </button>

                <button type="button"
                        wire:click="openDeleteModal"
                        @if($deleteBlocked || $app_active) disabled @endif
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-semibold rounded-md text-white
                        {{ ($deleteBlocked || $app_active) ? 'bg-red-300 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700' }}">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m-4 0h14"></path>
                    </svg>
                    Excluir Aplicação
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal de confirmação de exclusão --}}
@if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-gray-900 opacity-50"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-xl w-full mx-4 p-6">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-red-100 text-red-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m2-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"></path>
                        </svg>
                    </span>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Excluir aplicação?</h3>
                    <p class="mt-1 text-sm text-gray-600">Esta ação não pode ser desfeita, se tem certeza clique em Confirmar.</p>
                </div>
            </div>
            <div>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-3 text-sm">
                    <div class="p-3 rounded border bg-gray-50">
                        <div class="text-gray-500">Clientes</div>
                        <div class="font-semibold text-gray-900">{{ $deleteSummary['customers'] ?? 0 }}</div>
                    </div>
                    <div class="p-3 rounded border bg-gray-50">
                        <div class="text-gray-500">Gateways</div>
                        <div class="font-semibold text-gray-900">{{ $deleteSummary['gateways'] ?? 0 }}</div>
                    </div>
                    <div class="p-3 rounded border bg-gray-50">
                        <div class="text-gray-500">Campanhas</div>
                        <div class="font-semibold text-gray-900">{{ $deleteSummary['campaigns'] ?? 0 }}</div>
                    </div>
                    <div class="p-3 rounded border bg-gray-50">
                        <div class="text-gray-500">Eventos</div>
                        <div class="font-semibold text-gray-900">{{ $deleteSummary['events'] ?? 0 }}</div>
                    </div>
                </div>

                @if($deleteBlocked)
                    <p class="mt-3 text-sm text-red-600">Exclusão bloqueada: existem registros vinculados. Remova-os antes de prosseguir.</p>
                @endif
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" wire:click="closeDeleteModal"
                        class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="button"
                        wire:click="deleteApp"
                        @if($deleteBlocked || $app_active) disabled @endif
                        class="inline-flex justify-center rounded-md px-4 py-2 text-sm font-semibold text-white
                        {{ ($deleteBlocked || $app_active) ? 'bg-red-300 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700' }}">
                    Confirmar exclusão
                </button>
            </div>
        </div>
    </div>
@endif
