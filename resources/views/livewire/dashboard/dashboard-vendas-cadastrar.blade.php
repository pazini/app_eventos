<div class="w-full max-w-7xl mx-auto mb-6">

    <div class="mb-3">
        <x-jet-validation-errors />
    </div>

    @if ($target ?? false)

        {{-- HEADER MODERNO COM GRADIENTE --}}
        <div class="mb-4 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern-cadastrar" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-cadastrar)"/>
                </svg>
            </div>
            <div class="relative z-10 p-4">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-3">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <div class="p-1.5 bg-white/20 rounded backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-lg font-bold text-white">Cadastrar Venda Manual</h1>
                                <p class="text-white/90 text-xs mt-0.5">{{ $target->event_name }} - {{ formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? 'Sem data definida' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-button flat white xs icon="reply" label="VOLTAR" href="{{ route('dashboard-evento-vendas') }}" class="hover:bg-white/20" />
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD: DADOS DO COMPRADOR --}}
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-2">
                <h2 class="text-base font-semibold text-gray-800">Dados do Comprador</h2>
            </div>
            <div class="p-4">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input placeholder="{{ __('buyer_name_placeholder') }}" label="{{ __('buyer_name') }}" wire:model.defer="buyer_name" />
                        </div>
                        <div>
                            <x-input placeholder="{{ __('buyer_email_placeholder') }}" label="{{ __('buyer_email') }}" wire:model.defer="buyer_email" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div>
                            <x-input placeholder="{{ __('buyer_birth_date_placeholder') }}" label="{{ __('buyer_birth_date') }}" wire:model.defer="buyer_birth_date" type="date" />
                        </div>
                        <div>
                            <x-native-select label="{{ __('buyer_doc_type') }}" wire:model.defer="buyer_doc_type">
                                <option value="">--</option>
                                @foreach (listDocType() as $itemKey => $itemValue)
                                    <option value="{{ $itemKey }}">{{ $itemValue }}</option>
                                @endforeach
                            </x-native-select>
                        </div>
                        <div>
                            <x-inputs.maskable
                                label="{{ __('buyer_doc_num') }}"
                                placeholder="{{ __('buyer_doc_num_placeholder') }}"
                                wire:model.defer="buyer_doc_num"
                                mask="['###.###.###-##','##.###.###/####-##']"
                            />
                        </div>
                        <div>
                            <x-native-select label="{{ __('buyer_contact_ddd') }}" wire:model.defer="buyer_contact_ddd">
                                <option value="">--</option>
                                @foreach (listDdd() as $itemKey => $itemValue)
                                    <option value="{{ $itemKey }}">{{ $itemKey }}</option>
                                @endforeach
                            </x-native-select>
                        </div>
                        <div>
                            <x-inputs.maskable
                                label="{{ __('buyer_contact_num') }}"
                                placeholder="{{ __('buyer_contact_num_placeholder') }}"
                                wire:model.defer="buyer_contact_num"
                                mask="['####-####', '#####-####']"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD: ITENS --}}
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-2">
                <h2 class="text-base font-semibold text-gray-800">Itens da Compra</h2>
            </div>
            <div class="p-4">
                {{-- ADICIONAR ITEM --}}
                <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-5">
                            <x-input placeholder="Nome do Participante" wire:model.defer="user_name" class="uppercase" />
                        </div>
                        <div class="md:col-span-6">
                            <x-native-select wire:model.defer="item_ticket_type_id">
                                <option value="">Selecione o tipo</option>
                                @foreach ($target->ticketsTypes as $itemValue)
                                    <option value="{{ $itemValue->id }}">{{ $itemValue->ticket_name }} - {{ toMoney($itemValue->price,'R$ ') }}</option>
                                @endforeach
                            </x-native-select>
                        </div>
                        <div class="md:col-span-1">
                            <x-button positive label="+" class="w-full" wire:click="adicionaItem" />
                        </div>
                    </div>
                </div>

                {{-- LISTA DE ITENS --}}
                <div class="space-y-2">
                    @php
                        $itneCount = 1;
                    @endphp
                    @forelse ($compraItens ?? [] as $orderKey => $orderItem)
                        <div class="border border-gray-200 rounded p-3 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-gray-900">
                                        <span class="text-gray-500">{{ $itneCount++ }} - </span>
                                        <span>{{ $target->event_tickets_nomenclature ?? 'INGRESSO' }}</span>
                                        <span class="uppercase">{{ $orderItem['item_description'] ?? 'ND' }}</span>
                                        <span class="text-gray-600 uppercase"> - {{ $orderItem['user_name'] ?? 'PARTICIPANTE #' . $itneCount }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 ml-4">
                                    <div class="text-base font-bold text-gray-900">{{ toMoney($orderItem['item_amount'] ?? 0, 'R$ ') }}</div>
                                    <x-button negative xs label="X" wire:click="removeItem('{{ $orderKey }}')" />
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 border border-gray-200 rounded-lg bg-gray-50">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-900">Nenhum item adicionado</p>
                            <p class="text-xs text-gray-500 mt-1">Adicione itens usando o formulário acima</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- CARD: VALORES --}}
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-2">
                <h2 class="text-base font-semibold text-gray-800">Valores</h2>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center py-2 bg-green-50 rounded-lg px-4">
                    <div class="text-sm font-medium text-green-700">Total da Compra</div>
                    <div class="text-lg font-bold text-green-700">{{ toMoney($order['order_amount'] ?? 0, 'R$ ') }}</div>
                </div>
            </div>
        </div>

        {{-- FOOTER COM BOTÃO --}}
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
            <div class="p-4 flex justify-end">
                <x-button 
                    primary 
                    label="CADASTRAR" 
                    right-icon="check"
                    onclick="confirm('Confirma a criação da compra de {{ count($compraItens ?? []) }} itens, no valor total de {{ toMoney($order['order_amount'] ?? 0, 'R$ ') }}?!') || event.stopImmediatePropagation()" 
                    wire:click="criarOrder" 
                    spinner="criarOrder"
                />
            </div>
        </div>

    @else

        {{-- SEM TARGET SELECIONADO --}}
        <div class="mb-4 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="relative z-10 p-4">
                <div class="flex items-center space-x-2">
                    <div class="p-1.5 bg-white/20 rounded backdrop-blur-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white">Cadastrar Venda Manual</h1>
                        <p class="text-white/90 text-xs mt-0.5">É preciso selecionar um evento</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
            <div class="p-6 text-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Página Principal
                </a>
            </div>
        </div>

    @endif

</div>
