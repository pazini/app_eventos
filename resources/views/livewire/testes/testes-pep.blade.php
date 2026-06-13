<div>
    <div class="text-lg font-semibold">TESTES - PEP</div>

    <div class="pb-2"><hr></div>

    @if ($slug ?? false)
    <div class="py-2 uppercase">{{ $slug }}</div>
    @endif

    <div class="mt-2">
        <x-button black label="split" wire:click="executeTesteSplit" />
    </div>

    <div class="py-4"><hr></div>

    <x-jet-validation-errors />

    <div>

        @if ($target ?? false)
            {!! viewByGrid($target, false) !!}
        @endif

    </div>

</div>
