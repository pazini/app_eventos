<div class="w-full max-w-7xl mx-auto mb-10">

    {{-- HEADER MODERNO COM GRADIENTE --}}
    <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern-altera-evento" width="8" height="8" patternUnits="userSpaceOnUse">
                        <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern-altera-evento)"/>
            </svg>
        </div>
        <div class="relative z-10 p-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex-1">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Alterar Evento</h1>
                            <p class="text-white/90 text-sm">{{ $target->event_name }} - {{ formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? 'Sem data definida' }}</p>
                        </div>
                    </div>
                </div>
                <x-button flat white icon="reply" label="VOLTAR" href="{{ route('dashboard-evento') }}" class="hover:bg-white/20" />
            </div>
        </div>
    </div>

    <div class="mb-6">
        <x-jet-validation-errors no_loading="true" />
    </div>

    {{-- DADOS DO EVENTO --}}
    <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">Dados do Evento</h2>
        </div>
        <div class="p-6">
            <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4">

            <div class="col-span-full md:col-span-5">
                <x-input label="{{ __('event_name') }}" wire:model.defer="event_name" />
            </div>

            <div class="col-span-full md:col-span-7">
                <x-input label="{{ __('event_description') }}" wire:model.defer="event_description" />
            </div>

            <div class="col-span-full md:col-span-6 flex" title="Link Direto: Acesso apenas pela URL / Publico: Aparece na página inicial do site.">
                <x-toggle left-label="Acesso apenas com Link Direto" label="Ficará Público no site" title="Link Direto: Acesso apenas pela URL / Publico: Aparece na página inicial do site." lg wire:model.defer="active" />
            </div>

            <div class="col-span-full md:col-span-full my-4">
                <hr>
            </div>

            <div class="col-span-full md:col-span-3">
                <x-input label="{{ __('event_datetime_start') }}" type="datetime-local" wire:model.defer="event_datetime_start" />
            </div>

            <div class="col-span-full md:col-span-3">
                <x-input label="{{ __('event_datetime_finish') }}" type="datetime-local" wire:model.defer="event_datetime_finish" />
            </div>

            <div class="col-span-full md:col-span-3">
                <x-native-select
                    label="{{ __('type') }}"
                    wire:model.defer="type"
                    >
                    <option value="">--</option>
                    @foreach ($this->listType ?? [] as $values)
                    <option value="{{ $values->ref_slug }}">{{ $values->ref_label ?? $values->ref_value }}</option>
                    @endforeach
                </x-native-select>
            </div>

            <div class="col-span-full md:col-span-3">
                <x-native-select
                    label="{{ __('category') }}"
                    wire:model.defer="category"
                    placeholder="--"
                    >
                    <option value="">--</option>
                    @foreach ($this->listCategory ?? [] as $values)
                    <option value="{{ $values->ref_slug }}">{{ $values->ref_label ?? $values->ref_value }}</option>
                    @endforeach
                </x-native-select>
            </div>

            <div class="col-span-full md:col-span-full my-4">
                <hr>
            </div>

            <div class="col-span-full" wire:ignore>
                {{-- V5 --}}
                <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/decoupled-document/ckeditor.js"></script>

                <label class="block text-base font-light uppercase text-black dark:text-gray-400 mb-1">SOBRE O EVENTO</label>
                <div id="toolbar-container"></div>
                <div class="w-full border border-gray-300 bg-white">
                    <div id="event_about">{!! $event_about !!}</div>
                </div>

                <script>
                    DecoupledEditor
                        .create(document.querySelector('#event_about'))
                        .then(editor => {

                            const toolbarContainer = document.querySelector( '#toolbar-container' );
                                  toolbarContainer.appendChild( editor.ui.view.toolbar.element );

                            editor.model.document.on('change:data', () => {
                                @this.set('event_about', editor.getData());
                            });

                            // document.querySelector('#syncButton').addEventListener('click', () => {
                            //     const content = editor.getData();
                            //     @this.set('event_about', content);
                            // });

                            console.log('Editor carregado com sucesso!', editor);
                        })
                        .catch(error => {
                            console.error(error);
                        });
                </script>
                <style>.ck-file-dialog-button {display: none;}</style>
            </div>

            <div class="col-span-full md:col-span-full my-4">
                <hr>
            </div>

            <div class="col-span-full md:col-span-4">
                <x-input label="{{ __('notification_text_1') }}" wire:model.defer="notification_text_1" hint="Opcional" />
            </div>

            <div class="col-span-full md:col-span-8">
                <x-input label="{{ __('notification_text_2') }}" wire:model.defer="notification_text_2" hint="Orientação, aviso ou mesmo mensagem opcional que queira informar na página do evento" />
            </div>
        </div>
    </div>

    {{-- LOCAL --}}
    <div class="bg-white shadow-sm border-x border-b rounded-b-lg mt-6">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <h2 class="text-lg font-semibold text-gray-800">Local</h2>
                <div class="w-full md:w-1/2">
                    <x-native-select
                        wire:model="place_id"
                        label="Meus locais"
                        class="uppercase"
                    >
                    <option value="">Selecione</option>
                    @forelse ($this->myPlaces ?? [] as $placeItem)
                        <option value="{{ $placeItem->id }}" class="uppercase">{{ $placeItem->place_name }} {{ $placeItem->place_description ? '- ' . $placeItem->place_description : null }}</option>
                    @empty
                        <option disabled>Nenhum local cadastrado</option>
                    @endforelse
                    @if(($this->myPlaces ?? collect([]))->count() > 0)
                        <option disabled>----------------------------------------</option>
                    @endif
                    <option value="novo-local">Novo Local</option>
                    </x-native-select>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4">

                @if ($place_id == 'novo-local')

                    <div class="col-span-full md:col-span-4 uppercase">
                        @if (($place_id ?? false) && $place_id != 'novo-local')
                            <x-input label="{{ __('place_name') }}" wire:model.defer="place_name" class="cursor-not-allowed bg-gray-100 uppercase" readonly />
                        @else
                            <x-input label="{{ __('place_name') }}" wire:model.defer="place_name" />
                        @endif
                    </div>

                    <div class="col-span-full md:col-span-8 uppercase">
                        @if (($place_id ?? false) && $place_id != 'novo-local')
                            <x-input label="{{ __('place_description') }}" wire:model.defer="place_description" class="cursor-not-allowed bg-gray-100" readonly />
                        @else
                            <x-input label="{{ __('place_description') }}" wire:model.defer="place_description" />
                        @endif
                    </div>

                    <div class="col-span-full md:col-span-full my-4">
                        <hr>
                    </div>
                @endif

                <div class="col-span-full md:col-span-2">
                    @if (($place_id ?? false) && $place_id != 'novo-local')
                        <x-inputs.maskable label="{{ __('zip_code') }}" wire:model.defer="zip_code" mask="#####-###" placeholder="_____ - ___" class="cursor-not-allowed bg-gray-100" readonly />
                    @else
                        <div class="w-full flex items-start">
                            <div>
                                <x-inputs.maskable label="{{ __('zip_code') }}" wire:model.defer="zip_code" mask="#####-###" placeholder="_____ - ___" />
                            </div>
                            <div>
                                <x-button icon="search" class="mt-7 h-11" wire:click="buscarEndereco" positive flat squared />
                            </div>
                        </div>
                        @if (session('errorBuscaCep'))
                            <p class="mt-2 text-sm text-negative-600">{{ __(session('errorBuscaCep')) }}</p>
                        @endif
                    @endif
                </div>

                <div class="col-span-full md:col-span-5">
                    @if (($place_id ?? false) && $place_id != 'novo-local')
                        <x-input label="{{ __('address') }}" wire:model.defer="address" class="cursor-not-allowed bg-gray-100" readonly />
                    @else
                        <x-input label="{{ __('address') }}" wire:model.defer="address" />
                    @endif
                </div>

                <div class="col-span-full md:col-span-2">
                    @if (($place_id ?? false) && $place_id != 'novo-local')
                        <x-input label="{{ __('address_number') }}" wire:model.defer="address_number" class="cursor-not-allowed bg-gray-100" readonly />
                    @else
                        <x-input label="{{ __('address_number') }}" wire:model.defer="address_number" />
                    @endif
                </div>

                <div class="col-span-full md:col-span-3">
                    @if (($place_id ?? false) && $place_id != 'novo-local')
                        <x-input label="{{ __('address_complement') }}" wire:model.defer="address_complement" class="cursor-not-allowed bg-gray-100" readonly />
                    @else
                        <x-input label="{{ __('address_complement') }}" wire:model.defer="address_complement" />
                    @endif
                </div>

                <div class="col-span-full md:col-span-3">
                    @if (($place_id ?? false) && $place_id != 'novo-local')
                        <x-input label="{{ __('city_neighborhood') }}" wire:model.defer="city_neighborhood" class="cursor-not-allowed bg-gray-100" readonly />
                    @else
                        <x-input label="{{ __('city_neighborhood') }}" wire:model.defer="city_neighborhood" />
                    @endif
                </div>

                <div class="col-span-full md:col-span-3">
                    @if (($place_id ?? false) && $place_id != 'novo-local')
                        <x-input label="{{ __('city') }}" wire:model.defer="city" class="cursor-not-allowed bg-gray-100" readonly />
                    @else
                        <x-input label="{{ __('city') }}" wire:model.defer="city" />
                    @endif
                </div>

                <div class="col-span-full md:col-span-3 uppercase">
                    @if (($place_id ?? false) && $place_id != 'novo-local')
                        <x-input label="{{ __('state') }}" wire:model.defer="state" class="cursor-not-allowed bg-gray-100" readonly />
                    @else
                        <x-native-select
                            wire:model.defer="state"
                            label="{{ __('state') }}"
                            placeholder="--"
                            :options="$this->listStates"
                            option-value="ref_slug"
                            option-label="ref_value"
                        />
                    @endif
                </div>

                <div class="col-span-full md:col-span-3">
                    @if (($place_id ?? false) && $place_id != 'novo-local')
                        <x-input label="{{ __('address_reference') }}" wire:model.defer="address_reference" class="cursor-not-allowed bg-gray-100" readonly />
                    @else
                        <x-input label="{{ __('address_reference') }}" wire:model.defer="address_reference" />
                    @endif
                </div>

                <div class="col-span-full">
                    @if (($place_id ?? false) && $place_id != 'novo-local')
                        <x-textarea label="{{ __('google_maps_iframe') }}" wire:model.defer="google_maps_iframe" class="cursor-not-allowed bg-gray-100" readonly />
                    @else
                        <x-textarea label="{{ __('google_maps_iframe') }}" wire:model.defer="google_maps_iframe" />
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- VENDAS --}}
    <div class="bg-white shadow-sm border-x border-b rounded-b-lg mt-6">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">Vendas / Inscrições</h2>
        </div>
        <div class="p-6">
            <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4">

                <div class="col-span-full md:col-span-3">
                    <x-input label="{{ __('sales_amount_max') }}" type="number" min="1" wire:model.defer="sales_amount_max" />
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-input label="{{ __('sales_btn') }}" wire:model.defer="sales_btn" />
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-input label="{{ __('sales_label') }}" wire:model.defer="sales_label" />
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-input label="{{ __('event_tickets_nomenclature') }}" wire:model.defer="event_tickets_nomenclature" />
                </div>

                <div class="col-span-full">
                    <x-native-select
                        wire:model.defer="sales_label_item"
                        label="{{ __('Utilizador Tipo Unitário / Multiplo') }}"
                        placeholder="--"
                        class="uppercase"
                    >
                    <option value="">--</option>
                    @foreach ($this->sales_label_item_tipos ?? [] as $utilizadorKey => $utilizadorItem)
                    <option value="{{ $utilizadorKey }}" class="uppercase">{{ $utilizadorKey }} / {{ $utilizadorItem }}</option>
                    @endforeach
                    </x-native-select>
                </div>

            </div>
        </div>
    </div>

    {{-- FOOTER COM BOTÃO SALVAR --}}
    <div class="bg-white shadow-sm border-x border-b rounded-b-lg mt-6">
        <div class="p-6 flex justify-center md:justify-end">
            <x-button id="syncButton" lg positive spinner label="ALTERAR" onclick="confirm('Confirma a alteração do evento?') || event.stopImmediatePropagation()" wire:click="alterarEvento" />
        </div>
    </div>


</div>
