<div>

    <div class="{{ setClass('divContentHeader') }} ">
        <div class="w-full flex justify-between items-center">
            <div>
                {!! setLabelHeader('Perfil', 'Acesso') !!}
            </div>
            <div class="p-0">
                <x-button.circle flat white icon="refresh" wire:click="resetPerfil" spinner class="hover:text-sky-500" />
            </div>
        </div>
    </div>

    <div class="w-full max-w-7xl mx-auto">
        <x-jet-validation-errors />
    </div>

    <div class="{{ setClass('divContentTitleDiv') }}">

        <div class="w-full">

            {{-- SE CLLIENTES --}}
            @if (count($customers ?? []))
                <div class="flex-none flex gap-4">
                    <div class="w-full md:w-1/3">
                        <label class="text-xs text-gray-600 font-medium">PERFIL CLIENTE</label>

                        <x-native-select wire:model="customerId" class="w-full uppercase">
                            <option value="">--</option>
                            @foreach ($customers as $item)
                                <option value="{{ $item->id }}">{{ $item->name_corporate }}</option>
                            @endforeach
                        </x-native-select>
                    </div>
                    {{--  --}}
                    @if ($customerId ?? false)
                        <div class="w-full md:w-1/3">
                            <label class="text-xs text-gray-600 font-medium">ORGANIZAÇÃO</label>
                            <x-native-select wire:model="organizationId" class="w-full uppercase">
                                <option value="">--</option>
                                @foreach ($organizations as $item)
                                    <option value="{{ $item->id }}">{{ $item->organization_name }}</option>
                                @endforeach
                            </x-native-select>
                        </div>
                        {{--  --}}
                        @if ($organizationId ?? false)
                            <div class="w-full md:w-1/3">
                                {{-- {{dd($organizationSubs)}} --}}
                                <label class="text-xs text-gray-600 font-medium">ORGANIZAÇÃO ADICIONAL</label>
                                @if (count($organizationSubs))
                                    <x-native-select wire:model="organizationSubId" class="w-full uppercase">
                                        <option value="">--</option>
                                        @foreach ($organizationSubs as $item)
                                            <option value="{{ $item->id }}">{{ $item->organization_sub_name }}</option>
                                        @endforeach
                                    </x-native-select>
                                @else
                                    <div class="bg-gray-100 mt-0.5 py-2 px-4">NÃO POSSUI</div>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
            @endif

            {{-- SE ORGANIZADORES --}}
            @if ($organizers ?? false)
                <div>
                    <div class="pt-4 pb-4"><hr></div>
                    <div class="flex justify-between items-end gap-2">
                        <div class="w-full">
                            <label class="text-xs text-gray-600 font-medium">ORGANIZADOR</label>
                            @if (count($organizers))
                                <x-native-select wire:model="organizerId" class="w-full uppercase">
                                    <option value="">--</option>
                                    @foreach ($organizers as $item)
                                        <option value="{{ $item->id }}">{{ $item->organizer_name_full }}</option>
                                    @endforeach
                                </x-native-select>
                            @else
                                <div class="bg-gray-100 mt-0.5 py-2 px-4">PERFIL NÃO POSSUI ORGANIZADORES</div>
                            @endif
                        </div>
                        {{--  --}}
                        @if ($referer ?? false)
                            <div class="w-auto">
                                <x-button right-icon="link" info md href="{{ $referer }}" label="Ir" />
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- SE CUSTOMER --}}
            @if (($customer ?? false) && in_array($customer->user_role, ['owner']))
                <div>
                    <div class="py-4"><hr></div>
                    {{-- <div>
                        name_corporate: {{ $customer->name_corporate ?? '--' }}
                    </div> --}}
                </div>
            @endif

        </div>

    </div>
</div>
