<!-- Step 8: Tour Guiado -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-route text-4xl text-green-500 mb-4"></i>
        <p class="text-gray-600">
            Configure o tour guiado que será apresentado aos usuários no primeiro acesso.
        </p>
    </div>

    <!-- Status do Tour -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-toggle-on mr-2"></i>Configuração do Tour
        </h3>

        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <i class="fas fa-route text-green-500 text-xl"></i>
                <div>
                    <p class="font-medium text-gray-900">Ativar Tour Guiado</p>
                    <p class="text-sm text-gray-600">Tour automático será exibido no primeiro login dos usuários</p>
                </div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model.defer="tour_enabled" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
            </label>
        </div>
    </div>

    <!-- Configuração dos Steps do Tour -->
    <div x-show="$wire.tour_enabled" x-transition class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
                <i class="fas fa-list-ol mr-2"></i>Steps do Tour
            </h3>

            <div class="space-y-6">
                <!-- Step 1: Dashboard -->
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-green-600 font-medium">1</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Dashboard</h4>
                                <p class="text-sm text-gray-600">Visão geral do sistema</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.defer="tour_steps.dashboard" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="tour_dashboard_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Título do Step
                            </label>
                            <input type="text" id="tour_dashboard_title" wire:model.defer="tour_texts.dashboard.title"
                                   placeholder="Bem-vindo ao Dashboard!"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label for="tour_dashboard_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Descrição
                            </label>
                            <textarea id="tour_dashboard_description" wire:model.defer="tour_texts.dashboard.description" rows="2"
                                      placeholder="Aqui você encontra um resumo de todas as suas atividades..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Navegação -->
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-medium">2</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Menu de Navegação</h4>
                                <p class="text-sm text-gray-600">Como navegar pelo sistema</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.defer="tour_steps.navigation" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <input type="text" wire:model.defer="tour_texts.navigation.title"
                                   placeholder="Menu de Navegação"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <textarea wire:model.defer="tour_texts.navigation.description" rows="2"
                                      placeholder="Use o menu lateral para acessar diferentes seções..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Campanhas/Eventos -->
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <span class="text-purple-600 font-medium">3</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Campanhas & Eventos</h4>
                                <p class="text-sm text-gray-600">Funcionalidades principais</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.defer="tour_steps.features" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <input type="text" wire:model.defer="tour_texts.features.title"
                                   placeholder="Principais Funcionalidades"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <textarea wire:model.defer="tour_texts.features.description" rows="2"
                                      placeholder="Crie campanhas, organize eventos e gerencie pagamentos..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Configurações -->
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                <span class="text-orange-600 font-medium">4</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Configurações</h4>
                                <p class="text-sm text-gray-600">Personalize sua experiência</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.defer="tour_steps.settings" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                        </label>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <input type="text" wire:model.defer="tour_texts.settings.title"
                                   placeholder="Configurações"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <textarea wire:model.defer="tour_texts.settings.description" rows="2"
                                      placeholder="Ajuste configurações de perfil, notificações e preferências..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Suporte -->
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <span class="text-red-600 font-medium">5</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Suporte e Ajuda</h4>
                                <p class="text-sm text-gray-600">Como obter ajuda</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.defer="tour_steps.support" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                        </label>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <input type="text" wire:model.defer="tour_texts.support.title"
                                   placeholder="Precisa de Ajuda?"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                        </div>
                        <div>
                            <textarea wire:model.defer="tour_texts.support.description" rows="2"
                                      placeholder="Acesse nossa central de ajuda ou entre em contato conosco..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configurações Avançadas do Tour -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
                <i class="fas fa-cogs mr-2"></i>Configurações Avançadas
            </h3>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Configurações de Exibição -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900">Comportamento do Tour</h4>

                    <div class="space-y-3">
                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <span class="font-medium text-gray-900">Exibir automaticamente</span>
                                <p class="text-sm text-gray-600">Tour inicia automaticamente no primeiro login</p>
                            </div>
                            <input type="checkbox" wire:model.defer="tour_auto_start" checked class="text-green-600">
                        </label>

                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <span class="font-medium text-gray-900">Permitir pular</span>
                                <p class="text-sm text-gray-600">Usuários podem pular o tour</p>
                            </div>
                            <input type="checkbox" wire:model.defer="tour_skippable" checked class="text-blue-600">
                        </label>

                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <span class="font-medium text-gray-900">Permitir repetir</span>
                                <p class="text-sm text-gray-600">Usuários podem reexibir o tour</p>
                            </div>
                            <input type="checkbox" wire:model.defer="tour_repeatable" checked class="text-purple-600">
                        </label>
                    </div>
                </div>

                <!-- Personalização Visual -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900">Estilo Visual</h4>

                    <div class="space-y-4">
                        <div>
                            <label for="tour_theme_color" class="block text-sm font-medium text-gray-700 mb-2">
                                Cor do Tema
                            </label>
                            <div class="flex space-x-3">
                                <input type="color" id="tour_theme_color" wire:model.defer="tour_theme_color"
                                       value="#3B82F6"
                                       class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text" wire:model.defer="tour_theme_color"
                                       placeholder="#3B82F6"
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label for="tour_position" class="block text-sm font-medium text-gray-700 mb-2">
                                Posição dos Tooltips
                            </label>
                            <select id="tour_position" wire:model.defer="tour_position"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="auto">Automática</option>
                                <option value="top">Acima</option>
                                <option value="bottom">Abaixo</option>
                                <option value="left">Esquerda</option>
                                <option value="right">Direita</option>
                            </select>
                        </div>

                        <div>
                            <label for="tour_animation" class="block text-sm font-medium text-gray-700 mb-2">
                                Animação
                            </label>
                            <select id="tour_animation" wire:model.defer="tour_animation"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="fade">Fade</option>
                                <option value="slide">Slide</option>
                                <option value="bounce">Bounce</option>
                                <option value="none">Sem animação</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview do Tour -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-green-800 mb-4">
                <i class="fas fa-play mr-2"></i>Preview do Tour
            </h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">Tour Configurado</h4>
                        <p class="text-sm text-gray-600">
                            {{ count(array_filter($tour_steps ?? [])) }} steps habilitados
                        </p>
                    </div>
                    <button type="button" wire:click="previewTour"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>Visualizar
                    </button>
                </div>

                <!-- Steps Resumo -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    @if($tour_steps['dashboard'] ?? false)
                        <div class="p-3 bg-white rounded border-l-4 border-green-500">
                            <p class="font-medium text-sm text-green-700">Dashboard</p>
                            <p class="text-xs text-gray-600">{{ $tour_texts['dashboard']['title'] ?? 'Bem-vindo!' }}</p>
                        </div>
                    @endif

                    @if($tour_steps['navigation'] ?? false)
                        <div class="p-3 bg-white rounded border-l-4 border-blue-500">
                            <p class="font-medium text-sm text-blue-700">Navegação</p>
                            <p class="text-xs text-gray-600">{{ $tour_texts['navigation']['title'] ?? 'Menu' }}</p>
                        </div>
                    @endif

                    @if($tour_steps['features'] ?? false)
                        <div class="p-3 bg-white rounded border-l-4 border-purple-500">
                            <p class="font-medium text-sm text-purple-700">Funcionalidades</p>
                            <p class="text-xs text-gray-600">{{ $tour_texts['features']['title'] ?? 'Features' }}</p>
                        </div>
                    @endif

                    @if($tour_steps['settings'] ?? false)
                        <div class="p-3 bg-white rounded border-l-4 border-orange-500">
                            <p class="font-medium text-sm text-orange-700">Configurações</p>
                            <p class="text-xs text-gray-600">{{ $tour_texts['settings']['title'] ?? 'Config' }}</p>
                        </div>
                    @endif

                    @if($tour_steps['support'] ?? false)
                        <div class="p-3 bg-white rounded border-l-4 border-red-500">
                            <p class="font-medium text-sm text-red-700">Suporte</p>
                            <p class="text-xs text-gray-600">{{ $tour_texts['support']['title'] ?? 'Ajuda' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informações sobre o tour -->
    <div class="bg-green-50 border-l-4 border-green-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-green-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-green-800">Sobre o tour guiado:</h4>
                <ul class="mt-2 text-sm text-green-700 list-disc list-inside space-y-1">
                    <li>O tour é exibido automaticamente no primeiro acesso do usuário</li>
                    <li>Usuários podem reativar o tour a qualquer momento no menu ajuda</li>
                    <li>Cada step pode ser habilitado/desabilitado individualmente</li>
                    <li>Os textos podem ser personalizados para sua marca</li>
                </ul>
            </div>
        </div>
    </div>
</div>
