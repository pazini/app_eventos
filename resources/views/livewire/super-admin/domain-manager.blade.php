<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8">
            <div class="flex items-center space-x-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-white">Gerenciador de Domínios</h1>
                    <p class="text-blue-100 text-sm mt-1">Configuração de domínios multi-tenant</p>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="mx-6 mt-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mx-6 mt-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Content -->
        <div class="p-6 space-y-6">
            <!-- App Selector -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Selecionar Aplicação
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($apps as $app)
                        <button
                            wire:click="selectApp('{{ $app->id }}')"
                            class="p-4 border-2 rounded-lg transition-all duration-200 text-left
                                {{ $selectedAppId === $app->id
                                    ? 'border-blue-500 bg-blue-50 shadow-md'
                                    : 'border-gray-200 hover:border-blue-300 hover:bg-gray-50'
                                }}"
                        >
                            <div class="font-semibold text-gray-900">{{ $app->app_name }}</div>
                            <div class="text-sm text-gray-600 mt-1">{{ $app->domain_primary ?: 'Sem domínio' }}</div>
                            <div class="flex items-center mt-2 text-xs">
                                <span class="px-2 py-1 rounded-full {{ $app->app_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $app->app_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            @if($selectedAppId)
                <div class="border-t pt-6">
                    <!-- Primary Domain -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Domínio Principal
                            <span class="text-red-500">*</span>
                        </label>
                        <x-input
                            wire:model.defer="domain_primary"
                            placeholder="exemplo.com.br"
                            class="w-full"
                        />
                        @error('domain_primary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Este é o domínio principal da aplicação. Não incluir www ou subdomínios.
                        </p>
                    </div>

                    <!-- Domain Aliases -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Domínios Alternativos (Aliases)
                        </label>

                        <!-- Add Alias -->
                        <div class="flex gap-2 mb-4">
                            <x-input
                                wire:model.defer="newAlias"
                                placeholder="painel.exemplo.com.br"
                                class="flex-1"
                                wire:keydown.enter="addAlias"
                            />
                            <x-button
                                primary
                                icon="plus"
                                label="Adicionar"
                                wire:click="addAlias"
                            />
                        </div>

                        <!-- Alias List -->
                        @if(count($domain_aliases) > 0)
                            <div class="space-y-2">
                                @foreach($domain_aliases as $index => $alias)
                                    <div class="flex items-center justify-between bg-gray-50 px-4 py-3 rounded-lg border border-gray-200">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                            </svg>
                                            <span class="font-mono text-sm">{{ $alias }}</span>
                                        </div>
                                        <x-button
                                            flat
                                            negative
                                            icon="trash"
                                            wire:click="removeAlias({{ $index }})"
                                        />
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                <p class="text-sm">Nenhum domínio alternativo configurado</p>
                            </div>
                        @endif

                        <p class="mt-2 text-xs text-gray-500">
                            Subdomínios e domínios alternativos que também devem acessar esta aplicação.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t">
                        <x-button
                            white
                            icon="refresh"
                            label="Limpar Cache do Sistema"
                            wire:click="clearAllCache"
                        />

                        <x-button
                            primary
                            icon="save"
                            label="Salvar Domínios"
                            wire:click="saveDomains"
                            spinner="saveDomains"
                        />
                    </div>
                </div>
            @else
                <div class="text-center py-12 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-lg font-medium">Selecione uma aplicação acima</p>
                    <p class="text-sm mt-1">Escolha uma aplicação para gerenciar seus domínios</p>
                </div>
            @endif
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border-t border-blue-100 px-6 py-4">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="text-sm text-blue-800">
                    <strong>Importante:</strong> Após alterar domínios, sempre limpe o cache. Os domínios alternativos (aliases)
                    permitem que múltiplos domínios/subdomínios acessem a mesma aplicação. Domínios não cadastrados serão
                    redirecionados para uma página 404 no domínio principal.
                </div>
            </div>
        </div>
    </div>
</div>
