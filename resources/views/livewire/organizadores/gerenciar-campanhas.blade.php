<div>
    @if ($customer ?? false)

        {{-- MODERN HEADER WITH GRADIENT --}}
        <div class="{{ setClass('divContentHeader') }} relative overflow-hidden">
            <!-- Decorative Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern-unified" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-unified)"/>
                </svg>
            </div>

            <div class="relative z-10 w-full space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                    <div class="col-span-3 flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            {!! setLabelHeader($this->customer->name_corporate, 'Gerenciar ' . ($context === 'campanhas' ? 'Campanhas' : 'Eventos')) !!}
                        </div>
                    </div>

                    <div>
                        <label for="" class="block text-sm font-medium text-white/90 mb-2">Empresas</label>
                        <x-native-select xs wire:model="customer_id" class="text-gray-700 w-full uppercase pt-2 pb-2 bg-white/95 backdrop-blur-sm border-white/30 focus:border-white focus:ring-2 focus:ring-white/50 rounded-lg shadow-sm transition-all duration-200">
                            <option value="">Selecione</option>
                            @foreach ($customers->sortBy('customer_slug') as $customer_item)
                            <option value="{{ $customer_item->id }}" class="uppercase">{{ $customer_item->name_corporate }}</option>
                            @endforeach
                        </x-native-select>
                    </div>
                </div>

                {{-- TAB NAVIGATION --}}
                <nav class="grid grid-cols-1 md:grid-cols-4 gap-4" role="tablist">

                    <button
                        wire:click="switchTab('organizadores')"
                        class="px-4 py-2 font-medium transition-all duration-200 rounded-lg shadow-sm
                            {{ ($activeTab ?? 'organizadores') === 'organizadores'
                                ? 'bg-white text-sky-600'
                                : 'bg-white/10 backdrop-blur-sm border border-white/30 text-white hover:bg-white/20' }}"
                        role="tab"
                        aria-selected="{{ ($activeTab ?? 'organizadores') === 'organizadores' ? 'true' : 'false' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Centros de Custo</span>
                        </div>
                    </button>

                    <button
                        wire:click="switchTab('filiais')"
                        class="px-4 py-2 font-medium transition-all duration-200 rounded-lg shadow-sm
                            {{ ($activeTab ?? 'organizadores') === 'filiais'
                                ? 'bg-white text-sky-600'
                                : 'bg-white/10 backdrop-blur-sm border border-white/30 text-white hover:bg-white/20' }}"
                        role="tab"
                        aria-selected="{{ ($activeTab ?? 'organizadores') === 'filiais' ? 'true' : 'false' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>Filiais</span>
                        </div>
                    </button>

                    <button
                        wire:click="switchTab('usuarios')"
                        class="px-4 py-2 font-medium transition-all duration-200 rounded-lg shadow-sm
                            {{ ($activeTab ?? 'organizadores') === 'usuarios'
                                ? 'bg-white text-sky-600'
                                : 'bg-white/10 backdrop-blur-sm border border-white/30 text-white hover:bg-white/20' }}"
                        role="tab"
                        aria-selected="{{ ($activeTab ?? 'organizadores') === 'usuarios' ? 'true' : 'false' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>Usuários</span>
                        </div>
                    </button>

                    {{-- <button title="Não se aplica nas campanhas"
                        class="px-4 py-2 font-medium transition-all duration-200 rounded-lg shadow-sm cursor-not-allowed bg-white/10 backdrop-blur-sm border border-red-600/30 hover:bg-red-600/20 border-red-600 text-red-600" role="tab">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Subdivisões</span>
                        </div>
                    </button> --}}
                </nav>
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

        {{-- ============================================
            MODAIS - FORA DOS IFS
        ============================================= --}}

        {{-- MODAL REMOVER CENTRO DE CUSTO COM DUPLA CONFIRMAÇÃO --}}
        <x-modal.card
            title="⚠️ Confirmar Remoção do Centro de Custo"
            blur
            wire:model="removerOrganizer"
            max-width="2xl"
        >
            @if ($this->organizer ?? false)
                <div class="space-y-6">
                    <!-- Informações do Centro de Custo -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-600 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-red-800">
                                    {{ $this->organizer->organizer_name ?? 'Centro de Custo' }}
                                </h3>
                                <p class="text-sm text-red-700 mt-1">
                                    Esta ação não pode ser desfeita!
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Verificação de Dependências -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <span class="text-gray-700">Campanhas associadas:</span>
                            <span class="font-semibold {{ ($this->organizer->campaigns->count() > 0) ? 'text-red-600' : 'text-green-600' }}">
                                {{ $this->organizer->campaigns->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <span class="text-gray-700">Usuários associados:</span>
                            <span class="font-semibold {{ ($this->organizer->users->count() > 0) ? 'text-red-600' : 'text-green-600' }}">
                                {{ $this->organizer->users->count() }}
                            </span>
                        </div>
                    </div>

                    <!-- Lista de Campanhas (se houver) -->
                    @if($this->organizer->campaigns->count() > 0)
                        <div class="border-t pt-4">
                            <h4 class="font-medium text-gray-900 mb-2">Campanhas vinculadas:</h4>
                            <div class="space-y-2 max-h-32 overflow-y-auto">
                                @foreach($this->organizer->campaigns as $campaign)
                                    <div class="text-sm bg-yellow-50 border border-yellow-200 rounded px-3 py-2">
                                        {{ $campaign->name ?? 'Campanha sem nome' }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Processo de Dupla Confirmação -->
                    @if(!$confirmDelete)
                        <!-- Primeira Confirmação -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800 text-sm mb-3">
                                <strong>Passo 1 de 2:</strong> Você tem certeza que deseja remover este centro de custo?
                            </p>
                            <div class="flex gap-3">
                                <x-button
                                    flat
                                    label="Cancelar"
                                    wire:click="cancelarRemocao"
                                />
                                <x-button
                                    warning
                                    label="Sim, continuar"
                                    wire:click="confirmarPrimeiraEtapa"
                                    :disabled="($this->organizer->campaigns->count() > 0) || ($this->organizer->users->count() > 0)"
                                />
                            </div>

                            @if(($this->organizer->campaigns->count() > 0) || ($this->organizer->users->count() > 0))
                                <p class="text-red-600 text-xs mt-2">
                                    ❌ Não é possível remover. Existem dependências associadas.
                                </p>
                            @endif
                        </div>
                    @else
                        <!-- Segunda Confirmação -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-800 text-sm mb-3">
                                <strong>Passo 2 de 2:</strong>
                                Digite <span class="font-mono bg-red-100 px-1 rounded">CONFIRMAR</span> para finalizar a remoção:
                            </p>
                            <div class="space-y-3">
                                <x-input
                                    wire:model="confirmDeleteText"
                                    placeholder="Digite CONFIRMAR"
                                    class="text-center"
                                />
                                <div class="flex gap-3">
                                    <x-button
                                        flat
                                        label="Cancelar"
                                        wire:click="cancelarRemocao"
                                    />
                                    <x-button
                                        negative
                                        label="🗑️ Remover Definitivamente"
                                        wire:click="executarRemocao"
                                        spinner="executarRemocao"
                                    />
                                </div>

                                <!-- Debug info -->
                                <div class="text-xs text-gray-500">
                                    Digitado: "{{ $confirmDeleteText }}" |
                                    Válido: {{ $confirmDeleteText === 'CONFIRMAR' ? 'SIM' : 'NÃO' }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">Centro de custo não encontrado</p>
                </div>
            @endif
        </x-modal.card>

        {{-- MODAL NOVO CENTRO DE CUSTO --}}
        <x-modal.card
            title="{{ ($this->organizer ?? false) ? 'Alterar Centro de Custo' : 'Novo Centro de Custo' }}"
            blur
            wire:model="novoOrganizer"
            max-width="3xl"
        >
            <!-- Erros de Validação -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Corrija os erros abaixo:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if (!($this->organizer ?? false))
                <!-- SEÇÃO FILIAL - Apenas para novos centros de custo -->
                <div class="space-y-3">

                    <x-native-select
                        label="Selecione a Filial"
                        wire:key="novoOrganizerOrganizationId_{{ __LINE__ }}"
                        wire:model="novoOrganizerOrganizationId"
                        placeholder="Escolha a filial"
                    >
                        <option value="">-- Selecione uma Filial --</option>
                        @forelse (($this->organizations ?? collect([]))->sortBy('organization_name') as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->organization_name }} - {{ $item->organization_description }}
                            </option>
                        @empty
                            <option disabled>Nenhuma filial disponível</option>
                        @endforelse
                    </x-native-select>
                </div>
            @elseif (($this->organizer ?? false) && !is_null($this->organizer->organization_id))
                <!-- SEÇÃO FILIAL - Para edição de organizadores que têm filial (não da própria empresa) -->
                <div class="space-y-3">
                    <div class="flex items-center">
                        <h3 class="text-lg font-medium text-gray-900">Filial Atual</h3>
                    </div>

                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                        <p class="text-sm text-gray-700">
                            A filial não pode ser alterada durante a edição.
                        </p>
                    </div>
                </div>
            @elseif (($this->organizer ?? false) && is_null($this->organizer->organization_id))
                <!-- Organizador da própria empresa - sem filial -->
                <div class="space-y-3">
                    <div class="p-3 mb-4 bg-blue-50 border border-blue-200 rounded-md">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-blue-800 font-medium">Centro de Custo da Própria Empresa</span>
                        </div>
                        <p class="text-sm text-blue-700 mt-1">
                            Este centro de custo pertence à própria empresa e não está vinculado a uma filial específica.
                        </p>
                    </div>
                </div>
            @endif

            <!-- SEÇÃO RESPONSÁVEL -->
            <div class="space-y-4 mt-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input
                            label="Nome Completo"
                            wire:model.defer="owner_name"
                            placeholder="Nome do responsável"
                            required
                        />
                    </div>
                    <div>
                        <x-input
                            label="E-mail"
                            wire:model.defer="owner_email"
                            type="email"
                            placeholder="email@exemplo.com"
                            required
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-native-select
                            label="DDD"
                            wire:model.defer="owner_phone_ddd"
                            required
                        >
                            <option value="">Selecione o DDD</option>
                            @foreach (listDdd() ?? [] as $ddd => $descricao)
                                <option value="{{ $ddd }}">{{ $descricao }}</option>
                            @endforeach
                        </x-native-select>
                    </div>
                    <div>
                        <x-inputs.maskable
                            label="Telefone"
                            wire:key="owner_phone_num_{{ __LINE__ }}"
                            wire:model.defer="owner_phone_num"
                            mask="['####-####','#####-####']"
                            placeholder="Número do telefone"
                            inputmode="numeric"
                            required
                        />
                    </div>
                </div>

                <p class="text-xs text-gray-500 mt-2">
                    Todos os campos são obrigatórios
                </p>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-3">
                    <x-button
                        flat
                        label="Cancelar"
                        wire:click="$set('novoOrganizer', false)"
                    />
                    <x-button
                        primary
                        label="{{ ($this->organizer ?? false) ? 'Alterar' : 'Cadastrar' }}"
                        wire:click="cadastrarOrganizer"
                        spinner="cadastrarOrganizer"
                    />
                </div>
            </x-slot>
        </x-modal.card>
        {{-- MODAL NOVO CENTRO DE CUSTO - FIM --}}

        {{-- MODAL EDITAR USUÁRIO --}}
        <x-modal.card title="Editar Usuário" max-width="2xl" wire:model="showEditModal">
            <div class="space-y-6 p-6">
                @if($selectedUser)
                    <div class="space-y-4">
                        <div>
                            <x-input
                                label="Nome"
                                wire:model="editName"
                                placeholder="Nome completo do usuário"
                            />
                        </div>

                        <div>
                            <x-input
                                label="E-mail"
                                wire:model="editEmail"
                                type="email"
                                placeholder="email@exemplo.com"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input
                                    label="País"
                                    wire:model="editContactCountry"
                                    placeholder="Ex: +55"
                                />
                            </div>
                            <div>
                                <x-input
                                    label="DDD"
                                    wire:model="editContactDdd"
                                    placeholder="Ex: 11"
                                />
                            </div>
                            <div>
                                <x-input
                                    label="Telefone"
                                    wire:model="editContactNum"
                                    placeholder="Ex: 987654321"
                                    inputmode="numeric"
                                    required
                                />
                            </div>
                        </div>
                    </div>
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
                    />
                </div>
            </x-slot>
        </x-modal.card>

        {{-- MODAL NOVA SUBDIVISÃO --}}
        <x-modal.card title="Nova Subdivisão" blur wire:model.defer="organizationSubCadastrar">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div class="col-span-full">
                    <p class="text-sm text-gray-600 mb-4">Cadastre uma nova subdivisão para <strong>{{ $this->organization->organization_name ?? '' }}</strong></p>
                </div>

                <div class="col-span-full">
                    <x-input label="{{ __('organization_sub_name') }}" wire:model.defer="organization_sub_name" class="uppercase" />
                    @error('organization_sub_name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-full">
                    <x-input label="{{ __('organization_sub_description') }}" wire:model.defer="organization_sub_description" class="uppercase" />
                    @error('organization_sub_description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-between gap-x-4">
                    <div></div>
                    <div class="flex gap-2">
                        <x-button flat label="Cancelar" wire:click="$set('organizationSubCadastrar', false)" />
                        <x-button primary label="CADASTRAR" wire:click="cadastrar(true)" />
                    </div>
                </div>
            </x-slot>
        </x-modal.card>

        {{-- MODAL NOVA FILIAL --}}
        <x-modal.card title="Nova Filial" blur wire:model="organizationCadastrar" max-width="2xl">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div class="col-span-full">
                    <p class="text-sm text-gray-600 mb-4">Cadastre uma nova filial para <strong>{{ $customer->name_corporate ?? '' }}</strong></p>
                </div>

                <div class="col-span-full">
                    <x-input label="{{ __('organization_name') }}" wire:model.defer="organization_name" class="uppercase" />
                </div>

                <div class="col-span-full">
                    <x-input label="{{ __('organization_description') }}" wire:model.defer="organization_description" class="uppercase" />
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-between gap-x-4">
                    <div></div>
                    <div class="flex gap-2">
                        <x-button flat label="Cancelar" wire:click="$set('organizationCadastrar', false)" />
                        <x-button primary label="CADASTRAR" wire:click="cadastrarFilial(true)" />
                    </div>
                </div>
            </x-slot>
        </x-modal.card>

        {{-- ============================================
            TAB 1: ORGANIZADORES
        ============================================= --}}
        @if (($activeTab ?? 'organizadores') === 'organizadores')

            {{-- HEADER --}}
            <div class="{{ setClass('divContent') }} rounded-none border-x border-b">
                <div class="w-full flex justify-between items-center">
                    <div class="{{ setClass('divContentTitle') }} uppercase mb-2">
                        <span class="font-light">Organizadores</span>
                        <p class="text-gray-600 text-sm">Gerencie os centros de custo de {{ $context === 'campanhas' ? 'campanhas' : 'eventos' }}</p>
                    </div>
                    <x-button flat green label="NOVO CENTRO DE CUSTO" wire:click="setNovoOrganizer" class="px-6 py-2 font-medium bg-lime-50 hover:bg-lime-100 transition-colors duration-200 rounded-lg whitespace-nowrap" />
                </div>
            </div>

            {{-- FILTERS AND SEARCH BAR --}}
            <div class="w-full max-w-7xl mx-auto bg-white border-x border-b px-16 py-4">
                <div class="flex flex-col md:flex-row gap-4 items-start">
                    {{-- Filtro de Filial --}}
                    <div class="w-full md:w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filial</label>
                        <x-native-select xs wire:model="organization_id" class="text-gray-700 w-full uppercase pt-2 pb-2">
                            <option value="">Todas as Filiais</option>
                            @foreach (($this->organizations ?? collect([]))->sortBy('organization_name') as $organization)
                            <option value="{{ $organization->id }}" class="uppercase">{{ $organization->organization_name }}</option>
                            @endforeach
                        </x-native-select>
                    </div>

                    {{-- Campo de Busca --}}
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <x-input
                            wire:model.debounce.300ms="searchOrganizadores"
                            placeholder="Buscar por centro de custo, responsável, e-mail, filial, usuário ou campanha..."
                            icon="search"
                            class="w-full"
                        />
                        <p class="mt-1 text-xs text-gray-500">
                            Campos: centro de custo, responsável, e-mail, telefone, filial, usuários e campanhas.
                        </p>
                    </div>

                    {{-- Botões --}}
                    <div class="flex gap-2">
                        @if(!empty($searchOrganizadores))
                            <x-button
                                flat
                                negative
                                icon="x"
                                label="Limpar"
                                wire:click="$set('searchOrganizadores', '')"
                                class="whitespace-nowrap"
                            />
                        @endif
                    </div>
                </div>
            </div>

            {{-- ORGANIZERS LIST --}}
            <div class="w-full max-w-7xl mx-auto bg-white border-x border-b px-16 py-10">
                <div class="space-y-6">
                    @forelse ($organizers ?? [] as $organizer)
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                            <div class="p-6 grid grid-cols-12">
                                @if (session('organizer_success_' . $organizer->id))
                                    <div class="col-span-12">
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

                                <div class="col-span-12 border-b border-gray-100 pb-4">
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                        <div class="flex-1 space-y-2">
                                            <div class="flex items-center gap-3 flex-wrap">
                                                <h3 class="text-xl font-semibold text-gray-800 uppercase">
                                                    {{ mb_strtoupper($organizer->organizer_name_full ?? ($organizer->organizer_name ?? ($organizer->owner_name ?? 'Centro de Custo'))) }}
                                                </h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    @if($context === 'campanhas')
                                                        {{ $organizer->campaigns->count() }} campanha{{ $organizer->campaigns->count() != 1 ? 's' : '' }}
                                                    @else
                                                        {{ ($organizer->events ?? collect())->count() }} evento{{ ($organizer->events ?? collect())->count() != 1 ? 's' : '' }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <x-button
                                                flat
                                                primary
                                                icon="pencil-alt"
                                                label="Alterar"
                                                wire:click="setAlteraOrganizer('{{ $organizer->id }}')"
                                                class="w-auto"
                                            />

                                            {{-- Botão Remover apenas se NÃO for da própria empresa --}}
                                            @if(isAdmin())
                                                <x-button
                                                    flat
                                                    negative
                                                    icon="trash"
                                                    label="Remover by Admin"
                                                    wire:click="confirmarRemocaoOrganizer('{{ $organizer->id }}')"
                                                    class="w-auto"
                                                />
                                            @elseif(!is_null($organizer->organization_id))
                                                <x-button
                                                    flat
                                                    negative
                                                    icon="trash"
                                                    label="Remover"
                                                    wire:click="confirmarRemocaoOrganizer('{{ $organizer->id }}')"
                                                    class="w-auto"
                                                />
                                            @else
                                                <div class="px-3 py-2 text-xs bg-blue-50 text-blue-700 rounded border border-blue-200">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                    Própria Empresa
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-12 mb-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <span class="text-xs text-gray-500 uppercase tracking-wider">Responsável</span>
                                                <p class="text-sm font-medium text-gray-800 mt-1">
                                                    {{ $organizer->owner_name ?? 'Não informado' }}
                                                </p>
                                            </div>
                                            <div>
                                                <span class="text-xs text-gray-500 uppercase tracking-wider">E-mail</span>
                                                <p class="text-sm text-gray-800 mt-1">
                                                    {{ strtolower($organizer->owner_email ?? 'Não informado') }}
                                                </p>
                                            </div>
                                            @if($organizer->owner_phone_ddd && $organizer->owner_phone_num)
                                            <div>
                                                <span class="text-xs text-gray-500 uppercase tracking-wider">Telefone</span>
                                                <p class="text-sm text-gray-800 mt-1">
                                                    {{ putMask($organizer->owner_phone_ddd . $organizer->owner_phone_num, 'telefone') }}
                                                </p>
                                            </div>
                                            @endif
                                            <div>
                                                <span class="text-xs text-gray-500 uppercase tracking-wider">Filial</span>
                                                <p class="text-sm text-gray-800 mt-1">
                                                    @if(is_null($organizer->organization_id))
                                                        <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                            </svg>
                                                            Própria Empresa
                                                        </span>
                                                    @else
                                                        {{ $organizer->organization->organization_name ?? 'Filial não encontrada' }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-12">
                                    {{-- HEADER DO ACCORDION --}}
                                    <div class="flex justify-between items-center mb-4">
                                        <div><span>Usuários Associados ({{ $organizer->users->count() }})</span></div>
                                        <x-button flat lime icon="user-add" label="Adicionar Usuário" wire:click="setNovoUsuario('{{ $organizer->id }}')" class="px-4 py-2 text-sm font-medium hover:bg-lime-50 transition-colors duration-200 rounded-lg" />
                                    </div>

                                    {{-- CONTEÚDO DO ACCORDION --}}
                                    <div>

                                        @if (($novoUsuario ?? false) && $novoUsuario == $organizer->id)
                                        <div class="mb-4">
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                                <h5 class="text-lg font-medium text-blue-800 mb-4">Adicionar Usuário</h5>
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
                                                <div class="">
                                                    <h6 class="text-sm font-medium text-gray-700 uppercase tracking-wider mb-4">Ou Selecionar Usuário Existente</h6>
                                                    <div class="flex flex-col md:flex-row gap-4 items-end">
                                                        <div class="flex-1">
                                                            <x-native-select wire:model="novoUsuarioId" class="w-full">
                                                                <option value="">Selecione um usuário existente</option>
                                                                @foreach (($this->novoUsuarioListUsers ?? collect([]))->sortBy('name') as $userItem)
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

                                        <div class="space-y-2">
                                        @forelse (($organizer->users ?? collect([]))->sortBy('name') as $user)
                                            <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors duration-200">
                                                <div class="flex-1 grid grid-cols-1 md:grid-cols-5 gap-4">
                                                    <div class="col-span-2">
                                                        <span class="text-xs text-gray-500 uppercase tracking-wider">Nome</span>
                                                        <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                                    </div>
                                                    <div class="col-span-2">
                                                        <span class="text-xs text-gray-500 uppercase tracking-wider">Email</span>
                                                        <p class="text-gray-700">{{ $user->email }}</p>
                                                    </div>
                                                    <div class="flex justify-between gap-2">
                                                        <div>
                                                            <span class="text-xs text-gray-500 uppercase tracking-wider">Telefone</span>
                                                            <p class="text-gray-700">{{ putMask($user->contact_ddd . $user->contact_num, 'telefone') }}</p>
                                                        </div>
                                                        <div class="flex items-center">
                                                            @if ($user->email != auth()->user()->email)
                                                                <x-button flat negative icon="trash" title="Remover usuário"
                                                                    onclick="confirm('Confirma a remoção do usuário deste organizador?') || event.stopImmediatePropagation()"
                                                                    wire:click="desassociarUsuario('{{ $organizer->id }}','{{ $user->id }}')"
                                                                    class="p-2 hover:bg-red-50 rounded-lg transition-colors duration-200" />
                                                            @endif
                                                        </div>
                                                    </div>
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

                                    </div> {{-- Fim do accordion x-show --}}
                                </div> {{-- Fim do x-data --}}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="max-w-md mx-auto">
                                @if ($organization_id ?? false)
                                    <svg class="w-16 h-16 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <h3 class="text-xl font-medium text-gray-800 mb-3">Nenhum centro de custo encontrado</h3>
                                    <p class="text-gray-600 mb-6">Esta filial ainda não possui centros de custo cadastrados.</p>
                                    <x-button flat lime label="CADASTRAR PRIMEIRO CENTRO DE CUSTO" wire:click="setNovoOrganizer" class="px-6 py-3 font-medium" />
                                @else
                                    <svg class="w-16 h-16 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <h3 class="text-xl font-medium text-gray-800 mb-3">Selecione uma Filial</h3>
                                    <p class="text-gray-600">Escolha uma filial no filtro acima para visualizar os centros de custo.</p>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

        @endif

        {{-- ============================================
            TAB 2: USUÁRIOS
        ============================================= --}}
        @if (($activeTab ?? 'organizadores') === 'usuarios')

            {{-- HEADER --}}
            <div class="{{ setClass('divContent') }} rounded-none border-x border-b">
                <div class="flex-1">
                    <div class="{{ setClass('divContentTitle') }} uppercase mb-2">
                        <span class="font-light">Usuários da Empresa</span>
                        <p class="text-gray-600 text-sm">Gerencie os usuários do cliente</p>
                    </div>
                </div>
            </div>

            {{-- FILTERS AND SEARCH BAR --}}
            <div class="w-full max-w-7xl mx-auto bg-white border-x border-b px-16 py-4">
                <div class="flex flex-col md:flex-row gap-4 items-end">

                    {{-- Campo de Busca --}}
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <x-input
                            wire:model.debounce.300ms="searchUsuarios"
                            placeholder="Buscar usuário por nome, email ou descrição..."
                            icon="search"
                            class="w-full"
                        />
                    </div>

                    {{-- Botões --}}
                    <div class="flex gap-2">
                        @if(!empty($searchUsuarios))
                            <x-button
                                flat
                                negative
                                icon="x"
                                label="Limpar"
                                wire:click="$set('searchUsuarios', '')"
                                class="whitespace-nowrap"
                            />
                        @endif
                    </div>
                </div>
            </div>

            {{-- USERS LIST --}}
            <div class="w-full max-w-7xl mx-auto bg-white border-x border-b px-16 py-4">
                <div class="pt-6 space-y-6">
                    @forelse ($customerUsers ?? [] as $user)
                        @if(!$user->pivot)
                        @continue
                        @endif
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                            <div class="p-6 grid grid-cols-12 gap-4">
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

        @endif

        {{-- ============================================
            TAB 3: FILIAIS
        ============================================= --}}
        @if (($activeTab ?? 'organizadores') === 'filiais')

            {{-- HEADER --}}
            <div class="{{ setClass('divContent') }} rounded-none border-x border-b">
                <div class="flex-1">
                    <div class="{{ setClass('divContentTitle') }} uppercase mb-2">
                        <span class="font-light">Filiais</span>
                        <p class="text-gray-600 text-sm">Gerencie as filiais da empresa</p>
                    </div>
                </div>
                <div class="flex gap-4 items-center">
                    <x-button flat lime label="NOVA FILIAL" wire:click="cadastrarFilial" class="px-6 py-2 font-medium bg-lime-50 hover:bg-lime-100 transition-colors duration-200 rounded-lg whitespace-nowrap" />
                </div>
            </div>

            {{-- FILIAIS LIST --}}
            <div class="w-full max-w-7xl mx-auto bg-white border-x border-b px-16 py-4">
                <div class="pt-6 space-y-6">
                    @forelse ($organizations ?? [] as $organizationItem)
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                            <div class="p-6 grid grid-cols-12 gap-4">
                                <div class="col-span-12">
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                        <div class="flex-1 space-y-2">
                                            <h3 class="text-xl font-semibold text-gray-800 uppercase">
                                                {{ $organizationItem->organization_name ?? 'N/A' }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                {{ $organizationItem->organization_description ?? 'N/A' }}
                                            </p>
                                            <div class="flex gap-3 mt-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                                    {{ $organizationItem->organizers->count() ?? 0 }} Centro{{ ($organizationItem->organizers->count() ?? 0) != 1 ? 's' : '' }} de Custo
                                                </span>
                                                @if($context !== 'campanhas')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                                    {{ $organizationItem->organizationSubs->count() ?? 0 }} Subdivisão{{ ($organizationItem->organizationSubs->count() ?? 0) != 1 ? 'ões' : '' }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <x-button
                                                flat
                                                primary
                                                icon="pencil-alt"
                                                label="Alterar"
                                                wire:click="alterarFilial('{{ $organizationItem->id }}')"
                                                class="w-auto"
                                            />
                                        </div>
                                    </div>
                                </div>

                                @if (session('organization_success_' . $organizationItem->id))
                                    <div class="col-span-12">
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-green-800 font-medium">{{ __(session('organization_success_' . $organizationItem->id)) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (session('organization_error_' . $organizationItem->id))
                                    <div class="col-span-12">
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-red-800 font-medium">{{ session('organization_error_' . $organizationItem->id) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (($organization_id ?? false) && $organization_id == $organizationItem->id)
                                    <div class="col-span-12 border-t border-gray-200 pt-4 mt-4">
                                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                                            <h4 class="text-lg font-semibold text-gray-800 mb-4 uppercase">Editar Filial</h4>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <x-input label="{{ __('organization_name') }}" wire:model.defer="organization_name" class="uppercase" />
                                                </div>
                                                <div>
                                                    <x-input label="{{ __('organization_description') }}" wire:model.defer="organization_description" class="uppercase" />
                                                </div>
                                            </div>

                                            <div class="flex justify-between gap-x-4 pt-4 border-t border-blue-200">
                                                <x-button flat negative label="Remover" onclick="confirm('Confirma remoção da filial?') || event.stopImmediatePropagation()"  wire:click="removerFilial('{{ $organizationItem->id }}')" class="w-auto" />
                                                <div class="flex gap-2">
                                                    <x-button flat label="Cancelar" wire:click="cancelarEdicaoFilial" />
                                                    <x-button primary label="ALTERAR" wire:click="alterarFilial('{{ $organizationItem->id }}',true)" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="max-w-md mx-auto">
                                <svg class="w-16 h-16 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <h3 class="text-xl font-medium text-gray-800 mb-3">Nenhuma filial encontrada</h3>
                                <p class="text-gray-600 mb-6">Comece criando sua primeira filial.</p>
                                <x-button flat lime label="CADASTRAR PRIMEIRA FILIAL" wire:click="cadastrarFilial" class="px-6 py-3 font-medium" />
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

        @endif

        {{-- ============================================
            TAB 4: SUBDIVISÕES - Only for Eventos
        ============================================= --}}
        @if (($activeTab ?? 'organizadores') === 'setores' && $context !== 'campanhas')

            {{-- HEADER --}}
            <div class="{{ setClass('divContent') }} rounded-none border-x border-b">
                <div class="flex-1">
                    <div class="{{ setClass('divContentTitle') }} uppercase mb-2">
                        <span class="font-light">Subdivisões</span>
                        <p class="text-gray-600 text-sm">Gerencie as subdivisões das filiais</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center w-full sm:w-auto">
                    {{-- <div class="min-w-0 flex-1 sm:min-w-64">
                        <x-native-select xs wire:model="organization_id_for_subs" class="text-gray-700 w-full uppercase pt-2 pb-2">
                            <option value="">Selecione uma Filial</option>
                            @foreach (($this->organizations ?? collect([]))->sortBy('organization_name') as $organization)
                            <option value="{{ $organization->id }}" class="uppercase">{{ $organization->organization_name }}</option>
                            @endforeach
                        </x-native-select>
                    </div> --}}
                    <x-button flat lime label="NOVA SUBDIVISÃO" wire:click="cadastrarCentroCusto" class="px-6 py-2 font-medium hover:bg-lime-50 transition-colors duration-200 rounded-lg whitespace-nowrap" />
                </div>
            </div>

            {{-- FILTERS AND SEARCH BAR --}}
            <div class="w-full max-w-7xl mx-auto bg-white border-x border-b px-16 py-4">
                <div class="flex flex-col md:flex-row gap-4 items-end">
                    {{-- Filtro de Filial --}}
                    <div class="w-full md:w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filial</label>
                        <x-native-select xs wire:model="organization_id_for_subs" class="text-gray-700 w-full uppercase pt-2 pb-2">
                            <option value="">Selecione</option>
                            @foreach (($this->organizations ?? collect([]))->sortBy('organization_name') as $organization)
                            <option value="{{ $organization->id }}" class="uppercase">{{ $organization->organization_name }}</option>
                            @endforeach
                        </x-native-select>
                    </div>

                    {{-- Campo de Busca --}}
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <x-input
                            wire:model.debounce.300ms="searchOrganizationSubs"
                            placeholder="Buscar centro de custo por nome, email ou descrição..."
                            icon="search"
                            class="w-full"
                        />
                    </div>

                    {{-- Botões --}}
                    <div class="flex gap-2">
                        @if(!empty($searchOrganizationSubs))
                            <x-button
                                flat
                                negative
                                icon="x"
                                label="Limpar"
                                wire:click="$set('searchOrganizationSubs', '')"
                                class="whitespace-nowrap"
                            />
                        @endif
                    </div>
                </div>
            </div>

            @if ($organization_id_for_subs ?? false)
                @if (session('organization_sub_success'))
                    <div class="w-full max-w-7xl mx-auto bg-green-50 border border-green-200 rounded-b-lg px-6 py-4">
                        <div class="flex items-center gap-2 text-green-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('organization_sub_success') }}</span>
                        </div>
                    </div>
                @endif

                @php $organizationSubList = $this->getFilteredOrganizationSubs(); @endphp

                {{-- SUBDIVISÕES LIST --}}
                <div class="w-full max-w-7xl mx-auto bg-white border-x border-b px-16 py-4">
                    <div class="pt-6 space-y-6">
                        @forelse ($organizationSubList ?? [] as $organizationSubItem)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                                <div class="p-6 grid grid-cols-12 gap-4">
                                    <div class="col-span-12">
                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                            <div class="flex-1 space-y-2">
                                                <h3 class="text-xl font-semibold text-gray-800 uppercase">
                                                    <span>
                                                        {{ $organizationSubItem->organization_sub_name ?? 'N/A' }}
                                                    </span>
                                                    <span class="text-sm text-gray-600 capitalize">
                                                        {{ $organizationSubItem->organization_sub_description ?? 'N/A' }}
                                                    </span>
                                                </h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ $organizationSubItem->organizers->count() ?? 0 }} Centro{{ ($organizationSubItem->organizers->count() ?? 0) != 1 ? 's' : '' }} de Custo
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <x-button
                                                    flat
                                                    primary
                                                    icon="pencil-alt"
                                                    label="Alterar"
                                                    wire:click="alterarOrganizationSub('{{ $organizationSubItem->id }}')"
                                                    class="w-auto"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    @if (session('organization_sub_error_' . $organizationSubItem->id))
                                        <div class="col-span-12">
                                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                                <div class="flex items-center text-red-800 gap-2">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 011.414 0L12 9.172l1.879-1.88a1 1 0 111.415 1.415L13.415 10.6l1.879 1.879a1 1 0 11-1.415 1.415L12 12.015l-1.879 1.879a1 1 0 11-1.414-1.415L10.586 10.6 8.707 8.721a1 1 0 010-1.428z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="font-medium">{{ session('organization_sub_error_' . $organizationSubItem->id) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (session('organization_success_' . $organizationSubItem->id))
                                        <div class="col-span-12">
                                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-green-800 font-medium">{{ __(session('organization_success_' . $organizationSubItem->id)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (($organization_sub_id ?? false) && $organization_sub_id == $organizationSubItem->id)
                                        <div class="col-span-12">
                                            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                                                <h4 class="text-lg font-semibold text-gray-800 mb-4 uppercase">Editar Centro de Custo</h4>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                    <div>
                                                        <x-input label="{{ __('organization_sub_name') }}" wire:model.defer="organization_sub_name" class="uppercase" />
                                                    </div>
                                                    <div>
                                                        <x-input label="{{ __('organization_sub_description') }}" wire:model.defer="organization_sub_description" class="uppercase" />
                                                    </div>
                                                </div>

                                                <div class="flex justify-between gap-x-4">
                                                    <x-button negative outline label="REMOVER" onclick="confirm('Confirma remoção do centro de custo?') || event.stopImmediatePropagation()"  wire:click="removerCentroCusto('{{ $organizationSubItem->id }}')" class="w-auto" />
                                                    <div class="flex gap-2">
                                                        <x-button flat label="CANCELAR" wire:click="$set('organization_sub_id',false)" />
                                                        <x-button primary label="ALTERAR" wire:click="alterarOrganizationSub('{{ $organizationSubItem->id }}',true)" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="max-w-md mx-auto">
                                    <svg class="w-16 h-16 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <h3 class="text-xl font-medium text-gray-800 mb-3">Nenhuma subdivisão encontrada</h3>
                                    <p class="text-gray-600 mb-6">Esta filial ainda não possui subdivisões cadastradas.</p>
                                    <x-button flat lime label="CADASTRAR PRIMEIRA SUBDIVISÃO" wire:click="cadastrarCentroCusto" class="px-6 py-3 font-medium" />
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="w-full max-w-7xl mx-auto bg-white border-x border-b p-16">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Selecione uma Filial</h3>
                        <p class="text-gray-600">Escolha uma filial no filtro acima para visualizar suas subdivisões.</p>
                    </div>
                </div>
            @endif

        @endif

    @elseif($customers ?? false)

        {{-- NO CUSTOMER SELECTED --}}
        <div class="{{ setClass('divContentTitleDiv') }} rounded-t-lg">
            <div>
                <div class="{{ setClass('divContentTitle') }}">Gerenciar {{ $context === 'campanhas' ? 'Campanhas' : 'Eventos' }}</div>
                <p class="text-gray-600 text-sm mt-2">Selecione uma empresa para começar</p>
            </div>
        </div>
        <div class="w-full max-w-7xl mx-auto bg-white border-x border-b px-16 py-4 p-8">
            <div class="max-w-lg mx-auto p-6">
                <div class="text-center mb-6">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Selecione uma Empresa</h3>
                    <p class="text-sm text-gray-600">Escolha a empresa que deseja gerenciar</p>
                </div>

                <x-native-select label="" wire:model="customer_id" class="w-full uppercase font-normal text-gray-700 rounded-lg border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                    <option value="">Selecione uma empresa</option>
                    @foreach ($customers->sortBy('customer_slug') as $customer_item)
                    <option value="{{ $customer_item->id }}" class="uppercase">{{ $customer_item->doc_type }} {{ $customer_item->doc_num }} - {{ $customer_item->name_corporate }}</option>
                    @endforeach
                </x-native-select>
            </div>
        </div>

    @else

        {{-- NO ACCESS --}}
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

    {{-- NAO REMOVER --}}
    <br>

</div>
