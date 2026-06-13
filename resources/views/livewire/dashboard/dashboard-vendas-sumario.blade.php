<div class="w-full max-w-7xl mx-auto mb-10">

    <div class="mb-6">
        <x-jet-validation-errors />
    </div>

    @if ($target ?? false)

        {{-- HEADER MODERNO COM GRADIENTE --}}
        <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern-sumario" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-sumario)"/>
                </svg>
            </div>
            <div class="relative z-10 p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <div>
                                <div class="text-2xl font-bold text-white">Sumário de Vendas</div>
                                <div class="text-white/90 text-sm">{{ $target->event_name }} - {{ formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? 'Sem data definida' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @auth
                            <a href="{{ $target->id ? route('evento-by-uuid', $target->id) : route('dashboard-evento') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-white/20 border border-white/40 rounded hover:bg-white/30 hover:border-white/60 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                VOLTAR
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        {{-- CARDS DE RESUMO --}}
        @if ($pedidos ?? false)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Quantidade Vendida</div>
                        <div class="text-3xl font-bold text-gray-900 mt-2">{{ $this->pedidos['totais']['vendidos_qtd'] }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Valor Bruto</div>
                        <div class="text-3xl font-bold text-gray-900 mt-2">{{ toMoney($this->pedidos['totais']['vendidos_valor'] ?? 0, 'R$ ') }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 px-6 py-4 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Valor Descontos</div>
                        <div class="text-3xl font-bold text-gray-900 mt-2">{{ toMoney($this->pedidos['totais']['descontos_valor'] ?? 0, 'R$ ') }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 px-6 py-4 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Valor Líquido</div>
                        <div class="text-3xl font-bold text-gray-900 mt-2">{{ toMoney(($this->pedidos['totais']['vendidos_valor'] ?? 0) - ($this->pedidos['totais']['descontos_valor'] ?? 0), 'R$ ') }}</div>
                    </div>
                </div>
            </div>

            {{-- TABELA DE VENDAS --}}
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-6">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Vendas por Data</h2>
                </div>
                <div class="p-6">
                    <div class="w-full bg-white p-4 rounded">
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
                                    @foreach ($this->pedidos['columns'] as $column)
                                        <th class="text-center text-sm uppercase">{{ __($column) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->pedidos['data'] as $dataItem)
                                    <tr>
                                        <td class="text-center text-sm whitespace-nowrap">{{ convertToDate($dataItem['data']) }}</td>
                                        <td class="text-center text-sm whitespace-nowrap">{{ $dataItem['total_vendidos'] }}</td>
                                        <td class="text-center text-sm whitespace-nowrap">{{ toMoney($dataItem['total_valor_bruto'], 'R$ ') }}</td>
                                        <td class="text-center text-sm whitespace-nowrap">{{ toMoney($dataItem['total_valor_descontos'], 'R$ ') }}</td>
                                        {{--  --}}
                                        @foreach ($this->pedidos['columnsPayTypes'] as $payType)
                                            <td class="text-center text-sm whitespace-nowrap">{{ $dataItem[$payType] ?? '--' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <script>
                            $(document).ready(function () {

                                $.fn.dataTable.moment('DD/MM/YYYY');    //Formatação sem Hora

                                $('#example').DataTable({
                                    autoWidth: true,
                                    dom: 'Bfrt',
                                    buttons: [
                                        'excelHtml5',
                                        'pdfHtml5'
                                    ],
                                    language: {url:"https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"},
                                    lengthMenu: [[-1]],
                                    columnDefs: [
                                        { className: "dt-center", targets: "_all"}
                                    ],
                                    scrollX: true,
                                    // ordering: false,
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>

            {{-- TIPOS DE PAGAMENTO --}}
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Resumo por Tipo de Pagamento</h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pagamento</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Porcentagem</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($this->pedidos['totais']['types'] ?? [] as $typeKey => $typeItem)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 uppercase">{{ __($typeKey ?? '--') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">{{ number_format((float) ((($typeItem['qtd'] ?? 0)  * 100) / $this->pedidos['totais']['vendidos_qtd']), (int) 2) }} %</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">{{ $typeItem['qtd'] ?? '--' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-center">{{ toMoney($typeItem['valor'] ?? '--', 'R$ ') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum tipo de pagamento encontrado</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="mt-2 text-sm font-medium text-gray-900">Por enquanto não temos pedidos</p>
                    <p class="mt-1 text-sm text-gray-500">Os pedidos aparecerão aqui quando houver vendas</p>
                </div>
            </div>
        @endif

    @else
        {{-- SEM TARGET SELECIONADO --}}
        <div class="mb-6 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="relative z-10 p-6">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white">Sumário de Vendas</div>
                        <div class="text-white/90 text-sm">É preciso selecionar um evento</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
            <div class="p-6 text-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Página Principal
                </a>
            </div>
        </div>
    @endif

</div>
