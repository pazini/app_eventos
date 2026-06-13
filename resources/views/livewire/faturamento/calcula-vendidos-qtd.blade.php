<div>
    @foreach ($totalizador as $totalizador_item)
        <div class="flex justify-between gap-2">
            {{-- <div class="capitalize">{{ __($totalizador_item->ticket_status) }}</div> --}}
            <div>{{ $totalizador_item->total_qtd ?? '--' }} vendas</div>
        </div>
    @endforeach
</div>
