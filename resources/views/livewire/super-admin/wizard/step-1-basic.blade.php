<!-- Step 1: Informações Básicas -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-info-circle text-4xl text-blue-500 mb-4"></i>
        <p class="text-gray-600">
            Vamos começar com as informações básicas da sua nova aplicação white label.
        </p>
    </div>

    <div class="grid md:grid-cols-1 gap-6">
        <!-- Nome da Aplicação -->
        <div>
            <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">
                Nome da Aplicação *
            </label>
            <input type="text" id="app_name" wire:model.defer="app_name"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('app_name') border-red-500 @enderror"
                   placeholder="Ex: EventoTech, FestasJá, MeuEvento">
            @error('app_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">
                Este será o nome exibido em toda a aplicação e nos e-mails.
            </p>
        </div>

        <!-- Descrição -->
        <div>
            <label for="app_description" class="block text-sm font-medium text-gray-700 mb-2">
                Descrição (Opcional)
            </label>
            <textarea id="app_description" wire:model.defer="app_description" rows="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('app_description') border-red-500 @enderror"
                      placeholder="Breve descrição sobre a aplicação e seu propósito..."></textarea>
            @error('app_description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">
                Será usada nas meta tags para SEO e descrições gerais.
            </p>
        </div>

        <!-- E-mail do Administrador -->
        <div>
            <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">
                E-mail do Administrador Principal *
            </label>
            <input type="email" id="admin_email" wire:model.defer="admin_email"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('admin_email') border-red-500 @enderror"
                   placeholder="admin@suaempresa.com">
            @error('admin_email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">
                Este e-mail será usado para configurações importantes e como e-mail de reply-to.
            </p>
        </div>
    </div>

    <!-- Informações adicionais -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mt-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800">Dicas:</h4>
                <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                    <li>Escolha um nome único e memorável para sua aplicação</li>
                    <li>A descrição será útil para SEO e identificação interna</li>
                    <li>O e-mail do admin deve ser válido - receberá notificações importantes</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Preview Card -->
    @if (isset($app_name) ? $app_name : null)
        <div class="bg-gray-50 rounded-lg p-4 border-2 border-dashed border-gray-200">
            <h4 class="font-medium text-gray-800 mb-2">Preview:</h4>
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                    {{ strtoupper(substr(isset($app_name) ? $app_name : '', 0, 1)) }}
                </div>
                <div>
                    <h5 class="font-semibold text-gray-900">{{ $app_name ?? '' }}</h5>
                    @if (isset($app_description) && $app_description)
                        <p class="text-sm text-gray-600">{{ Str::limit($app_description, 50) }}</p>
                    @endif
                    @if (isset($admin_email) && $admin_email)
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-envelope mr-1"></i>{{ $admin_email }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
