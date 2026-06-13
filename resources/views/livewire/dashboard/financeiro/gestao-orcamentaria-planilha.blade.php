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
                        <pattern id="grid-pattern-planilha" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-planilha)"/>
                </svg>
            </div>
            <div class="relative z-10 p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Gestão Orçamentária - Planilha</h1>
                                <p class="text-white/90 text-sm">{{ $target->event_name }} - {{ formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? 'Sem data definida' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-button flat white icon="reply" label="VOLTAR" href="{{ route('dashboard-financeiro-gestao-orcamentaria') }}" class="hover:bg-white/20" />
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full">

            {{-- PLANILHA --}}
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Planilha Orçamentária</h2>
                </div>
                <div class="p-6">

                        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.2/css/uikit.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

                        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>

                    <table id="table" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center text-sm uppercase whitespace-nowrap">TIPO</th>
                                <th class="text-center text-sm uppercase whitespace-nowrap">GRUPO</th>
                                <th class="text-center text-sm uppercase whitespace-nowrap">DESCRIÇÃO</th>
                                <th class="text-center text-sm uppercase whitespace-nowrap">VALOR</th>
                                <th class="text-center text-sm uppercase whitespace-nowrap">QTD</th>
                                <th class="text-center text-sm uppercase whitespace-nowrap">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($receitas ?? [] as $receita_item)
                            <tr>
                                <td class="text-sm uppercase whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">RECEITA</span>
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap">
                                    {{ $receita_item['grupo'] ?? '-' }}
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap">
                                    {{ $receita_item['descricao'] ?? '-' }}
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap text-right">
                                    {{ $receita_item['valor'] ?? '-' }}
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap text-center">
                                    {{ $receita_item['quantidade'] ?? '-' }}
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap text-right font-semibold text-green-600">
                                    {{ $receita_item['total'] ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                            @foreach ($despesas ?? [] as $despesa_item)
                            <tr>
                                <td class="text-sm uppercase whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">DESPESA</span>
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap">
                                    {{ $despesa_item['grupo'] ?? '-' }}
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap">
                                    {{ $despesa_item['descricao'] ?? '-' }}
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap text-right">
                                    {{ $despesa_item['valor'] ?? '-' }}
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap text-center">
                                    {{ $despesa_item['quantidade'] ?? '-' }}
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap text-right font-semibold text-red-600">
                                    {{ $despesa_item['total'] ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                            @foreach ($saldos ?? [] as $saldo_item)
                            <tr class="bg-gray-50 font-bold">
                                <td class="text-sm uppercase whitespace-nowrap">
                                    {{ $saldo_item['tipo'] ?? null }}
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap">
                                    {{ $saldo_item['grupo'] ?? null }}
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap">
                                    {{ $saldo_item['descricao'] ?? null }}
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap text-right">
                                    {{ $saldo_item['valor'] ??  null }}
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap text-center">
                                    {{ $saldo_item['quantidade'] ?? '-' }}
                                </td>
                                <td class="text-sm uppercase whitespace-nowrap text-right text-lg">
                                    {{ $saldo_item['total'] ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <script>
                        document.title = '{{ mb_strtoupper($target->event_name) }}-GESTAO-ORÇAMENTARIA-{{ now()->format("Ymd-Hi")}}';

                        $(document).ready(function () {
                            $.fn.dataTable.moment('DD/MM/YYYY');    //Formatação sem Hora

                            $('#table').DataTable({
                                autoWidth: true,
                                dom: 'Bfrti',
                                buttons: [
                                    'excelHtml5'
                                ],
                                language: {url:"https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"},
                                lengthMenu: [[-1]],
                                columnDefs: [
                                    { className: "dt-center", targets: "_all"}
                                ],
                                order: [],
                                scrollX: true,
                                // ordering: false,
                            });
                        });
                    </script>
                </div>
            </div>
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
                            <h1 class="text-2xl font-bold text-white">Gestão Orçamentária - Planilha</h1>
                            <p class="text-white/90 text-sm">É preciso selecionar um evento</p>
                                <div class="mt-2"><a href="{{ route('dashboard') }}" class="text-white/90 text-sm hover:text-white/70 border border-white mt-4 p-2 rounded shadow hover:bg-gray-50 hover:text-blue-500">Página Principal</a></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

</div>
