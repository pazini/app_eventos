<div class="mb-10">

    @if ($customer ?? false)

        <div class="{{ setClass('divContentHeader') }} relative overflow-hidden">
            <!-- Decorative Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern-usuarios" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-usuarios)"/>
                </svg>
            </div>

            <div class="relative z-10 w-full space-y-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            {!! setLabelHeader($this->customer->name_corporate, 'Usuários') !!}
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                        <div class="min-w-0 flex-1 sm:min-w-48">
                            <label for="" class="block text-sm font-medium text-white/90 mb-2">Empresas</label>
                            <x-native-select xs wire:model.live="customer_id" class="text-gray-700 w-full uppercase pt-2 pb-2 bg-white/95 backdrop-blur-sm border-white/30 focus:border-white focus:ring-2 focus:ring-white/50 rounded-lg shadow-sm transition-all duration-200">
                                <option value="">Selecione</option>
                                @foreach ($customers->sortBy('customer_slug') as $customer_item)
                                <option value="{{ $customer_item->id }}" class="uppercase">{{ $customer_item->name_corporate }}</option>
                                @endforeach
                            </x-native-select>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/30"></div>

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <nav class="flex flex-wrap gap-2">
                        @php
                            $routeName = request()->route()->getName() ?? '';
                            $isEventos = str_contains($routeName, 'eventos');
                            $prefix = $isEventos ? 'eventos' : 'campanhas';
                        @endphp
                        <x-button white sm outline label="Usuários" class="px-4 py-2 bg-white/10 backdrop-blur-sm border-white/30 hover:bg-white/20 hover:text-white transition-all duration-200 rounded-lg font-medium shadow-sm" />
                        <x-button white sm label="Filiais" href="{{ route($prefix . '-organizadores-instituicoes') }}" class="px-4 py-2 bg-white/95 text-sky-600 hover:bg-white hover:text-sky-700 transition-all duration-200 rounded-lg font-medium shadow-sm" />
                        @if($isEventos)
                            <x-button white sm label="Setores" href="{{ route('eventos-organizadores-setores') }}" class="px-4 py-2 bg-white/95 text-sky-600 hover:bg-white hover:text-sky-700 transition-all duration-200 rounded-lg font-medium shadow-sm" />
                        @endif
                        <x-button white sm label="Organizadores" href="{{ route($prefix . '-organizadores') }}" class="px-4 py-2 bg-white/95 text-sky-600 hover:bg-white hover:text-sky-700 transition-all duration-200 rounded-lg font-medium shadow-sm" />
                    </nav>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-4 right-4 w-16 h-16 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute bottom-4 left-4 w-12 h-12 bg-lime-400/20 rounded-full blur-lg"></div>
        </div>

        <div class="{{ setClass('divContentErros') }}">
            <div class="w-full my-2">
                <x-jet-validation-errors />
            </div>
        </div>

        {{-- USUÁRIOS --}}
        <div class="{{ setClass('divContent') }} rounded-none border-x border-b">
            <div class="flex-1">
                <div class="{{ setClass('divContentTitle') }} uppercase mb-2">
                    <span class="font-light">Usuários da Empresa</span>
                </div>
                <p class="text-gray-600 text-sm">Gerencie os usuários do cliente</p>
            </div>
        </div>

        {{-- Campo de Busca --}}
        <div class="w-full max-w-7xl mx-auto bg-white border-x border-b px-6 py-4">
            <div class="max-w-md flex gap-2 items-end">
                <div class="flex-1">
                    <x-input
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar usuário por nome ou email..."
                        icon="search"
                        class="w-full"
                    />
                </div>
                @if(!empty($search))
                    <x-button
                        flat
                        negative
                        icon="x"
                        label="Limpar"
                        wire:click="$set('search', '')"
                        class="whitespace-nowrap"
                    />
                @endif
            </div>
        </div>

        <div class="w-full max-w-7xl mx-auto bg-white shadow-sm border-x border-b rounded-b-lg">
            <div class="p-6 space-y-6">
                @forelse ($customerUsers ?? [] as $user)
                    @if(!$user->pivot)
                    @continue
                    @endif
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                        <div class="p-6 grid grid-cols-12 gap-4">
                            <!-- Cabeçalho do Usuário -->
                            <div class="col-span-12">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div class="flex-1 space-y-2">
                                        <h3 class="text-xl font-semibold text-gray-800">
                                            {{ $user->name ?? 'N/A' }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $user->email ?? 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ $user->contact_country ?? '' }} {{ $user->contact_ddd ?? '' }} {{ $user->contact_num ?? '' }}
                                        </p>
                                        {{--                                         <div class="flex gap-2 mt-2">
                                            @if($user->pivot && $user->pivot->user_role)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ ucfirst($user->pivot->user_role) }}
                                                </span>
                                            @endif
                                        </div> --}}
                                        {{-- @dump($user->toArray()) --}}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-button
                                            flat
                                            primary
                                            icon="pencil-alt"
                                            label="Editar"
                                            wire:click="openEditModal('{{ $user->id }}')"
                                            class="w-auto"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="max-w-md mx-auto">
                            <svg class="w-16 h-16 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <h3 class="text-xl font-medium text-gray-800 mb-3">Nenhum usuário encontrado</h3>
                            <p class="text-gray-600">Este cliente ainda não possui usuários cadastrados.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- MODAL DE EDIÇÃO --}}
        <x-modal.card wire:model.defer="showEditModal" title="Editar Usuário" max-width="2xl">
            <div class="space-y-6 p-6">
                @if($selectedUser)
                    <!-- Informações do Usuário -->
                    <div class="space-y-4">
                        <div>
                            <x-input
                                label="Nome"
                                wire:model.defer="editName"
                                placeholder="Nome completo do usuário"
                            />
                            @error('editName')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-input
                                label="E-mail"
                                wire:model.defer="editEmail"
                                type="email"
                                placeholder="email@exemplo.com"
                            />
                            @error('editEmail')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input
                                    label="País"
                                    wire:model.defer="editContactCountry"
                                    placeholder="Ex: +55"
                                />
                                @error('editContactCountry')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-input
                                    label="DDD"
                                    wire:model.defer="editContactDdd"
                                    placeholder="Ex: 11"
                                />
                                @error('editContactDdd')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-input
                                    label="Telefone"
                                    wire:model.defer="editContactNum"
                                    placeholder="Ex: 987654321"
                                />
                                @error('editContactNum')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Seção de Alteração de Senha -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Alterar Senha</h3>
                            <x-button
                                flat
                                sm
                                label="{{ $showPasswordSection ? 'Ocultar' : 'Alterar Senha' }}"
                                wire:click="$set('showPasswordSection', {{ $showPasswordSection ? 'false' : 'true' }})"
                            />
                        </div>

                        @if($showPasswordSection)
                            <div class="space-y-4 bg-gray-50 p-4 rounded-lg">
                                <div>
                                    <x-input
                                        label="Nova Senha"
                                        wire:model.defer="newPassword"
                                        type="password"
                                        placeholder="Mínimo 8 caracteres"
                                    />
                                    @error('newPassword')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <x-input
                                        label="Confirmar Nova Senha"
                                        wire:model.defer="newPasswordConfirmation"
                                        type="password"
                                        placeholder="Digite a senha novamente"
                                    />
                                    @error('newPasswordConfirmation')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <x-button
                                        primary
                                        label="Salvar Nova Senha"
                                        wire:click="updateUserPassword"
                                        spinner="updateUserPassword"
                                    />
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Confirmação de Remoção -->
                    @if($showDeleteConfirmation)
                        <div class="border-t border-gray-200 pt-6">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-red-800 mb-2">Confirmar Remoção</h4>
                                        <p class="text-sm text-red-700 mb-4">
                                            Tem certeza que deseja remover o usuário <strong>{{ $selectedUser->name }}</strong> deste cliente?
                                            Esta ação não pode ser desfeita.
                                        </p>
                                        <div class="flex gap-2">
                                            <x-button
                                                flat
                                                label="Cancelar"
                                                wire:click="$set('showDeleteConfirmation', false)"
                                            />
                                            <x-button
                                                negative
                                                label="Confirmar Remoção"
                                                wire:click="removeUser"
                                                spinner="removeUser"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="border-t border-gray-200 pt-6">
                            @if($selectedUserId && auth()->check() && auth()->id() !== $selectedUserId)
                                <x-button
                                    negative
                                    outline
                                    label="Remover Usuário"
                                    wire:click="confirmDelete('{{ $selectedUserId }}')"
                                    class="w-full"
                                />
                            @elseif($selectedUserId && auth()->check() && auth()->id() === $selectedUserId)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-yellow-800 text-sm font-medium">Você não pode remover a si mesmo.</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                @endif
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-2">
                    <x-button
                        flat
                        label="Fechar"
                        wire:click="closeEditModal"
                    />
                    <x-button
                        primary
                        label="Salvar Alterações"
                        wire:click="updateUser"
                        spinner="updateUser"
                    />
                </div>
            </x-slot>
        </x-modal.card>

        @if(session('success'))
            <script>
                window.$wireui.notify({
                    title: 'Sucesso!',
                    description: '{{ session('success') }}',
                    icon: 'success'
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                window.$wireui.notify({
                    title: 'Erro!',
                    description: '{{ session('error') }}',
                    icon: 'error'
                });
            </script>
        @endif

    @elseif($customers ?? false)

        {{-- USUÁRIOS --}}
        <div class="{{ setClass('divContentTitleDiv') }} rounded-t-lg">
            <div>
                <div class="{{ setClass('divContentTitle') }}">Usuários</div>
                <p class="text-gray-600 text-sm mt-2">Selecione uma empresa para gerenciar seus usuários</p>
            </div>
        </div>
        <div class="w-full max-w-7xl mx-auto bg-white shadow-sm border-x border-b rounded-b-lg p-8">
            <div class="max-w-lg mx-auto p-6">
                <div class="text-center mb-6">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Selecione uma Empresa</h3>
                    <p class="text-sm text-gray-600">Escolha a empresa que deseja gerenciar</p>
                </div>

                <x-native-select label="" wire:model.live="customer_id" class="w-full uppercase font-normal text-gray-700 rounded-lg border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                    <option value="">Selecione uma empresa</option>
                    @foreach ($customers->sortBy('customer_slug') as $customer_item)
                    <option value="{{ $customer_item->id }}" class="uppercase">{{ $customer_item->doc_type }} {{ $customer_item->doc_num }} - {{ $customer_item->name_corporate }}</option>
                    @endforeach
                </x-native-select>
            </div>
        </div>

    @else

        <div class="{{ setClass('divContentHeader') }} rounded-lg">
            <div class="w-full text-center">
                <div class="max-w-md mx-auto">
                    <svg class="w-16 h-16 mx-auto mb-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <h2 class="text-2xl font-light mb-2">Acesso Restrito</h2>
                    <p class="text-white/90">Este usuário ainda não está vinculado a nenhum cliente do sistema.</p>
                </div>
            </div>
        </div>
    @endif

</div>

