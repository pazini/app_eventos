{{-- Step 5: Confirmação e Primeiro Admin --}}
<div class="space-y-6">
    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
        <div class="flex">
            <svg class="h-5 w-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-blue-800">Quase pronto!</h3>
                <p class="text-sm text-blue-700 mt-1">
                    Revise as configurações abaixo e crie o primeiro usuário administrador para acessar a nova aplicação.
                </p>
            </div>
        </div>
    </div>

    {{-- Resumo da Aplicação --}}
    <div class="bg-gray-50 rounded-md p-4 space-y-3">
        <h4 class="font-semibold text-gray-900">Resumo da Aplicação</h4>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div>
                <span class="text-gray-500">Nome:</span>
                <span class="ml-2 font-medium">{{ $app_name }}</span>
            </div>
            <div>
                <span class="text-gray-500">Domínio:</span>
                <span class="ml-2 font-medium">{{ $domain_primary }}</span>
            </div>
            <div>
                <span class="text-gray-500">Status:</span>
                <span class="ml-2">
                    <span class="px-2 py-1 rounded-full text-xs {{ $app_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $app_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </span>
            </div>
            <div>
                <span class="text-gray-500">Limite Storage:</span>
                <span class="ml-2 font-medium">{{ $storage_limit_mb }} MB</span>
            </div>
        </div>
    </div>

    {{-- Checkbox para criar admin --}}
    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input wire:model="create_first_admin"
                   type="checkbox"
                   id="create_first_admin"
                   class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
        </div>
        <div class="ml-3">
            <label for="create_first_admin" class="font-medium text-gray-700">
                Criar primeiro usuário administrador
            </label>
            <p class="text-sm text-gray-500">
                Recomendado: Crie o usuário admin para acessar imediatamente a nova aplicação
            </p>
        </div>
    </div>

    {{-- Formulário do Admin (condicional) --}}
    @if ($create_first_admin)
        <div class="bg-white border border-gray-200 rounded-md p-4 space-y-4">
            <h4 class="font-semibold text-gray-900">Dados do Administrador</h4>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nome Completo <span class="text-red-500">*</span>
                </label>
                <input wire:model="admin_name"
                       type="text"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Ex: João da Silva">
                @error('admin_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    E-mail <span class="text-red-500">*</span>
                </label>
                <input wire:model="admin_email"
                       type="email"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="admin@exemplo.com">
                @error('admin_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Senha <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="admin_password"
                           type="password"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Mínimo 6 caracteres">
                    @error('admin_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Confirmar Senha <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="admin_password_confirmation"
                           type="password"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Repita a senha">
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 text-sm text-yellow-800">
                <div class="flex">
                    <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <strong>Importante:</strong> Guarde essas credenciais em local seguro. Você precisará delas para fazer o primeiro login.
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Informações Importantes --}}
    <div class="bg-green-50 border border-green-200 rounded-md p-4">
        <h4 class="font-semibold text-green-900 mb-2">O que será criado:</h4>
        <ul class="space-y-1 text-sm text-green-800">
            <li class="flex items-start">
                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Aplicação White Label</strong> com todas as configurações definidas</span>
            </li>
            <li class="flex items-start">
                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Customer Padrão</strong> com o nome "{{ $app_name }}" para gerenciar eventos e campanhas</span>
            </li>
            @if ($create_first_admin)
                <li class="flex items-start">
                    <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Usuário Administrador</strong> com acesso total à aplicação e ao customer padrão</span>
                </li>
            @endif
            <li class="flex items-start">
                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Estrutura de Diretórios</strong> para armazenamento de arquivos</span>
            </li>
        </ul>
    </div>
</div>
