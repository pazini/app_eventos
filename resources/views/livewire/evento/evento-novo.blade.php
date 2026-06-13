<div class="max-w-7xl mx-auto space-y-8 pt-2 mb-12">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-sky-600 to-indigo-700 shadow-xl rounded-2xl p-6 md:p-8 flex flex-col md:flex-row justify-between gap-6">
        <div class="space-y-2 text-white">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-xl bg-white/15">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-white/80">Organizador</p>
                    <p class="text-lg font-semibold uppercase">{{ $organizer->organizer_name_full }}</p>
                </div>
            </div>
            <h1 class="text-3xl font-bold">Cadastrar Novo Evento</h1>
            <p class="text-sm text-white/80">Preencha os dados abaixo. Você poderá editar depois.</p>
        </div>
        <div class="flex items-center gap-3">
            <x-button flat white icon="reply" label="Voltar" href="{{ route('dashboard-eventos') }}" class="border border-white/40 text-white hover:text-sky-600" />
            {{-- <x-button positive icon="check" label="Cadastrar" onclick="confirm('Confirma a criação do novo evento?') || event.stopImmediatePropagation()" wire:click="cadastrarNovoEvento" /> --}}
        </div>
    </div>

    <x-jet-validation-errors />

    {{-- Sidebar --}}
    <div class="space-y-6">

        {{-- Dados do Evento --}}
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Dados do Evento</h2>
                <p class="text-sm text-gray-500">Nome, descrição, datas, tipo e categoria.</p>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="col-span-full md:col-span-5">
                        <x-input label="{{ __('event_name') }}" wire:model.defer="event_name" />
                    </div>
                    <div class="col-span-full md:col-span-7">
                        <x-input label="{{ __('event_description') }}" wire:model.defer="event_description" />
                    </div>
                    <div class="col-span-full md:col-span-3">
                        <x-input label="{{ __('event_datetime_start') }}" type="datetime-local" wire:model.defer="event_datetime_start" />
                    </div>
                    <div class="col-span-full md:col-span-3">
                        <x-input label="{{ __('event_datetime_finish') }}" type="datetime-local" wire:model.defer="event_datetime_finish" />
                    </div>
                    <div class="col-span-full md:col-span-3">
                        <x-native-select label="{{ __('type') }}" wire:model.defer="type">
                            <option value="">--</option>
                            @foreach ($listType ?? [] as $values)
                                <option value="{{ $values->ref_slug }}">{{ $values->ref_label ?? $values->ref_value }}</option>
                            @endforeach
                        </x-native-select>
                    </div>
                    <div class="col-span-full md:col-span-3">
                        <x-native-select label="{{ __('category') }}" wire:model.defer="category" placeholder="--">
                            <option value="">--</option>
                            @foreach ($listCategory ?? [] as $values)
                                <option value="{{ $values->ref_slug }}">{{ $values->ref_label ?? $values->ref_value }}</option>
                            @endforeach
                        </x-native-select>
                    </div>
                    {{-- <div class="col-span-full md:col-span-4">
                        <x-input label="{{ __('notification_text_1') }}" wire:model.defer="notification_text_1" />
                    </div> --}}
                    {{-- <div class="col-span-full md:col-span-8">
                        <x-input label="{{ __('notification_text_2') }}" wire:model.defer="notification_text_2" />
                    </div> --}}
                    {{-- <div class="col-span-full md:col-span-12">
                        <x-textarea label="{{ __('event_about') }}" wire:model.defer="event_about" />
                    </div> --}}
                </div>
            </div>
        </div>

        {{-- Local --}}
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl">
            <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Local</h2>
                    <p class="text-sm text-gray-500">Selecione um local salvo ou preencha manualmente.</p>
                </div>
                @empty($myPlaces)
                    <span class="text-xs text-red-600 font-semibold">Nenhum local salvo</span>
                @endempty
            </div>
            <div class="p-6 space-y-6">
                <div class="w-full md:w-1/2">
                    @empty($myPlaces)
                        <span class="font-light text-red-600 text-sm">ESSA EMPRESA AINDA NÃO POSSUI LOCAIS SALVOS!</span>
                    @else
                        <x-native-select
                            wire:model="place"
                            label="Meus locais"
                            placeholder="Selecione um local cadastado"
                            class="uppercase"
                        >
                            <option value="" class="uppercase">Selecione</option>
                            @forelse ($myPlaces as $myPlace_item)
                                <option value="{{ $myPlace_item->id }}" class="uppercase">{{ $myPlace_item->place_name }}{{ ($myPlace_item->place_description) ? ' - ' . $myPlace_item->place_description : null }}</option>
                            @empty
                                <option value="" class="uppercase" disabled>Não existem locais salvo</option>
                            @endforelse
                        </x-native-select>
                    @endempty
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 border-t pt-6">
                    <div class="col-span-full md:col-span-4">
                        @if ($place ?? false)
                            <x-input label="{{ __('place_name') }}" wire:model.defer="place_name" class="cursor-not-allowed bg-gray-100" readonly />
                        @else
                            <x-input label="{{ __('place_name') }}" wire:model.defer="place_name" />
                        @endif
                    </div>
                    <div class="col-span-full md:col-span-6">
                        @if ($place ?? false)
                            <x-input label="{{ __('place_description') }}" wire:model.defer="place_description" class="cursor-not-allowed bg-gray-100" readonly />
                        @else
                            <x-input label="{{ __('place_description') }}" wire:model.defer="place_description" />
                        @endif
                    </div>
                    <div class="col-span-full md:col-span-2">
                        @if ($place ?? false)
                            <x-inputs.maskable label="{{ __('zip_code') }}" wire:model.defer="zip_code" mask="#####-###" placeholder="_____ - ___" class="cursor-not-allowed bg-gray-100" readonly />
                        @else
                            <div class="w-full flex items-start">
                                <div>
                                    <x-inputs.maskable label="{{ __('zip_code') }}" wire:model.defer="zip_code" mask="#####-###" placeholder="_____ - ___" />
                                </div>
                                <div>
                                    <x-button icon="search" class="ml-2 mt-8 h-8" wire:click="buscarEndereco" positive flat rounded />
                                </div>
                            </div>
                            @if (session('errorBuscaCep'))
                                <p class="mt-2 text-sm text-negative-600">{{ __(session('errorBuscaCep')) }}</p>
                            @endif
                        @endif
                    </div>

                    <div class="col-span-full md:col-span-6">
                        @if ($place ?? false)
                            <x-input label="{{ __('address') }}" wire:model.defer="address" class="cursor-not-allowed bg-gray-100" readonly />
                        @else
                            <x-input label="{{ __('address') }}" wire:model.defer="address" />
                        @endif
                    </div>
                    <div class="col-span-full md:col-span-3">
                        @if ($place ?? false)
                            <x-input label="{{ __('address_number') }}" wire:model.defer="address_number" class="cursor-not-allowed bg-gray-100" readonly />
                        @else
                            <x-input label="{{ __('address_number') }}" wire:model.defer="address_number" />
                        @endif
                    </div>
                    <div class="col-span-full md:col-span-3">
                        @if ($place ?? false)
                            <x-input label="{{ __('address_complement') }}" wire:model.defer="address_complement" class="cursor-not-allowed bg-gray-100" readonly />
                        @else
                            <x-input label="{{ __('address_complement') }}" wire:model.defer="address_complement" />
                        @endif
                    </div>

                    <div class="col-span-full md:col-span-3">
                        @if ($place ?? false)
                            <x-input label="{{ __('city_neighborhood') }}" wire:model.defer="city_neighborhood" class="cursor-not-allowed bg-gray-100" readonly />
                        @else
                            <x-input label="{{ __('city_neighborhood') }}" wire:model.defer="city_neighborhood" />
                        @endif
                    </div>
                    <div class="col-span-full md:col-span-3">
                        @if ($place ?? false)
                            <x-input label="{{ __('city') }}" wire:model.defer="city" class="cursor-not-allowed bg-gray-100" readonly />
                        @else
                            <x-input label="{{ __('city') }}" wire:model.defer="city" />
                        @endif
                    </div>
                    <div class="col-span-full md:col-span-3">
                        @if ($place ?? false)
                            <x-input label="{{ __('state') }}" wire:model.defer="state" class="cursor-not-allowed bg-gray-100" readonly />
                        @else
                            <x-native-select
                                wire:model.defer="state"
                                label="{{ __('state') }}"
                                placeholder="--"
                                :options="$listStates"
                                option-value="ref_slug"
                                option-label="ref_value"
                            />
                        @endif
                    </div>
                    <div class="col-span-full md:col-span-3">
                        @if ($place ?? false)
                            <x-input label="{{ __('address_reference') }}" wire:model.defer="address_reference" class="cursor-not-allowed bg-gray-100" readonly />
                        @else
                            <x-input label="{{ __('address_reference') }}" wire:model.defer="address_reference" />
                        @endif
                    </div>
                    <div class="col-span-full border-t mt-4 pt-6">
                        <div class="flex justify-between mb-2">
                            <div><label class="block text-base font-light uppercase text-black dark:text-gray-400" for="c831656213f343de8067188a860f4912">{{ __('iframe_google_maps') }}</label></div>
                            <div></div>
                        </div>
                        @if ($place ?? false)
                            <x-textarea wire:model.defer="iframe_google_maps" hint="Em https://www.google.com/maps copie o iframe do local exato do evento" class="cursor-not-allowed bg-gray-100" readonly />
                        @else
                            <x-textarea wire:model.defer="iframe_google_maps" hint="Em https://www.google.com/maps copie o iframe do local exato do evento" />
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Vendas / Inscrições --}}
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Vendas / Inscrições</h2>
                <p class="text-sm text-gray-500">Configurações básicas de vendas para o evento.</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
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
                </div>
            </div>
        </div>

        <div class="flex justify-end items-center gap-3 p-4 bg-white shadow-sm border border-gray-200 rounded-xl">
            <x-button red flat label="Cancelar" icon="x" href="{{ route('dashboard-evento') }}" />
            <x-button positive label="Cadastrar" icon="check" onclick="confirm('Confirma a criação do novo evento?') || event.stopImmediatePropagation()" wire:click="cadastrarNovoEvento" />
        </div>
    </div>
</div>
