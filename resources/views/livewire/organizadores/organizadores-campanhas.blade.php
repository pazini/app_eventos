<div class="mb-10">

    @if ($customer ?? false)

        <div class="{{ setClass('divContentHeader') }} relative overflow-hidden">
            <!-- Decorative Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern)"/>
                </svg>
            </div>

            <div class="relative z-10 w-full space-y-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            {!! setLabelHeader($this->customer->name_corporate, 'Organizadores') !!}
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
                        <x-button white sm label="Usuários" href="{{ route('campanhas-organizadores-usuarios') }}" class="px-4 py-2 bg-white/95 text-sky-600 hover:bg-white hover:text-sky-700 transition-all duration-200 rounded-lg font-medium shadow-sm" />
                        <x-button white sm label="Filiais" href="{{ route('campanhas-organizadores-instituicoes') }}" class="px-4 py-2 bg-white/95 text-sky-600 hover:bg-white hover:text-sky-700 transition-all duration-200 rounded-lg font-medium shadow-sm" />
                        <x-button white sm outline label="Organizadores" class="px-4 py-2 bg-white/10 backdrop-blur-sm border-white/30 hover:bg-white/20 hover:text-white transition-all duration-200 rounded-lg font-medium shadow-sm" />
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

        {{-- MODAL REMOVE ORGANIZADOR --}}
        <x-modal.card title="Remover Organizador" blur wire:model.defer="removerOrganizer">

            @if ($this->organizer ?? false)

                <div class="grid grid-cols-1 md:grid-cols-6">

                    <div class="col-span-5">
                        {!! setLabel('organizer', $this->organizer->organizer_name_full) !!}
                    </div>

                    <div class="col-span-1">
                        {!! setLabel('campanhas', $this->organizer->campaigns->count() > 0 ? $this->organizer->campaigns->count() : 'nenhum') !!}
                    </div>

                    @if ($this->organizer->campaigns->count())
                        <div class="col-span-full mb-4">
                            <hr>
                        </div>
                        @foreach ($this->organizer->campaigns as $campaign)
                            <div class="col-span-full">
                                <div class="bg-gray-50 pt-6 px-4 shadow border-b">
                                    {!! setLabel(' ', $campaign->name) !!}
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>

                <x-slot name="footer">

                    @if($this->organizer->campaigns->count())
                        {!! setLabel(' ', "<span class='text-red-600 font-bold'>O organizador não pode ser removido</span>") !!}
                    @else
                        <div class="flex justify-between gap-x-4">
                            <div>
                            </div>

                            <div class="flex">
                                <x-button flat label="Cancelar" x-on:click="close" />
                                <x-button negative label="Remover" onclick="confirm('Confirma remoção do organizador?') || event.stopImmediatePropagation()" wire:click="removerOrganizer('{{ $this->organizer->id }}',true)" />
                            </div>
                        </div>
                    @endif

                </x-slot>

            @else

                <div class="col-span-full flex justify-between items-center bg-white hover:bg-gray-50 py-2 px-4 shadow border-b">
                    <div class="font-light">Nenhum organizador encontrado</div>
                </div>

            @endif

        </x-modal.card>
        {{-- MODAL REMOVER ORGANIZADOR --}}

        {{-- MODAL NOVO ORGANIZADOR --}}
        <x-modal.card title="{{ ($this->organizer ?? false) ? 'Alterar Organizador' : 'Novo Organizador' }}" blur wire:model.defer="novoOrganizer">

            @if ($this->organizer ?? false)

                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">

                    <div class="col-span-2">
                        <x-input label="{{ __('owner_name') }}" wire:model.defer="owner_name" />
                    </div>

                    <div class="col-span-4">
                        <x-input label="{{ __('owner_email') }}" wire:model.defer="owner_email" />
                    </div>

                    <div class="col-span-3">
                        <x-native-select label="{{ __('owner_phone_ddd') }}" wire:model.defer="owner_phone_ddd" class="">
                            <option value="">--</option>
                            @foreach (listDdd() ?? [] as $ddd => $descricao)
                            <option value="{{ $ddd }}">{{ $descricao }}</option>
                            @endforeach
                        </x-native-select>
                    </div>

                    <div class="col-span-3">
                        <x-inputs.maskable
                            label="{{ __('owner_phone_num') }}"
                            wire:model.defer="owner_phone_num"
                            mask="['####-####','#####-####']"
                            placeholder="Telefone"
                        />
                    </div>

                </div>

                <x-slot name="footer">
                    <div class="flex justify-between gap-x-4">
                        <div>
                        </div>
                        <div class="flex">
                            <x-button flat label="CANCELAR" x-on:click="close" />
                            <x-button primary label="ALTERAR" wire:click="cadastrarOrganizer" />
                        </div>
                    </div>
                </x-slot>

            @else

                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">

                    <div class="col-span-full">
                        <x-native-select label="Filial (Opcional)" wire:model="novoOrganizerOrganizationId" class="uppercase">
                            <option value="">-- Sem Filial --</option>
                            @foreach (($this->organizations ?? false) ? $this->organizations->sortBy('organization_name') : [] as $item)
                            <option value="{{ $item->id }}" class="uppercase">{{ $item->organization_name }} | {{ $item->organization_description }}</option>
                            @endforeach
                        </x-native-select>
                    </div>

                    <div class="col-span-2">
                        <x-input label="{{ __('owner_name') }}" wire:model.defer="owner_name" />
                    </div>

                    <div class="col-span-4">
                        <x-input label="{{ __('owner_email') }}" wire:model.defer="owner_email" />
                    </div>

                    <div class="col-span-3">
                        <x-native-select label="{{ __('owner_phone_ddd') }}" wire:model.defer="owner_phone_ddd" class="">
                            <option value="">--</option>
                            @foreach (listDdd() ?? [] as $ddd => $descricao)
                            <option value="{{ $ddd }}">{{ $descricao }}</option>
                            @endforeach
                        </x-native-select>
                    </div>

                    <div class="col-span-3">
                        <x-inputs.maskable
                            label="{{ __('owner_phone_num') }}"
                            wire:model.defer="owner_phone_num"
                            mask="['####-####','#####-####']"
                            placeholder="Telefone"
                        />
                    </div>

                </div>

                <x-slot name="footer">
                    <div class="flex justify-between gap-x-4">
                        <div>
                        </div>

                        <div class="flex">
                            <x-button flat label="Cancelar" x-on:click="close" />
                            <x-button primary label="CADASTRAR" wire:click="cadastrarOrganizer" />
                        </div>
                    </div>
                </x-slot>

            @endif

        </x-modal.card>
        {{-- MODAL NOVO ORGANIZADOR --}}

        {{-- ORGANIZADORES --}}
        <div class="{{ setClass('divContent') }} rounded-none border-x border-b">
            <div class="flex-1">
                <div class="{{ setClass('divContentTitle') }} uppercase mb-2">
                    <span class="font-light">Organizadores de Campanhas</span>
                </div>
                <p class="text-gray-600 text-sm">Gerencie os organizadores de campanhas</p>
            </div>
            <div class="flex gap-4 items-center">
                @if ($customer_id ?? false)
                    <x-button flat lime label="NOVO ORGANIZADOR" wire:click="setNovoOrganizer" class="px-6 py-2 font-medium hover:bg-lime-50 transition-colors duration-200 rounded-lg" />
                @endif
            </div>
        </div>

        {{-- Campo de Busca --}}
        @if ($customer_id ?? false)
            <div class="w-full max-w-7xl mx-auto bg-white border-x border-b px-6 py-4">
                <div class="max-w-md flex gap-2 items-end">
                    <div class="flex-1">
                        <x-input
                            wire:model.live.debounce.300ms="search"
                            placeholder="Buscar organizador por nome, email ou descrição..."
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
        @endif

        <div class="w-full max-w-7xl mx-auto bg-white shadow-sm border-x border-b rounded-b-lg">

            <div class="p-6 space-y-6">

                @forelse ($organizers->sortBy('organizer_name') ?? [] as $organizer)

                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">

                        <div class="p-6 grid grid-cols-12 gap-4">

                            <!-- Mensagens de Feedback -->
                            @if (session('organizer_success_' . $organizer->id))
                                <div class="col-span-12 mb-4">
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-green-800 font-medium">{{ __(session('organizer_success_' . $organizer->id)) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Cabeçalho do Organizador -->
                            <div class="col-span-12 border-b border-gray-100 pb-4 mb-4">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div class="flex-1 space-y-2">
                                        <div class="flex items-center gap-3">
                                            <h3 class="text-xl font-semibold text-gray-800 uppercase" title="organizerId: {{$organizer->id ?? '--'}}">
                                                {{ mb_strtoupper($organizer->organizer_name ?? 'N/A') }}
                                            </h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $organizer->campaigns->count() }} campanha{{ $organizer->campaigns->count() != 1 ? 's' : '' }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 uppercase" title="organizationId: {{$organizer->organization_id ?? '--'}}">
                                            {{ mb_strtoupper($organizer->organizer_name_full ?? 'N/A') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center">
                                        <x-dropdown>
                                            <x-dropdown.item icon="pencil-alt" label="ALTERAR" wire:click="setAlteraOrganizer('{{ $organizer->id }}')" class="w-auto" />
                                            <x-dropdown.item icon="trash" label="REMOVER" wire:click="removerOrganizer('{{ $organizer->id }}')" class="w-auto" />
                                        </x-dropdown>
                                    </div>
                                </div>
                            </div>

                            <!-- Informações de Contato -->
                            <div class="col-span-12 mb-6">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3 uppercase tracking-wider">Informações de Contato</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <span class="text-xs text-gray-500 uppercase tracking-wider">Nome</span>
                                            <p class="text-sm font-medium text-gray-800">{{ $organizer->owner_name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <span class="text-xs text-gray-500 uppercase tracking-wider">Contato</span>
                                            <p class="text-sm text-gray-800">
                                                {{ putMask($organizer->owner_phone_ddd . $organizer->owner_phone_num, 'telefone') }} • {{ strtolower($organizer->owner_email) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mensagens de Feedback para Usuários -->
                            @if (session('associarUsuario_success_' . $organizer->id))
                                <div class="col-span-12 mb-4">
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-green-800 font-medium">{{ __(session('associarUsuario_success_' . $organizer->id)) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (session('associarUsuario_status_' . $organizer->id))
                                <div class="col-span-12 mb-4">
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-blue-800 font-medium">{{ __(session('associarUsuario_status_' . $organizer->id)) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Seção de Usuários -->
                            <div class="col-span-12">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-lg font-medium text-gray-800">Usuários Associados</h4>
                                    <x-button flat lime icon="user-add" label="Adicionar Usuário" wire:click="setNovoUsuario('{{ $organizer->id }}')" class="px-4 py-2 text-sm font-medium hover:bg-lime-50 transition-colors duration-200 rounded-lg" />
                                </div>
                            </div>

                            @if (($novoUsuario ?? false) && $novoUsuario == $organizer->id)
                            <div class="col-span-12 mb-4">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                    <h5 class="text-lg font-medium text-blue-800 mb-4">Adicionar Usuário</h5>

                                    <!-- Novo Usuário -->
                                    <div class="space-y-4 mb-6">
                                        <h6 class="text-sm font-medium text-gray-700 uppercase tracking-wider">Criar Novo Usuário</h6>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <x-input icon="user" wire:model.defer="name" placeholder="Nome Completo" />
                                            <x-input icon="mail" wire:model.defer="email" placeholder="Email" />
                                            <x-inputs.maskable
                                                wire:model.defer="telefone"
                                                icon="phone"
                                                mask="['(##) ####-####','(##) #####-####']"
                                                placeholder="DDD + Telefone"
                                            />
                                        </div>
                                    </div>

                                    <div class="border-t border-blue-200 pt-6">
                                        <h6 class="text-sm font-medium text-gray-700 uppercase tracking-wider mb-4">Ou Selecionar Usuário Existente</h6>
                                        <div class="flex flex-col md:flex-row gap-4 items-end">
                                            <div class="flex-1">
                                                <x-native-select wire:model.defer="novoUsuarioId" class="w-full">
                                                    <option value="">Selecione um usuário existente</option>
                                                    @foreach ($this->novoUsuarioListUsers->sortBy('name') ?? [] as $userItem)
                                                    <option value="{{ $userItem->id }}">{{ $userItem->name }} | {{ $userItem->email }}</option>
                                                    @endforeach
                                                </x-native-select>
                                            </div>
                                            <div class="flex gap-2">
                                                <x-button negative label="Cancelar" wire:click="cancelNovoUsuario" class="px-4 py-2" />
                                                <x-button primary label="ASSOCIAR" onclick="confirm('Confirma a associação do usuário?') || event.stopImmediatePropagation()" wire:click="associarUsuario('{{ $organizer->id }}')" class="px-4 py-2" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="col-span-12">
                                <div class="space-y-2">
                                    @forelse ($organizer->users->whereNotIn('email',['admin@empresateste.com'])->sortBy('name') ?? [] as $user)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors duration-200">
                                            <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <span class="text-xs text-gray-500 uppercase tracking-wider">Nome</span>
                                                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                                </div>
                                                <div>
                                                    <span class="text-xs text-gray-500 uppercase tracking-wider">Email</span>
                                                    <p class="text-gray-700">{{ $user->email }}</p>
                                                </div>
                                                <div>
                                                    <span class="text-xs text-gray-500 uppercase tracking-wider">Telefone</span>
                                                    <p class="text-gray-700">{{ putMask($user->contact_ddd . $user->contact_num, 'telefone') }}</p>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                @if ($user->email != auth()->user()->email)
                                                    <x-button flat negative icon="trash" title="Remover usuário"
                                                        onclick="confirm('Confirma a remoção do usuário deste organizador?') || event.stopImmediatePropagation()"
                                                        wire:click="desassociarUsuario('{{ $organizer->id }}','{{ $user->id }}')"
                                                        class="p-2 hover:bg-red-50 rounded-lg transition-colors duration-200" />
                                                @else
                                                    <div class="p-2 text-gray-400" title="Não é possível remover seu próprio usuário">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                            </svg>
                                            <p class="font-medium">Nenhum usuário associado</p>
                                            <p class="text-sm">Clique em "Adicionar Usuário" para começar</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="max-w-md mx-auto">
                            <svg class="w-16 h-16 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <h3 class="text-xl font-medium text-gray-800 mb-3">Nenhum organizador encontrado</h3>
                            <p class="text-gray-600 mb-6">Este cliente ainda não possui organizadores cadastrados.</p>
                            @if ($customer_id ?? false)
                                <x-button flat lime label="CADASTRAR PRIMEIRO ORGANIZADOR" wire:click="setNovoOrganizer" class="px-6 py-3 font-medium" />
                            @endif
                        </div>
                    </div>
                @endforelse

            </div>

        </div>

    @elseif($customers ?? false)

        {{-- ORGANIZADORES --}}
        <div class="{{ setClass('divContentTitleDiv') }} rounded-t-lg">
            <div>
                <div class="{{ setClass('divContentTitle') }}">Organizadores de Campanhas</div>
                <p class="text-gray-600 text-sm mt-2">Selecione uma empresa para gerenciar seus organizadores</p>
            </div>
        </div>
        <div class="w-full max-w-7xl mx-auto bg-white shadow-sm border-x border-b rounded-b-lg p-8">

            <div class="max-w-lg mx-auto p-6">
                <div class="text-center mb-6">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
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

