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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 8H7a2 2 0 01-2-2V6a2 2 0 012-2h6l6 6v8a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ $planId ? 'Editar Plano' : 'Novo Plano' }}
                    </h1>
                    <p class="mt-2 text-emerald-100 text-sm">
                        {{ $planId ? 'Ajuste os detalhes do plano da assinatura.' : 'Crie um novo plano para esta assinatura.' }}
                    </p>
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <x-input label="Nome do Plano *" wire:model.defer="plan_name" />
                    </div>
                    <div>
                        <x-input label="Codigo do Plano" wire:model.defer="plan_code" />
                    </div>
                    <div>
                        <x-native-select label="Status *" wire:model.defer="status">
                            <option value="active">Ativo</option>
                            <option value="paused">Pausado</option>
                            <option value="cancelled">Cancelado</option>
                        </x-native-select>
                    </div>
                    <div>
                        <x-input label="Trial (dias)" type="number" min="0" wire:model.defer="trial_days" />
                    </div>
                    <div class="flex items-center gap-3">
                        <x-toggle label="Plano padrao" wire:model.defer="is_default" />
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
                    Descricao do Plano
                </h3>
            </div>
            <div class="p-6">
                <div wire:key="editor-plan-description">
                    <label class="block text-base font-light uppercase text-black mb-1">Descricao</label>
                    <div id="toolbar-plan-description" wire:ignore></div>
                    <div class="w-full border border-gray-300 bg-white" wire:ignore>
                        <div id="plan_description_editor" style="min-height: 180px;">{!! $description !!}</div>
                    </div>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white border rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12a2 2 0 012 2v10a2 2 0 01-2 2H3a2 2 0 01-2-2V7a2 2 0 012-2zm14 4l4 4m0 0l-4 4m4-4H9"></path>
                    </svg>
                    Imagem de Destque do Plano
                </h3>
            </div>
            <div class="p-6">
                <label class="text-xs font-semibold text-gray-700 uppercase mb-2">Imagem (opcional)</label>
                @if($preview_header)
                    @php
                        $previewHeaderUrl = str_starts_with($preview_header ?? '', '/storage/')
                            ? asset($preview_header)
                            : tenantAsset($preview_header, true);
                    @endphp
                    <div class="mt-2 relative">
                        <img src="{{ $previewHeaderUrl }}" alt="Preview Header" class="w-full h-40 object-cover rounded border">
                        <button type="button" wire:click="removerHeader" class="absolute top-2 right-2 bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                            Remover
                        </button>
                    </div>
                @else
                    <input type="file" wire:model="image_header" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                    @error('image_header')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                @endif
                <div wire:loading wire:target="image_header" class="text-xs text-emerald-600 mt-1">Carregando imagem...</div>
            </div>
        </div>

        <div class="bg-white border rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0-6a10 10 0 100 20 10 10 0 000-20z"></path>
                    </svg>
                    Pagamento do Plano
                </h3>
            </div>
            <div class="p-6 space-y-4">
                @if(($availableGateways ?? collect())->isEmpty())
                    <div class="text-sm text-gray-500">Nenhum gateway configurado para esta empresa.</div>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-full">
                        <x-native-select label="Gateway" wire:model="pay_gateway_id">
                            <option value="">Selecione um gateway...</option>
                            @foreach($availableGateways ?? [] as $gatewayItem)
                                <option value="{{ $gatewayItem->id }}">{{ $gatewayItem->pay_gateway_label ?? $gatewayItem->id }}</option>
                            @endforeach
                        </x-native-select>
                        @error('pay_gateway_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center gap-3">
                        <x-toggle label="PIX" wire:model.defer="pay_pix" />
                    </div>
                    <div class="flex items-center gap-3">
                        <x-toggle label="Boleto" wire:model.defer="pay_boleto" />
                    </div>
                    <div class="flex items-center gap-3">
                        <x-toggle label="Cartao de credito" wire:model="pay_card_credit" />
                    </div>
                    <div class="flex items-center gap-3">
                        <x-toggle label="Modo teste" wire:model.defer="pay_sandbox" />
                    </div>
                </div>

                @if($pay_card_credit)
                    @php
                        $selectedGateway = ($availableGateways ?? collect())->firstWhere('id', $pay_gateway_id);
                        $installmentLimit = $selectedGateway->pay_card_credit_installment_max ?? 12;
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t pt-4">
                        <x-native-select label="Maximo de parcelas" wire:model.defer="pay_card_credit_installment_max">
                            @foreach(range(1, max(1, (int) $installmentLimit)) as $installmentOption)
                                <option value="{{ $installmentOption }}">{{ $installmentOption }}x</option>
                            @endforeach
                        </x-native-select>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 uppercase">Valor minimo da parcela (R$)</label>
                            <div
                                class="flex items-stretch rounded-lg border border-gray-300 overflow-hidden focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-transparent bg-white"
                                x-data="currencyField('{{ $pay_card_credit_installment_amount_min_input ?? '' }}')"
                                x-init="init()"
                            >
                                <span class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 border-r border-gray-200 flex justify-center items-center">
                                    R$
                                </span>
                                <input
                                    type="text"
                                    x-model="display"
                                    x-on:input="handleInput($event.target.value)"
                                    x-on:blur="updateModel()"
                                    inputmode="decimal"
                                    pattern="[0-9.,]*"
                                    placeholder="0,00"
                                    maxlength="18"
                                    class="border-none rounded-lg flex-1 px-4 py-2 text-left text-base font-semibold text-gray-900 placeholder-gray-400 focus:outline-none"
                                />
                            </div>
                            @error('pay_card_credit_installment_amount_min')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <input type="hidden" wire:model.defer="pay_card_credit_installment_amount_min_input" />
                        </div>
                        <x-native-select label="Pagador dos juros" wire:model.defer="pay_card_credit_installment_fee_payer">
                            <option value="customer">Cliente</option>
                            <option value="merchant">Empresa</option>
                        </x-native-select>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-end gap-3 pt-4 border-t">
            <x-button outline label="Cancelar" href="{{ route('dashboard-assinaturas-detalhes', ['product_id' => $productId]) }}" />
            <x-button primary label="{{ $planId ? 'Salvar Alteracoes' : 'Criar Plano' }}" wire:click="save" spinner="save" />
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initPlanEditor();
        });

        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.processed', () => {
                setTimeout(() => initPlanEditor(), 100);
            });
        });

        function initPlanEditor() {
            if (document.querySelector('#plan_description_editor') && !document.querySelector('#plan_description_editor').classList.contains('ck-editor__editable')) {
                DecoupledEditor
                    .create(document.querySelector('#plan_description_editor'))
                    .then(editor => {
                        const toolbarContainer = document.querySelector('#toolbar-plan-description');
                        toolbarContainer.innerHTML = '';
                        toolbarContainer.appendChild(editor.ui.view.toolbar.element);

                        editor.model.document.on('change:data', () => {
                            @this.set('description', editor.getData());
                        });
                    })
                .catch(error => console.error('Erro editor descricao:', error));
            }
        }
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('currencyField', (initialValue) => ({
                display: '',
                rawValue: '',
                init() {
                    this.display = this.format(initialValue ?? '');
                    this.rawValue = this.getRawValue(initialValue ?? '');
                },
                handleInput(value) {
                    this.display = this.format(value);
                    this.rawValue = this.getRawValue(value);
                },
                updateModel() {
                    const digits = (this.rawValue || '').toString().replace(/\D/g, '');
                    const number = (parseInt(digits || '0', 10) / 100).toFixed(2);
                    const formattedValue = number.replace('.', ',');

                    const hiddenInput = this.$el.closest('div')?.querySelector('input[type="hidden"]');
                    if (hiddenInput) {
                        hiddenInput.value = formattedValue;
                        hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                },
                getRawValue(value) {
                    if (!value) return '';
                    return value.toString().replace(/\D/g, '');
                },
                format(value) {
                    const digits = (value || '').toString().replace(/\D/g, '');
                    const number = (parseInt(digits || '0', 10) / 100).toFixed(2);
                    const [intPart, decimalPart] = number.split('.');
                    const formattedInt = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    return `${formattedInt},${decimalPart}`;
                },
            }));
        });
    </script>

    <style>.ck-file-dialog-button {display: none;}</style>
</div>
