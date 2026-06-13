<div class="p-6">
    <form wire:submit.prevent="saveBasicInfo">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            {{-- Nome da aplicação --}}
            <div>
                <label for="app_name" class="block text-sm font-medium text-gray-700">Nome da Aplicação</label>
                <div class="mt-1">
                    <input type="text" wire:model.defer="app_name" id="app_name"
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                           placeholder="Ex: ProEventPay">
                </div>
                @error('basic') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Domínio principal --}}
            <div>
                <label for="domain_primary" class="block text-sm font-medium text-gray-700">Domínio Principal</label>
                <div class="mt-1">
                    <input type="text" wire:model.defer="domain_primary" id="domain_primary"
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                           placeholder="Ex: meuapp.com.br">
                </div>
                <p class="mt-1 text-sm text-gray-500">Domínio principal usado para identificar a aplicação</p>
            </div>

            {{-- Domínios aliases --}}
            <div class="sm:col-span-2">
                <label for="domain_aliases" class="block text-sm font-medium text-gray-700">Domínios Alternativos</label>
                <div class="mt-1">
                    <input type="text" wire:model.defer="domain_aliases" id="domain_aliases"
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                           placeholder="Ex: www.meuapp.com.br, app.empresa.com">
                </div>
                <p class="mt-1 text-sm text-gray-500">Domínios alternativos separados por vírgula (opcional)</p>
            </div>

            {{-- Data de expiração --}}
            <div>
                <label for="app_limit_date" class="block text-sm font-medium text-gray-700">Data de Expiração</label>
                <div class="mt-1">
                    <input type="datetime-local" wire:model.defer="app_limit_date" id="app_limit_date"
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
                <p class="mt-1 text-sm text-gray-500">Deixe vazio para licença ilimitada</p>
            </div>

            {{-- Status ativo --}}
            <div class="flex items-center">
                <div class="flex items-center h-5">
                    <input type="checkbox" wire:model.defer="app_active" id="app_active"
                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="app_active" class="font-medium text-gray-700">Aplicação Ativa</label>
                    <p class="text-gray-500">Permitir acesso à aplicação pelos usuários</p>
                </div>
            </div>
        </div>

        {{-- Botões de ação --}}
        <div class="mt-8 flex justify-end space-x-3">
            <button type="button" wire:click="loadAppData"
                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancelar
            </button>
            <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg wire:loading wire:target="saveBasicInfo" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="saveBasicInfo">Salvar Informações</span>
                <span wire:loading wire:target="saveBasicInfo">Salvando...</span>
            </button>
        </div>
    </form>
</div>
