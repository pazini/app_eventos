<div>
    {{-- <pre>
        {{ print_r($order_json) }}
    </pre> --}}
    @livewire('pagamento.realizar-pagamento',[
        'targetType'  => $order_json['order_type'],
        'localizador' => $order_json['localizador'],
    ])
</div>
