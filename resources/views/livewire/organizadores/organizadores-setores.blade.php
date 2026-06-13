<div class="mb-10">

    @if ($customer ?? false)

        <div class="{{ setClass('divContentHeader') }} relative overflow-hidden">
            <!-- Decorative Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern-setores" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-setores)"/>
                </svg>
            </div>

            <div class="relative z-10 w-full space-y-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            {!! setLabelHeader($this->customer->name_corporate ?? 'Setores', 'Setores') !!}
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                        <div class="min-w-0 flex-1 sm:min-w-48">
                            <label for="" class="block text-sm font-medium text-white/90 mb-2">Empresas</label>
                            <x-native-select xs wire:model.live="customer_id" class="text-gray-700 w-full uppercase pt-2 pb-2 bg-white/95 backdrop-blur-sm border-white/30 focus:border-white focus:ring-2 focus:ring-white/50 rounded-lg shadow-sm transition-all duration-200">
                                <option value="">Selecione</option>
                                @foreach ($customers->sortBy('customer_slug') ?? [] as $customer_item)
                                <option value="{{ $customer_item->id }}" class="uppercase">{{ $customer_item->name_corporate }}</option>
                                @endforeach
                            </x-native-select>
                        </div>
                        <div class="min-w-0 flex-1 sm:min-w-48">
                            <label for="" class="block text-sm font-medium text-white/90 mb-2">Filiais</label>
                            <x-native-select xs wire:model.live="organization_id" class="text-gray-700 w-full uppercase pt-2 pb-2 bg-white/95 backdrop-blur-sm border-white/30 focus:border-white focus:ring-2 focus:ring-white/50 rounded-lg shadow-sm transition-all duration-200">
                                <option value="">Selecione</option>
                                @foreach (($this->organizations ?? false) ? $this->organizations->sortBy('organization_name') : [] as $organization)
                                <option value="{{ $organization->id }}" class="uppercase">{{ $organization->organization_name }}</option>
                                @endforeach
                            </x-native-select>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/30"></div>

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <nav class="flex flex-wrap gap-2">
                        <x-button white sm label="Usuários" href="{{ route($context . '-organizadores-usuarios') }}" class="px-4 py-2 bg-white/95 text-sky-600 hover:bg-white hover:text-sky-700 transition-all duration-200 rounded-lg font-medium shadow-sm" />
                        <x-button white sm label="Filiais" href="{{ route($context . '-organizadores-instituicoes') }}" class="px-4 py-2 bg-white/95 text-sky-600 hover:bg-white hover:text-sky-700 transition-all duration-200 rounded-lg font-medium shadow-sm" />
                        <x-button white sm outline label="Setores" class="px-4 py-2 bg-white/10 backdrop-blur-sm border-white/30 hover:bg-white/20 hover:text-white transition-all duration-200 rounded-lg font-medium shadow-sm" />
                        <x-button white sm label="Organizadores" href="{{ route($context . '-organizadores') }}" class="px-4 py-2 bg-white/95 text-sky-600 hover:bg-white hover:text-sky-700 transition-all duration-200 rounded-lg font-medium shadow-sm" />
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

    @if ($this->organization_id ?? false)

        {{-- SETORES --}}
        <div class="{{ setClass('divContent') }} rounded-none border-x border-b">
            <div class="flex-1">
                <div class="{{ setClass('divContentTitle') }} uppercase mb-2">
                    <span class="font-light">Setores</span>
                    @if ($this->organization ?? false)
                        <span class="font-medium text-sky-600 ml-2">{{ strtolower($this->organization->organization_name ?? '') }}</span>
                    @endif
                </div>
                @if ($this->organization ?? false)
                    <p class="text-gray-600 text-sm">Gerencie os setores desta filial</p>
                @endif
            </div>
            <div class="flex gap-4 items-center">
                <x-button flat lime label="NOVO SETOR" wire:click="cadastrar" class="px-6 py-2 font-medium hover:bg-lime-50 transition-colors duration-200 rounded-lg" />
            </div>
        </div>

        {{-- MODAL NOVO SETOR --}}
        <x-modal.card title="Novo Setor" blur wire:model.defer="organizationSubCadastrar">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div class="col-span-full">
                    <p class="text-sm text-gray-600 mb-4">Cadastre um novo setor para <strong>{{ $this->organization->organization_name ?? '' }}</strong></p>
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
                        <x-button flat label="Cancelar" x-on:click="close" />
                        <x-button primary label="CADASTRAR" wire:click="cadastrar(true)" />
                    </div>
                </div>
            </x-slot>
        </x-modal.card>

        @if (!($organizationSubCadastrar ?? false))

            <div class="w-full max-w-7xl mx-auto bg-white shadow-sm border-x border-b rounded-b-lg">

                <div class="p-6 space-y-6">

                    @forelse ($organization_subs ?? [] as $organizationSubItem)
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">

                            <div class="p-6 grid grid-cols-12 gap-4">

                                <!-- Cabeçalho do Setor -->
                                <div class="col-span-12 border-b border-gray-100 pb-4 mb-4">
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                        <div class="flex-1 space-y-2">
                                            <h3 class="text-xl font-semibold text-gray-800 uppercase">
                                                {{ $organizationSubItem->organization_sub_name ?? 'N/A' }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                {{ $organizationSubItem->organization_sub_description ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center">
                                            <x-dropdown>
                                                <x-dropdown.item icon="pencil-alt" label="ALTERAR" wire:click="alterarOrganizationSub('{{ $organizationSubItem->id }}')" class="w-auto" />
                                            </x-dropdown>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mensagens de Feedback -->
                                @if (session('organization_success_' . $organizationSubItem->id))
                                    <div class="col-span-12 mb-4">
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

                                @if (session('organization_status_' . $organizationSubItem->id))
                                    <div class="col-span-12 mb-4">
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-blue-800 font-medium">{{ __(session('organization_status_' . $organizationSubItem->id)) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (session('organization_error_' . $organizationSubItem->id))
                                    <div class="col-span-12 mb-4">
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-red-800 font-medium">{{ __(session('organization_error_' . $organizationSubItem->id)) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (($organization_sub_id ?? false) && $organization_sub_id == $organizationSubItem->id)

                                    <div class="col-span-12 border-t border-gray-200 pt-4 mt-4">
                                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                                            <h4 class="text-lg font-semibold text-gray-800 mb-4 uppercase">Editar Setor</h4>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <x-input label="{{ __('organization_sub_name') }}" wire:model.defer="organization_sub_name" class="uppercase" />
                                                </div>

                                                <div>
                                                    <x-input label="{{ __('organization_sub_description') }}" wire:model.defer="organization_sub_description" class="uppercase" />
                                                </div>
                                            </div>

                                            <div class="flex justify-between gap-x-4 pt-4 border-t border-blue-200">
                                                <x-button negative outline label="REMOVER" onclick="confirm('Confirma remoção do setor?') || event.stopImmediatePropagation()"  wire:click="remover('{{ $organizationSubItem->id }}')" class="w-auto" />
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <h3 class="text-xl font-medium text-gray-800 mb-3">Nenhum setor encontrado</h3>
                                <p class="text-gray-600 mb-6">Esta filial ainda não possui setores cadastrados.</p>
                                <x-button flat lime label="CADASTRAR PRIMEIRO SETOR" wire:click="cadastrar" class="px-6 py-3 font-medium" />
                            </div>
                        </div>
                    @endforelse

                </div>
            </div>

        @endif

    @else
        <div class="w-full max-w-7xl mx-auto bg-white shadow-sm border-x border-b rounded-b-lg p-12">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="text-xl font-medium text-gray-800 mb-3">Selecione uma Filial</h3>
                <p class="text-gray-600">Escolha uma filial no filtro acima para visualizar seus setores.</p>
            </div>
        </div>
    @endif

    @endif

</div>
