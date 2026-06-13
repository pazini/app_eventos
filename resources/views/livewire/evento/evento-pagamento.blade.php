<div>

    {{-- @livewire('pagamento.realizar-pagamento',[
        'targetType'  => $order_json['order_type'],
        'localizador' => $order_json['localizador'],
    ]) --}}

    @livewire('compras.exibir-compra',[
        'localizador' => $order_json['localizador'],
    ])

</div>
