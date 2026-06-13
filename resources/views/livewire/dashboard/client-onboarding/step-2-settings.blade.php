<!-- Step 2: Configurações Personalizadas -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-cog text-4xl text-purple-500 mb-4"></i>
        <p class="text-gray-600">
            Configure preferências e limites específicos para este cliente.
        </p>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        <!-- Configurações Gerais -->
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                <i class="fas fa-globe mr-2"></i>Configurações Gerais
            </h3>

            <!-- Timezone -->
            <div>
                <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">
                    Fuso Horário
                </label>
                <select wire:model.defer="timezone" id="timezone"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                    <option value="America/Sao_Paulo">Brasília (UTC-3)</option>
                    <option value="America/Manaus">Manaus (UTC-4)</option>
                    <option value="America/Rio_Branco">Rio Branco (UTC-5)</option>
                    <option value="America/Noronha">Fernando de Noronha (UTC-2)</option>
                </select>
                <p class="mt-1 text-sm text-gray-500">
                    Usado para exibir datas e horários corretamente.
                </p>
            </div>

            <!-- Moeda -->
            <div>
                <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                    Moeda Padrão
                </label>
                <select wire:model.defer="currency" id="currency"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                    <option value="BRL">Real Brasileiro (R$)</option>
                    <option value="USD">Dólar Americano (US$)</option>
                    <option value="EUR">Euro (€)</option>
                </select>
                <p class="mt-1 text-sm text-gray-500">
                    Moeda usada em campanhas, eventos e relatórios.
                </p>
            </div>
        </div>

        <!-- Notificações -->
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                <i class="fas fa-bell mr-2"></i>Preferências de Notificação
            </h3>

            <div class="space-y-4">
                <!-- E-mail -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-blue-500 text-xl mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">E-mail</p>
                            <p class="text-sm text-gray-600">Receber notificações por e-mail</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.defer="notification_email" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>

                <!-- SMS -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-sms text-green-500 text-xl mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">SMS</p>
                            <p class="text-sm text-gray-600">Receber notificações por SMS</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.defer="notification_sms" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>

                <!-- Push -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-mobile-alt text-orange-500 text-xl mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">Push Notifications</p>
                            <p class="text-sm text-gray-600">Notificações no navegador/app</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.defer="notification_push" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Limites do Cliente -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-chart-line mr-2"></i>Limites e Quotas
        </h3>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Campanhas -->
            <div>
                <label for="max_campaigns" class="block text-sm font-medium text-gray-700 mb-2">
                    Máximo de Campanhas
                </label>
                <div class="relative">
                    <input type="number" id="max_campaigns" wire:model.defer="max_campaigns" min="1" max="999"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors @error('max_campaigns') border-red-500 @enderror">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-bullhorn text-gray-400"></i>
                    </div>
                </div>
                @error('max_campaigns')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Campanhas simultâneas permitidas
                </p>
            </div>

            <!-- Eventos -->
            <div>
                <label for="max_events" class="block text-sm font-medium text-gray-700 mb-2">
                    Máximo de Eventos
                </label>
                <div class="relative">
                    <input type="number" id="max_events" wire:model.defer="max_events" min="1" max="999"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors @error('max_events') border-red-500 @enderror">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                    </div>
                </div>
                @error('max_events')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Eventos simultâneos permitidos
                </p>
            </div>

            <!-- Usuários -->
            <div>
                <label for="max_users" class="block text-sm font-medium text-gray-700 mb-2">
                    Máximo de Usuários
                </label>
                <div class="relative">
                    <input type="number" id="max_users" wire:model.defer="max_users" min="1" max="100"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors @error('max_users') border-red-500 @enderror">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-users text-gray-400"></i>
                    </div>
                </div>
                @error('max_users')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Usuários que podem acessar
                </p>
            </div>
        </div>

        <!-- Preview dos Limites -->
        <div class="mt-6 p-4 bg-purple-50 rounded-lg border border-purple-200">
            <h4 class="font-medium text-purple-800 mb-3">Resumo dos Limites Configurados:</h4>
            <div class="grid md:grid-cols-3 gap-4 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-purple-700">Campanhas:</span>
                    <span class="font-medium text-purple-900">{{ $max_campaigns }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-purple-700">Eventos:</span>
                    <span class="font-medium text-purple-900">{{ $max_events }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-purple-700">Usuários:</span>
                    <span class="font-medium text-purple-900">{{ $max_users }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações sobre configurações -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800">Sobre as configurações:</h4>
                <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                    <li>Todas as configurações podem ser ajustadas posteriormente</li>
                    <li>As notificações respeitam as preferências individuais dos usuários</li>
                    <li>Os limites são baseados nas configurações do plano da aplicação</li>
                    <li>O fuso horário afeta relatórios e agendamentos</li>
                </ul>
            </div>
        </div>
    </div>
</div>
