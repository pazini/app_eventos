<!-- Step 5: Primeiro Usuário Admin -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-user-shield text-4xl text-teal-500 mb-4"></i>
        <p class="text-gray-600">
            Configure o primeiro usuário administrador que terá acesso total à aplicação.
        </p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
                <i class="fas fa-user-plus mr-2"></i>Dados do Administrador
            </h3>

            <div class="space-y-6">
                <!-- Nome Completo -->
                <div>
                    <label for="user_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome Completo *
                    </label>
                    <input type="text" id="user_name" wire:model.defer="user_name"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('user_name') border-red-500 @enderror"
                           placeholder="Ex: João Silva">
                    @error('user_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Nome que aparecerá no sistema e assinatura de e-mails.
                    </p>
                </div>

                <!-- E-mail -->
                <div>
                    <label for="user_email" class="block text-sm font-medium text-gray-700 mb-2">
                        E-mail de Login *
                    </label>
                    <input type="email" id="user_email" wire:model.defer="user_email"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('user_email') border-red-500 @enderror"
                           placeholder="admin@{{ $domain_primary ?: 'suaempresa.com' }}">
                    @error('user_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        E-mail usado para login no sistema. Deve ser único no sistema.
                    </p>
                </div>

                <!-- Senha -->
                <div>
                    <label for="user_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Senha *
                    </label>
                    <div class="relative">
                        <input type="password" id="user_password" wire:model.defer="user_password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('user_password') border-red-500 @enderror"
                               placeholder="Digite uma senha segura">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                    @error('user_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmação de Senha -->
                <div>
                    <label for="user_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmar Senha *
                    </label>
                    <div class="relative">
                        <input type="password" id="user_password_confirmation" wire:model.defer="user_password_confirmation"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('user_password_confirmation') border-red-500 @enderror"
                               placeholder="Repita a senha">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                    @error('user_password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Configurações de Limites -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
                <i class="fas fa-sliders-h mr-2"></i>Limites da Aplicação
            </h3>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Data de Expiração -->
                <div>
                    <label for="app_limit_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Data de Expiração
                    </label>
                    <input type="date" id="app_limit_date" wire:model.defer="app_limit_date"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('app_limit_date') border-red-500 @enderror">
                    @error('app_limit_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Data até a qual a aplicação ficará ativa.
                    </p>
                </div>

                <!-- Storage -->
                <div>
                    <label for="storage_mb" class="block text-sm font-medium text-gray-700 mb-2">
                        Storage (MB)
                    </label>
                    <select wire:model.defer="storage_mb"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        <option value="1024">1 GB (1024 MB)</option>
                        <option value="2048">2 GB (2048 MB)</option>
                        <option value="5120">5 GB (5120 MB)</option>
                        <option value="10240">10 GB (10240 MB)</option>
                        <option value="20480">20 GB (20480 MB)</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">
                        Limite de armazenamento para uploads.
                    </p>
                </div>

                <!-- Campanhas por Cliente -->
                <div>
                    <label for="campaigns_per_customer" class="block text-sm font-medium text-gray-700 mb-2">
                        Campanhas por Cliente
                    </label>
                    <select wire:model.defer="campaigns_per_customer"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        <option value="10">10 campanhas</option>
                        <option value="25">25 campanhas</option>
                        <option value="50">50 campanhas</option>
                        <option value="100">100 campanhas</option>
                        <option value="999999">Ilimitado</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">
                        Máximo de campanhas por cliente.
                    </p>
                </div>

                <!-- Eventos por Cliente -->
                <div>
                    <label for="events_per_customer" class="block text-sm font-medium text-gray-700 mb-2">
                        Eventos por Cliente
                    </label>
                    <select wire:model.defer="events_per_customer"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        <option value="5">5 eventos</option>
                        <option value="15">15 eventos</option>
                        <option value="50">50 eventos</option>
                        <option value="100">100 eventos</option>
                        <option value="999999">Ilimitado</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">
                        Máximo de eventos por cliente.
                    </p>
                </div>
            </div>
        </div>

        <!-- Preview do Usuário -->
        @if ($user_name || $user_email)
            <div class="bg-teal-50 rounded-lg p-6 border border-teal-200 mt-6">
                <h4 class="font-semibold text-teal-800 mb-4 flex items-center">
                    <i class="fas fa-user-check text-teal-600 mr-2"></i>
                    Preview do Administrador
                </h4>

                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-teal-500 rounded-full flex items-center justify-center text-white text-xl font-bold">
                        {{ $user_name ? strtoupper(substr($user_name, 0, 1)) : '?' }}
                    </div>
                    <div>
                        <h5 class="text-lg font-semibold text-teal-900">{{ $user_name ?: 'Nome do Administrador' }}</h5>
                        <p class="text-teal-700">{{ $user_email ?: 'email@exemplo.com' }}</p>
                        <div class="flex items-center mt-2">
                            <span class="bg-teal-600 text-white px-2 py-1 rounded text-xs font-medium">ADMIN</span>
                            <span class="text-teal-600 text-sm ml-2">• Acesso total à aplicação</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Permissões do Admin -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mt-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-blue-800">Permissões do Administrador:</h4>
                    <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                        <li>Acesso completo a todos os módulos habilitados</li>
                        <li>Gestão de clientes, usuários e configurações</li>
                        <li>Visualização de relatórios e analytics</li>
                        <li>Configuração de textos e branding personalizado</li>
                        <li>Gestão de limites e funcionalidades</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Requisitos de Senha -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mt-6">
            <h4 class="font-medium text-gray-800 mb-2">Requisitos de Senha:</h4>
            <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                <li>Mínimo de 8 caracteres</li>
                <li>Recomenda-se incluir letras maiúsculas e minúsculas</li>
                <li>Incluir números e símbolos para maior segurança</li>
                <li>Evitar informações pessoais óbvias</li>
            </ul>
        </div>
    </div>
</div>
