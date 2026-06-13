<div>
    @foreach ($totalizador as $totalizador_item)
        <div class="flex justify-between gap-2">
            {{-- <div class="capitalize">{{ __($totalizador_item->ticket_status) }}</div> --}}
            <div>{{ toMoney($totalizador_item->total_amount,'R$ ') }}</div>
        </div>
    @endforeach
</div>
