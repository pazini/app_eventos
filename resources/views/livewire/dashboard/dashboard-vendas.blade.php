<div>

    @if ($target ?? false)

        <div class="{{ setClass('divContentHeader') }} ">
            <div class="w-full flex justify-between items-center">
                <div>
                    {!! setLabelHeader('Evento', $target->event_name, 'VENDAS - HORA CONSULTA ' . now()->format('d/m/Y H:i')) !!}
                </div>
                <div class="p-0">
                    <x-button flat white icon="reply" label="VOLTAR" href="{{ route('dashboard-evento') }}" class="hover:text-sky-500" />
                </div>
            </div>
        </div>

        <div class="w-full max-w-7xl mx-auto">
            <x-jet-validation-errors />
        </div>

        {{-- DADOS --}}
        <div class="{{ setClass('divContent') }} bg-white pb-8">

            <div class="w-full">
                @if ($pedidos ?? false)
                    @php
                        //
                        $ingressoQtd = 0;
                        foreach ($target->ticketsTypes as $ticketType)
                            $ingressoQtd += $ticketType->amount;

                        //
                        $ingressoQtdVendidos   = $target->tickets->whereIn('ticket_status',['disponivel'])->count() ?? 0;
                        $ingressoQtdDisponivel = $ingressoQtd - $ingressoQtdVendidos;
                    @endphp
                    <div class="flex flex-col md:flex-row justify-between items-center  border-b">
                        <div class="flex flex-col md:flex-row py-2 gap-2">
                            <div class="w-full md:w-auto text-xs md:text-sm font-normal pb-0.5 px-3 bg-black text-white rounded-full shadow-sm uppercase">
                                <div class="flex justify-between gap-2">
                                    <div>VENDIDOS</div>
                                    <div class="font-bold">{{ $ingressoQtdVendidos }}</div>
                                </div>
                            </div>
                            <div class="w-full md:w-auto text-xs md:text-sm font-normal pb-0.5 px-3 bg-black text-white rounded-full shadow-sm uppercase">
                                <div class="flex justify-between gap-2">
                                    <div>DISPONÍVEIS</div>
                                    <div class="font-bold">{{ $ingressoQtdDisponivel }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="py-2">
                            <x-dropdown>
                                <x-slot name="trigger">
                                    <x-button label="{{ __($view_status) }}" right-icon="eye" class="py-0.5 md:py-2 uppercase" />
                                </x-slot>

                                @foreach ($statusList as $statusKey => $statusCount)
                                    <div class="w-full bg-gray-100 rounded-sm my-0.5">
                                        <x-button
                                            href="{{ route('dashboard-vendas',['target_ref' => $target_ref, 'target_slug' => $target_slug, 'target_id' => $target_id, 'view_status' => $statusKey]) }}"
                                            label="{{ __($statusKey) }} ({{ $statusCount ?? 0 }})"
                                            class="w-full"
                                        />
                                    </div>
                                @endforeach

                            </x-dropdown>
                        </div>
                    </div>

                    <div class="w-full bg-white my-4">
                        {{--  --}}
                        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.2/css/uikit.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
                        {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.uikit.min.css"> --}}

                        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
                        {{-- <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> --}}
                        {{-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/dataTables.uikit.min.js"></script> --}}
                        {{--  --}}
                        <script type="text/javascript" charset="utf8" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>
                        {{--  --}}
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    @foreach (['pedido','comprador','quantidade comprada','cupom','pagamentos'] as $column)
                                        <th class="text-center text-xs uppercase">{{ __($column) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
@foreach ($pedidos->sortByDesc('created_at') as $pedido)
<tr>
<td class="text-xs">
<span class="sr-only">{{ $pedido->created_at->format('YmdHi') }}</span>
<span class="sr-only">:::</span>
<div class="text-xs text-gray-500 font-normal">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>
<span class="sr-only">:::</span>
<div class="text-sm text-gray-600 font-semibold">{{ $pedido->order_control }}</div>
<span class="sr-only">:::</span>
<div class="mt-1">
@php
$statusClass = (in_array($pedido->status, listOrderStatusPaid())) ? 'statusPago' : 'statusPendente';
@endphp
<span class="{{ setClass($statusClass,'py-0 px-2 text-xs') }}">{{ __($pedido->status) }}</span>
</div>
</td>
{{--  --}}
<td class="text-xs truncate">
<div class="text-sm text-gray-500 font-medium uppercase truncate">{{ $pedido->buyer_name }}</div>
<span class="sr-only">:::</span>
<div class="-mt-0.5 text-xs text-gray-500 font-normal lowercase truncate">{{ $pedido->buyer_email }}</div>
<span class="sr-only">:::</span>
<div class="text-xs text-gray-500 font-normal lowercase truncate">({{ $pedido->buyer_contact_ddd }}) {{ $pedido->buyer_contact_num }}</div>
<span class="sr-only">:::</span>
<div class="text-xs text-gray-500 font-normal lowercase truncate">{{ $pedido->buyer_birth_date->format('d/m/Y') }} {{ $pedido->buyer_birth_date->age }} anos</div>
</td>
{{--  --}}
<td class="text-xs text-center">
<div class="flex justify-center items-center">
<span>{{ $pedido->tickets->count() }}</span>
@if (in_array($pedido->status, listOrderStatusPaid()))
<span class="pt-1">
<x-button flat primary class="px-1 py-1" right-icon="external-link" title="Acessar Online3" href="{{ route('evento-ingressos',['order_control' => $pedido->order_control,'order_id' => $pedido->id]) }}" target="_blank" />
</span>
@endif
</div>
</td>
<td class="text-xs text-center">
@if ($pedido->code_promo_id ?? false)
<div>{{ $pedido->codePromo->code_name }}</div>
<div>{{ $pedido->codePromo->code_description }}</div>
@else
<span>--</span>
@endif
</td>
{{--  --}}
<td class="text-xs">
@if ($pedido->payments->count() ?? false)
@foreach ($pedido->payments as $payment)
<div class="w-full bg-white py-1 px-2 rounded-sm shadow">
<div class="text-xs text-gray-500 font-normal uppercase truncate">
<span>{{ $payment->paid_label }}</span>
<span>no</span>
<span>{{ __($payment->pay_type) ?? '--' }}</span>
{{--  --}}
</div>
<span class="sr-only">:::</span>
@if (in_array(strtoupper($payment->pay_type),['CREDIT_CARD']))
<div>
<span class="uppercase">Cartão</span>
<span class="uppercase">{{ $payment->pay_card_brand ?? '--' }}</span>
<span class="uppercase">{{ $payment->pay_card_last ?? '--' }}</span>
</div>
@endif
@if (in_array(strtoupper($payment->pay_type),['BOLETO']))
<div class="text-xs text-gray-500 font-normal uppercase truncate">
@if ($pedido->status == 'paid')
<div>
<span class="text-black uppercase">BOLETO PAGO</span>
</div>
@else
<a href="{{ $payment->pay_boleto_url }}" class="text-blue-700">
<span>Ver Boleto</span>
<span>- VENCIMENTO {{ convertToDate($payment->pay_boleto_expiration_date) }}</span>
</a>
@endif
</div>
@endif

@if ($payment->pay_nsu ?? false)
<span class="sr-only">:::</span>
<div class="text-xs uppercase truncate">
<span class="text-gray-500 font-normal">NSU</span>
<span class="text-black font-normal">{{ $payment->pay_nsu }}</span>
</div>
@endif
</div>
@endforeach
@else
<div class="font-normal text-xs mt-1">PAGAMENTOS NÃO LOCALIZADOS</div>
@endif
</td>
</tr>
@endforeach
                            </tbody>
                        </table>
                        {{--  --}}
                        <script>
                            $(document).ready(function () {
                                // $.fn.dataTable.moment('DD/MM/YYYY'); //Formatação sem Hora
                                $.fn.dataTable.moment('DD/MM/YYYY hh:mm'); //Formatação sem Hora
                                $('#example').DataTable({
                                    autoWidth: true,
                                    scrollX: true,
                                    dom: 'Bfrti',
                                    buttons: [
                                        'excelHtml5',
                                        'pdfHtml5'
                                    ],
                                    language: {
                                        url:"https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"
                                    },
                                    lengthMenu: [[-1]],
                                    order: [[0, 'desc']],
                                    columnDefs: [
                                        { className: "dt-head-center", targets: "_all"}
                                    ],
                                    // ordering: false,
                                });
                            });
                        </script>
                    </div>

                    {{-- @foreach ($pedidos->sortByDesc('created_at') as $pedido) --}}
                    @foreach ([] as $pedido)
                        <div class="flex flex-col md:flex-row w-full shadow bg-gray-100 border mt-2 p-2">
                            <div class="w-full md:w-1/3">
                                <div class="text-lg text-gray-600 font-semibold">{{ $pedido->order_control }}</div>
                                <div class="text-sm text-gray-500 font-normal">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>
                                <div>
                                    @if (in_array($pedido->status, listOrderStatusPaid()))
                                    <span class="text-xs text-white bg-green-700 font-semibold rounded-sm shadow px-1 uppercase">{{ __($pedido->status) }}</span>
                                    @else
                                    <span class="text-xs text-white bg-gray-500 font-semibold rounded-sm shadow px-1 uppercase">{{ __($pedido->status) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="w-full md:w-1/3 border-t md:border-none my-1 md:my-0 py-1 md:py-0">
                                <div class="text-base text-gray-500 font-medium uppercase truncate">{{ $pedido->buyer_name }}</div>
                                <div class="text-sm text-gray-500 font-normal lowercase truncate">{{ $pedido->buyer_email }}</div>
                                <div class="text-sm text-gray-500 font-normal lowercase truncate">({{ $pedido->buyer_contact_ddd }}) {{ $pedido->buyer_contact_num }}</div>
                                <div class="text-sm text-gray-500 font-normal lowercase truncate">{{ $pedido->buyer_birth_date->format('d/m/Y') }} {{ $pedido->buyer_birth_date->age }} anos</div>
                            </div>
                            <div class="w-full md:w-1/3 ">
                                <div class="font-medium text-xs mb-1">
                                    <span>{{ $pedido->tickets->count() }}</span>
                                    @if ($pedido->tickets->count() > 1)
                                        <span>INGRESSOS</span>
                                    @else
                                        <span>INGRESSO</span>
                                    @endif
                                </div>
                                @if ($pedido->payments->count() ?? false)
                                    @foreach ($pedido->payments as $payment)
                                        <div class="w-full bg-white py-1 px-2 rounded-sm shadow">
                                            <div class="text-sm text-gray-500 font-normal uppercase truncate">
                                                <span>{{ $payment->paid_label }}</span>
                                                <span>no</span>
                                                <span>{{ __($payment->pay_type) ?? '--' }}</span>
                                                {{--  --}}
                                            </div>
                                            @if (in_array(strtoupper($payment->pay_type),['CREDIT_CARD']))
                                                <div>
                                                    <span class="uppercase">Cartão</span>
                                                    <span class="uppercase">{{ $payment->pay_card_brand ?? '--' }}</span>
                                                    <span class="uppercase">{{ $payment->pay_card_last ?? '--' }}</span>
                                                </div>
                                            @endif
                                            @if (in_array(strtoupper($payment->pay_type),['BOLETO']))
                                                <div class="text-sm text-gray-500 font-normal uppercase truncate">
                                                    @if ($pedido->status == 'paid')
                                                        <div>
                                                            <span class="text-black uppercase">BOLETO PAGO</span>
                                                        </div>
                                                    @else
                                                        <a href="{{ $payment->pay_boleto_url }}" class="text-blue-700">
                                                            <span>Ver Boleto</span>
                                                            <span>- VENCIMENTO {{ convertToDate($payment->pay_boleto_expiration_date) }}</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif

                                            @if ($payment->pay_nsu ?? false)
                                                <div class="text-sm uppercase truncate">
                                                    <span class="text-gray-500 font-normal">NSU</span>
                                                    <span class="text-black font-normal">{{ $payment->pay_nsu }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="font-normal text-xs mt-1">PAGAMENTOS NÃO LOCALIZADOS</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="w-full p-4 bg-red-100 shadow-sm">
                        <h2>POR ENQUANTO NÃO TEMOS PEDIDOS</h2>
                    </div>
                @endif

            </div>
        </div>

    @else
        <div class="w-full p-4 bg-red-100 shadow-sm">
            <h2>SEM DADOS PARA EXIBIR</h2>
        </div>
    @endif

</div>
