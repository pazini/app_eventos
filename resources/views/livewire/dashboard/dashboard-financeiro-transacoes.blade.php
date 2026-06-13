<div>

    <div class="w-full max-w-7xl mx-auto">
        <x-jet-validation-errors />
    </div>

    <div class="w-full h-full mb-6">

        {{-- SE EXISTE TARGET --}}
        @if ($target ?? false)

            <div class="{{ setClass('divContentHeader') }} ">
                <div class="w-full flex justify-between items-center">
                    <div>
                        {!! setLabelHeader('Evento', $target->event_name, 'VENDAS / TRANSAÇÕES') !!}
                    </div>
                    <div class="p-0">
                        <div class="flex flex-col justify-center items-start gap-2">
                            <div>
                                <x-button flat white icon="reply" label="VOLTAR" class="hover:no-underline hover:text-sky-500" href="{{ $referer ?? route('dashboard-evento') }}" />
                            </div>
                            <div>
                                <x-button flat white positive rounded class="px-3 py-1 border hover:no-underline hover:text-sky-500" label="CADASTRAR" title="Cadastrar Venda" href="{{ route('dashboard-evento-vendas-cadastro') }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full max-w-7xl mx-auto">
                <x-jet-validation-errors />
            </div>

            {{-- DADOS --}}
            <div class="{{ setClass('divContent') }} bg-white py-8">

                <div class="w-full">
                    @if ($pedidos ?? false)

                        <div class="flex flex-col md:flex-row justify-end md:justify-between gap-4 py-2">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-2 pt-1 items-center">
                                <div class="w-full md:w-auto text-sm font-normal px-4 bg-gray-700 text-white rounded-full shadow-sm uppercase">
                                    <div class="flex justify-between items-center gap-1">
                                        <div class="truncate">TOTAL PEDIDOS</div>
                                        <div class="font-bold ml-2">{{ $pedidos->count() ?? '0' }}</div>
                                    </div>
                                </div>
                                @php
                                    //
                                    $ingressoQtd  = 0;
                                    $estatisticas = $pedidos->groupBy('status');
                                    $list_status = array_keys($estatisticas->toArray());
                                    //
                                    foreach ($target->ticketsTypes as $ticketType)
                                        $ingressoQtd += $ticketType->amount;
                                @endphp
                                @if ($estatisticas ?? false)
                                    @foreach ($estatisticas as $estatisticaName => $estatisticaItems)
                                        @if (!in_array($estatisticaName, $list_status))
                                            @continue
                                        @endif
                                        <div class="w-full md:w-auto text-sm font-normal px-3 bg-gray-500 text-white rounded-full shadow-sm uppercase" title="{{ __($estatisticaName) }}">
                                            <div class="flex justify-between items-center gap-1">
                                                <div class="truncate">{{ __($estatisticaName) }}</div>
                                                <div class="font-bold ml-2">{{ $estatisticaItems->count() ?? '0' }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="text-sm font-normal uppercase">
                                <div class="w-full flex justify-end items-center">
                                    <x-dropdown class="w-full">
                                        <x-slot name="trigger">
                                            <div class="text-center bg-gray-50 hover:bg-gray-100 border rounded shadow-sm hover:shadow-md py-1 px-2 w-auto">{{ __($list_status_selected ?? '--') }}</div>
                                        </x-slot>
                                        <x-dropdown.item label="todos" href="{{ route('dashboard-evento-vendas') }}" class="hover:no-underline" />
                                        @foreach ($list_status ?? [] as $statusItem)
                                            @if (in_array($statusItem, $list_status_deny))
                                                @continue
                                            @endif
                                            <x-dropdown.item label="{{ __($statusItem) }}" href="{{ route('dashboard-evento-vendas',['status' => $statusItem]) }}" class="hover:no-underline w-auto text-sm" />
                                        @endforeach
                                    </x-dropdown>
                                </div>
                            </div>
                        </div>

                        <div class="w-full bg-white my-4">
                            {{--  --}}
                            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
                            <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.2/css/uikit.min.css">
                            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
                            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.uikit.min.css">
                            {{--  --}}
                            <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
                            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
                            <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                            <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
                            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
                            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
                            <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
                            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/dataTables.uikit.min.js"></script>
                            {{--  --}}
                            <script type="text/javascript" charset="utf8" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
                            <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>
                            {{--  --}}
                            <table id="example" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        @foreach (['DATA','LOCALIZADOR','SITUAÇÃO','COMPRADOR','QTD','VALOR','PAGAMENTOS'] as $column)
                                            <th class="w-full text-center text-sm uppercase whitespace-nowrap">{{ __($column) }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="text-sm text-gray-500 font-normal">
                                    @foreach ($pedidos->sortByDesc('created_at') ?? [] as $pedido)
                                        @if (!in_array($pedido->status, $list_status))
                                            @continue
                                        @endif
                                        <tr class="hover:shadow-md hover:border hover:bg-green-50 cursor-pointer"">
                                            {{-- DATA --}}
                                            <td wire:click="verDetalhes('{{ $pedido->id }}')" class="text-center">
<span class="sr-only">{{ $pedido->created_at->format('YmdHi') }}</span>
<div>{{ convertToDate($pedido->created_at) }}</div>
<div>{{ convertToTime($pedido->created_at) }}</div>
                                        </td>

                                        {{-- LOCALIZADOR --}}
                                        <td wire:click="verDetalhes('{{ $pedido->id }}')" class="whitespace-nowrap text-center">
<span class="text-gray-600 font-semibold">{{ $pedido->order_control }}</span>
                                        </td>

                                        {{-- SITUAÇÃO --}}
                                        <td wire:click="verDetalhes('{{ $pedido->id }}')" class="whitespace-nowrap text-center">
@php
if (in_array($pedido->status, listOrderStatusPaid()))
{
    $statusClass = 'statusPago';

}
elseif (in_array($pedido->status, listOrderStatusCancelada()) || in_array($pedido->status, ['refused']))
{
    $statusClass = 'statusCanceled';

}
else
{
    $statusClass = 'statusPendente';
}

@endphp
<span class="{{ setClass($statusClass,'py-0 px-2 text-xs') }}" title="{{$pedido->status}}">{{ __($pedido->status) }}</span>
                                        </td>

                                        {{-- COMPRADOR --}}
                                        <td wire:click="verDetalhes('{{ $pedido->id }}')" class="whitespace-nowrap text-gray-500 font-medium truncate">
<div class="uppercase">{{ ucwords($pedido->buyer_name) }}</div>
<div class="text-xs lowercase">{{ strtolower($pedido->buyer_email) }}</div>
<div class="text-xs">({{ $pedido->buyer_contact_ddd }}) {{ $pedido->buyer_contact_num }}</div>
<div class="text-xs">{{ $pedido->buyer_birth_date->format('d/m/Y') }} {{ $pedido->buyer_birth_date->age }} anos</div>
                                        </td>

                                        {{-- QTD --}}
                                        <td wire:click="verDetalhes('{{ $pedido->id }}')" class="whitespace-nowrap">
{{ $pedido->order_items_qtd ?? '--' }}
                                        </td>

                                        {{-- VALOR --}}
                                        <td wire:click="verDetalhes('{{ $pedido->id }}')" class="whitespace-nowrap text-center">
<div class="w-full">
{{ toMoney($pedido->order_amount) }}
</div>
@if ($pedido->code_promo_id ?? false)
<div class="border-t mt-1 pt-1 text-xs font-medium text-gray-700">{{ $pedido->codePromo->code_name }}</div>
<div class="text-xs">{{ $pedido->codePromo->code_description }}</div>
@endif
<div>
                                        </td>

                                        {{-- PAGAMENTO --}}
                                        <td class="whitespace-nowrap">
@if ($pedido->payments->count() ?? false)
@foreach ($pedido->payments as $payment)
@if (!in_array(strtoupper($payment->status),['PAID','PENDING_BOLETO']))
@continue
@endif
<div class="w-full bg-white p-1 rounded-sm shadow border">
<div class="flex w-full uppercase gap-1">
<div>{{ $payment->paid_label }}</div>
<div class="font-medium"> {{ __($payment->pay_type) ?? '--' }}</div>
</div>
{{--  --}}
<div class="flex gap-0.5 text-xs uppercase">
@if (in_array(strtoupper($payment->pay_type),['CREDIT_CARD']))
<x-icon name="credit-card" class="w-4 h-4 mt-0.5" />
<div>{{ strtoupper($payment->pay_card_brand) ?? '--' }} {{ $payment->pay_card_last ?? '--' }}</div>
@endif
{{--  --}}
@if (in_array(strtoupper($payment->pay_type),['BOLETO']))
@if ($pedido->status == 'paid')
<x-icon name="document-text" class="w-4 h-4" />
<span class="text-black uppercase">PAGO</span>
<span class="text-gray-500 lowercase">{{ $payment->pay_datetime ? $payment->pay_datetime->format('d/m/Y') : null }}</span>
@else
<a href="{{ $payment->pay_boleto_url }}" target="_blank" class="flex">
<x-icon name="calendar" class="w-4 h-4 mt-0.5" />
<span class="ml-1 hover:font-semibold">{{ convertToDate($payment->pay_boleto_expiration_date) }}</span>
</a>
@endif
@endif
</div>
@if ($payment->pay_nsu ?? false)
<div class="text-xs uppercase truncate mt-0.5">
<span class="text-gray-700 font-normal">NSU</span>
<span class="text-black font-medium">{{ $payment->pay_nsu }}</span>
</div>
@endif
</div>
@endforeach
@else
<div class="font-normal text-center text-xs mt-1">NÃO POSSUI</div>
@endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <script>
                                $(document).ready(function () {
                                    $.fn.dataTable.moment('DD/MM/YYYY HH:mm');
                                    // $.fn.dataTable.moment('DD/MM/YYYY'); //Formatação sem Hora

                                    $('#example').DataTable({
                                        autoWidth: false,
                                        scrollX: true,
                                        dom: 'Bfrtip',
                                        buttons: [
                                            'excelHtml5',
                                            'pdfHtml5'
                                        ],
                                        language: {
                                            url:"https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"
                                        },
                                        // lengthMenu: [[-1]],
                                        lengthMenu: [[100]],
                                        columnDefs: [
                                            { className: "dt-head-center", targets: "_all"}
                                        ],
                                        // order: [[0, 'desc']],
                                        order: [],
                                        // ordering: false,
                                    });
                                });
                            </script>
                        </div>

                    @else
                        <div class="w-full p-4 bg-red-100 shadow-sm">
                            <h2>POR ENQUANTO NÃO TEMOS PEDIDOS</h2>
                        </div>
                    @endif
                </div>

            </div>
        @else

            <div class="{{ setClass('divContentHeader') }} ">
                <div class="w-full flex justify-between items-center">
                    <div>
                        {!! setLabelHeader('Ops!', 'Teremos que recomeçar') !!}
                    </div>
                    <div class="p-0">
                        <div class="flex flex-col justify-center items-start gap-2">
                            <div>
                                <x-button flat white icon="home" label="Home" class="hover:no-underline hover:text-sky-500" href="{{ route('dashboard') }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif
    </div>
</div>

