<!-- Step 6: Criação do Administrador -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-user-shield text-4xl text-blue-500 mb-4"></i>
        <p class="text-gray-600">
            Configure o usuário administrador que terá acesso total a esta aplicação.
        </p>
    </div>

    <!-- Dados do Administrador -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-user mr-2"></i>Dados do Administrador
        </h3>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Nome Completo -->
            <div>
                <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome Completo <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" id="admin_name" wire:model.defer="admin_name"
                           placeholder="João Silva Santos"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('admin_name') border-red-500 @enderror">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                </div>
                @error('admin_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Nome completo do responsável pela administração
                </p>
            </div>

            <!-- E-mail -->
            <div>
                <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">
                    E-mail <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="email" id="admin_email" wire:model.defer="admin_email"
                           placeholder="admin@empresa.com"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('admin_email') border-red-500 @enderror">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                </div>
                @error('admin_email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    E-mail será usado para fazer login no sistema
                </p>
            </div>

            <!-- Telefone -->
            <div>
                <label for="admin_phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Telefone <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" id="admin_phone" wire:model.defer="admin_phone"
                           placeholder="(11) 99999-9999"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('admin_phone') border-red-500 @enderror">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-400"></i>
                    </div>
                </div>
                @error('admin_phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cargo -->
            <div>
                <label for="admin_position" class="block text-sm font-medium text-gray-700 mb-2">
                    Cargo/Função
                </label>
                <div class="relative">
                    <input type="text" id="admin_position" wire:model.defer="admin_position"
                           placeholder="CEO, CTO, Gerente..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-briefcase text-gray-400"></i>
                    </div>
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    Cargo ou função na empresa (opcional)
                </p>
            </div>
        </div>
    </div>

    <!-- Configuração de Senha -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-lock mr-2"></i>Configuração de Acesso
        </h3>

        <div class="space-y-6">
            <!-- Tipo de Senha -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Como definir a senha inicial?
                </label>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="radio" wire:model.defer="password_type" value="generate" class="mr-3" checked>
                        <div class="flex-1">
                            <span class="font-medium text-gray-900">Gerar automaticamente</span>
                            <p class="text-sm text-gray-600">Sistema criará uma senha segura e enviará por e-mail</p>
                        </div>
                        <i class="fas fa-random text-green-500"></i>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" wire:model.defer="password_type" value="custom" class="mr-3">
                        <div class="flex-1">
                            <span class="font-medium text-gray-900">Definir senha personalizada</span>
                            <p class="text-sm text-gray-600">Você escolhe a senha inicial do administrador</p>
                        </div>
                        <i class="fas fa-edit text-blue-500"></i>
                    </label>
                </div>
            </div>

            <!-- Senha Personalizada -->
            <div x-show="$wire.password_type === 'custom'" x-transition class="space-y-4">
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Senha -->
                    <div>
                        <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Senha <span class="text-red-500">*</span>
                        </label>
                        <div class="relative" x-data="{ showPassword: false }">
                            <input :type="showPassword ? 'text' : 'password'"
                                   id="admin_password" wire:model.defer="admin_password"
                                   placeholder="Mínimo 8 caracteres"
                                   class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('admin_password') border-red-500 @enderror">
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"
                                   :class="showPassword ? 'text-gray-600' : 'text-gray-400'"></i>
                            </button>
                        </div>
                        @error('admin_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmação -->
                    <div>
                        <label for="admin_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Senha <span class="text-red-500">*</span>
                        </label>
                        <div class="relative" x-data="{ showPassword: false }">
                            <input :type="showPassword ? 'text' : 'password'"
                                   id="admin_password_confirmation" wire:model.defer="admin_password_confirmation"
                                   placeholder="Digite a senha novamente"
                                   class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('admin_password_confirmation') border-red-500 @enderror">
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"
                                   :class="showPassword ? 'text-gray-600' : 'text-gray-400'"></i>
                            </button>
                        </div>
                        @error('admin_password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Força da Senha -->
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700">Força da senha:</span>
                        <span class="font-medium"
                              :class="{
                                  'text-red-600': $wire.password_strength === 'weak',
                                  'text-yellow-600': $wire.password_strength === 'medium',
                                  'text-green-600': $wire.password_strength === 'strong'
                              }">
                            {{ ucfirst($password_strength ?? 'não avaliada') }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all duration-300"
                             :class="{
                                 'bg-red-500 w-1/3': $wire.password_strength === 'weak',
                                 'bg-yellow-500 w-2/3': $wire.password_strength === 'medium',
                                 'bg-green-500 w-full': $wire.password_strength === 'strong'
                             }"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissões e Recursos -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-shield-alt mr-2"></i>Permissões e Recursos
        </h3>

        <div class="space-y-6">
            <!-- Permissões Administrativas -->
            <div>
                <h4 class="font-medium text-gray-900 mb-4">Permissões Administrativas</h4>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <input type="checkbox" wire:model.defer="admin_permissions" value="manage_users" checked class="mr-3">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900">Gerenciar Usuários</span>
                                <p class="text-sm text-gray-600">Criar, editar e remover usuários</p>
                            </div>
                            <i class="fas fa-users text-blue-500"></i>
                        </label>

                        <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <input type="checkbox" wire:model.defer="admin_permissions" value="manage_settings" checked class="mr-3">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900">Configurações</span>
                                <p class="text-sm text-gray-600">Alterar configurações do sistema</p>
                            </div>
                            <i class="fas fa-cog text-blue-500"></i>
                        </label>

                        <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <input type="checkbox" wire:model.defer="admin_permissions" value="view_analytics" checked class="mr-3">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900">Analytics</span>
                                <p class="text-sm text-gray-600">Visualizar relatórios e estatísticas</p>
                            </div>
                            <i class="fas fa-chart-line text-blue-500"></i>
                        </label>
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <input type="checkbox" wire:model.defer="admin_permissions" value="manage_payments" checked class="mr-3">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900">Pagamentos</span>
                                <p class="text-sm text-gray-600">Configurar métodos de pagamento</p>
                            </div>
                            <i class="fas fa-credit-card text-blue-500"></i>
                        </label>

                        <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <input type="checkbox" wire:model.defer="admin_permissions" value="manage_branding" checked class="mr-3">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900">Branding</span>
                                <p class="text-sm text-gray-600">Alterar logo, cores e textos</p>
                            </div>
                            <i class="fas fa-palette text-blue-500"></i>
                        </label>

                        <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <input type="checkbox" wire:model.defer="admin_permissions" value="full_admin" class="mr-3">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900">Administrador Total</span>
                                <p class="text-sm text-gray-600">Acesso irrestrito ao sistema</p>
                            </div>
                            <i class="fas fa-crown text-yellow-500"></i>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Recursos Habilitados -->
            <div>
                <h4 class="font-medium text-gray-900 mb-4">Recursos Disponíveis</h4>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="p-4 border border-gray-200 rounded-lg text-center">
                        <div class="w-12 h-12 mx-auto mb-3 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bullhorn text-blue-600 text-xl"></i>
                        </div>
                        <h5 class="font-medium text-gray-900">Campanhas</h5>
                        <p class="text-sm text-gray-600 mt-1">Até {{ $max_campaigns }} campanhas</p>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg text-center">
                        <div class="w-12 h-12 mx-auto mb-3 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                        </div>
                        <h5 class="font-medium text-gray-900">Eventos</h5>
                        <p class="text-sm text-gray-600 mt-1">Até {{ $max_events }} eventos</p>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg text-center">
                        <div class="w-12 h-12 mx-auto mb-3 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <h5 class="font-medium text-gray-900">Usuários</h5>
                        <p class="text-sm text-gray-600 mt-1">Até {{ $max_users }} usuários</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notificações Iniciais -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-envelope mr-2"></i>Configurações de Boas-vindas
        </h3>

        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-envelope text-blue-500 text-xl"></i>
                    <div>
                        <p class="font-medium text-gray-900">E-mail de Boas-vindas</p>
                        <p class="text-sm text-gray-600">Enviar e-mail com dados de acesso para o administrador</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.defer="send_welcome_email" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-sms text-green-500 text-xl"></i>
                    <div>
                        <p class="font-medium text-gray-900">SMS de Confirmação</p>
                        <p class="text-sm text-gray-600">Enviar SMS de confirmação com link de acesso</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.defer="send_welcome_sms" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-route text-purple-500 text-xl"></i>
                    <div>
                        <p class="font-medium text-gray-900">Tour Guiado</p>
                        <p class="text-sm text-gray-600">Ativar tour automático no primeiro acesso</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.defer="enable_welcome_tour" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                </label>
            </div>
        </div>
    </div>

    <!-- Preview do Administrador -->
    @if ($admin_name && $admin_email)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-4">
                <i class="fas fa-eye mr-2"></i>Preview do Administrador
            </h3>

            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-shield text-blue-600 text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">{{ $admin_name }}</h4>
                        <p class="text-blue-600">{{ $admin_email }}</p>
                        @if ($admin_position)
                            <p class="text-sm text-gray-600">{{ $admin_position }}</p>
                        @endif
                        @if ($admin_phone)
                            <p class="text-sm text-gray-600">{{ $admin_phone }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="flex flex-wrap gap-1">
                            @foreach($admin_permissions ?? [] as $permission)
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">
                                    {{ ucfirst(str_replace('_', ' ', $permission)) }}
                                </span>
                            @endforeach
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            Senha: {{ $password_type === 'generate' ? 'Será gerada automaticamente' : 'Definida manualmente' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Informações importantes -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-yellow-800">Dicas para configuração do administrador:</h4>
                <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside space-y-1">
                    <li><strong>E-mail:</strong> Use um e-mail corporativo para maior segurança</li>
                    <li><strong>Senha:</strong> Recomendamos gerar automaticamente para maior segurança</li>
                    <li><strong>Permissões:</strong> Todas as opções podem ser alteradas posteriormente</li>
                    <li><strong>Contato:</strong> Mantenha os dados atualizados para recuperação de conta</li>
                </ul>
            </div>
        </div>
    </div>
</div>
