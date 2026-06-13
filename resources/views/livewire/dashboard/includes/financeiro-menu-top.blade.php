<div class="w-full md:w-4/12">
    <x-native-select
        wire:model="target_ref" label="Selecione um Tipo1" class="uppercase">
        <option value="">--</option>
        @foreach ($target_list_ref ?? [] as $targetRefValues)
        <option value="{{ $targetRefValues->module_name }}">{{ __($targetRefValues->module_name) }}</option>
        @endforeach
    </x-native-select>
</div>
@if ($target_ref ?? false)
    <div class="w-full md:w-8/12">
        <x-native-select
            wire:model="target_id" label="Selecione um dos {{ __($target_ref) }}" class="uppercase">
            <option value="">--</option>
            @foreach ($target_list ?? [] as $targetValues)
            <option value="{{ $targetValues->id }}">{{ __($targetValues->event_name) }}</option>
            @endforeach
        </x-native-select>
    </div>
@endif
