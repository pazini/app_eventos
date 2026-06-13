<div class="min-h-screen">
    <x-notifications position="top-right" />

    @if(session('message'))
        <div class="max-w-7xl mx-auto px-2 sm:px-2 lg:px-0 mt-4">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg px-4 py-3">
                {{ session('message') }}
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-2 sm:px-2 lg:px-0">
        <div class="bg-gradient-to-r from-teal-600 to-emerald-600 shadow-lg rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar Assinatura
                    </h1>
                    <p class="mt-2 text-emerald-100 text-sm">Atualize os dados principais da assinatura.</p>
                </div>
                <x-button white label="VOLTAR" href="{{ route('dashboard-assinaturas-detalhes', ['product_id' => $productId]) }}" />
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-2 sm:px-2 lg:px-0 py-6 space-y-6">
        <x-jet-validation-errors />

        <div class="bg-white border rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informacoes Basicas
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input label="Nome da Assinatura *" wire:model.defer="name" />
                    </div>
                    <div>
                        <x-input label="Nome Curto" wire:model.defer="name_short" />
                    </div>
                    <div>
                        <x-input label="Slug (nao editavel)" wire:model="slug" readonly class="bg-gray-50" />
                    </div>
                    <div>
                        <x-native-select label="Status *" wire:model.defer="status">
                            <option value="draft">Rascunho</option>
                            <option value="active">Ativo</option>
                            <option value="paused">Pausado</option>
                            <option value="cancelled">Cancelado</option>
                        </x-native-select>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-toggle label="Visibilidade Publica" wire:model.defer="visibility_public" />
                    </div>
                    <div></div>
                    <div>
                        <x-input label="Data inicio" type="date" wire:model.defer="datetime_start" />
                    </div>
                    <div>
                        <x-input label="Data fim" type="date" wire:model.defer="datetime_finish" />
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white border rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                    Conteudo Descritivo
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <x-textarea label="Descricao" wire:model.defer="description" />
                <x-textarea label="Sobre (detalhes)" wire:model.defer="about" />
            </div>
        </div>

        <div class="bg-white border rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12a2 2 0 012 2v10a2 2 0 01-2 2H3a2 2 0 01-2-2V7a2 2 0 012-2zm14 4l4 4m0 0l-4 4m4-4H9"></path>
                    </svg>
                    Imagem de Cabecalho
                </h3>
            </div>
            <div class="p-6">
                <label class="text-xs font-semibold text-gray-700 uppercase mb-2">Imagem (opcional)</label>
                @if($preview_banner)
                    @php
                        $previewBannerUrl = str_starts_with($preview_banner ?? '', '/storage/')
                            ? asset($preview_banner)
                            : tenantAsset($preview_banner, true);
                    @endphp
                    <div class="mt-2 relative">
                        <img src="{{ $previewBannerUrl }}" alt="Preview Banner" class="w-full h-40 object-cover rounded border">
                        <button type="button" wire:click="removerBanner" class="absolute top-2 right-2 bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                            Remover
                        </button>
                    </div>
                @else
                    <input type="file" wire:model="image_banner" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                    @error('image_banner')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                @endif
                <div wire:loading wire:target="image_banner" class="text-xs text-emerald-600 mt-1">Carregando imagem...</div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pt-4 border-t">
            <button
                type="button"
                wire:click="openDeleteModal"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-700 bg-white border border-red-200 rounded-lg hover:bg-red-50 hover:border-red-300 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                APAGAR ASSINATURA
            </button>

            <div class="flex gap-3">
                <x-button outline label="CANCELAR" href="{{ route('dashboard-assinaturas-detalhes', ['product_id' => $productId]) }}" />
                <x-button primary label="Salvar Alteracoes" wire:click="save" />
            </div>
        </div>
    </div>

    <x-modal.card blur wire:model="showDeleteModal" title="Apagar Assinatura">
        @php
            $summary = $deleteSummary ?? [];
            $plans = $summary['plans'] ?? 0;
            $subscriptions = $summary['subscriptions'] ?? 0;
            $cycles = $summary['cycles'] ?? 0;
        @endphp

        @if($plans > 0 || $subscriptions > 0 || $cycles > 0)
            <div class="p-4 bg-red-50 border border-red-300 rounded">
                <p class="text-red-800 font-bold">Impossivel apagar!</p>
                <p class="text-red-600 text-sm">
                    {{ $subscriptions }} assinatura(s), {{ $cycles }} ciclo(s) e {{ $plans }} plano(s) vinculados.
                </p>
            </div>

            @if(\App\Http\Middleware\EnsureSuperAdmin::check())
                @php
                    $blockAssinaturas = $cycles > 0;
                    $blockPlanos = $subscriptions > 0 || $cycles > 0;
                    $blockProduto = $plans > 0 || $subscriptions > 0 || $cycles > 0;
                @endphp

                <div class="mt-4">
                    <div class="text-xs text-gray-500 mb-2">Apagamento em etapas (super-admin)</div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-3 p-3 border rounded">
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Ciclos</div>
                                <div class="text-xs text-gray-500">{{ $cycles }} registro(s)</div>
                            </div>
                            <button
                                type="button"
                                class="shrink-0 inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                wire:click="apagarCiclos"
                                onclick="confirm('Apagar ciclos desta assinatura?') || event.stopImmediatePropagation()"
                                @if($cycles === 0) disabled @endif
                            >
                                Apagar
                            </button>
                        </div>

                        <div class="flex items-center justify-between gap-3 p-3 border rounded">
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Assinaturas</div>
                                <div class="text-xs text-gray-500">{{ $subscriptions }} registro(s)</div>
                                @if($blockAssinaturas)
                                    <div class="text-xs text-red-600">Bloqueado: apague ciclos antes.</div>
                                @endif
                            </div>
                            <button
                                type="button"
                                class="shrink-0 inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                wire:click="apagarAssinaturas"
                                onclick="confirm('Apagar assinaturas deste produto?') || event.stopImmediatePropagation()"
                                @if($subscriptions === 0 || $blockAssinaturas) disabled @endif
                            >
                                Apagar
                            </button>
                        </div>

                        <div class="flex items-center justify-between gap-3 p-3 border rounded">
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Planos</div>
                                <div class="text-xs text-gray-500">{{ $plans }} registro(s)</div>
                                @if($blockPlanos)
                                    <div class="text-xs text-red-600">Bloqueado: apague assinaturas antes.</div>
                                @endif
                            </div>
                            <button
                                type="button"
                                class="shrink-0 inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                wire:click="apagarPlanos"
                                onclick="confirm('Apagar planos deste produto?') || event.stopImmediatePropagation()"
                                @if($plans === 0 || $blockPlanos) disabled @endif
                            >
                                Apagar
                            </button>
                        </div>

                        <div class="flex items-center justify-between gap-3 p-3 border rounded">
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Produto</div>
                                <div class="text-xs text-gray-500">Registro principal</div>
                                @if($blockProduto)
                                    <div class="text-xs text-red-600">Bloqueado: finalize as etapas anteriores.</div>
                                @endif
                            </div>
                            <button
                                type="button"
                                class="shrink-0 inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                wire:click="apagarProdutoFinal"
                                onclick="confirm('Apagar o produto da assinatura?') || event.stopImmediatePropagation()"
                                @if($blockProduto) disabled @endif
                            >
                                Apagar
                            </button>
                        </div>
                    </div>

                    @error('deleteConfirmationStatus')
                        <div class="text-white bg-red-600 text-sm mt-3 p-2 rounded shadow">{{ $message }}</div>
                    @enderror
                </div>
            @else
                <div class="text-xs text-gray-500 mt-3">Apenas super-admin pode apagar dados por etapas.</div>
            @endif
        @else
            <p class="text-gray-600 mb-4">Digite <strong class="text-red-600">apagar-assinatura</strong> para confirmar.</p>
            <x-input wire:model.defer="deleteConfirmation" placeholder="apagar-assinatura" />
            @error('deleteConfirmationStatus')<div class="text-white bg-red-600 text-sm mt-2 p-2 rounded shadow">{{ $message }}</div>@enderror

            <x-slot name="footer">
                <div class="flex gap-3 justify-end">
                    <x-button flat label="Cancelar" onclick="@this.call('closeDeleteModal')" />
                    <x-button red label="Apagar" onclick="@this.call('apagarAssinatura')" spinner="apagarAssinatura" />
                </div>
            </x-slot>
        @endif
    </x-modal.card>
</div>
