<div class="min-h-screen">
    <x-notifications position="top-right" />

    @if(session('message'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.$wireui.notify({
                    title: 'Sucesso!',
                    description: '{{ session('message') }}',
                    icon: 'success'
                });
            });
        </script>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-2 sm:px-2 lg:px-0 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-2 sm:px-2 lg:px-0">
        <div class="bg-gradient-to-r from-teal-600 to-emerald-600 shadow-lg rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6M6 18a6 6 0 0110.95-3M18 6a6 6 0 00-10.95 3" />
                        </svg>
                        Assinaturas
                    </h1>
                    <p class="mt-2 text-emerald-100 text-sm">Produtos, planos e assinantes em um so lugar</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-2 sm:px-2 lg:px-0 py-6">

        @if ($customers ?? false)
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Empresa</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Empresa *</label>
                        <x-native-select
                            wire:model="customer_id"
                            class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm uppercase"
                            placeholder="Selecione uma empresa">
                            <option value="">Selecione uma empresa...</option>
                            @foreach(($customers ?? collect())->sortBy('name_corporate') as $item)
                                <option value="{{ $item->id }}" class="uppercase">{{ $item->name_corporate }}</option>
                            @endforeach
                        </x-native-select>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="text-xs uppercase text-gray-500">Produtos</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $stats['products'] ?? 0 }}</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="text-xs uppercase text-gray-500">Planos</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $stats['plans'] ?? 0 }}</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="text-xs uppercase text-gray-500">Assinaturas ativas</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $stats['subscriptions'] ?? 0 }}</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="text-xs uppercase text-gray-500">MRR</div>
                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ toMoney($stats['mrr'] ?? 0, 'R$ ') }}</div>
            </div>
        </div>

        @if(! $customer_id)
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <div class="text-center text-gray-500 py-8">
                    Selecione uma empresa para visualizar os produtos de assinatura.
                </div>
            </div>
        @elseif(($products ?? collect())->isEmpty())
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <div class="text-center text-gray-500 py-8">
                    <div class="text-lg font-semibold text-gray-800 mb-2">Nenhum produto cadastrado</div>
                    <p class="text-sm text-gray-600 mb-4">Crie o primeiro produto para começar a vender assinaturas.</p>
                    <x-button primary label="Criar Produto" wire:click="openNewProductModal" />
                </div>
            </div>
        @else
            <div class="space-y-4">
                <div class="flex flex-col gap-3 px-4 md:flex-row md:items-center md:justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ $products->count() }} {{ $products->count() == 1 ? 'Produto' : 'Produtos' }}
                    </h2>
                    <div>
                        <x-button
                            white
                            label="Novo Produto"
                            icon="plus"
                            wire:click="openNewProductModal"
                            class="shadow-lg hover:shadow-xl transition-all duration-200"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @foreach($products as $product)
                        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-200 overflow-hidden">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-3">
                                            <a
                                                href="{{ route('dashboard-assinaturas-detalhes', ['product_id' => $product->id]) }}"
                                                class="text-left text-xl font-bold text-gray-900 hover:text-emerald-600 transition-colors uppercase"
                                            >
                                                {{ $product->name }}
                                            </a>
                                            @php
                                                $status = $product->status ?? 'draft';
                                                $statusClass = 'bg-gray-100 text-gray-700';
                                                $statusLabel = 'RASCUNHO';
                                                if ($status === 'active') {
                                                    $statusClass = 'bg-green-100 text-green-700';
                                                    $statusLabel = 'ATIVO';
                                                } elseif ($status === 'paused') {
                                                    $statusClass = 'bg-orange-100 text-orange-700';
                                                    $statusLabel = 'PAUSADO';
                                                } elseif ($status === 'cancelled') {
                                                    $statusClass = 'bg-red-100 text-red-700';
                                                    $statusLabel = 'CANCELADO';
                                                }
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>
                                        <div class="mt-2 text-xs text-emerald-700 font-mono bg-emerald-50 rounded px-2 py-1 inline-flex">
                                            {{ $product->slug }}
                                        </div>
                                        @if($product->description)
                                            <div class="mt-2 text-sm text-gray-600">{{ $product->description }}</div>
                                        @endif
                                        <div class="mt-2 text-xs text-gray-500">
                                            Criado em {{ optional($product->created_at)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('dashboard-assinaturas-detalhes', ['product_id' => $product->id]) }}">
                                            <x-button
                                                primary
                                                label="Visualizar"
                                                icon="eye"
                                                class="whitespace-nowrap shadow-sm hover:shadow-md transition-all duration-200"
                                            />
                                        </a>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-4 mt-4 text-xs text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        {{ $product->plans_count ?? 0 }} plano(s)
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6M6 18a6 6 0 0110.95-3M18 6a6 6 0 00-10.95 3" />
                                        </svg>
                                        {{ $product->subscriptions_active_count ?? 0 }} assinante(s) ativo(s)
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <x-modal.card wire:model="showNewProductModal" title="Novo Produto" max-width="2xl">
        <div class="space-y-4 px-6 pb-6">
            <div>
                <x-input label="Nome do Produto" wire:model.debounce.400ms="newProductName" placeholder="Ex: Clube Storyverse" />
            </div>

            <div>
                <x-input label="Slug (gerado automaticamente)" wire:model="newProductSlug" readonly class="bg-gray-50" />
                <p class="text-xs text-gray-500 mt-1">Gerado automaticamente conforme o nome.</p>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                <x-native-select wire:model.defer="newProductStatus" class="w-full">
                    <option value="draft">Rascunho</option>
                    <option value="active">Ativo</option>
                    <option value="paused">Pausado</option>
                    <option value="cancelled">Cancelado</option>
                </x-native-select>
            </div>

            <div>
                <x-textarea label="Descricao" wire:model.defer="newProductDescription" placeholder="Descreva o produto e o beneficio da assinatura" />
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-button flat label="Cancelar" wire:click="closeNewProductModal" />
                <x-button primary label="Salvar Produto" wire:click="createProduct" spinner="createProduct" />
            </div>
        </x-slot>
    </x-modal.card>

</div>
