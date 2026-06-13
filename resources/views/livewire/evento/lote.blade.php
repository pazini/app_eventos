<div class="w-full max-w-7xl mx-auto mb-10">

    {{-- HEADER MODERNO COM GRADIENTE --}}
    <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern-lote" width="8" height="8" patternUnits="userSpaceOnUse">
                        <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern-lote)"/>
            </svg>
        </div>
        <div class="relative z-10 p-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex-1">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">
                                @if ($this->tickets_type ?? false)
                                    Alterar Lote
                                @else
                                    Cadastrar Lote
                                @endif
                            </h1>
                            <p class="text-white/90 text-sm">
                                {{ $target->event_name }} - {{ formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? 'Sem data definida' }}
                                @if ($this->tickets_type ?? false)
                                    <span class="ml-2">• {{ $this->tickets_type->ticket_name }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <x-button flat white icon="reply" label="VOLTAR" href="{{ route('dashboard-evento') }}" class="hover:bg-white/20" />
            </div>
        </div>
    </div>

    <div class="mb-6">
        <x-jet-validation-errors />
    </div>

    {{-- FORMULÁRIO --}}
    <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">Dados do Lote</h2>
        </div>
        <div class="p-6">
            <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4">

                <div class="col-span-full md:col-span-4">
                    <x-input label="{{ __('ticket_name') }}" title="{{ __('ticket_name_title') }}" wire:model.defer="ticket_name" />
                </div>

                <div class="col-span-full md:col-span-6">
                    <x-input label="{{ __('ticket_description') }}" title="{{ __('ticket_description_title') }}" wire:model.defer="ticket_description" />
                </div>

                <div class="col-span-full md:col-span-2">
                    <x-native-select
                        label="Visível"
                        wire:model="lote_publico"
                        >
                        <option value="1">SIM</option>
                        <option value="0">NÃO</option>
                    </x-native-select>
                </div>

                <div class="col-span-full">
                    <hr class="my-4">
                </div>

                <div class="col-span-full md:col-span-2">
                    <x-input label="{{ __('amount') }}" title="{{ __('ticket_amount_title') }}" wire:model.defer="amount" type="number" min="0" />
                </div>

                <div class="col-span-full md:col-span-2">
                    <x-input label="{{ __('price') }}" title="{{ __('ticket_price_title') }}" wire:model.defer="price" prefix="R$ " class="w-full pl-10" type="number" min="1" step="any" />
                </div>

                <div class="col-span-full md:col-span-2">
                    <x-native-select
                        label="{{ __('sale_period_type') }}"
                        wire:model.defer="sale_period_type"
                        >
                        <option value="">--</option>
                        @foreach ($this->listSalePeriodType ?? [] as $typeKey => $typeValue)
                        <option value="{{ $typeKey }}">{{ $typeValue }}</option>
                        @endforeach
                    </x-native-select>
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-input label="{{ __('sale_start_datetime') }}" title="{{ __('sale_start_datetime_title') }}" type="datetime-local" wire:model.defer="sale_start_datetime" />
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-input label="{{ __('sale_finish_datetime') }}" title="{{ __('sale_finish_datetime_title') }}" type="datetime-local" wire:model.defer="sale_finish_datetime" />
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-input label="{{ __('sale_amount_min') }}" wire:model.defer="sale_amount_min" type="number" min="1" />
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-input label="{{ __('sale_amount_max') }}" wire:model.defer="sale_amount_max" type="number" min="1" />
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-input label="{{ __('sale_label_title') }}" wire:model.defer="sale_label_title" />
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-input label="{{ __('sale_label_btn') }}" wire:model.defer="sale_label_btn" />
                </div>

            </div>
        </div>

        <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                @if ($this->tickets_type ?? false)
                    <x-button lg red spinner flat label="REMOVER LOTE" onclick="confirm('ATENÇÃO - Tem certeza que deseja remover esse lote? Novos pedidos não poderão ser realizados!') || event.stopImmediatePropagation()" wire:click="loteRemove('{{ $this->tickets_type->id }}')" />
                @endif
            </div>
            <div class="flex gap-3">
                @if ($this->tickets_type ?? false)
                    <x-button lg positive spinner label="ALTERAR" onclick="confirm('Confirma a alteração?') || event.stopImmediatePropagation()" wire:click="loteSubmit" />
                @else
                    <x-button lg positive spinner label="CADASTRAR" onclick="confirm('Confirma o cadastro?') || event.stopImmediatePropagation()" wire:click="loteSubmit" />
                @endif
            </div>
        </div>
    </div>

    @if ($tickets_type ?? false)
        <div class="mt-4 text-center">
            <span class="text-xs text-gray-400">EVENT: {{$tickets_type->event_id}}</span>
        </div>
    @endif

</div>
