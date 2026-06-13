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
                        <pattern id="grid-pattern-vendas" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-vendas)"/>
                </svg>
            </div>
            <div class="relative z-10 p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Vendas e Transações</h1>
                                <p class="text-white/90 text-sm">{{ $target->event_name }} - {{ formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? 'Sem data definida' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-button positive label="CADASTRAR VENDA" right-icon="plus" href="{{ route('dashboard-evento-vendas-cadastro') }}" class="hover:bg-green-600" />
                        <x-button flat white icon="reply" label="VOLTAR" href="{{ $referer ?? route('dashboard-evento') }}" class="hover:bg-white/20" />
                    </div>
                </div>
            </div>
        </div>

        @php
            $ingressoQtd  = 0;
            $estatisticas = ($pedidos ?? false) ? $pedidos->groupBy('status') : collect();
            $list_status = array_keys($estatisticas->toArray());
            $statusDeny = $list_status_deny ?? [];
            $estatisticasCards = ($estatisticas ?? collect())->reject(function ($items, $status) use ($statusDeny) {
                return in_array($status, $statusDeny);
            });
            $cardsCount = min(max(($estatisticasCards->count() ?? 0) + 1, 1), 6); // +1 pelo card "Total de Pedidos"
            $gridColsClass = match ($cardsCount) {
                1 => 'lg:grid-cols-1',
                2 => 'lg:grid-cols-2',
                3 => 'lg:grid-cols-3',
                4 => 'lg:grid-cols-4',
                5 => 'lg:grid-cols-5',
                default => 'lg:grid-cols-6',
            };
            foreach ($target->ticketsTypes as $ticketType)
                $ingressoQtd += $ticketType->amount;
        @endphp

        {{-- CARDS DE ESTATÍSTICAS --}}
        @if ($pedidos ?? false && $pedidos->count())
            <div class="grid grid-cols-1 md:grid-cols-2 {{ $gridColsClass }} gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total de Pedidos</div>
                        <div class="text-3xl font-bold text-gray-900 mt-2">{{ $pedidos->count() ?? '0' }}</div>
                    </div>
                </div>

                @if ($estatisticasCards ?? false)
                    @foreach ($estatisticasCards as $estatisticaName => $estatisticaItems)
                        @php
                            $statusColors = [
                                'paid' => 'from-green-50 to-green-100',
                                'pending' => 'from-yellow-50 to-yellow-100',
                                'canceled' => 'from-red-50 to-red-100',
                                'refused' => 'from-red-50 to-red-100',
                            ];
                            $colorClass = $statusColors[$estatisticaName] ?? 'from-gray-50 to-gray-100';
                        @endphp
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-br {{ $colorClass }} px-6 py-4 border-b border-gray-200">
                                <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">{{ __($estatisticaName) }}</div>
                                <div class="text-3xl font-bold text-gray-900 mt-2">{{ $estatisticaItems->count() ?? '0' }}</div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- FILTRO E TABELA --}}
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h2 class="text-lg font-semibold text-gray-800">Lista de Pedidos</h2>
                    {{--
                    <div class="flex items-center gap-3">
                        <x-dropdown>
                            <x-slot name="trigger">
                                <x-button outline primary xs>
                                    @if ($list_status_selected == 'all')
                                        Todos
                                    @else
                                        {{ __($list_status_selected ?? '--') }}
                                    @endif
                                    <x-icon name="chevron-down" class="ml-1 h-4" />
                                </x-button>
                            </x-slot>
                            <x-dropdown.item label="Todos" href="{{ route('dashboard-evento-vendas') }}" />
                            @foreach ($list_status ?? [] as $statusItem)
                                @if (in_array($statusItem, $list_status_deny ?? []))
                                    @continue
                                @endif
                                <x-dropdown.item label="{{ __($statusItem) }}" href="{{ route('dashboard-evento-vendas',['status' => $statusItem]) }}" />
                            @endforeach
                        </x-dropdown>
                    </div>
                    --}}
                </div>
                <div class="p-6">
                    <div class="w-full bg-white">
                        <div class="mb-4 grid grid-cols-1 lg:grid-cols-12 gap-3">
                            <div class="lg:col-span-5">
                                <label for="ordersSearch" class="block text-sm font-medium text-gray-700 mb-1">Busca</label>
                                <input id="ordersSearch" type="text" placeholder="Localizador, comprador, email, telefone..."
                                    class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                            </div>
                            <div class="lg:col-span-3">
                                <label for="ordersStatusFilter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select id="ordersStatusFilter" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">TODOS</option>
                                    @foreach ($list_status ?? [] as $statusItem)
                                        @if (in_array($statusItem, $list_status_deny ?? []))
                                            @continue
                                        @endif
                                        <option value="{{ $statusItem }}">{{ mb_strtoupper(__($statusItem)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="lg:col-span-2">
                                <label for="ordersPageSize" class="block text-sm font-medium text-gray-700 mb-1">Por página</label>
                                <select id="ordersPageSize" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="20">20</option>
                                    <option value="50" selected>50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="lg:col-span-2 flex items-end gap-2">
                                <button id="ordersExportCsv" type="button"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                                    Exportar CSV
                                </button>
                                <button id="ordersClearFilters" type="button"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                    Limpar
                                </button>
                            </div>
                        </div>

                        <div class="mb-3 flex items-center justify-between text-sm text-gray-600">
                            <div id="ordersResultsInfo">Exibindo 0 de 0 resultados</div>
                            <div class="flex items-center gap-2">
                                <button id="ordersPrevPage" type="button"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                                    &lt;
                                </button>
                                <span id="ordersPageInfo" class="text-xs text-gray-500">Página 1 de 1</span>
                                <button id="ordersNextPage" type="button"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                                    &gt;
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table id="ordersGrid" class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-3 py-2 text-center text-xs font-semibold uppercase whitespace-nowrap">Data</th>
                                    <th class="px-3 py-2 text-center text-xs font-semibold uppercase whitespace-nowrap">Localizador</th>
                                    <th class="px-3 py-2 text-center text-xs font-semibold uppercase whitespace-nowrap">Situação</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase whitespace-nowrap">Comprador</th>
                                    <th class="px-3 py-2 text-center text-xs font-semibold uppercase whitespace-nowrap">Qtd</th>
                                    <th class="px-3 py-2 text-center text-xs font-semibold uppercase whitespace-nowrap">Valor</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase whitespace-nowrap">Pagamentos</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-500 font-normal divide-y divide-gray-100">
                                @foreach ($pedidos->sortByDesc('created_at') ?? [] as $pedido)
                                    @if (!in_array($pedido->status, $list_status))
                                        @continue
                                    @endif
                                    @php
                                        if (in_array($pedido->status, listOrderStatusPaid())) {
                                            $statusClass = 'bg-green-100 text-green-800';
                                        }
                                        elseif (in_array($pedido->status, listOrderStatusCancelada()) || in_array($pedido->status, ['refused'])) {
                                            $statusClass = 'bg-red-100 text-red-800';
                                        } else {
                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                        }

                                        $paymentsExport = collect($pedido->payments ?? [])->map(function ($payment) {
                                            return strtoupper($payment->pay_type ?? '--') . ': ' . trim(str_replace(' - COM JUROS', '', mb_strtoupper($payment->paid_label ?? toMoney($payment->value_paid ?? 0, 'R$ '))));
                                        })->implode(' | ');

                                        $pedidoUrl = ($target->id ?? false)
                                            ? route('dashboard-evento-vendas-controle-uuid', ['event_id' => $target->id, 'controle' => $pedido->order_control])
                                            : route('dashboard-evento-vendas-controle', ['controle' => $pedido->order_control]);

                                        $searchText = mb_strtolower(implode(' ', [
                                            $pedido->order_control,
                                            $pedido->status,
                                            $pedido->buyer_name,
                                            $pedido->buyer_email,
                                            $pedido->buyer_contact_ddd,
                                            $pedido->buyer_contact_num,
                                        ]));
                                    @endphp
                                    <tr
                                        class="order-row hover:bg-gray-50 cursor-pointer transition-colors"
                                        data-status="{{ $pedido->status }}"
                                        data-search="{{ $searchText }}"
                                        data-date="{{ convertToDate($pedido->created_at) }} {{ convertToTime($pedido->created_at) }}"
                                        data-localizador="{{ $pedido->order_control }}"
                                        data-situacao="{{ mb_strtoupper(__($pedido->status)) }}"
                                        data-comprador="{{ mb_strtoupper($pedido->buyer_name) }}"
                                        data-email="{{ strtolower($pedido->buyer_email) }}"
                                        data-qtd="{{ $pedido->order_items_qtd ?? '--' }}"
                                        data-valor="{{ toMoney($pedido->order_amount, 'R$') }}"
                                        data-pagamentos="{{ $paymentsExport }}"
                                    >
                                        {{-- DATA --}}
                                        <td class="px-3 py-2 text-center" onclick="window.location.href='{{ $pedidoUrl }}'">
                                            <span class="sr-only">{{ $pedido->created_at->format('YmdHi') }}</span>
                                            <div class="text-sm font-medium text-gray-900">{{ convertToDate($pedido->created_at) }}</div>
                                            <div class="text-xs text-gray-500">{{ convertToTime($pedido->created_at) }}</div>
                                        </td>

                                        {{-- LOCALIZADOR --}}
                                        <td class="whitespace-nowrap px-3 py-2 text-center" onclick="window.location.href='{{ $pedidoUrl }}'">
                                            <div class="text-sm font-bold text-blue-600 hover:text-blue-800">{{ $pedido->order_control }}</div>
                                        </td>

                                        {{-- SITUAÇÃO --}}
                                        <td class="whitespace-nowrap px-3 py-2 text-center" onclick="window.location.href='{{ $pedidoUrl }}'">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}" title="{{$pedido->status}}">{{ mb_strtoupper(__($pedido->status)) }}</span>
                                        </td>

                                        {{-- COMPRADOR --}}
                                        <td class="whitespace-nowrap px-3 py-2" onclick="window.location.href='{{ $pedidoUrl }}'">
                                            <div class="text-sm font-medium text-gray-900 uppercase">{{ mb_strtoupper($pedido->buyer_name) }}</div>
                                            <div class="text-xs text-gray-500 lowercase truncate">{{ strtolower($pedido->buyer_email) }}</div>
                                            <div class="text-xs text-gray-500">({{ $pedido->buyer_contact_ddd }}) {{ $pedido->buyer_contact_num }}</div>
                                        </td>

                                        {{-- QTD --}}
                                        <td class="whitespace-nowrap px-3 py-2 text-center" onclick="window.location.href='{{ $pedidoUrl }}'">
                                            @if (in_array($pedido->status, listOrderStatusCancelada()) || in_array($pedido->status, ['refused']))
                                                <span class="text-gray-400">---</span>
                                            @else
                                                <span class="text-sm font-semibold text-gray-900">{{ $pedido->order_items_qtd ?? '--' }}</span>
                                            @endif
                                        </td>

                                        {{-- VALOR --}}
                                        <td class="whitespace-nowrap px-3 py-2 text-center" onclick="window.location.href='{{ $pedidoUrl }}'">
                                            @if (in_array($pedido->status, listOrderStatusCancelada()) || in_array($pedido->status, ['refused']))
                                                <span class="text-gray-400">---</span>
                                            @else
                                                <div class="text-sm font-bold text-gray-900">{{ toMoney($pedido->order_amount,'R$') }}</div>
                                                @if ($pedido->code_promo_id ?? false)
                                                    <div class="text-xs text-blue-600 mt-1 font-medium">{{ $pedido->codePromo->code_name }}</div>
                                                @endif
                                            @endif
                                        </td>

                                        {{-- PAGAMENTOS --}}
                                        <td class="whitespace-nowrap px-3 py-2">
                                            @if ($pedido->payments->count() ?? false)
                                                <div class="space-y-2">
                                                    @foreach ($pedido->payments as $payment)
                                                        @php
                                                            $paymentStatus = strtolower((string) ($payment->status ?? ''));
                                                            $paymentErrorStatuses = [
                                                                'error',
                                                                'payment_error',
                                                                'return-error',
                                                                'refused',
                                                                'denied',
                                                                'failed',
                                                                'failure',
                                                                'erro',
                                                            ];
                                                            $paymentHasError = in_array($paymentStatus, $paymentErrorStatuses, true);
                                                            $paymentCardClass = $paymentHasError
                                                                ? 'bg-red-50 border border-red-200 hover:bg-red-100'
                                                                : 'bg-gray-50 border border-gray-200 hover:bg-gray-100';
                                                        @endphp
                                                        <div class="{{ $paymentCardClass }} rounded-lg p-2 transition-colors">
                                                            <div class="flex items-center justify-between mb-1">
                                                                <span class="text-xs font-semibold text-gray-700 uppercase">{{ __($payment->pay_type) ?? '--' }}</span>
                                                                <span class="text-xs font-bold text-green-600">{{ str_replace(" - COM JUROS","", mb_strtoupper($payment->paid_label)) }}</span>
                                                            </div>
                                                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                                                @if ($paymentHasError)
                                                                    <div class="mb-1">
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-red-200 text-red-800 uppercase">
                                                                            {{ __($payment->status ?? 'erro') }}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                                @if (in_array(strtoupper($payment->pay_type),['CREDIT_CARD']))
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                                    </svg>
                                                                    <span>{{ strtoupper($payment->pay_card_brand) ?? '--' }} {{ $payment->pay_card_last ?? '--' }}</span>
                                                                @endif
                                                                @if (in_array(strtoupper($payment->pay_type),['BOLETO']))
                                                                    @if ($pedido->status == 'paid')
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                        </svg>
                                                                        <span class="text-green-600 font-semibold">PAGO</span>
                                                                        <span class="text-gray-500">{{ $payment->pay_datetime ? $payment->pay_datetime->format('d/m/Y') : null }}</span>
                                                                    @else
                                                                        <a href="{{ $payment->pay_boleto_url }}" target="_blank" class="flex items-center gap-1 text-blue-600 hover:text-blue-800">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                            </svg>
                                                                            <span>{{ convertToDate($payment->pay_boleto_expiration_date) }}</span>
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                                @if ($payment->pay_nsu ?? false)
                                                                    <span class="ml-auto text-xs">
                                                                        <span class="text-gray-500">NSU:</span>
                                                                        <span class="font-medium">{{ $payment->pay_nsu }}</span>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400">---</span>
                                            @endif
                                        </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <script>
                            document.title = "{{ mb_strtoupper(toSlug($target->event_name,'-') .'['. now()->format('YmdHis') .']') }}";

                            (function () {
                                const table = document.getElementById('ordersGrid');
                                if (!table) {
                                    return;
                                }

                                const searchInput = document.getElementById('ordersSearch');
                                const statusFilter = document.getElementById('ordersStatusFilter');
                                const pageSizeFilter = document.getElementById('ordersPageSize');
                                const clearFiltersButton = document.getElementById('ordersClearFilters');
                                const exportCsvButton = document.getElementById('ordersExportCsv');
                                const prevPageButton = document.getElementById('ordersPrevPage');
                                const nextPageButton = document.getElementById('ordersNextPage');
                                const resultsInfo = document.getElementById('ordersResultsInfo');
                                const pageInfo = document.getElementById('ordersPageInfo');
                                const rows = Array.from(table.querySelectorAll('tbody tr.order-row'));

                                let currentPage = 1;
                                let filteredRows = rows;

                                function normalize(text) {
                                    return (text || '')
                                        .toString()
                                        .toLowerCase()
                                        .normalize('NFD')
                                        .replace(/[\u0300-\u036f]/g, '');
                                }

                                function visibleRowsForPage() {
                                    const pageSize = parseInt(pageSizeFilter.value || '50', 10);
                                    const start = (currentPage - 1) * pageSize;
                                    return filteredRows.slice(start, start + pageSize);
                                }

                                function renderRows() {
                                    const pageSize = parseInt(pageSizeFilter.value || '50', 10);
                                    const total = filteredRows.length;
                                    const totalPages = Math.max(Math.ceil(total / pageSize), 1);

                                    if (currentPage > totalPages) {
                                        currentPage = totalPages;
                                    }
                                    if (currentPage < 1) {
                                        currentPage = 1;
                                    }

                                    rows.forEach((row) => {
                                        row.style.display = 'none';
                                    });

                                    visibleRowsForPage().forEach((row) => {
                                        row.style.display = '';
                                    });

                                    const start = total === 0 ? 0 : ((currentPage - 1) * pageSize) + 1;
                                    const end = Math.min(currentPage * pageSize, total);
                                    resultsInfo.textContent = `Exibindo ${start}-${end} de ${total} resultados`;
                                    pageInfo.textContent = `Página ${currentPage} de ${totalPages}`;
                                    prevPageButton.disabled = currentPage <= 1;
                                    nextPageButton.disabled = currentPage >= totalPages;
                                }

                                function applyFilters() {
                                    const searchValue = normalize(searchInput.value);
                                    const statusValue = statusFilter.value;

                                    filteredRows = rows.filter((row) => {
                                        const rowStatus = row.dataset.status || '';
                                        const rowSearch = normalize(row.dataset.search || '');
                                        const matchesStatus = !statusValue || rowStatus === statusValue;
                                        const matchesSearch = !searchValue || rowSearch.includes(searchValue);
                                        return matchesStatus && matchesSearch;
                                    });

                                    currentPage = 1;
                                    renderRows();
                                }

                                function toCsvCell(value) {
                                    const escaped = (value || '').toString().replace(/"/g, '""');
                                    return `"${escaped}"`;
                                }

                                function exportFilteredRowsToCsv() {
                                    const header = ['Data', 'Localizador', 'Situacao', 'Comprador', 'Email', 'Qtd', 'Valor', 'Pagamentos'];
                                    const lines = [header.map(toCsvCell).join(',')];

                                    filteredRows.forEach((row) => {
                                        lines.push([
                                            row.dataset.date,
                                            row.dataset.localizador,
                                            row.dataset.situacao,
                                            row.dataset.comprador,
                                            row.dataset.email,
                                            row.dataset.qtd,
                                            row.dataset.valor,
                                            row.dataset.pagamentos,
                                        ].map(toCsvCell).join(','));
                                    });

                                    const csvContent = '\uFEFF' + lines.join('\n');
                                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                                    const link = document.createElement('a');
                                    const eventSlug = @json(toSlug($target->event_name, '-'));
                                    link.href = URL.createObjectURL(blob);
                                    link.download = `vendas-${eventSlug}-${new Date().toISOString().slice(0, 10)}.csv`;
                                    document.body.appendChild(link);
                                    link.click();
                                    document.body.removeChild(link);
                                }

                                searchInput.addEventListener('input', applyFilters);
                                statusFilter.addEventListener('change', applyFilters);
                                pageSizeFilter.addEventListener('change', renderRows);

                                clearFiltersButton.addEventListener('click', function () {
                                    searchInput.value = '';
                                    statusFilter.value = '';
                                    pageSizeFilter.value = '50';
                                    applyFilters();
                                });

                                exportCsvButton.addEventListener('click', exportFilteredRowsToCsv);
                                prevPageButton.addEventListener('click', function () {
                                    currentPage -= 1;
                                    renderRows();
                                });
                                nextPageButton.addEventListener('click', function () {
                                    currentPage += 1;
                                    renderRows();
                                });

                                applyFilters();
                            })();
                        </script>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
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
                        <h1 class="text-2xl font-bold text-white">Vendas e Transações</h1>
                        <p class="text-white/90 text-sm">É preciso selecionar um evento</p>
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
