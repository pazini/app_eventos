@php
    use Illuminate\Support\Str;
@endphp

<div>
    <style>
        /* Compatibilidade visual do conteúdo rico do CKEditor nesta tela */
        .campaign-editor-content .text-tiny {
            font-size: 0.7em;
        }

        .campaign-editor-content .text-small {
            font-size: 0.85em;
        }

        .campaign-editor-content .text-big {
            font-size: 1.4em;
        }

        .campaign-editor-content .text-huge {
            font-size: 1.8em;
        }

        .campaign-editor-content span[style*="color"] strong,
        .campaign-editor-content span[style*="color"] b {
            color: inherit;
        }
    </style>

    <div class="{{ setClass('divContentHeader') }}">
        <div class="w-full flex justify-between items-center">
            <div>
                {!! setLabelHeader(
                    'Campanha',
                    $campaign->name,
                    $campaign->organizer->organizer_name_full ?? ($campaign->organizer->organizer_name ?? 'Organizador'),
                ) !!}
            </div>
            <div class="flex items-center gap-2">
                @if ($activeTab === 'detalhes')
                    <x-button outline white label="VOLTAR"
                        href="{{ route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id]) }}" />
                @else
                    <x-button white label="DETALHES"
                        href="{{ route('dashboard-campanhas-detalhes-detalhes', ['campaign_id' => $campaign->id]) }}" />
                    <x-button outline white label="FECHAR" href="{{ route('dashboard-campanhas') }}" />
                @endif

                <!-- @if ($campaign->status !== 'cancelled')
<x-button red outline label="ARQUIVAR" wire:click="arquivar" wire:confirm="Tem certeza que deseja arquivar esta campanha?" />
@endif -->
            </div>
        </div>
    </div>

    @if (!$showManualOrderModal && !$showOrderEditModal)
        <div class="w-full max-w-7xl mx-auto">
            <x-jet-validation-errors />
        </div>
    @endif

    <div class="w-full max-w-7xl mx-auto mt-4 space-y-4">
        @if ($activeTab !== 'detalhes')

            {{-- URL Pública da Campanha --}}
            <div class="bg-white border border-green-200 rounded-xl shadow-md px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-[10px] font-semibold text-gray-600 uppercase mb-1">URL da Campanha</div>
                        <div class="flex items-center gap-2">
                            <input type="text"
                                value="{{ campanhaUrl($campaign->customer_organization_slug, $campaign->slug) }}"
                                id="campaign-url" readonly
                                class="flex-1 px-0 py-1 text-sm font-mono bg-white border border-white rounded text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <button onclick="copiarURL()"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Copiar
                            </button>
                            <a href="{{ campanhaUrl($campaign->customer_organization_slug, $campaign->slug) }}"
                                target="_blank"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg>
                                Abrir
                            </a>
                            <button wire:click="openQrCodeModal"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                    </path>
                                </svg>
                                QR Code
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status e resumo --}}
            <div class="bg-white border rounded-xl shadow-md px-4 md:px-6 py-4">
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <div class="text-[10px] font-semibold text-gray-500 uppercase">Organizador</div>
                        <div class="text-lg text-gray-900 uppercase">
                            {{ $campaign->organizer->organizer_name_full ?? ($campaign->organizer->organizer_name ?? '-') }}
                        </div>
                    </div>
                    {{-- <div>
                    <div class="text-[10px] font-semibold text-gray-500 uppercase">Nome Curto</div>
                    <div class="text-lg font-semibold text-gray-900">
                        {{ $campaign->name_short ?? '-' }}
                    </div>
                </div> --}}
                    <div>
                        <div class="text-[10px] font-semibold text-gray-500 uppercase">Status</div>
                        <div class="text-lg font-bold uppercase">
                            @if ($campaign->status === 'active')
                                <span class="text-green-600">Ativa</span>
                            @elseif($campaign->status === 'active_direct')
                                <span class="text-blue-600">Ativa - Link Direto</span>
                            @elseif($campaign->status === 'draft')
                                <span class="text-gray-600">Rascunho</span>
                            @elseif($campaign->status === 'paused')
                                <span class="text-orange-600">Pausada</span>
                            @elseif($campaign->status === 'finished')
                                <span class="text-blue-600">Finalizada</span>
                            @else
                                <span class="text-red-600">Arquivada</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-[10px] font-semibold text-gray-500 uppercase">A partir de</div>
                        <div class="text-lg text-gray-900">
                            {{ $campaign->datetime_start ? \Carbon\Carbon::parse($campaign->datetime_start)->format('d/m/Y') : 'Sem data início' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-[10px] font-semibold text-gray-500 uppercase">Até</div>
                        <div
                            class="text-lg {{ $campaign->datetime_finish ? 'text-gray-900' : 'text-gray-400 italic' }}">
                            {{ $campaign->datetime_finish ? \Carbon\Carbon::parse($campaign->datetime_finish)->format('d/m/Y') : 'não definida' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs de Navegação --}}
            @if (!$selectedOrderId && $activeTab !== 'detalhes')
                <div class="bg-white border rounded-xl shadow-md">
                    <div class="flex border-b">
                        <button wire:click="setTab('analiticos')"
                            class="flex-1 px-6 py-4 text-sm font-semibold transition-colors {{ $activeTab === 'analiticos' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                <span>ANALÍTICO</span>
                            </div>
                        </button>
                        <button wire:click="setTab('adesoes')"
                            class="flex-1 px-6 py-4 text-sm font-semibold transition-colors {{ $activeTab === 'adesoes' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <span>ADESÕES</span>
                            </div>
                        </button>
                        <button wire:click="setTab('participantes')"
                            class="flex-1 px-6 py-4 text-sm font-semibold transition-colors {{ $activeTab === 'participantes' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>
                                </svg>
                                <span>PARTICIPANTES</span>
                            </div>
                        </button>
                        <button wire:click="setTab('questionarios')"
                            class="flex-1 px-6 py-4 text-sm font-semibold transition-colors {{ $activeTab === 'questionarios' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <span>QUESTIONÁRIOS</span>
                            </div>
                        </button>
                        <button wire:click="setTab('transacoes')"
                            class="flex-1 px-6 py-4 text-sm font-semibold transition-colors {{ $activeTab === 'transacoes' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                                <span>TRANSAÇÕES</span>
                            </div>
                        </button>
                    </div>
                </div>
            @endif
        @endif

        {{-- Conteúdo da Tab Analíticos --}}
        @if ($activeTab === 'analiticos')
            <div wire:key="tab-analiticos" class="space-y-6">
                @php
                    $metrics = $metricsLast30Days ?? [];
                    $period = $periodComparison ?? [];
                    $hasGoalAmount = !is_null($campaign->goal_amount) && (int) $campaign->goal_amount > 0;
                    $hasGoalLeads = !is_null($campaign->goal_leads) && (int) $campaign->goal_leads > 0;
                @endphp
                {{-- Métricas dos últimos 30 dias com comparativo --}}
                <div class="grid grid-cols-4 gap-4">
                    {{-- Receita --}}
                    <div class="bg-white border rounded-sm shadow px-4 py-4">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase mb-1">Receita (30 dias)</div>
                        <div class="text-2xl font-bold text-green-600">
                            {{ toMoney(data_get($metrics, 'revenue', 0), 'R$ ') }}
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-[10px] text-gray-500">
                                Meta: {{ $hasGoalAmount ? toMoney($campaign->goal_amount, 'R$ ') : '-' }}
                            </div>
                            @if (!is_null(data_get($period, 'revenue')))
                                <div
                                    class="text-[10px] font-bold {{ data_get($period, 'revenue.percent', 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ data_get($period, 'revenue.percent', 0) >= 0 ? '▲' : '▼' }}
                                    {{ abs(data_get($period, 'revenue.percent', 0)) }}%
                                </div>
                            @endif
                        </div>
                        @if (!is_null(data_get($period, 'revenue')))
                            <div class="mt-2 text-[9px] text-gray-400">
                                Período anterior: {{ toMoney(data_get($period, 'revenue.previous', 0), 'R$ ') }}
                            </div>
                        @endif
                        {{-- Barra de progresso da meta --}}
                        @php
                            $revenuePercent = $hasGoalAmount
                                ? min(100, (data_get($metrics, 'revenue', 0) / $campaign->goal_amount) * 100)
                                : 0;
                        @endphp
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full transition-all"
                                    style="width: {{ $revenuePercent }}%"></div>
                            </div>
                            <div class="text-[9px] text-gray-500 mt-1">
                                {{ $hasGoalAmount ? number_format($revenuePercent, 1) . '% da meta alcançada' : 'Meta não definida' }}
                            </div>
                        </div>
                    </div>

                    {{-- Leads --}}
                    <div class="bg-white border rounded-sm shadow px-4 py-4">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase mb-1">Leads (30 dias)</div>
                        <div class="text-2xl font-bold text-purple-600">
                            {{ data_get($metrics, 'leads', 0) }}
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-[10px] text-gray-500">
                                Meta: {{ $hasGoalLeads ? number_format($campaign->goal_leads, 0, ',', '.') : '-' }}
                            </div>
                            @if (!is_null(data_get($period, 'leads')))
                                <div
                                    class="text-[10px] font-bold {{ data_get($period, 'leads.percent', 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ data_get($period, 'leads.percent', 0) >= 0 ? '▲' : '▼' }}
                                    {{ abs(data_get($period, 'leads.percent', 0)) }}%
                                </div>
                            @endif
                        </div>
                        @if (!is_null(data_get($period, 'leads')))
                            <div class="mt-2 text-[9px] text-gray-400">
                                Período anterior: {{ data_get($period, 'leads.previous', 0) }}
                            </div>
                        @endif
                        {{-- Barra de progresso da meta --}}
                        @php
                            $leadsPercent = $hasGoalLeads
                                ? min(100, (data_get($metrics, 'leads', 0) / $campaign->goal_leads) * 100)
                                : 0;
                        @endphp
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full transition-all"
                                    style="width: {{ $leadsPercent }}%"></div>
                            </div>
                            <div class="text-[9px] text-gray-500 mt-1">
                                {{ $hasGoalLeads ? number_format($leadsPercent, 1) . '% da meta alcançada' : 'Meta não definida' }}
                            </div>
                        </div>
                    </div>

                    {{-- Adesões --}}
                    <div class="bg-white border rounded-sm shadow px-4 py-4">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase mb-1">Adesões (30 dias)</div>
                        <div class="text-2xl font-bold text-blue-600">
                            {{ data_get($metrics, 'orders', 0) }}
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-[10px] text-gray-500">
                                Pagas: {{ data_get($metrics, 'paid_orders', 0) }}
                            </div>
                            @if (!is_null(data_get($period, 'orders')))
                                <div
                                    class="text-[10px] font-bold {{ data_get($period, 'orders.percent', 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ data_get($period, 'orders.percent', 0) >= 0 ? '▲' : '▼' }}
                                    {{ abs(data_get($period, 'orders.percent', 0)) }}%
                                </div>
                            @endif
                        </div>
                        @if (!is_null(data_get($period, 'orders')))
                            <div class="mt-2 text-[9px] text-gray-400">
                                Período anterior: {{ data_get($period, 'orders.previous', 0) }}
                            </div>
                        @endif
                        {{-- Taxa de conversão --}}
                        @php
                            $ordersTotal = data_get($metrics, 'orders', 0);
                            $ordersPaid = data_get($metrics, 'paid_orders', 0);
                            $conversionRate = $ordersTotal > 0 ? ($ordersPaid / $ordersTotal) * 100 : 0;
                        @endphp
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all"
                                    style="width: {{ $conversionRate }}%"></div>
                            </div>
                            <div class="text-[9px] text-gray-500 mt-1">
                                {{ number_format($conversionRate, 1) }}% de conversão de pagamento
                            </div>
                        </div>
                    </div>

                    {{-- Conversões --}}
                    <div class="bg-white border rounded-sm shadow px-4 py-4">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase mb-1">Conversões (30 dias)</div>
                        <div class="text-2xl font-bold text-orange-600">
                            {{ data_get($metrics, 'conversions', 0) }}
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-[10px] text-gray-500">
                                Meta: {{ number_format($campaign->goal_conversions ?? 0, 0, ',', '.') }}
                            </div>
                            @if (!is_null(data_get($period, 'conversions')))
                                <div
                                    class="text-[10px] font-bold {{ data_get($period, 'conversions.percent', 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ data_get($period, 'conversions.percent', 0) >= 0 ? '▲' : '▼' }}
                                    {{ abs(data_get($period, 'conversions.percent', 0)) }}%
                                </div>
                            @endif
                        </div>
                        @if (!is_null(data_get($period, 'conversions')))
                            <div class="mt-2 text-[9px] text-gray-400">
                                Período anterior: {{ data_get($period, 'conversions.previous', 0) }}
                            </div>
                        @endif
                        {{-- Barra de progresso da meta --}}
                        @php
                            $conversionsPercent =
                                $campaign->goal_conversions > 0
                                    ? min(
                                        100,
                                        (data_get($metrics, 'conversions', 0) / $campaign->goal_conversions) * 100,
                                    )
                                    : 0;
                        @endphp
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-600 h-2 rounded-full transition-all"
                                    style="width: {{ $conversionsPercent }}%"></div>
                            </div>
                            <div class="text-[9px] text-gray-500 mt-1">
                                {{ number_format($conversionsPercent, 1) }}% da meta alcançada
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Gráficos --}}
                {{-- Gráfico de Receita --}}
                <div class="mt-6 bg-white border rounded-sm shadow px-4 py-4 w-full">
                    <div class="text-sm font-semibold text-gray-800 uppercase border-b pb-2 mb-3">
                        <div class="flex justify-between items-center">
                            <div>Receita Diária (Últimos 30 Dias)</div>
                            <div>{{ now()->subDays(30)->format('d/m/Y') }} até {{ now()->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div class="w-full" style="position: relative; height: 300px;">
                        <canvas id="revenueChart" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>

                {{-- Gráfico de Adesões --}}
                <div class="mt-6 bg-white border rounded-sm shadow px-4 py-4 w-full">
                    <div class="text-sm font-semibold text-gray-800 uppercase border-b pb-2 mb-3">
                        Adesões Diárias (Últimos 30 Dias)
                    </div>
                    <div class="w-full" style="position: relative; height: 300px;">
                        <canvas id="transactionsChart"
                            style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>

            </div>
        @endif
        {{-- Fim da Tab Analíticos --}}

        {{-- Conteúdo da Tab Detalhes --}}
        @if ($activeTab === 'detalhes')
            <div wire:key="tab-detalhes" class="space-y-6">

                {{-- Botão de Preview --}}
                {{-- <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <div>
                                <div class="font-bold text-lg">Visualizar Página Pública</div>
                                <div class="text-xs text-blue-100">Veja como sua campanha está sendo exibida para o público</div>
                            </div>
                        </div>
                        <a
                            href="{{ campanhaUrl($campaign->customer_organization_slug, $campaign->slug) }}"
                            target="_blank"
                            class="px-6 py-3 bg-white text-blue-600 font-bold rounded-lg hover:bg-blue-50 transition-all duration-200 shadow-lg flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            ABRIR PREVIEW
                        </a>
                    </div>
                </div> --}}

                {{-- Informações Básicas --}}
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informações Básicas
                            </h3>
                            {{-- Menu de três pontos --}}
                            <div class="relative inline-block" x-data="{ open: false }" @click.away="open = false"
                                @close.stop="open = false">
                                <button type="button" @click.stop="open = ! open"
                                    class="inline-flex items-center justify-center p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 min-w-[36px] min-h-[36px]"
                                    aria-label="Menu de opções" aria-expanded="false" x-bind:aria-expanded="open">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                        </path>
                                    </svg>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-50 mt-2 w-52 rounded-md shadow-lg origin-top-right right-0"
                                    style="display: none;" @click.stop="open = false">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                        <a href="{{ route('dashboard-campanhas-editar', ['campaign_id' => $campaign->id]) }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            Editar
                                        </a>
                                        @if ($campaign->status === 'draft' || $campaign->status === 'paused')
                                            <button wire:click="ativar"
                                                class="w-full text-left block px-4 py-2 text-sm text-green-700 hover:bg-green-50 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                </svg>
                                                Ativar
                                            </button>
                                        @endif
                                        @if ($campaign->status === 'active' || $campaign->status === 'active_direct')
                                            <button wire:click="pausar"
                                                class="w-full text-left block px-4 py-2 text-sm text-orange-700 hover:bg-orange-50 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Pausar
                                            </button>
                                        @endif
                                        @if (isAdmin())
                                            <div class="border-t border-gray-100 my-1"></div>
                                            <button wire:click="abrirModalClonar"
                                                class="w-full text-left block px-4 py-2 text-sm text-indigo-700 hover:bg-indigo-50 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                Clonar Campanha
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Nome da
                                    Campanha</label>
                                <div class="text-base font-bold text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    {{ $campaign->name }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Nome
                                    Curto</label>
                                <div class="text-base text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    {{ $campaign->name_short ?: '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Slug
                                    (URL)</label>
                                <div
                                    class="text-base font-mono text-blue-600 bg-blue-50 px-4 py-3 rounded-lg border border-blue-200">
                                    {{ $campaign->slug }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Status</label>
                                <div class="flex items-center gap-2">
                                    @if ($campaign->status === 'active')
                                        <span
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-bold bg-green-100 text-green-700 border border-green-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            CAMPANHA ATIVA
                                        </span>
                                    @elseif($campaign->status === 'active_direct')
                                        <span
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-bold bg-blue-100 text-blue-700 border border-blue-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            CAMPANHA ATIVA - LINK DIRETO
                                        </span>
                                    @elseif($campaign->status === 'draft')
                                        <span
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-bold bg-gray-100 text-gray-700 border border-gray-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            CAMPANHA RASCUNHO
                                        </span>
                                    @elseif($campaign->status === 'paused')
                                        <span
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-bold bg-orange-100 text-orange-700 border border-orange-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            CAMPANHA PAUSADA
                                        </span>
                                    @elseif($campaign->status === 'finished')
                                        <span
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-bold bg-blue-100 text-blue-700 border border-blue-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            CAMPANHA FINALIZADA
                                        </span>
                                    @else
                                        <span
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-bold bg-red-100 text-red-700 border border-red-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            CAMPANHA CANCELADA
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Data de
                                    Início</label>
                                <div class="text-base text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    {{ $campaign->datetime_start ? \Carbon\Carbon::parse($campaign->datetime_start)->format('d/m/Y H:i') : 'Não definida' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Data de
                                    Término</label>
                                <div class="text-base text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    {{ $campaign->datetime_finish ? \Carbon\Carbon::parse($campaign->datetime_finish)->format('d/m/Y H:i') : 'Não definida' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Metas e Valores --}}
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 md:px-6 py-3 border-b">
                        <h3 class="text-base md:text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            Metas e Valores
                        </h3>
                    </div>
                    <div class="p-4 md:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="flex flex-col">
                                <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1.5">Meta de
                                    Receita</label>
                                <div
                                    class="text-lg md:text-xl font-bold text-green-700 bg-green-50 px-3 py-2.5 rounded-lg border border-green-200 break-words">
                                    {{ !is_null($campaign->goal_amount) ? toMoney($campaign->goal_amount, 'R$ ') : '-' }}
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1.5">Meta de
                                    Leads</label>
                                <div
                                    class="text-lg md:text-xl font-bold text-purple-700 bg-purple-50 px-3 py-2.5 rounded-lg border border-purple-200 break-words">
                                    {{ !is_null($campaign->goal_leads) ? number_format($campaign->goal_leads, 0, ',', '.') : '-' }}
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1.5">Meta de
                                    Conversões</label>
                                <div
                                    class="text-lg md:text-xl font-bold text-orange-700 bg-orange-50 px-3 py-2.5 rounded-lg border border-orange-200 break-words">
                                    {{ number_format($campaign->goal_conversions ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1.5">Valor
                                    Mínimo</label>
                                <div
                                    class="text-lg md:text-xl font-bold text-blue-700 bg-blue-50 px-3 py-2.5 rounded-lg border border-blue-200 break-words">
                                    {{ toMoney($campaign->amount_min ?? 1000, 'R$ ') }}
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 mt-4 border-t"></div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <div
                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg border transition-all {{ $campaign->show_goal_amount ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                @if ($campaign->show_goal_amount)
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                @endif
                                <span
                                    class="text-xs font-medium pl-2 {{ $campaign->show_goal_amount ? 'text-green-700' : 'text-gray-500' }}">Meta
                                    de receita</span>
                            </div>
                            <div
                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg border transition-all {{ $campaign->show_goal_leads ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                @if ($campaign->show_goal_leads)
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                @endif
                                <span
                                    class="text-xs font-medium pl-2 {{ $campaign->show_goal_leads ? 'text-green-700' : 'text-gray-500' }}">Meta
                                    de leads</span>
                            </div>
                            <div
                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg border transition-all {{ $campaign->show_goal_conversions ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                @if ($campaign->show_goal_conversions)
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                @endif
                                <span
                                    class="text-xs font-medium pl-2 {{ $campaign->show_goal_conversions ? 'text-green-700' : 'text-gray-500' }}">Meta
                                    de conversões</span>
                            </div>
                            <div
                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg border transition-all {{ $campaign->show_progress ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                @if ($campaign->show_progress)
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div
                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                @endif
                                <span
                                    class="text-xs font-medium pl-2 {{ $campaign->show_progress ? 'text-green-700' : 'text-gray-500' }}">Exibir
                                    progresso</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Descrição e Sobre --}}
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            Conteúdo Descritivo
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Descrição</label>
                            <div
                                class="prose max-w-none bg-gray-50 px-4 py-3 rounded-lg border min-h-[100px] campaign-editor-content">
                                @if ($campaign->description)
                                    {!! $campaign->description !!}
                                @else
                                    <span class="text-gray-400 italic">Sem descrição</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Sobre
                                (Detalhes)</label>
                            <div
                                class="prose max-w-none bg-gray-50 px-4 py-3 rounded-lg border min-h-[100px] campaign-editor-content">
                                @if ($campaign->about)
                                    {!! $campaign->about !!}
                                @else
                                    <span class="text-gray-400 italic">Sem informações detalhadas</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Privacidade e Configurações --}}
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            Privacidade e Configurações
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Perguntas do Quiz
                                    </label>
                                    @if ($campaign->enable_questions)
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                            ATIVO
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">
                                            INATIVO
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 mt-2">
                                    @if ($campaign->enable_questions)
                                        Os doadores devem responder perguntas personalizadas antes de contribuir
                                    @else
                                        As perguntas do quiz estão desabilitadas para esta campanha
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Exigir CPF/CNPJ
                                    </label>
                                    @if ($campaign->require_doc)
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                            OBRIGATÓRIO
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-bold">
                                            OPCIONAL
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 mt-2">
                                    @if ($campaign->require_doc)
                                        O preenchimento do documento é obrigatório para todos os doadores
                                    @else
                                        O documento do doador é opcional
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Doação Anônima
                                    </label>
                                    @if ($campaign->allow_anonymous)
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                            PERMITIDO
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">
                                            NÃO PERMITIDO
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 mt-2">
                                    @if ($campaign->allow_anonymous)
                                        Os doadores podem optar por fazer contribuições anônimas
                                    @else
                                        Todas as doações devem ser identificadas
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Recorrência Mensal
                                    </label>
                                    @if ($campaign->allow_recurring)
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                            ATIVO
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">
                                            INATIVO
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 mt-2">
                                    @if ($campaign->allow_recurring)
                                        Permite doações recorrentes mensais via cartão de crédito
                                    @else
                                        Doações recorrentes não estão habilitadas para esta campanha
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Perguntas do Quiz Configuradas --}}
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Perguntas do Quiz ({{ $campaign->questions->count() }})
                        </h3>
                    </div>
                    <div class="p-6">
                        @if ($campaign->questions->count() > 0)
                            <div class="space-y-4">
                                @foreach ($campaign->questions as $question)
                                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                        <div class="flex items-start gap-3">
                                            <span class="bg-purple-600 text-white px-2 py-1 rounded font-bold text-xs">
                                                #{{ $question->order + 1 }}
                                            </span>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="text-lg font-semibold text-purple-700 uppercase">
                                                        {{ $question->question_text }}
                                                    </span>
                                                    @if ($question->placeholder)
                                                        <span
                                                            class="text-basetext-blue-700 font-light">{{ $question->placeholder }}</span>
                                                    @endif
                                                    @if ($question->is_required)
                                                        <span
                                                            class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded font-bold">OBRIGATÓRIA</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <div class="text-sm font-bold text-gray-900">
                                                        {{ [
                                                            'text' => 'Texto Curto',
                                                            'textarea' => 'Texto Longo',
                                                            'select' => 'Lista Suspensa',
                                                            'radio' => 'Botão Escolha Única',
                                                            'checkbox' => 'Botão Seleção Múltipla',
                                                            'number' => 'Campo Número',
                                                            'date' => 'Campo Data',
                                                        ][$question->question_type] ?? $question->question_type }}
                                                    </div>
                                                    @if ($question->question_options && in_array($question->question_type, ['select', 'radio', 'checkbox']))
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach ($question->question_options as $option)
                                                                <span
                                                                    class="text-xs bg-white border border-purple-300 text-purple-700 px-2 py-1 rounded">
                                                                    {{ $option }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                                @if ($question->help_text)
                                                    <p class="text-xs text-gray-600 mt-1">💡
                                                        {{ $question->help_text }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <p class="text-gray-500 font-medium">Nenhuma pergunta configurada</p>
                                <p class="text-xs text-gray-400 mt-1">Adicione perguntas na edição da campanha</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Personalização Visual --}}
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                                </path>
                            </svg>
                            Personalização Visual
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Cores --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Cor
                                    Primária</label>
                                <div class="flex items-center gap-3">
                                    <div class="w-16 h-16 rounded-lg border-2 border-gray-300 shadow-inner"
                                        style="background-color: {{ $campaign->color_primary ?: '#3B82F6' }}"></div>
                                    <div class="flex-1">
                                        <div
                                            class="text-sm font-mono font-semibold text-gray-900 bg-gray-50 px-3 py-2 rounded border">
                                            {{ $campaign->color_primary ?: '#3B82F6' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Cor
                                    Secundária</label>
                                <div class="flex items-center gap-3">
                                    <div class="w-16 h-16 rounded-lg border-2 border-gray-300 shadow-inner"
                                        style="background-color: {{ $campaign->color_secondary ?: '#6366F1' }}"></div>
                                    <div class="flex-1">
                                        <div
                                            class="text-sm font-mono font-semibold text-gray-900 bg-gray-50 px-3 py-2 rounded border">
                                            {{ $campaign->color_secondary ?: '#6366F1' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Imagens --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Banner --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Banner</label>
                                @php
                                    // Função helper para gerar URL completa da imagem (storage isolado por tenant)
                                    $getImageUrl = function ($url) {
                                        if (empty($url)) {
                                            return null;
                                        }
                                        // Verifica se já é URL completa
                                        if (substr($url, 0, 7) === 'http://' || substr($url, 0, 8) === 'https://') {
                                            return $url;
                                        }
                                        // Se começa com /storage/, usa asset diretamente (compatibilidade)
                                        if (substr($url, 0, 9) === '/storage/') {
                                            return asset($url);
                                        }
                                        // Se começa com storage/, adiciona / (compatibilidade)
                                        if (substr($url, 0, 8) === 'storage/') {
                                            return asset('/' . $url);
                                        }
                                        // Caso padrão: usa tenantAsset para storage isolado
                                        return tenantAsset($url, true);
                                    };
                                @endphp
                                @if ($campaign->url_image_banner)
                                    <div class="relative group">
                                        <img src="{{ $getImageUrl($campaign->url_image_banner) }}" alt="Banner"
                                            class="w-full h-40 object-cover bg-gray-50 rounded-lg border-2 border-gray-300" />
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all rounded-lg flex items-center justify-center">
                                            <a href="{{ $getImageUrl($campaign->url_image_banner) }}" target="_blank"
                                                class="opacity-0 group-hover:opacity-100 px-4 py-2 bg-white text-gray-900 rounded-lg font-semibold text-sm">
                                                Ver Imagem
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="w-full h-40 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">Sem banner</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Thumbnail --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Miniatura
                                    (Thumbnail)</label>
                                @if ($campaign->url_image_thumb)
                                    <div class="relative group">
                                        <img src="{{ $getImageUrl($campaign->url_image_thumb) }}" alt="Thumbnail"
                                            class="w-full h-40 object-cover bg-gray-50 rounded-lg border-2 border-gray-300" />
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all rounded-lg flex items-center justify-center">
                                            <a href="{{ $getImageUrl($campaign->url_image_thumb) }}" target="_blank"
                                                class="opacity-0 group-hover:opacity-100 px-4 py-2 bg-white text-gray-900 rounded-lg font-semibold text-sm">
                                                Ver Imagem
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="w-full h-40 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">Sem miniatura</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif
        {{-- Fim da Tab Detalhes --}}

        {{-- Conteúdo da Tab Transações --}}
        @if ($activeTab === 'transacoes')
            <div wire:key="tab-transacoes">

                {{-- Gateway de Pagamento e Configurações (oculto quando há transação selecionada) --}}
                @if (!$selectedTransaction)
                    <div class="bg-white border rounded-lg shadow-md overflow-hidden mb-4">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                        </path>
                                    </svg>
                                    Gateway de Pagamento
                                </h3>
                                {{-- Menu de três pontos --}}
                                <div class="relative inline-block" x-data="{ open: false }" @click.away="open = false"
                                    @close.stop="open = false">
                                    <button type="button" @click.stop="open = ! open"
                                        class="inline-flex items-center justify-center p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 min-w-[36px] min-h-[36px]"
                                        aria-label="Menu de opções" aria-expanded="false"
                                        x-bind:aria-expanded="open">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                            </path>
                                        </svg>
                                    </button>

                                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute z-50 mt-2 w-56 rounded-md shadow-lg origin-top-right right-0"
                                        style="display: none;" @click.stop="open = false">
                                        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                            <a href="{{ route('dashboard-campanhas-metodo-pagamento', ['campaign_id' => $campaign->id]) }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                    </path>
                                                </svg>
                                                Editar Método
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {{-- Gateway Info --}}
                                <div>
                                    <div class="text-[10px] font-semibold text-gray-500 uppercase mb-2">Gateway
                                        Configurado</div>
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ $campaign->gateway->pay_gateway_label ?? 'Não configurado' }}
                                    </div>
                                    @if ($campaign->gateway)
                                        <div class="text-[10px] text-gray-500">
                                            {{ $campaign->gateway->pay_gateway_description ?? '' }}</div>
                                        <div class="text-[10px] mt-4">
                                            <span
                                                class="px-2 py-1 rounded text-white {{ $campaign->pay_sandbox ? 'bg-orange-500' : 'bg-green-600' }}">
                                                {{ $campaign->pay_sandbox ? 'MODO TESTE' : 'ATIVADO' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Formas de Pagamento --}}
                                <div>
                                    <div class="text-[10px] font-semibold text-gray-500 uppercase mb-2">Formas de
                                        Pagamento</div>
                                    <div class="space-y-2">
                                        {{-- PIX --}}
                                        <div
                                            class="flex items-center gap-2 text-xs {{ $campaign->pay_pix ? '' : 'opacity-40' }}">
                                            <svg class="w-4 h-4 {{ $campaign->pay_pix ? 'text-green-600' : 'text-gray-400' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                @if ($campaign->pay_pix)
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                @else
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                            <span
                                                class="font-medium {{ $campaign->pay_pix ? '' : 'line-through' }}">PIX</span>
                                        </div>

                                        {{-- Boleto --}}
                                        <div
                                            class="flex items-center gap-2 text-xs {{ $campaign->pay_boleto ? '' : 'opacity-40' }}">
                                            <svg class="w-4 h-4 {{ $campaign->pay_boleto ? 'text-green-600' : 'text-gray-400' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                @if ($campaign->pay_boleto)
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                @else
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                            <span
                                                class="font-medium {{ $campaign->pay_boleto ? '' : 'line-through' }}">Boleto
                                                Bancário</span>
                                        </div>

                                        {{-- Cartão de Crédito --}}
                                        <div
                                            class="flex items-center justify-between text-xs {{ $campaign->pay_card_credit ? '' : 'opacity-40' }}">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <svg class="w-4 h-4 {{ $campaign->pay_card_credit ? 'text-green-600' : 'text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    @if ($campaign->pay_card_credit)
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"></path>
                                                    @else
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                            clip-rule="evenodd"></path>
                                                    @endif
                                                </svg>
                                                <span
                                                    class="font-medium {{ $campaign->pay_card_credit ? '' : 'line-through' }}">Cartão
                                                    de Crédito</span>
                                                @if ($campaign->pay_card_credit && $campaign->pay_card_credit_installment_max > 1)
                                                    <span
                                                        class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                                                        Até {{ $campaign->pay_card_credit_installment_max }}x
                                                    </span>
                                                    {{-- Juros do Parcelamento --}}
                                                    <span
                                                        class="text-[9px] px-2 py-0.5 rounded {{ $campaign->pay_card_credit_installment_fee_payer === 'customer' ? 'bg-orange-50 text-orange-700' : 'bg-green-50 text-green-700' }}">
                                                        @if ($campaign->pay_card_credit_installment_fee_payer === 'customer')
                                                            Juros para o Cliente
                                                        @else
                                                            Sem juros (a campanha absorve)
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    {{-- Valor Meta --}}
                                    <div>
                                        <div class="text-[10px] font-semibold text-gray-500 uppercase">Valor da Meta
                                        </div>
                                        <div class="text-xl font-bold text-gray-900">
                                            {{ !is_null($campaign->goal_amount) ? toMoney($campaign->goal_amount, 'R$ ') : '-' }}
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        @if ($campaign->pay_card_credit ?? false)
                                            {{-- Valor Mínimo Crédito --}}
                                            <div>
                                                <div class="text-[10px] font-semibold text-gray-500 uppercase">Valor
                                                    Mínimo Crédito</div>
                                                <div class="text-xl font-bold text-gray-900">
                                                    {{ toMoney($campaign->pay_card_credit_installment_amount_min ?? 0, 'R$ ') }}
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Valor Mínimo --}}
                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Valor Mínimo
                                                Campanha</div>
                                            <div class="text-xl font-bold text-gray-900">
                                                {{ toMoney($campaign->amount_min ?? 0, 'R$ ') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Transações --}}
                @php
                    $filteredTransactions = $this->getFilteredTransactions();
                @endphp

                @if (!$selectedTransactionId)
                    <div class="bg-white border rounded-sm shadow px-4 md:px-6 py-4 mb-4">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Transações
                                ({{ $filteredTransactions->total() }})</h3>
                            <div class="flex gap-2">
                                <button wire:click="refreshTransacoes"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Atualizar
                                </button>
                                <a href="{{ route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id]) }}?export=transacoes"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Exportar CSV
                                </a>
                            </div>
                        </div>

                        {{-- Filtros --}}
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status</label>
                                <select wire:model="filterTransactionStatus"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todos</option>
                                    <option value="paid">Pago</option>
                                    <option value="processing">Processando</option>
                                    <option value="pending">Pendente</option>
                                    <option value="error">Erro</option>
                                    <option value="cancelled">Cancelado</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data
                                    Início</label>
                                <input type="date" wire:model="filterTransactionDateFrom"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data
                                    Fim</label>
                                <input type="date" wire:model="filterTransactionDateTo"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Buscar</label>
                                <input type="text" wire:model="filterTransactionSearch"
                                    placeholder="Localizador, Nome, E-mail, NSU..."
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Por
                                    página</label>
                                <select wire:model="transactionPerPage"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="10">10</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                    <option value="500">500</option>
                                </select>
                            </div>
                        </div>

                        {{-- Tabela de Transações --}}
                        <div id="transaction-table-top" x-data="{}" x-init="window.addEventListener('transactionPageChanged', () => {
                            const el = document.getElementById('transaction-table-top');
                            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        });">

                            {{-- Paginação Superior --}}
                            @if ($filteredTransactions->hasPages())
                                <div
                                    class="flex flex-col sm:flex-row items-center justify-between gap-3 mb-3 pb-3 border-b border-gray-100">
                                    <div class="text-xs text-gray-500">
                                        Exibindo <span
                                            class="font-semibold">{{ $filteredTransactions->firstItem() }}</span>
                                        a <span class="font-semibold">{{ $filteredTransactions->lastItem() }}</span>
                                        de <span class="font-semibold">{{ $filteredTransactions->total() }}</span>
                                        transações
                                    </div>
                                    <div class="flex items-center gap-1">
                                        @if ($filteredTransactions->onFirstPage())
                                            <span
                                                class="px-3 py-1.5 text-xs text-gray-400 border border-gray-200 rounded cursor-not-allowed">&laquo;</span>
                                        @else
                                            <button
                                                wire:click="gotoPageAndScroll({{ $filteredTransactions->currentPage() - 1 }})"
                                                class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">&laquo;</button>
                                        @endif
                                        @if ($filteredTransactions->lastPage() <= 10)
                                            @foreach ($filteredTransactions->getUrlRange(1, $filteredTransactions->lastPage()) as $page => $url)
                                                @if ($page == $filteredTransactions->currentPage())
                                                    <span
                                                        class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 border border-blue-600 rounded">{{ $page }}</span>
                                                @else
                                                    <button wire:click="gotoPageAndScroll({{ $page }})"
                                                        class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">{{ $page }}</button>
                                                @endif
                                            @endforeach
                                        @else
                                            @php
                                                $cur = $filteredTransactions->currentPage();
                                                $last = $filteredTransactions->lastPage();
                                            @endphp
                                            @if ($cur > 5)
                                                <button wire:click="gotoPageAndScroll(1)"
                                                    class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">1</button>
                                                <span class="px-2 py-1.5 text-xs text-gray-400">...</span>
                                            @endif
                                            @foreach ($filteredTransactions->getUrlRange(max(1, $cur - 4), min($last, $cur + 4)) as $page => $url)
                                                @if ($page == $cur)
                                                    <span
                                                        class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 border border-blue-600 rounded">{{ $page }}</span>
                                                @else
                                                    <button wire:click="gotoPageAndScroll({{ $page }})"
                                                        class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">{{ $page }}</button>
                                                @endif
                                            @endforeach
                                            @if ($cur < $last - 4)
                                                <span class="px-2 py-1.5 text-xs text-gray-400">...</span>
                                                <button wire:click="gotoPageAndScroll({{ $last }})"
                                                    class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">{{ $last }}</button>
                                            @endif
                                        @endif
                                        @if ($filteredTransactions->hasMorePages())
                                            <button
                                                wire:click="gotoPageAndScroll({{ $filteredTransactions->currentPage() + 1 }})"
                                                class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">&raquo;</button>
                                        @else
                                            <span
                                                class="px-3 py-1.5 text-xs text-gray-400 border border-gray-200 rounded cursor-not-allowed">&raquo;</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="w-full overflow-hidden">

                                <div class="w-full overflow-hidden">
                                    <table class="w-full table-fixed divide-y divide-gray-200">
                                        <colgroup>
                                            <col style="width:10%">
                                            <col style="width:10%">
                                            <col style="width:22%">
                                            <col style="width:20%">
                                            <col style="width:9%">
                                            <col style="width:13%">
                                            <col style="width:9%">
                                            <col style="width:7%">
                                        </colgroup>
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Data/Hora</th>
                                                <th
                                                    class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Adesão</th>
                                                <th
                                                    class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Doador</th>
                                                <th
                                                    class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Gateway</th>
                                                <th
                                                    class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Método</th>
                                                <th
                                                    class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    NSU</th>
                                                <th
                                                    class="text-center px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Valor</th>
                                                <th
                                                    class="text-center px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                                    Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($filteredTransactions as $transaction)
                                                @php
                                                    $statusClass = match ($transaction->status) {
                                                        'paid', 'approved' => 'bg-green-100 text-green-700',
                                                        'processing' => 'bg-blue-100 text-blue-700',
                                                        'pending' => 'bg-orange-100 text-orange-700',
                                                        'error', 'refused' => 'bg-red-100 text-red-700',
                                                        default => 'bg-gray-100 text-gray-700',
                                                    };

                                                    $statusLabel = match ($transaction->status) {
                                                        'paid' => 'PAGO',
                                                        'approved' => 'APROVADO',
                                                        'processing' => 'PROCESSANDO',
                                                        'pending' => 'PENDENTE',
                                                        'error' => 'ERRO',
                                                        'refused' => 'RECUSADO',
                                                        default => strtoupper($transaction->status ?? 'N/D'),
                                                    };
                                                @endphp
                                                <tr class="hover:bg-gray-50 cursor-pointer"
                                                    wire:key="transaction-{{ $transaction->id }}"
                                                    wire:click="goToTransaction('{{ $transaction->id }}')">
                                                    <td class="px-4 py-3 whitespace-nowrap"
                                                        title="{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s') }}">
                                                        <div class="text-xs font-semibold text-gray-900">
                                                            {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}
                                                        </div>
                                                        <div class="text-[10px] text-gray-500">
                                                            {{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i:s') }}
                                                        </div>
                                                        @if (
                                                            ($transaction->pay_type === 'pix' || $transaction->pay_type === 'slip_pix') &&
                                                                !empty($transaction->pay_pix_expires_at) &&
                                                                in_array($transaction->status, ['pending', 'processing', 'pix_expired']))
                                                            @php
                                                                $pixExpiresAt = \Carbon\Carbon::parse(
                                                                    $transaction->pay_pix_expires_at,
                                                                );
                                                                $pixIsExpired =
                                                                    $pixExpiresAt->isPast() ||
                                                                    $transaction->status === 'pix_expired';
                                                            @endphp
                                                            <div
                                                                class="text-[10px] font-semibold mt-1 {{ $pixIsExpired ? 'text-red-600' : 'text-orange-600' }}">
                                                                {{ $pixIsExpired ? '⚠️ Expirado' : '⏰ Expira: ' . $pixExpiresAt->format('d/m H:i') }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap"
                                                        title="Adesão: {{ $transaction->order_control ?? '-' }}">
                                                        <div class="text-xs font-mono font-semibold text-blue-600">
                                                            {{ $transaction->order_control ?? '-' }}</div>
                                                        <div class="text-[10px] text-gray-500">
                                                            {{ substr($transaction->id, 0, 8) }}</div>
                                                    </td>
                                                    <td class="px-4 py-3 overflow-hidden"
                                                        title="{{ ($transaction->buyer_name ?? '-') . ' — ' . ($transaction->buyer_email ?? '') }}">
                                                        <div class="text-xs font-semibold text-gray-900 truncate">
                                                            {{ $transaction->buyer_name ?? '-' }}</div>
                                                        <div class="text-[10px] text-gray-500 truncate">
                                                            {{ $transaction->buyer_email ?? '-' }}</div>
                                                    </td>
                                                    <td class="px-4 py-3 overflow-hidden"
                                                        title="{{ $transaction->pay_gateway_label ?? '-' }}">
                                                        <div class="text-xs text-gray-900 truncate">
                                                            {{ $transaction->pay_gateway_label ?? '-' }}</div>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap"
                                                        title="Método: {{ strtoupper($transaction->pay_type ?? '-') }}">
                                                        <div class="text-xs font-semibold text-gray-900">
                                                            {{ strtoupper($transaction->pay_type ?? '-') }}</div>
                                                    </td>
                                                    <td class="px-4 py-3 overflow-hidden"
                                                        title="NSU: {{ $transaction->pay_nsu ?? ($transaction->external_payment_id ?? '-') }}">
                                                        <div class="text-xs font-mono text-gray-600 truncate">
                                                            {{ $transaction->pay_nsu ?? ($transaction->external_payment_id ? substr($transaction->external_payment_id, 0, 16) : '-') }}
                                                        </div>
                                                    </td>
                                                    <td class="text-center px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900"
                                                        title="Valor: {{ toMoney($transaction->amount, 'R$ ') }}">
                                                        {{ toMoney($transaction->amount, 'R$ ') }}
                                                    </td>
                                                    <td class="text-center px-4 py-3 whitespace-nowrap"
                                                        title="Status: {{ $statusLabel }}">
                                                        <span
                                                            class="px-2 py-1 text-xs font-semibold rounded {{ $statusClass }}">
                                                            {{ $statusLabel }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9"
                                                        class="px-4 py-8 text-center text-sm text-gray-500">
                                                        Nenhuma transação encontrada com os filtros aplicados.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>{{-- /overflow-hidden --}}

                                {{-- Paginacao Inferior --}}
                                @if ($filteredTransactions->hasPages())
                                    <div
                                        class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-4 pt-4 border-t border-gray-100">
                                        <div class="text-xs text-gray-500">
                                            Exibindo <span
                                                class="font-semibold">{{ $filteredTransactions->firstItem() }}</span>
                                            a <span
                                                class="font-semibold">{{ $filteredTransactions->lastItem() }}</span>
                                            de <span
                                                class="font-semibold">{{ $filteredTransactions->total() }}</span>
                                            transações
                                        </div>
                                        <div class="flex items-center gap-1">
                                            {{-- Anterior --}}
                                            @if ($filteredTransactions->onFirstPage())
                                                <span
                                                    class="px-3 py-1.5 text-xs text-gray-400 border border-gray-200 rounded cursor-not-allowed">&laquo;</span>
                                            @else
                                                <button
                                                    wire:click="gotoPageAndScroll({{ $filteredTransactions->currentPage() - 1 }})"
                                                    class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">&laquo;</button>
                                            @endif

                                            {{-- Páginas --}}
                                            @if ($filteredTransactions->lastPage() <= 10)
                                                @foreach ($filteredTransactions->getUrlRange(1, $filteredTransactions->lastPage()) as $page => $url)
                                                    @if ($page == $filteredTransactions->currentPage())
                                                        <span
                                                            class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 border border-blue-600 rounded">{{ $page }}</span>
                                                    @else
                                                        <button wire:click="gotoPageAndScroll({{ $page }})"
                                                            class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">{{ $page }}</button>
                                                    @endif
                                                @endforeach
                                            @else
                                                @php
                                                    $cur = $filteredTransactions->currentPage();
                                                    $last = $filteredTransactions->lastPage();
                                                @endphp
                                                @if ($cur > 5)
                                                    <button wire:click="gotoPageAndScroll(1)"
                                                        class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">1</button>
                                                    <span class="px-2 py-1.5 text-xs text-gray-400">...</span>
                                                @endif
                                                @foreach ($filteredTransactions->getUrlRange(max(1, $cur - 4), min($last, $cur + 4)) as $page => $url)
                                                    @if ($page == $cur)
                                                        <span
                                                            class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 border border-blue-600 rounded">{{ $page }}</span>
                                                    @else
                                                        <button wire:click="gotoPageAndScroll({{ $page }})"
                                                            class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">{{ $page }}</button>
                                                    @endif
                                                @endforeach
                                                @if ($cur < $last - 4)
                                                    <span class="px-2 py-1.5 text-xs text-gray-400">...</span>
                                                    <button wire:click="gotoPageAndScroll({{ $last }})"
                                                        class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">{{ $last }}</button>
                                                @endif
                                            @endif

                                            {{-- Próximo --}}
                                            @if ($filteredTransactions->hasMorePages())
                                                <button
                                                    wire:click="gotoPageAndScroll({{ $filteredTransactions->currentPage() + 1 }})"
                                                    class="px-3 py-1.5 text-xs text-gray-700 border border-gray-300 rounded hover:bg-gray-100">&raquo;</button>
                                            @else
                                                <span
                                                    class="px-3 py-1.5 text-xs text-gray-400 border border-gray-200 rounded cursor-not-allowed">&raquo;</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>{{-- /transaction-table-top --}}
                        </div>
                @endif

                {{-- Detalhes da Transação Selecionada --}}
                @if ($selectedTransaction)
                    <div class="space-y-4">
                        {{-- Botão Voltar --}}
                        <button wire:click="goToTransactionList"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Voltar para Transações
                        </button>

                        {{-- Flash Messages --}}
                        @if (session()->has('success'))
                            <div
                                class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div
                                class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">{{ session('error') }}</span>
                            </div>
                        @endif

                        {{-- Card de Informações da Transação --}}
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Transação</h3>
                                        <p class="text-sm text-gray-600 mt-1">ID: {{ $selectedTransaction->id }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-3xl font-bold text-blue-600">
                                            {{ toMoney($selectedTransaction->value_paid, 'R$ ') }}</div>
                                        @php
                                            $txStatusClass = match ($selectedTransaction->status) {
                                                'paid', 'approved' => 'bg-green-100 text-green-700',
                                                'processing' => 'bg-blue-100 text-blue-700',
                                                'pending' => 'bg-orange-100 text-orange-700',
                                                'error', 'refused' => 'bg-red-100 text-red-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp
                                        <span
                                            class="inline-block mt-2 px-3 py-1 text-sm font-semibold rounded {{ $txStatusClass }}">
                                            {{ strtoupper($selectedTransaction->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Coluna Esquerda --}}
                                    <div class="space-y-4">
                                        <h4 class="text-sm font-bold text-gray-800 uppercase border-b pb-2">Dados da
                                            Transação</h4>

                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Data/Hora
                                            </div>
                                            <div class="text-sm text-gray-900">
                                                {{ $selectedTransaction->created_at->format('d/m/Y H:i:s') }}</div>
                                        </div>

                                        @if (
                                            ($selectedTransaction->pay_type === 'pix' || $selectedTransaction->pay_type === 'slip_pix') &&
                                                !empty($selectedTransaction->pay_pix_expires_at) &&
                                                in_array($selectedTransaction->status, ['pending', 'processing', 'pix_expired']))
                                            @php
                                                $pixExpiresAt = \Carbon\Carbon::parse(
                                                    $selectedTransaction->pay_pix_expires_at,
                                                );
                                                $pixIsExpired =
                                                    $pixExpiresAt->isPast() ||
                                                    $selectedTransaction->status === 'pix_expired';
                                            @endphp
                                            <div
                                                class="p-3 rounded-lg {{ $pixIsExpired ? 'bg-red-50 border border-red-200' : 'bg-orange-50 border border-orange-200' }}">
                                                <div
                                                    class="text-[10px] font-semibold {{ $pixIsExpired ? 'text-red-700' : 'text-orange-700' }} uppercase">
                                                    {{ $pixIsExpired ? '⚠️ PIX Expirado' : '⏰ PIX Expira em' }}
                                                </div>
                                                <div
                                                    class="text-sm font-semibold {{ $pixIsExpired ? 'text-red-900' : 'text-orange-900' }}">
                                                    {{ $pixExpiresAt->format('d/m/Y H:i:s') }}
                                                </div>
                                                @if (!$pixIsExpired)
                                                    <div
                                                        class="text-xs {{ $pixIsExpired ? 'text-red-600' : 'text-orange-600' }} mt-1">
                                                        {{ $pixExpiresAt->diffForHumans() }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Gateway
                                            </div>
                                            <div class="text-sm text-gray-900">
                                                {{ $selectedTransaction->gateway->pay_gateway_label ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Método</div>
                                            <div class="text-sm text-gray-900">
                                                {{ strtoupper($selectedTransaction->pay_type ?? '-') }}</div>
                                        </div>

                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">NSU</div>
                                            <div class="text-sm font-mono text-gray-900">
                                                {{ $selectedTransaction->pay_nsu ?? '-' }}</div>
                                        </div>
                                    </div>

                                    {{-- Coluna Direita --}}
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between border-b pb-2">
                                            <h4 class="text-sm font-bold text-gray-800 uppercase">Dados do Comprador
                                            </h4>
                                            @if ($selectedTransaction->order)
                                                <button
                                                    wire:click="viewOrderFromTransaction('{{ $selectedTransaction->order->id }}')"
                                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Ver Adesão
                                                </button>
                                            @endif
                                        </div>

                                        @if ($selectedTransaction->order)
                                            <div>
                                                <div class="text-[10px] font-semibold text-gray-500 uppercase">Adesão
                                                </div>
                                                <div class="text-sm font-mono text-blue-600">
                                                    {{ $selectedTransaction->order->order_control ?? '-' }}</div>
                                            </div>

                                            <div>
                                                <div class="text-[10px] font-semibold text-gray-500 uppercase">Nome
                                                </div>
                                                <div class="text-sm text-gray-900">
                                                    {{ $selectedTransaction->order->buyer_name ?? '-' }}</div>
                                            </div>

                                            <div>
                                                <div class="text-[10px] font-semibold text-gray-500 uppercase">E-mail
                                                </div>
                                                <div class="text-sm text-gray-900 lowercase">
                                                    {{ $selectedTransaction->order->buyer_email ?? '-' }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Seção REQUEST/RESPONSE --}}
                                @if ($selectedTransaction->pay_json_request || $selectedTransaction->pay_json_response)
                                    <div class="mt-6 pt-6 border-t border-gray-200">
                                        <h4 class="text-sm font-bold text-gray-800 uppercase mb-4">Dados Técnicos</h4>
                                        <div class="space-y-3">
                                            {{-- REQUEST Accordion --}}
                                            @if ($selectedTransaction->pay_json_request)
                                                <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden"
                                                    x-data="{ open: false }">
                                                    {{-- Cabeçalho do Accordion --}}
                                                    <button @click="open = !open"
                                                        class="w-full px-4 py-3 flex items-center justify-between hover:bg-gray-100 transition">
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-blue-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                            </svg>
                                                            <span
                                                                class="text-sm font-semibold text-gray-800 uppercase">Request
                                                                (Enviado)</span>
                                                        </div>
                                                        <svg class="w-5 h-5 text-gray-500 transition-transform"
                                                            :class="{ 'rotate-180': open }" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </button>

                                                    {{-- Conteúdo do Accordion --}}
                                                    <div x-show="open" x-collapse
                                                        class="border-t border-gray-200 bg-white">
                                                        <div class="p-4">
                                                            <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200 max-h-96">{{ json_encode($selectedTransaction->pay_json_request, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- RESPONSE Accordion --}}
                                            @if ($selectedTransaction->pay_json_response)
                                                <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden"
                                                    x-data="{ open: false }">
                                                    {{-- Cabeçalho do Accordion --}}
                                                    <button @click="open = !open"
                                                        class="w-full px-4 py-3 flex items-center justify-between hover:bg-gray-100 transition">
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-green-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                            </svg>
                                                            <span
                                                                class="text-sm font-semibold text-gray-800 uppercase">Response
                                                                (Retornado)</span>
                                                        </div>
                                                        <svg class="w-5 h-5 text-gray-500 transition-transform"
                                                            :class="{ 'rotate-180': open }" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </button>

                                                    {{-- Conteúdo do Accordion --}}
                                                    <div x-show="open" x-collapse
                                                        class="border-t border-gray-200 bg-white">
                                                        <div class="p-4">
                                                            <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200 max-h-96">{{ json_encode($selectedTransaction->pay_json_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Seção WEBHOOKS (Admin Only) --}}
                                @if (isAdmin())
                                    <div class="mt-6 pt-6 border-t border-gray-200">
                                        <h4 class="text-sm font-bold text-gray-800 uppercase mb-4">Webhooks Recebidos
                                        </h4>

                                        @php
                                            // Carrega webhooks através do relacionamento
                                            $callbacks = $selectedTransaction->webhooks ?? collect();
                                        @endphp

                                        @if ($callbacks->count() > 0)
                                            <div class="space-y-3">
                                                @foreach ($callbacks as $index => $callback)
                                                    <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden"
                                                        x-data="{ open: false }">
                                                        {{-- Cabeçalho do Accordion --}}
                                                        <button @click="open = !open"
                                                            class="w-full px-4 py-3 flex items-center justify-between hover:bg-gray-100 transition">
                                                            <div class="flex items-center gap-3 flex-1">
                                                                <div class="flex-1 text-left">
                                                                    <div
                                                                        class="flex gap-2 items-centertext-sm font-semibold text-gray-900">
                                                                        <span>Webhook
                                                                            #{{ $callbacks->count() - $index }}</span>
                                                                        <span
                                                                            class="px-2 py-1 rounded text-xs font-semibold
                                                            @if (in_array($callback->status, ['paid', 'pago', 'autorizado'])) bg-green-100 text-green-800
                                                            @elseif(in_array($callback->status, ['canceled', 'cancelado', 'estornado'])) bg-red-100 text-red-800
                                                            @else bg-yellow-100 text-yellow-800 @endif">
                                                                            {{ strtoupper($callback->status) }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="text-xs text-gray-600">
                                                                        <span>
                                                                            {{ dataDataHora($callback->created_at) }}
                                                                        </span>
                                                                        <span>-</span>
                                                                        <span>
                                                                            {{ $callback->id }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                @if ($callback->event_type)
                                                                    <div class="hidden md:block">
                                                                        <span
                                                                            class="text-xs text-gray-600 bg-white px-2 py-1 rounded border border-gray-300">
                                                                            {{ $callback->event_type }}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <svg class="w-5 h-5 text-gray-500 transition-transform"
                                                                :class="{ 'rotate-180': open }" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                        </button>

                                                        {{-- Conteúdo do Accordion --}}
                                                        <div x-show="open" x-collapse
                                                            class="border-t border-gray-200 bg-white">
                                                            <div class="p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                                                                {{-- Informações Principais --}}
                                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                                    @if ($callback->gateway_slug)
                                                                        <div>
                                                                            <div
                                                                                class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                Gateway</div>
                                                                            <div class="text-sm text-gray-900">
                                                                                {{ $callback->gateway_slug }}</div>
                                                                        </div>
                                                                    @endif

                                                                    @if ($callback->webhook_id)
                                                                        <div>
                                                                            <div
                                                                                class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                Webhook ID</div>
                                                                            <div
                                                                                class="text-xs font-mono text-gray-900">
                                                                                {{ $callback->webhook_id }}</div>
                                                                        </div>
                                                                    @endif

                                                                    @if ($callback->received_at)
                                                                        <div>
                                                                            <div
                                                                                class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                Recebido em</div>
                                                                            <div class="text-sm text-gray-900">
                                                                                {{ dataDataHora($callback->received_at) }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                {{-- Dados da Transação --}}
                                                                @if ($callback->transaction_id || $callback->external_id || $callback->amount)
                                                                    <div class="pt-3 border-t border-gray-100">
                                                                        <div
                                                                            class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                                            @if ($callback->transaction_id)
                                                                                <div>
                                                                                    <div
                                                                                        class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                        Transaction ID</div>
                                                                                    <div
                                                                                        class="text-xs font-mono text-gray-900">
                                                                                        {{ $callback->transaction_id }}
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if ($callback->external_id)
                                                                                <div>
                                                                                    <div
                                                                                        class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                        External ID</div>
                                                                                    <div
                                                                                        class="text-xs font-mono text-gray-900">
                                                                                        {{ $callback->external_id }}
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if ($callback->amount)
                                                                                <div>
                                                                                    <div
                                                                                        class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                        Valor</div>
                                                                                    <div
                                                                                        class="text-sm font-semibold text-gray-900">
                                                                                        {{ convertMoney($callback->amount, 'R$ ') }}
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                {{-- Processamento --}}
                                                                @if ($callback->processed_at || $callback->error_message)
                                                                    <div class="pt-3 border-t border-gray-100">
                                                                        <div
                                                                            class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                            @if ($callback->processed_at)
                                                                                <div>
                                                                                    <div
                                                                                        class="text-[10px] font-semibold text-gray-500 uppercase mb-1">
                                                                                        Processado em</div>
                                                                                    <div class="text-sm text-gray-900">
                                                                                        {{ dataDataHora($callback->processed_at) }}
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if ($callback->error_message)
                                                                                <div class="md:col-span-2">
                                                                                    <div
                                                                                        class="text-[10px] font-semibold text-red-600 uppercase mb-1">
                                                                                        Erro</div>
                                                                                    <div
                                                                                        class="text-sm text-red-700 bg-red-50 p-2 rounded border border-red-200">
                                                                                        {{ $callback->error_message }}
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                <div class="flex-none md:flex justify-end">
                                                                    <button
                                                                        wire:click="reprocessWebhook('{{ $callback->id }}')"
                                                                        wire:loading.attr="disabled"
                                                                        wire:target="reprocessWebhook"
                                                                        class="mt-4 w-auto flex justify-center items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white text-xs font-semibold rounded transition-colors"
                                                                        title="Reprocessar webhook ID: {{ $callback->id }}">
                                                                        <svg wire:loading.remove
                                                                            wire:target="reprocessWebhook"
                                                                            class="w-4 h-4" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                                            </path>
                                                                        </svg>
                                                                        <svg wire:loading
                                                                            wire:target="reprocessWebhook"
                                                                            class="animate-spin w-4 h-4"
                                                                            fill="none" viewBox="0 0 24 24">
                                                                            <circle class="opacity-25" cx="12"
                                                                                cy="12" r="10"
                                                                                stroke="currentColor"
                                                                                stroke-width="4"></circle>
                                                                            <path class="opacity-75"
                                                                                fill="currentColor"
                                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                            </path>
                                                                        </svg>
                                                                        <span>Reprocessar</span>
                                                                    </button>
                                                                </div>

                                                                {{-- Payload do Webhook --}}
                                                                @if ($callback->payload)
                                                                    <div
                                                                        class="col-span-full pt-3 border-t border-gray-100">
                                                                        <div
                                                                            class="text-xs font-semibold text-gray-700 uppercase mb-2 flex items-center gap-2">
                                                                            <svg class="w-4 h-4 text-blue-600"
                                                                                fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                                </path>
                                                                            </svg>
                                                                            Payload Recebido
                                                                        </div>
                                                                        <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200 max-h-64">{{ json_encode($callback->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                                    </div>
                                                                @endif

                                                                {{-- Response --}}
                                                                @if ($callback->response)
                                                                    <div
                                                                        class="col-span-full pt-3 border-t border-gray-100">
                                                                        <div
                                                                            class="text-xs font-semibold text-gray-700 uppercase mb-2 flex items-center gap-2">
                                                                            <svg class="w-4 h-4 text-green-600"
                                                                                fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                                </path>
                                                                            </svg>
                                                                            Response Enviada
                                                                        </div>
                                                                        <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200 max-h-64">{{ json_encode($callback->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                    </path>
                                                </svg>
                                                <p class="text-sm text-gray-500 font-medium">Não possui webhooks
                                                    registrados</p>
                                                <p class="text-xs text-gray-400 mt-1">Nenhum callback foi recebido do
                                                    gateway para esta transação</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        @endif
        {{-- Fim da Tab Transações --}}

        {{-- Conteúdo da Tab Adesões --}}
        @if ($activeTab === 'adesoes')
            <div wire:key="tab-adesoes">

                @php
                    $filteredOrders = $this->getFilteredOrders();
                @endphp

                @if (!$selectedOrderId)
                    <div class="bg-white border rounded-sm shadow px-4 md:px-6 py-4 mb-4">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-lg font-bold text-gray-800">Adesões ({{ $filteredOrders->count() }})
                                </h3>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" wire:click="openManualOrderModal"
                                    class="px-4 py-2 border border-green-600 text-green-700 hover:bg-green-50 text-xs font-semibold rounded transition-colors inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Cadastrar
                                </button>
                                <button type="button" wire:click="refreshAdesoes" wire:target="refreshAdesoes"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                    <span class="flex items-center gap-2" wire:loading.remove
                                        wire:target="refreshAdesoes" wire:loading.class="hidden">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                        <span>Atualizar</span>
                                    </span>

                                    <span class="flex justify-center items-center gap-2 hidden" wire:loading
                                        wire:target="refreshAdesoes" wire:loading.class.remove="hidden">
                                        <span>Atualizando...</span>
                                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </span>
                                </button>
                                <a href="{{ route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id]) }}?export=adesoes"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Exportar CSV
                                </a>
                            </div>
                        </div>

                        {{-- Filtros --}}
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status</label>
                                <select wire:model="filterStatus"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todos</option>
                                    <option value="pending">Pendente</option>
                                    <option value="paid">Pago</option>
                                    <option value="cancelled">Cancelado</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data
                                    Início</label>
                                <input type="date" wire:model="filterDateFrom"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data
                                    Fim</label>
                                <input type="date" wire:model="filterDateTo"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Buscar</label>
                                <input type="text" wire:model.debounce.300ms="filterSearch"
                                    placeholder="Nome, email, localizador..."
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                @endif

                @if (!$selectedOrderId)
                    {{-- Tabela de Adesões --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        Data/Hora / Localizador</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        Nome / Documento</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        E-mail / Telefone</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        Valor Total</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        Valor Pago</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        Forma de Pagamento</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($filteredOrders as $order)
                                    <tr wire:key="order-{{ $order->id }}"
                                        wire:click="goToOrder('{{ $order->id }}')"
                                        class="hover:bg-blue-50 cursor-pointer transition">
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <div class="text-xs text-gray-600">
                                                {{ $order->created_at->format('d/m/Y H:i') }}</div>
                                            <div class="font-mono font-bold text-sm text-blue-600">
                                                {{ $order->order_control }}
                                            </div>
                                        </td>
                                        <td
                                            class="text-center px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 capitalize">
                                            <div>{{ $order->buyer_name }}</div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $order->buyer_doc_num ?? '-' }}</div>
                                        </td>
                                        <td class="text-center px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                            <div>{{ $order->buyer_email ?? '-' }}</div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                @php
                                                    $orderPhone = trim(
                                                        implode(
                                                            ' ',
                                                            array_filter([
                                                                !empty($order->buyer_contact_country)
                                                                    ? '+' . $order->buyer_contact_country
                                                                    : '',
                                                                $order->buyer_contact_ddd ?? '',
                                                                $order->buyer_contact_num ?? '',
                                                            ]),
                                                        ),
                                                    );
                                                @endphp
                                                {{ $orderPhone !== '' ? $orderPhone : '-' }}
                                            </div>
                                        </td>
                                        <td
                                            class="text-center px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ toMoney($order->amount_total, 'R$ ') }}
                                        </td>
                                        <td
                                            class="text-center px-4 py-3 whitespace-nowrap text-sm font-semibold {{ $order->amount_paid > 0 ? 'text-green-600' : 'text-gray-600' }}">
                                            {{ toMoney($order->amount_paid, 'R$ ') }}
                                        </td>
                                        <td class="text-center px-4 py-3 whitespace-nowrap">
                                            @php
                                                $currentPayment = $order->campaignPayments
                                                    ->sortByDesc('created_at')
                                                    ->first();
                                                $paymentType = $currentPayment
                                                    ? strtoupper($currentPayment->pay_type ?? '-')
                                                    : '-';
                                                $gatewayLabel = $currentPayment
                                                    ? strtoupper($currentPayment->gateway_slug ?? '-')
                                                    : '-';
                                                $isManualGateway =
                                                    $gatewayLabel === 'MANUAL' ||
                                                    \Illuminate\Support\Str::endsWith(
                                                        (string) $order->order_control,
                                                        '-M',
                                                    );
                                                $orderStatusNormalized = strtolower((string) ($order->status ?? ''));
                                                $paidOrderStatuses = function_exists('listOrderStatusPaid')
                                                    ? array_map(
                                                        fn($status) => strtolower((string) $status),
                                                        listOrderStatusPaid(),
                                                    )
                                                    : ['paid'];
                                                $isOrderPaid = in_array(
                                                    $orderStatusNormalized,
                                                    $paidOrderStatuses,
                                                    true,
                                                );

                                                // Verifica se há carnê (múltiplos PaymentSlips)
                                                $hasCarne = false;
                                                $carneInfo = null;
                                                if ($order->paymentSlips && $order->paymentSlips->count() > 1) {
                                                    // Tem mais de um slip = é carnê
                                                    $hasCarne = true;
                                                    $totalCarne = $order->paymentSlips->count();
                                                    $paidCarne = $order->paymentSlips->whereNotNull('paid_at')->count();
                                                    $carneInfo = [
                                                        'total' => $totalCarne,
                                                        'paid' => $paidCarne,
                                                        'pending' => $totalCarne - $paidCarne,
                                                    ];
                                                }

                                                if ($currentPayment) {
                                                    $paymentStatus = strtolower($currentPayment->status ?? '');
                                                    $statusClass = match ($paymentStatus) {
                                                        'paid',
                                                        'approved',
                                                        'captured',
                                                        'autorizado',
                                                        'success',
                                                        'sucesso'
                                                            => 'bg-green-100 text-green-700',
                                                        'processing', 'processando' => 'bg-blue-100 text-blue-700',
                                                        'pending', 'pendente' => 'bg-orange-100 text-orange-700',
                                                        'error',
                                                        'erro',
                                                        'refused',
                                                        'recusado',
                                                        'cancelled',
                                                        'cancelado'
                                                            => 'bg-red-100 text-red-700',
                                                        default => 'bg-gray-100 text-gray-700',
                                                    };

                                                    $statusLabel = match ($paymentStatus) {
                                                        'paid' => 'PAGO',
                                                        'approved' => 'APROVADO',
                                                        'captured' => 'CAPTURADO',
                                                        'autorizado' => 'AUTORIZADO',
                                                        'processing', 'processando' => 'PROCESSANDO',
                                                        'pending', 'pendente' => 'PENDENTE',
                                                        'error', 'erro' => 'ERRO',
                                                        'refused', 'recusado' => 'RECUSADO',
                                                        'cancelled', 'cancelado' => 'CANCELADO',
                                                        default => strtoupper($paymentStatus ?: 'N/D'),
                                                    };
                                                }
                                            @endphp

                                            @if ($hasCarne && $carneInfo)
                                                {{-- Exibe informações do carnê --}}
                                                <div class="text-xs font-semibold text-gray-900">
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                            </path>
                                                        </svg>
                                                        CARNÊ
                                                    </span>
                                                </div>
                                                <div class="text-[10px] text-gray-600 mt-0.5">
                                                    {{ $paymentType }} •
                                                    {{ $carneInfo['paid'] }}/{{ $carneInfo['total'] }} pagas
                                                </div>
                                                @if ($isManualGateway)
                                                    <div class="mt-1">
                                                        <span
                                                            class="px-2 py-0.5 text-[10px] font-semibold rounded bg-slate-100 text-slate-700">
                                                            GATEWAY: MANUAL
                                                        </span>
                                                    </div>
                                                @endif
                                                <div class="mt-1">
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                        <div class="bg-green-600 h-1.5 rounded-full"
                                                            style="width: {{ ($carneInfo['paid'] / $carneInfo['total']) * 100 }}%">
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($carneInfo['pending'] > 0)
                                                    <div class="mt-1">
                                                        <span
                                                            class="px-2 py-0.5 text-[10px] font-semibold rounded bg-orange-100 text-orange-700">
                                                            {{ $carneInfo['pending'] }}
                                                            PENDENTE{{ $carneInfo['pending'] > 1 ? 'S' : '' }}
                                                        </span>
                                                    </div>
                                                @elseif(!$isOrderPaid)
                                                    <div class="mt-1">
                                                        <span
                                                            class="px-2 py-0.5 text-[10px] font-semibold rounded bg-green-100 text-green-700">
                                                            COMPLETO
                                                        </span>
                                                    </div>
                                                @endif
                                            @else
                                                {{-- Exibe informações de pagamento único --}}
                                                <div class="text-xs font-semibold text-gray-900">{{ $paymentType }}
                                                </div>
                                                @if ($isManualGateway)
                                                    <div>
                                                        <span
                                                            class="px-2 py-0.5 text-[10px] font-semibold rounded bg-slate-100 text-slate-700">
                                                            GATEWAY: MANUAL
                                                        </span>
                                                    </div>
                                                @endif

                                                @if ($currentPayment && isset($statusClass) && isset($statusLabel) && !$isOrderPaid)
                                                    <div>
                                                        <span
                                                            class="px-2 py-0.5 text-[10px] font-semibold rounded {{ $statusClass }}">
                                                            {{ $statusLabel }}
                                                        </span>
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center px-4 py-3 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded {{ $order->status === 'paid' ? 'bg-green-100 text-green-700' : ($order->status === 'pending' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700') }}">
                                                {{ $order->status === 'paid' ? 'PAGO' : ($order->status === 'pending' ? 'PENDENTE' : strtoupper($order->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                            Nenhuma adesão encontrada com os filtros aplicados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    {{-- Detalhes da Adesão Selecionada --}}
                    @if ($selectedOrder)
                        <div class="space-y-6">
                            {{-- Botão Voltar --}}
                            <button wire:click="goToOrderList"
                                class="flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold mb-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                                </svg>
                                Voltar para Lista
                            </button>

                            {{-- Flash Messages --}}
                            @if (session()->has('success'))
                                <div
                                    class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">{{ session('success') }}</span>
                                </div>
                            @endif

                            @if (session()->has('error'))
                                <div
                                    class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-medium">{{ session('error') }}</span>
                                </div>
                            @endif

                            {{-- Dados da Adesão --}}
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="p-6 bg-gray-50 border-b border-gray-200">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div>
                                            <div class="text-xs font-semibold text-gray-600 uppercase mb-2">
                                                Localizador</div>
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="text-2xl font-black tracking-wide text-blue-700 font-mono">
                                                    {{ $selectedOrder->order_control }}</div>
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold rounded-full
                                                {{ $selectedOrder->status === 'paid' ? 'bg-green-100 text-green-700' : ($selectedOrder->status === 'pending' ? 'bg-orange-100 text-orange-700' : 'bg-gray-200 text-gray-700') }}">
                                                    {{ strtoupper($selectedOrder->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button wire:click="openOrderEditModal"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-800 text-white text-sm font-semibold shadow transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.586-9.414a2 2 0 112.828 2.828L11 17l-4 1 1-4 9.414-9.414z">
                                                    </path>
                                                </svg>
                                                Editar
                                            </button>
                                            <button wire:click="enviarEmailPorStatus" wire:loading.attr="disabled"
                                                wire:target="enviarEmailPorStatus"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white text-sm font-semibold shadow transition">
                                                <svg wire:loading.remove wire:target="enviarEmailPorStatus"
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <svg wire:loading wire:target="enviarEmailPorStatus"
                                                    class="animate-spin w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12"
                                                        r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                <span>Enviar Email</span>
                                            </button>
                                            <a href="{{ campanhaUrl($campaign->customer_organization_slug, $campaign->slug, $selectedOrder->id) }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-semibold shadow transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                    </path>
                                                </svg>
                                                Acessar Adesão
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        {{-- Dados do Doador --}}
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-700 uppercase mb-4 border-b pb-2">
                                                Dados do Doador</h4>
                                            <div class="space-y-3">
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">Nome</div>
                                                    <div class="text-sm font-semibold text-gray-900">
                                                        {{ $selectedOrder->buyer_name }}</div>
                                                </div>
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">E-mail</div>
                                                    <div class="text-sm font-medium text-gray-900 lowercase">
                                                        {{ $selectedOrder->buyer_email }}</div>
                                                </div>
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">Telefone</div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        @php
                                                            $selectedOrderPhone = trim(
                                                                implode(
                                                                    ' ',
                                                                    array_filter([
                                                                        !empty($selectedOrder->buyer_contact_country)
                                                                            ? '+' .
                                                                                $selectedOrder->buyer_contact_country
                                                                            : '',
                                                                        $selectedOrder->buyer_contact_ddd ?? '',
                                                                        $selectedOrder->buyer_contact_num ?? '',
                                                                    ]),
                                                                ),
                                                            );
                                                        @endphp
                                                        {{ $selectedOrderPhone !== '' ? $selectedOrderPhone : '-' }}
                                                    </div>
                                                </div>
                                                @if ($selectedOrder->buyer_doc_num)
                                                    <div>
                                                        <div class="text-xs text-gray-500 uppercase mb-1">Documento
                                                        </div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $selectedOrder->buyer_doc_num }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Informações da Adesão --}}
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-700 uppercase mb-4 border-b pb-2">
                                                Informações da Adesão</h4>
                                            @php
                                                $currentPaymentInfo = $selectedOrder->campaignPayments->first();
                                                $gatewayLabel = strtoupper($currentPaymentInfo->gateway_slug ?? '-');
                                                $gatewayManual =
                                                    $gatewayLabel === 'MANUAL' ||
                                                    \Illuminate\Support\Str::endsWith(
                                                        (string) $selectedOrder->order_control,
                                                        '-M',
                                                    );
                                                $orderObservation =
                                                    data_get($selectedOrder->metadata, 'observation') ??
                                                    data_get($selectedOrder->metadata, 'manual_observation');
                                            @endphp
                                            <div class="space-y-3">
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">Data/Hora</div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $selectedOrder->created_at->format('d/m/Y H:i:s') }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">Gateway</div>
                                                    <div
                                                        class="text-sm font-semibold {{ $gatewayManual ? 'text-slate-700' : 'text-gray-900' }}">
                                                        {{ $gatewayManual ? 'MANUAL' : ($gatewayLabel ?: '-') }}
                                                    </div>
                                                    @if ($gatewayManual)
                                                        <div class="text-[11px] text-slate-600">Pagamento lançado sem
                                                            processamento no gateway online.</div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">Valor Total
                                                    </div>
                                                    <div class="text-lg font-bold text-blue-600">
                                                        {{ toMoney($selectedOrder->amount_total, 'R$ ') }}</div>
                                                </div>
                                                <div>
                                                    <div class="text-xs text-gray-500 uppercase mb-1">Valor Pago</div>
                                                    <div
                                                        class="text-lg font-bold {{ ($selectedOrder->amount_paid ?? 0) > 0 ? 'text-green-600' : 'text-gray-600' }}">
                                                        {{ toMoney($selectedOrder->amount_paid ?? 0, 'R$ ') }}
                                                    </div>
                                                </div>
                                                @if ($selectedOrder->paid_at)
                                                    <div>
                                                        <div class="text-xs text-gray-500 uppercase mb-1">Data do
                                                            Pagamento</div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $selectedOrder->paid_at->format('d/m/Y H:i:s') }}
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (!empty($orderObservation))
                                                    <div>
                                                        <div class="text-xs text-gray-500 uppercase mb-1">Observação
                                                        </div>
                                                        <div class="text-sm font-medium text-gray-900 break-words">
                                                            {{ $orderObservation }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($selectedSubscription)
                                @php
                                    $subscriptionStatusMap = [
                                        'active' => ['label' => 'Ativa', 'class' => 'bg-green-100 text-green-700'],
                                        'paused' => ['label' => 'Pausada', 'class' => 'bg-yellow-100 text-yellow-700'],
                                        'canceled' => ['label' => 'Cancelada', 'class' => 'bg-gray-200 text-gray-700'],
                                        'error_disabled' => [
                                            'label' => 'Desativada por erro',
                                            'class' => 'bg-red-100 text-red-700',
                                        ],
                                    ];
                                    $subscriptionStatus = $subscriptionStatusMap[$selectedSubscription->status] ?? [
                                        'label' => strtoupper($selectedSubscription->status ?? '-'),
                                        'class' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                    <div class="p-6 bg-gray-50 border-b border-gray-200">
                                        <div class="flex flex-wrap items-center justify-between gap-3">
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-900">Recorrência</h3>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Cartão:
                                                    {{ $selectedSubscription->card_description ?? 'Não informado' }}
                                                </div>
                                            </div>
                                            @php
                                                $hasRecurringActions = in_array(
                                                    $selectedSubscription->status,
                                                    ['active', 'paused', 'error_disabled'],
                                                    true,
                                                );
                                            @endphp
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold rounded-full {{ $subscriptionStatus['class'] }}">
                                                    {{ $subscriptionStatus['label'] }}
                                                </span>
                                                @if ($hasRecurringActions)
                                                    <div class="relative" x-data="{ open: false }">
                                                        <button type="button"
                                                            class="inline-flex items-center justify-center h-9 w-9 rounded-full border border-gray-300 bg-white text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition"
                                                            @click="open = !open" aria-label="Ações da recorrência">
                                                            <svg class="h-5 w-5" fill="currentColor"
                                                                viewBox="0 0 20 20" aria-hidden="true">
                                                                <path
                                                                    d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z" />
                                                            </svg>
                                                        </button>
                                                        <div class="absolute right-0 mt-2 w-40 rounded-lg border border-gray-200 bg-white shadow-lg z-10"
                                                            x-show="open"
                                                            x-transition:enter="transition ease-out duration-150"
                                                            x-transition:enter-start="opacity-0 translate-y-1"
                                                            x-transition:enter-end="opacity-100 translate-y-0"
                                                            x-transition:leave="transition ease-in duration-100"
                                                            x-transition:leave-start="opacity-100 translate-y-0"
                                                            x-transition:leave-end="opacity-0 translate-y-1"
                                                            @click.outside="open = false">
                                                            <div class="py-1 text-sm text-gray-700">
                                                                @if ($selectedSubscription->status === 'active')
                                                                    <button type="button"
                                                                        wire:click="pauseRecurring('{{ $selectedSubscription->id }}')"
                                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                                        Pausar
                                                                    </button>
                                                                    <button type="button"
                                                                        wire:click="cancelRecurring('{{ $selectedSubscription->id }}')"
                                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                                                                        Cancelar
                                                                    </button>
                                                                @elseif($selectedSubscription->status === 'paused')
                                                                    <button type="button"
                                                                        wire:click="resumeRecurring('{{ $selectedSubscription->id }}')"
                                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                                        Retomar
                                                                    </button>
                                                                    <button type="button"
                                                                        wire:click="cancelRecurring('{{ $selectedSubscription->id }}')"
                                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                                                                        Cancelar
                                                                    </button>
                                                                @elseif($selectedSubscription->status === 'error_disabled')
                                                                    <button type="button"
                                                                        wire:click="cancelRecurring('{{ $selectedSubscription->id }}')"
                                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                                                                        Cancelar
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-6 space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <div class="text-xs text-gray-500 uppercase mb-1">Próxima cobrança
                                                </div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $selectedSubscription->next_charge_at ? \Carbon\Carbon::parse($selectedSubscription->next_charge_at)->format('d/m/Y H:i') : '-' }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500 uppercase mb-1">Última cobrança
                                                </div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $selectedSubscription->last_charge_at ? \Carbon\Carbon::parse($selectedSubscription->last_charge_at)->format('d/m/Y H:i') : '-' }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500 uppercase mb-1">Ciclo atual</div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $selectedSubscription->current_cycle ?? 0 }}
                                                </div>
                                            </div>
                                        </div>

                                        @if ($selectedSubscription->error_message)
                                            <div
                                                class="bg-red-50 border border-red-200 text-red-700 text-xs rounded-lg px-4 py-2">
                                                {{ $selectedSubscription->error_message }}
                                            </div>
                                        @endif

                                        <div class="space-y-3">
                                            @if ($selectedSubscription->cycles && $selectedSubscription->cycles->count() > 0)
                                                @foreach ($selectedSubscription->cycles as $cycle)
                                                    @php
                                                        $cycleStatusMap = [
                                                            'paid' => [
                                                                'label' => 'Pago',
                                                                'class' => 'bg-green-100 text-green-700',
                                                            ],
                                                            'pending' => [
                                                                'label' => 'Pendente',
                                                                'class' => 'bg-yellow-100 text-yellow-700',
                                                            ],
                                                            'failed' => [
                                                                'label' => 'Falhou',
                                                                'class' => 'bg-red-100 text-red-700',
                                                            ],
                                                        ];
                                                        $cycleStatus = $cycleStatusMap[$cycle->status] ?? [
                                                            'label' => strtoupper($cycle->status ?? '-'),
                                                            'class' => 'bg-gray-100 text-gray-700',
                                                        ];
                                                        $cyclePayments = $cycle->order?->campaignPayments ?? collect();
                                                        $cycleAttempts = $cycle->attempts ?? collect();
                                                    @endphp
                                                    <div class="border border-gray-200 rounded-lg">
                                                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                                            <div
                                                                class="flex flex-wrap items-center justify-between gap-2">
                                                                <div class="text-sm font-semibold text-gray-900">
                                                                    RECORRÊNCIA
                                                                    {{ str_pad($cycle->cycle_number ?? 0, 2, '0', STR_PAD_LEFT) }}
                                                                    -
                                                                    {{ \Carbon\Carbon::parse($cycle->billing_date)->format('d/m/Y') }}
                                                                </div>
                                                                <span
                                                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $cycleStatus['class'] }}">
                                                                    {{ $cycleStatus['label'] }}
                                                                </span>
                                                            </div>
                                                            <div class="text-xs text-gray-500 mt-1">
                                                                @if ($cycle->paid_at)
                                                                    Pago em
                                                                    {{ \Carbon\Carbon::parse($cycle->paid_at)->format('d/m/Y H:i') }}
                                                                @endif
                                                                @if ($cycle->next_attempt_at)
                                                                    • Próxima tentativa:
                                                                    {{ \Carbon\Carbon::parse($cycle->next_attempt_at)->format('d/m/Y H:i') }}
                                                                @endif
                                                            </div>
                                                            @if ($cycle->error_message)
                                                                <div class="text-xs text-red-600 mt-1">
                                                                    {{ $cycle->error_message }}</div>
                                                            @endif
                                                        </div>
                                                        <div class="p-4 space-y-4">
                                                            <div>
                                                                <div
                                                                    class="text-[10px] font-semibold text-gray-500 uppercase mb-2">
                                                                    Transações</div>
                                                                @if ($cyclePayments->count() > 0)
                                                                    <div class="space-y-2">
                                                                        @foreach ($cyclePayments->sortByDesc('created_at') as $payment)
                                                                            @php
                                                                                $paymentStatusClass = match (
                                                                                    $payment->status
                                                                                ) {
                                                                                    'paid',
                                                                                    'approved',
                                                                                    'autorizado',
                                                                                    'captured'
                                                                                        => 'bg-green-100 text-green-700',
                                                                                    'pending',
                                                                                    'processing'
                                                                                        => 'bg-yellow-100 text-yellow-700',
                                                                                    'error'
                                                                                        => 'bg-red-100 text-red-700',
                                                                                    default
                                                                                        => 'bg-gray-100 text-gray-700',
                                                                                };
                                                                            @endphp
                                                                            <div
                                                                                class="flex flex-wrap items-center justify-between gap-2 text-xs border border-gray-200 rounded px-3 py-2">
                                                                                <div class="text-gray-700">
                                                                                    {{ strtoupper($payment->pay_type ?? '-') }}
                                                                                    •
                                                                                    {{ $payment->created_at->format('d/m/Y H:i') }}
                                                                                    @if ($payment->pay_transaction_id)
                                                                                        • ID:
                                                                                        {{ $payment->pay_transaction_id }}
                                                                                    @endif
                                                                                </div>
                                                                                <span
                                                                                    class="px-2 py-0.5 rounded {{ $paymentStatusClass }}">
                                                                                    {{ strtoupper($payment->status ?? '-') }}
                                                                                </span>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <div class="text-xs text-gray-400">Nenhuma
                                                                        transação registrada.</div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <div
                                                                    class="text-[10px] font-semibold text-gray-500 uppercase mb-2">
                                                                    Tentativas</div>
                                                                @if ($cycleAttempts->count() > 0)
                                                                    <div class="space-y-1">
                                                                        @foreach ($cycleAttempts as $attempt)
                                                                            @php
                                                                                $attemptStatusClass =
                                                                                    ($attempt->status ?? '') ===
                                                                                    'success'
                                                                                        ? 'bg-green-100 text-green-700'
                                                                                        : 'bg-red-100 text-red-700';
                                                                            @endphp
                                                                            <div
                                                                                class="flex flex-wrap items-center justify-between gap-2 text-xs">
                                                                                <div class="text-gray-600">
                                                                                    #{{ $attempt->attempt_number ?? '-' }}
                                                                                    •
                                                                                    {{ $attempt->attempted_at ? $attempt->attempted_at->format('d/m/Y H:i') : '-' }}
                                                                                    @if ($attempt->scheduled_at)
                                                                                        • Previsto:
                                                                                        {{ \Carbon\Carbon::parse($attempt->scheduled_at)->format('d/m/Y') }}
                                                                                    @endif
                                                                                </div>
                                                                                <span
                                                                                    class="px-2 py-0.5 rounded {{ $attemptStatusClass }}">
                                                                                    {{ strtoupper($attempt->status ?? '-') }}
                                                                                </span>
                                                                            </div>
                                                                            @if ($attempt->error_message)
                                                                                <div
                                                                                    class="text-[10px] text-red-600 ml-2">
                                                                                    {{ $attempt->error_message }}
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <div class="text-xs text-gray-400">Nenhuma
                                                                        tentativa registrada.</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-sm text-gray-500">Nenhum ciclo de recorrência
                                                    registrado.</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Card de Pagamentos e Transações --}}
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="p-6 bg-gray-50 border-b border-gray-200">
                                    <div class="flex justify-between items-center gap-4">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">Pagamentos</h3>
                                        </div>
                                        <div>
                                            <button type="button"
                                                wire:click="selectOrder('{{ $selectedOrder->id }}')"
                                                wire:target="selectOrder('{{ $selectedOrder->id }}')"
                                                wire:loading.attr="disabled"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow transition disabled:opacity-70">
                                                <span class="flex items-center gap-2" wire:loading.remove
                                                    wire:target="selectOrder('{{ $selectedOrder->id }}')"
                                                    wire:loading.class="hidden">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                        </path>
                                                    </svg>
                                                    <span>Atualizar</span>
                                                </span>
                                                <div class="flex items-center gap-2 hidden" wire:loading
                                                    wire:target="selectOrder('{{ $selectedOrder->id }}')"
                                                    wire:loading.class.remove="hidden">
                                                    <div>Atualizando...</div>
                                                    <svg class="animate-spin h-4 w-4"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6">

                                    {{-- Card de Pagamentos --}}
                                    @if ($selectedOrder->paymentSlips && $selectedOrder->paymentSlips->count() > 0)
                                        <div class="mt-1">
                                            <div class="space-y-3">
                                                @foreach ($selectedOrder->paymentSlips as $slip)
                                                    <div class="border border-gray-200 rounded">
                                                        {{-- Slip Header --}}
                                                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <div class="text-sm font-semibold text-gray-900">
                                                                        {{ $slip->description ?? 'Doação' }}
                                                                    </div>
                                                                    @php
                                                                        $firstPayment = $slip->payments->first();
                                                                    @endphp
                                                                    @if ($firstPayment && $firstPayment->pay_installments_number && $firstPayment->pay_installments_number > 1)
                                                                        <div class="text-xs text-gray-600 mt-0.5">
                                                                            {{ $firstPayment->pay_installments_number }}x
                                                                            de
                                                                            {{ toMoney($firstPayment->pay_installment_value, 'R$ ') }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="text-right">
                                                                    <div class="text-base font-bold text-gray-900">
                                                                        {{ toMoney($slip->total_amount, 'R$ ') }}
                                                                    </div>
                                                                    <div
                                                                        class="text-xs {{ in_array($slip->status, ['paid', 'approved']) ? 'text-green-600' : 'text-gray-500' }}">
                                                                        {{ in_array($slip->status, ['paid', 'approved']) ? 'Pago' : 'Pendente' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Payments --}}
                                                        @php
                                                            $slipPayments = $slip->payments ?? collect();
                                                        @endphp
                                                        @if ($slipPayments->count() > 0)
                                                            <div class="divide-y divide-gray-100">
                                                                @foreach ($slipPayments->sortByDesc('created_at') as $payment)
                                                                    @php
                                                                        if (
                                                                            in_array($payment->status, [
                                                                                'paid',
                                                                                'approved',
                                                                            ])
                                                                        ) {
                                                                            $classBorder = 'border-green-700';
                                                                            $classColor = 'bg-green-100 text-green-700';
                                                                            $classText = 'text-green-700 uppercase';
                                                                        } elseif (
                                                                            in_array($payment->status, ['pending'])
                                                                        ) {
                                                                            $classBorder = 'border-yellow-700';
                                                                            $classColor =
                                                                                'bg-yellow-100 text-yellow-700';
                                                                            $classText = 'text-yellow-700 uppercase';
                                                                        } elseif (
                                                                            in_array($payment->status, ['error'])
                                                                        ) {
                                                                            $classBorder = 'border-red-700';
                                                                            $classColor = 'bg-red-100 text-red-700';
                                                                            $classText = 'text-red-700 uppercase';
                                                                        } else {
                                                                            $classBorder = 'border-gray-600';
                                                                            $classColor = 'bg-gray-100 text-gray-600';
                                                                            $classText = 'text-gray-600 uppercase';
                                                                        }

                                                                        //
                                                                        $attempts = $payment->attempts ?? collect();
                                                                    @endphp
                                                                    <div class="px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer"
                                                                        wire:click="showPaymentDetails('{{ $payment->id }}')">
                                                                        <div
                                                                            class="border-l-8 {{ $classBorder }} flex items-start justify-between px-3">
                                                                            <div class="flex-1">
                                                                                <div class="flex items-center gap-2">
                                                                                    <span
                                                                                        class="text-sm font-medium text-gray-900">
                                                                                        {{ strtoupper($payment->pay_type ?? '-') }}
                                                                                    </span>
                                                                                    <span
                                                                                        class="px-2 py-0.5 text-xs font-medium rounded {{ $classColor }}">
                                                                                        {{ strtoupper($payment->status) }}
                                                                                    </span>
                                                                                </div>
                                                                                <div
                                                                                    class="text-xs text-gray-500 mt-1">
                                                                                    {{ $payment->created_at->format('d/m/Y H:i') }}
                                                                                    @if ($payment->pay_transaction_id)
                                                                                        • ID:
                                                                                        {{ $payment->pay_transaction_id }}
                                                                                    @endif
                                                                                    @if ($attempts->count() > 0)
                                                                                        @php
                                                                                            $lastAttempt = $attempts->last();
                                                                                            $errorMsg =
                                                                                                $lastAttempt->error_message ??
                                                                                                '';

                                                                                            // Se não tiver error_message, tenta buscar no response_data
                                                                                            if (
                                                                                                empty($errorMsg) &&
                                                                                                $lastAttempt->response_data &&
                                                                                                is_array(
                                                                                                    $lastAttempt->response_data,
                                                                                                )
                                                                                            ) {
                                                                                                if (
                                                                                                    isset(
                                                                                                        $lastAttempt
                                                                                                            ->response_data[
                                                                                                            'ResponseDetail'
                                                                                                        ]['Message'],
                                                                                                    )
                                                                                                ) {
                                                                                                    $errorMsg =
                                                                                                        $lastAttempt
                                                                                                            ->response_data[
                                                                                                            'ResponseDetail'
                                                                                                        ]['Message'];
                                                                                                } elseif (
                                                                                                    isset(
                                                                                                        $lastAttempt
                                                                                                            ->response_data[
                                                                                                            'message'
                                                                                                        ],
                                                                                                    )
                                                                                                ) {
                                                                                                    $errorMsg =
                                                                                                        $lastAttempt
                                                                                                            ->response_data[
                                                                                                            'message'
                                                                                                        ];
                                                                                                }

                                                                                                $message =
                                                                                                    $lastAttempt
                                                                                                        ->response_data[
                                                                                                        'msg'
                                                                                                    ] ?? null;
                                                                                                $messageSub =
                                                                                                    $lastAttempt
                                                                                                        ->response_data[
                                                                                                        'msg_sub'
                                                                                                    ] ?? null;
                                                                                            }
                                                                                        @endphp

                                                                                        {{-- <pre>
                                                                                        {{ print_r($lastAttempt->response_data) }}
                                                                                    </pre> --}}

                                                                                        @if ($lastAttempt->response_data['msg'] ?? false)
                                                                                            <span
                                                                                                class="{{ $classText }}"
                                                                                                title="{{ $lastAttempt->response_data['msg'] }}">•
                                                                                                {{ $lastAttempt->response_data['msg'] }}</span>
                                                                                            @if ($lastAttempt->response_data['msg_sub'] ?? false)
                                                                                                <span
                                                                                                    class="{{ $classText }}"
                                                                                                    title="{{ $lastAttempt->response_data['msg_sub'] }}">
                                                                                                    -
                                                                                                    {{ $lastAttempt->response_data['msg_sub'] }}</span>
                                                                                            @endif
                                                                                        @elseif(!$errorMsg)
                                                                                            <span
                                                                                                class="{{ $classText }}"
                                                                                                title="{{ $errorMsg }}">•
                                                                                                {{ $errorMsg }}</span>
                                                                                        @endif
                                                                                    @endif
                                                                                </div>
                                                                                @if (
                                                                                    ($payment->pay_type === 'pix' || $payment->pay_type === 'slip_pix') &&
                                                                                        !empty($payment->pay_pix_expires_at) &&
                                                                                        in_array($payment->status, ['pending', 'processing', 'pix_expired']))
                                                                                    @php
                                                                                        $pixExpiresAt = \Carbon\Carbon::parse(
                                                                                            $payment->pay_pix_expires_at,
                                                                                        );
                                                                                        $pixIsExpired =
                                                                                            $pixExpiresAt->isPast() ||
                                                                                            $payment->status ===
                                                                                                'pix_expired';
                                                                                    @endphp
                                                                                    <div
                                                                                        class="mt-2 p-2 rounded {{ $pixIsExpired ? 'bg-red-50 border border-red-200' : 'bg-orange-50 border border-orange-200' }}">
                                                                                        <div
                                                                                            class="text-[10px] font-semibold {{ $pixIsExpired ? 'text-red-700' : 'text-orange-700' }} uppercase">
                                                                                            {{ $pixIsExpired ? '⚠️ PIX Expirado' : '⏰ PIX Expira em' }}
                                                                                        </div>
                                                                                        <div
                                                                                            class="text-xs font-semibold {{ $pixIsExpired ? 'text-red-900' : 'text-orange-900' }}">
                                                                                            {{ $pixExpiresAt->format('d/m/Y H:i:s') }}
                                                                                        </div>
                                                                                        @if (!$pixIsExpired)
                                                                                            <div
                                                                                                class="text-[10px] {{ $pixIsExpired ? 'text-red-600' : 'text-orange-600' }} mt-0.5">
                                                                                                {{ $pixExpiresAt->diffForHumans() }}
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                @endif

                                                                                {{-- Attempts --}}
                                                                                @if ($attempts->count() > 1)
                                                                                    <div
                                                                                        class="mt-2 text-xs text-gray-500">
                                                                                        {{ $attempts->count() }}
                                                                                        tentativa{{ $attempts->count() > 1 ? 's' : '' }}
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                            <div class="ml-4">
                                                                                <div
                                                                                    class="text-sm font-semibold text-gray-900">
                                                                                    {{ toMoney($payment->value_paid, 'R$ ') }}
                                                                                </div>
                                                                                <div
                                                                                    class="text-xs font-light text-gray-600">
                                                                                    @if (($payment->pay_installments_number ?? false) && $payment->pay_installments_number > 0)
                                                                                        {{ $payment->pay_installments_number }}x
                                                                                        {{ toMoney($payment->pay_installment_value, 'R$ ') }}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="px-4 py-3 text-center text-sm text-gray-400">
                                                                Nenhuma tentativa de pagamento
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-6 text-center py-4 text-sm text-gray-400">
                                            Nenhum pagamento registrado
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>

                        {{-- Notificações Enviadas --}}
                        <div class="mt-6 bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="p-6 bg-gray-50 border-b border-gray-200">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <h4 class="text-lg font-semibold text-gray-900">Notificações Enviadas</h4>
                                    <button type="button" wire:click="refreshOrderNotifications"
                                        wire:loading.attr="disabled" wire:target="refreshOrderNotifications"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow transition disabled:opacity-70">
                                        <span class="flex items-center gap-2" wire:loading.remove
                                            wire:target="refreshOrderNotifications" wire:loading.class="hidden">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                </path>
                                            </svg>
                                            <span>Atualizar</span>
                                        </span>
                                        <div class="flex items-center gap-2 hidden" wire:loading
                                            wire:target="refreshOrderNotifications"
                                            wire:loading.class.remove="hidden">
                                            <div>Atualizando...</div>
                                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <div class="p-6">
                                @php
                                    $notificationTypes = [
                                        'payment_approved' => 'Pagamento Aprovado',
                                        'payment_pending' => 'Pagamento Pendente',
                                        'participation_proof' => 'Comprovante de Participação',
                                    ];
                                    $notificationStatuses = [
                                        'sent' => ['label' => 'Enviado', 'class' => 'bg-green-100 text-green-700'],
                                        'failed' => ['label' => 'Falhou', 'class' => 'bg-red-100 text-red-700'],
                                        'logged' => [
                                            'label' => 'Registrado',
                                            'class' => 'bg-orange-100 text-orange-700',
                                        ],
                                    ];
                                    $notificationLogs = $this->selectedOrderNotifications;
                                @endphp

                                @if ($notificationLogs->count())
                                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                                        Data/Hora</th>
                                                    <th
                                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                                        Destino</th>
                                                    <th
                                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                                        Tipo / Assunto</th>
                                                    <th
                                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                                        Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach ($notificationLogs as $log)
                                                    @php
                                                        $statusInfo = $notificationStatuses[$log->status] ?? [
                                                            'label' => ucfirst($log->status),
                                                            'class' => 'bg-gray-100 text-gray-600',
                                                        ];
                                                    @endphp
                                                    <tr>
                                                        <td class="px-4 py-3 text-xs text-gray-600">
                                                            {{ dataDataHora($log->created_at) }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">
                                                            <div class="font-medium">{{ $log->recipient_email }}
                                                            </div>
                                                            @if ($log->recipient_name)
                                                                <div class="text-xs text-gray-500">
                                                                    {{ $log->recipient_name }}</div>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-600">
                                                            <div class="font-medium">
                                                                {{ $notificationTypes[$log->notification_type] ?? $log->notification_type }}
                                                            </div>
                                                            <div>{{ $log->subject ?? '-' }}</div>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <div class="space-y-1">
                                                                <span
                                                                    class="inline-flex px-2 py-1 rounded-full text-xs {{ $statusInfo['class'] }}">
                                                                    {{ $statusInfo['label'] }}
                                                                </span>
                                                                @if ($log->error_message ?? false)
                                                                    <div class="text-xs {{ $log->error_message ? 'text-red-600' : 'text-gray-400' }} truncate w-48"
                                                                        title="{{ $log->error_message ?? '--' }}">
                                                                        {{ $log->error_message }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-sm text-gray-500 py-4">
                                        Nenhuma notificação registrada para esta adesão.
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Dados de Acesso --}}
                        <div class="mt-6 bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="p-6 bg-gray-50 border-b border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-900">Dados de Acesso</h4>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs text-gray-500 uppercase mb-1">ID da Adesão</div>
                                        <div class="text-sm font-mono font-semibold text-gray-900">
                                            {{ $selectedOrder->id }}</div>
                                    </div>
                                    @if ($selectedOrder->ip_address)
                                        <div>
                                            <div class="text-xs text-gray-500 uppercase mb-1">IP</div>
                                            <div class="text-sm font-mono text-gray-900">
                                                {{ $selectedOrder->ip_address }}</div>
                                        </div>
                                    @endif
                                    @if ($selectedOrder->user_agent)
                                        <div>
                                            <div class="text-xs text-gray-500 uppercase mb-1">User Agent</div>
                                            <div class="text-xs text-gray-700 break-words">
                                                {{ Str::limit($selectedOrder->user_agent ?? '--', 100) }}</div>
                                        </div>
                                    @endif
                                    @if ($selectedOrder->referer)
                                        <div>
                                            <div class="text-xs text-gray-500 uppercase mb-1">Referer</div>
                                            <div class="text-xs text-gray-700 break-words">
                                                {{ Str::limit($selectedOrder->referer ?? '--', 100) }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    @endif
                @endif

            </div>
        @endif
        {{-- Fim da Tab Adesões --}}

        {{-- Conteúdo da Tab Participantes --}}
        @if ($activeTab === 'participantes')
            <div wire:key="tab-participantes">

                @php
                    $participants = $this->getParticipantsList();
                @endphp

                <div class="bg-white border rounded-sm shadow px-4 md:px-6 py-4 mb-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h3 class="text-lg font-bold text-gray-800">Participantes das Adesões
                            ({{ $participants->count() }})</h3>
                        <a href="{{ route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id]) }}?export=participantes"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Exportar CSV
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto bg-white border rounded-sm shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                    Nome</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                    Data de Nascimento</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                    Telefone</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                    E-mail</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                    Adesões Geradas</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase whitespace-nowrap tracking-wider">
                                    Adesões Pagas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($participants as $participant)
                                @php
                                    $contactCountry = $participant->contact_country
                                        ? '+' . $participant->contact_country
                                        : '';
                                    $contactDdd = $participant->contact_ddd ?? '';
                                    $contactNum = $participant->contact_num ?? '';
                                    $phone = trim(
                                        implode(' ', array_filter([$contactCountry, $contactDdd, $contactNum])),
                                    );
                                @endphp
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        {{ $participant->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 text-center">
                                        {{ $participant->birth_date ? dataData($participant->birth_date) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 text-center">
                                        {{ $phone !== '' ? $phone : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $participant->email ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-center">
                                        {{ $participant->total_orders ?? 0 }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-center">
                                        {{ $participant->paid_orders ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                        Nenhum participante encontrado para esta campanha.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        @endif
        {{-- Fim da Tab Participantes --}}

        {{-- Conteúdo da Tab Questionários --}}
        @if ($activeTab === 'questionarios')
            <div wire:key="tab-questionarios">

                @if ($campaign->questions->count() > 0)
                    @php
                        $filteredAnswers = $this->getFilteredAnswers();
                    @endphp

                    <div class="bg-white border rounded-sm shadow px-4 md:px-6 py-4 mb-4">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Respostas dos Questionários
                                ({{ count($filteredAnswers) }} adesões)</h3>
                            <div class="flex gap-2">
                                <a href="{{ route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id]) }}?export=questionarios"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Exportar CSV
                                </a>
                            </div>
                        </div>

                        {{-- Filtros --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-600 uppercase mb-1">Pergunta</label>
                                <select wire:model="filterQuestion"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todas as perguntas</option>
                                    @foreach ($campaign->questions as $question)
                                        <option value="{{ $question->id }}">{{ $question->question_text }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data
                                    Início</label>
                                <input type="date" wire:model="filterQuestionDateFrom"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data
                                    Fim</label>
                                <input type="date" wire:model="filterQuestionDateTo"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        {{-- Tabela de Questionários --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">
                                            Localizador</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Data/Hora</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Nome</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            E-mail</th>
                                        @foreach ($campaign->questions->sortBy('order') as $question)
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider min-w-[200px]">
                                                <div class="flex flex-col">
                                                    <span>{{ Str::limit($question->question_text, 50) }}</span>
                                                    <span class="text-[10px] text-gray-500 font-normal mt-1">
                                                        {{ ucfirst($question->question_type) }}
                                                    </span>
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($filteredAnswers as $item)
                                        @php
                                            $order = $item['order'];
                                            $answers = $item['answers'];
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap sticky left-0 bg-white z-10">
                                                <span
                                                    class="font-mono font-bold text-sm text-blue-600">{{ $order->order_control }}</span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                {{ $order->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $order->buyer_name }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                {{ $order->buyer_email ?? '-' }}
                                            </td>
                                            @foreach ($campaign->questions->sortBy('order') as $question)
                                                <td class="px-4 py-3 text-sm text-gray-900">
                                                    @if (isset($answers[$question->id]))
                                                        @php
                                                            $answer = $answers[$question->id];
                                                            $decodedAnswer = json_decode($answer->answer_value, true);
                                                            if (is_array($decodedAnswer)) {
                                                                echo implode(', ', $decodedAnswer);
                                                            } else {
                                                                echo $answer->answer_value;
                                                            }
                                                        @endphp
                                                    @else
                                                        <span class="text-gray-400 italic">--</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ 4 + $campaign->questions->count() }}"
                                                class="px-4 py-8 text-center text-sm text-gray-500">
                                                Nenhuma resposta encontrada com os filtros aplicados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="bg-white border rounded-sm shadow px-4 md:px-6 py-4 mb-4">
                        <div class="text-center py-8">
                            <p class="text-gray-500">Esta campanha não possui perguntas configuradas.</p>
                        </div>
                    </div>
                @endif

            </div>
        @endif
        {{-- Fim da Tab Questionários --}}

        {{-- Modal de Adesão Manual --}}
        @if ($showManualOrderModal)
            <x-modal.card title="Adicionar Adesão Manual" wire:model.defer="showManualOrderModal" max-width="2xl">
                <div class="space-y-4">
                    @php
                        $manualErrorMessages = collect($errors->getMessages())
                            ->reject(function ($messages, $field) {
                                return \Illuminate\Support\Str::startsWith($field, 'edit');
                            })
                            ->flatten()
                            ->unique()
                            ->values();
                    @endphp
                    @if ($manualErrorMessages->isNotEmpty())
                        <div role="alert" aria-live="assertive"
                            class="sticky top-0 z-10 p-3 bg-red-50 border border-red-200 rounded">
                            <div class="text-xs font-semibold text-red-700 uppercase mb-2">Corrija os erros abaixo
                            </div>
                            <ul class="text-sm text-red-700 list-disc pl-5 space-y-1">
                                @foreach ($manualErrorMessages as $errorMessage)
                                    <li>{{ $errorMessage }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                        <div class="text-xs font-semibold text-slate-700 uppercase">Gateway</div>
                        <div class="text-sm font-bold text-slate-900">MANUAL</div>
                        <div class="text-[11px] text-slate-600">Esta adesão será registrada sem processamento no
                            gateway online.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nome do Doador
                                *</label>
                            <input type="text" wire:model.defer="manualBuyerName"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('manualBuyerName')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">E-mail</label>
                            <input type="email" wire:model.defer="manualBuyerEmail"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('manualBuyerEmail')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">CPF/CNPJ</label>
                            <input type="text" wire:model.defer="manualBuyerDocNum"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('manualBuyerDocNum')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-3">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">País
                                    (DDI)</label>
                                <input type="text" wire:model.defer="manualBuyerContactCountry" maxlength="5"
                                    inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    placeholder="55"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('manualBuyerContactCountry')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-3">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">DDD</label>
                                <input type="text" wire:model.defer="manualBuyerContactDdd"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('manualBuyerContactDdd')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-6">
                                <label
                                    class="block text-xs font-semibold text-gray-600 uppercase mb-1">Telefone</label>
                                <input type="text" wire:model.defer="manualBuyerContactNum"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('manualBuyerContactNum')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Valor Total (R$)
                                *</label>
                            <input type="text" wire:model.defer="manualAmountTotal" placeholder="Ex: 150,00"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('manualAmountTotal')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Valor Pago
                                (R$)</label>
                            <input type="text" wire:model.defer="manualAmountPaid" placeholder="Ex: 150,00"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('manualAmountPaid')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status *</label>
                            <select wire:model="manualStatus"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="paid">Pago</option>
                                <option value="pending">Pendente</option>
                            </select>
                            @error('manualStatus')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Forma de Pagamento
                                *</label>
                            <select wire:model.defer="manualPayType"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="manual">Manual</option>
                                <option value="cash">Dinheiro</option>
                                <option value="pix">PIX</option>
                                <option value="transfer">Transferência</option>
                                <option value="card_credit">Cartão</option>
                                <option value="boleto">Boleto</option>
                            </select>
                            @error('manualPayType')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @if ($manualStatus === 'paid')
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data do
                                    Pagamento</label>
                                <input type="date" wire:model.defer="manualPaidAt"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('manualPaidAt')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Descrição</label>
                            <input type="text" wire:model.defer="manualDescription"
                                placeholder="Ex: Recebido em dinheiro no evento"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('manualDescription')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Observação</label>
                            <textarea wire:model.defer="manualObservation" rows="3"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('manualObservation')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="flex justify-end gap-2">
                        <x-button flat label="Cancelar" wire:click="closeManualOrderModal" />
                        <x-button primary label="Salvar Adesão" wire:click="saveManualOrder" spinner />
                    </div>
                </x-slot>
            </x-modal.card>
        @endif

        {{-- Modal de Edição de Adesão --}}
        @if ($showOrderEditModal)
            <x-modal.card title="Editar Adesão" wire:model.defer="showOrderEditModal" max-width="3xl">
                <div class="space-y-5">
                    @php
                        $editErrorMessages = collect($errors->getMessages())
                            ->reject(function ($messages, $field) {
                                return \Illuminate\Support\Str::startsWith($field, 'manual');
                            })
                            ->flatten()
                            ->unique()
                            ->values();
                    @endphp
                    @if ($editErrorMessages->isNotEmpty())
                        <div role="alert" aria-live="assertive"
                            class="sticky top-0 z-10 p-3 bg-red-50 border border-red-200 rounded">
                            <div class="text-xs font-semibold text-red-700 uppercase mb-2">Corrija os erros abaixo
                            </div>
                            <ul class="text-sm text-red-700 list-disc pl-5 space-y-1">
                                @foreach ($editErrorMessages as $errorMessage)
                                    <li>{{ $errorMessage }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (!$editOrderIsManual)
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded text-xs text-blue-800">
                            Nesta adesão você pode ajustar os dados do doador. Valores e dados de pagamento permanecem
                            bloqueados.
                        </div>
                    @endif

                    <div>
                        <h4 class="text-sm font-bold text-gray-700 uppercase mb-3">Dados do Doador</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nome do Doador
                                    *</label>
                                <input type="text" wire:model.defer="editBuyerName"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('editBuyerName')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">E-mail</label>
                                <input type="email" wire:model.defer="editBuyerEmail"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('editBuyerEmail')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-600 uppercase mb-1">CPF/CNPJ</label>
                                <input type="text" wire:model.defer="editBuyerDocNum"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('editBuyerDocNum')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-12 gap-2">
                                <div class="col-span-3">
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">País
                                        (DDI)</label>
                                    <input type="text" wire:model.defer="editBuyerContactCountry"
                                        maxlength="5" inputmode="numeric"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="55"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('editBuyerContactCountry')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-span-3">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 uppercase mb-1">DDD</label>
                                    <input type="text" wire:model.defer="editBuyerContactDdd"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('editBuyerContactDdd')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-span-6">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 uppercase mb-1">Telefone</label>
                                    <input type="text" wire:model.defer="editBuyerContactNum"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('editBuyerContactNum')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Observação</label>
                            <textarea wire:model.defer="editOrderObservation" rows="3"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('editOrderObservation')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if ($editOrderIsManual)
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="text-sm font-bold text-gray-700 uppercase mb-3">Pagamento</h4>
                            <div class="mb-4 p-3 bg-slate-50 border border-slate-200 rounded">
                                <div class="text-xs font-semibold text-slate-700 uppercase">Gateway</div>
                                <div class="text-sm font-bold text-slate-900">MANUAL</div>
                                <div class="text-[11px] text-slate-600">Esta adesão foi lançada sem processamento no
                                    gateway online.</div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Valor
                                        Total (R$) *</label>
                                    <input type="text" wire:model.defer="editOrderAmountTotal"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('editOrderAmountTotal')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Valor Pago
                                        (R$)</label>
                                    <input type="text" wire:model.defer="editOrderAmountPaid"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('editOrderAmountPaid')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status
                                        *</label>
                                    <select wire:model="editOrderStatus"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="paid">Pago</option>
                                        <option value="pending">Pendente</option>
                                        <option value="cancelled">Cancelado</option>
                                    </select>
                                    @error('editOrderStatus')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Forma de
                                        Pagamento *</label>
                                    <select wire:model.defer="editOrderPayType"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="manual">Manual</option>
                                        <option value="cash">Dinheiro</option>
                                        <option value="pix">PIX</option>
                                        <option value="transfer">Transferência</option>
                                        <option value="card_credit">Cartão Crédito</option>
                                        <option value="card_debit">Cartão Débito</option>
                                        <option value="boleto">Boleto</option>
                                    </select>
                                    @error('editOrderPayType')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tipo da
                                        Transação *</label>
                                    <input type="text" wire:model.defer="editOrderPayIntegrationType"
                                        placeholder="Ex: manual, gateway, api"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('editOrderPayIntegrationType')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data do
                                        Pagamento</label>
                                    <input type="datetime-local" wire:model.defer="editOrderPaidAt"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('editOrderPaidAt')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data/Hora
                                        da Transação</label>
                                    <input type="datetime-local" wire:model.defer="editOrderPayDatetime"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('editOrderPayDatetime')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Número da
                                        Transação</label>
                                    <input type="text" wire:model.defer="editOrderPayTransactionId"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('editOrderPayTransactionId')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-600 uppercase mb-1">NSU</label>
                                    <input type="text" wire:model.defer="editOrderPayNsu"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('editOrderPayNsu')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label
                                        class="block text-xs font-semibold text-gray-600 uppercase mb-1">Descrição</label>
                                    <input type="text" wire:model.defer="editOrderDescription"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('editOrderDescription')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <x-slot name="footer">
                    <div class="w-full flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            @if ($canDeleteSelectedOrder)
                                @if (!$confirmDeleteOrder)
                                    <button type="button" wire:click="beginDeleteOrderConfirmation"
                                        class="px-3 py-2 rounded text-xs font-semibold text-red-700 border border-red-300 hover:bg-red-50 transition-colors">
                                        Excluir Adesão
                                    </button>
                                @else
                                    <span class="text-xs font-semibold text-red-700">Confirma a exclusão?</span>
                                    <button type="button" wire:click="cancelDeleteOrderConfirmation"
                                        class="px-3 py-2 rounded text-xs font-semibold text-gray-700 border border-gray-300 hover:bg-gray-50 transition-colors">
                                        Não
                                    </button>
                                    <button type="button" wire:click="deleteOrder"
                                        class="px-3 py-2 rounded text-xs font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors">
                                        Sim, Excluir
                                    </button>
                                @endif
                            @endif
                        </div>

                        <div class="flex justify-end gap-2">
                            <x-button flat label="Cancelar" wire:click="closeOrderEditModal" />
                            <x-button primary label="Salvar Alterações" wire:click="saveOrderEdit" spinner />
                        </div>
                    </div>
                </x-slot>
            </x-modal.card>
        @endif

        {{-- Modal de Detalhes do Pagamento --}}
        @if ($showPaymentModal && $selectedPayment)
            <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showPaymentModal') }">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    {{-- Overlay --}}
                    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                        wire:click="closePaymentModal"></div>

                    {{-- Modal --}}
                    <div
                        class="relative inline-block w-full max-w-4xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl">
                        {{-- Header --}}
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900">Detalhes do Pagamento</h3>
                                    @if ($selectedPayment->gateway_sandbox)
                                        <span
                                            class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 border-2 border-yellow-400 uppercase animate-pulse">
                                            🧪 SANDBOX
                                        </span>
                                    @endif
                                </div>
                                <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                            {{-- Alerta de Sandbox --}}
                            @if ($selectedPayment->gateway_sandbox)
                                <div class="mb-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-yellow-800">Ambiente de TESTE
                                                (Sandbox)</p>
                                            <p class="text-xs text-yellow-700">Esta transação foi processada em
                                                ambiente de testes. Não houve movimentação financeira real.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <table class="w-full">
                                <tbody class="divide-y divide-gray-100">
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600 w-1/3">ID</td>
                                        <td class="py-2 text-sm text-gray-900 font-mono">{{ $selectedPayment->id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Status</td>
                                        <td class="py-2">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded {{ in_array($selectedPayment->status, ['paid', 'approved'])
                                                    ? 'bg-green-100 text-green-700'
                                                    : ($selectedPayment->status === 'pending'
                                                        ? 'bg-yellow-100 text-yellow-700'
                                                        : ($selectedPayment->status === 'error'
                                                            ? 'bg-red-100 text-red-700'
                                                            : 'bg-gray-100 text-gray-600')) }}">
                                                {{ strtoupper($selectedPayment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Descrição</td>
                                        <td class="py-2 text-sm text-gray-900">
                                            {{ $selectedPayment->description ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Tipo de Pagamento</td>
                                        <td class="py-2 text-sm text-gray-900">
                                            {{ strtoupper($selectedPayment->pay_type ?? '-') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Gateway</td>
                                        <td class="py-2">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-sm text-gray-900">{{ strtoupper($selectedPayment->gateway_slug ?? '-') }}</span>
                                                @if ($selectedPayment->gateway_sandbox)
                                                    <span
                                                        class="px-2 py-0.5 text-xs font-bold rounded bg-yellow-100 text-yellow-800 border border-yellow-400">
                                                        SANDBOX
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 py-0.5 text-xs font-bold rounded bg-green-100 text-green-800 border border-green-400">
                                                        LIVE
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Valor Pago</td>
                                        <td class="py-2 text-sm font-bold text-gray-900">
                                            {{ toMoney($selectedPayment->value_paid, 'R$ ') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Taxa</td>
                                        <td class="py-2 text-sm text-gray-900">
                                            {{ toMoney($selectedPayment->value_fees ?? 0, 'R$ ') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Valor Líquido</td>
                                        <td class="py-2 text-sm font-bold text-green-600">
                                            {{ toMoney($selectedPayment->value_liquid, 'R$ ') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-sm font-semibold text-gray-600">Data de Criação</td>
                                        <td class="py-2 text-sm text-gray-900">
                                            {{ $selectedPayment->created_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                    @if ($selectedPayment->paid_at)
                                        <tr>
                                            <td class="py-2 text-sm font-semibold text-gray-600">Data de Pagamento
                                            </td>
                                            <td class="py-2 text-sm text-green-600">
                                                {{ $selectedPayment->paid_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    @endif
                                    @if ($selectedPayment->pay_transaction_id)
                                        <tr>
                                            <td class="py-2 text-sm font-semibold text-gray-600">Transaction ID</td>
                                            <td class="py-2 text-sm font-mono text-gray-900">
                                                {{ $selectedPayment->pay_transaction_id }}</td>
                                        </tr>
                                    @endif
                                    @if ($selectedPayment->pay_nsu)
                                        <tr>
                                            <td class="py-2 text-sm font-semibold text-gray-600">NSU</td>
                                            <td class="py-2 text-sm font-mono text-gray-900">
                                                {{ $selectedPayment->pay_nsu }}</td>
                                        </tr>
                                    @endif
                                    @if ($selectedPayment->pay_installments_number && $selectedPayment->pay_installments_number > 1)
                                        <tr>
                                            <td class="py-2 text-sm font-semibold text-gray-600">Parcelas</td>
                                            <td class="py-2 text-sm text-gray-900">
                                                {{ $selectedPayment->pay_installments_number }}x de
                                                {{ toMoney($selectedPayment->pay_installment_value, 'R$ ') }}</td>
                                        </tr>
                                    @endif
                                    @if ($selectedPayment->pay_pix_qrcode)
                                        <tr>
                                            <td class="py-2 text-sm font-semibold text-gray-600">PIX QR Code</td>
                                            <td class="py-2 text-xs font-mono text-gray-700 break-all">
                                                {{ Str::limit($selectedPayment->pay_pix_qrcode, 50) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            {{-- Attempts --}}
                            @if ($selectedPayment->attempts && $selectedPayment->attempts->count() > 0)
                                <div class="mt-6">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Tentativas
                                        ({{ $selectedPayment->attempts->count() }})</h4>
                                    <div class="space-y-2">
                                        @foreach ($selectedPayment->attempts as $attempt)
                                            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span
                                                        class="text-xs text-gray-500">{{ $attempt->attempted_at->format('d/m/Y H:i:s') }}</span>
                                                    <span
                                                        class="px-2 py-0.5 text-xs font-medium rounded {{ $attempt->status === 'success'
                                                            ? 'bg-green-100 text-green-700'
                                                            : ($attempt->status === 'error'
                                                                ? 'bg-red-100 text-red-700'
                                                                : 'bg-gray-100 text-gray-600') }}">
                                                        {{ strtoupper($attempt->status) }}
                                                    </span>
                                                </div>
                                                @if ($attempt->error_message)
                                                    <div class="text-xs text-red-600 font-semibold mb-1">
                                                        <span class="uppercase">Erro:</span>
                                                        {{ $attempt->error_message }}
                                                    </div>
                                                @endif
                                                @if ($attempt->response_data && is_array($attempt->response_data))
                                                    @php
                                                        $errorSub = null;
                                                        // Tenta buscar mensagem adicional do response_data
                                                        if (
                                                            isset($attempt->response_data['ResponseDetail']['Message'])
                                                        ) {
                                                            $errorSub =
                                                                $attempt->response_data['ResponseDetail']['Message'];
                                                        } elseif (
                                                            isset(
                                                                $attempt->response_data['ResponseDetail']['ErrorCode'],
                                                            )
                                                        ) {
                                                            $errorSub =
                                                                $attempt->response_data['ResponseDetail']['ErrorCode'];
                                                        } elseif (isset($attempt->response_data['message'])) {
                                                            $errorSub = $attempt->response_data['message'];
                                                        } elseif (isset($attempt->response_data['error_description'])) {
                                                            $errorSub = $attempt->response_data['error_description'];
                                                        }
                                                    @endphp
                                                    @if ($errorSub && $errorSub !== $attempt->error_message)
                                                        <div class="text-xs text-red-500 mb-1">
                                                            <span class="uppercase">Detalhes:</span>
                                                            {{ $errorSub }}
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Gateway Request/Response --}}
                            @if ($selectedPayment->pay_json_request || $selectedPayment->pay_json_response)
                                <div class="mt-6">
                                    <div class="space-y-3">
                                        <div class="border border-gray-200 rounded">
                                            <div class="bg-blue-50 px-3 py-2 border-b border-gray-200">
                                                <span class="text-xs font-semibold text-blue-700">REQUEST
                                                    GATEWAY</span>
                                            </div>
                                            <div class="p-3">
                                                @if ($selectedPayment->pay_json_request)
                                                    <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200">{{ json_encode($selectedPayment->pay_json_request, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                @else
                                                    <div
                                                        class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200">
                                                        NÃO POSSUI</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="border border-gray-200 rounded">
                                            <div class="bg-green-50 px-3 py-2 border-b border-gray-200">
                                                <span class="text-xs font-semibold text-green-700">RESPONSE
                                                    GATEWAY</span>
                                            </div>
                                            <div class="p-3">
                                                @if ($selectedPayment->pay_json_response)
                                                    <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200">{{ json_encode($selectedPayment->pay_json_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                @else
                                                    <div
                                                        class="text-xs bg-gray-50 p-3 rounded overflow-x-auto border border-gray-200">
                                                        NÃO POSSUI</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Webhooks Recebidos --}}
                            @if ($selectedPayment->webhooks && $selectedPayment->webhooks->count() > 0)
                                <div class="mt-6">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Webhooks Recebidos
                                        ({{ $selectedPayment->webhooks->count() }})</h4>
                                    <div class="space-y-2">
                                        @foreach ($selectedPayment->webhooks as $webhook)
                                            <div class="border border-gray-200 rounded">
                                                <div class="bg-purple-50 px-3 py-2 border-b border-gray-200">
                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <span
                                                                class="text-xs font-semibold text-purple-700">{{ strtoupper($webhook->webhook_type ?? 'WEBHOOK') }}</span>
                                                            <span
                                                                class="text-xs text-gray-500 ml-2">{{ $webhook->created_at->format('d/m/Y H:i:s') }}</span>
                                                        </div>
                                                        <span
                                                            class="px-2 py-0.5 text-xs font-medium rounded {{ $webhook->processing_status === 'processed'
                                                                ? 'bg-green-100 text-green-700'
                                                                : ($webhook->processing_status === 'error'
                                                                    ? 'bg-red-100 text-red-700'
                                                                    : 'bg-yellow-100 text-yellow-700') }}">
                                                            {{ strtoupper($webhook->processing_status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="p-3">
                                                    @if ($webhook->processing_error)
                                                        <div class="text-xs text-red-600 mb-2">Erro:
                                                            {{ $webhook->processing_error }}</div>
                                                    @endif
                                                    @if ($webhook->payload)
                                                        <details>
                                                            <summary
                                                                class="text-xs text-purple-600 cursor-pointer hover:text-purple-800 mb-2">
                                                                Ver Payload</summary>
                                                            <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto max-h-60 border border-gray-200">{{ json_encode($webhook->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </details>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                            <button wire:click="closePaymentModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- Scripts para Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Variáveis globais para armazenar instâncias dos gráficos
        let revenueChartInstance = null;
        let transactionsChartInstance = null;

        function initCharts() {
            const chartDataRaw = @json($chartData);

            // Converte valores de receita de centavos para reais
            const chartData = {
                ...chartDataRaw,
                revenue: chartDataRaw.revenue.map(value => value / 100)
            };

            // Verifica se estamos na tab de analíticos
            const revenueCanvas = document.getElementById('revenueChart');
            const transactionsCanvas = document.getElementById('transactionsChart');

            if (!revenueCanvas || !transactionsCanvas) {
                return; // Gráficos não estão visíveis
            }

            // Destroi instâncias anteriores se existirem
            if (revenueChartInstance) {
                revenueChartInstance.destroy();
                revenueChartInstance = null;
            }
            if (transactionsChartInstance) {
                transactionsChartInstance.destroy();
                transactionsChartInstance = null;
            }

            // Configuração comum
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grace: '5%',
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 9
                            },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            };

            // Gráfico de Receita
            const revenueCtx = revenueCanvas.getContext('2d');
            revenueChartInstance = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Receita (R$)',
                        data: chartData.revenue,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        ...commonOptions.scales,
                        y: {
                            ...commonOptions.scales.y,
                            ticks: {
                                ...commonOptions.scales.y.ticks,
                                callback: function(value) {
                                    return 'R$ ' + value.toLocaleString('pt-BR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }
                            }
                        }
                    },
                    plugins: {
                        ...commonOptions.plugins,
                        tooltip: {
                            ...commonOptions.plugins.tooltip,
                            callbacks: {
                                label: function(context) {
                                    return 'Receita: R$ ' + context.parsed.y.toLocaleString('pt-BR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de Transações
            const transactionsCtx = transactionsCanvas.getContext('2d');
            transactionsChartInstance = new Chart(transactionsCtx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                            label: 'Total de Adesões',
                            data: chartData.orders,
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1
                        },
                        {
                            label: 'Adesões Pagas',
                            data: chartData.paid,
                            backgroundColor: 'rgba(34, 197, 94, 0.7)',
                            borderColor: 'rgb(34, 197, 94)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        ...commonOptions.scales,
                        y: {
                            ...commonOptions.scales.y,
                            ticks: {
                                ...commonOptions.scales.y.ticks,
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Inicializa gráficos no carregamento da página
        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
        });

        // Reinicializa gráficos quando o Livewire atualiza o componente
        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.processed', (message, component) => {
                // Aguarda o DOM atualizar
                setTimeout(() => {
                    initCharts();
                }, 100);
            });
        });

        // Para Livewire v3
        if (typeof Livewire !== 'undefined') {
            Livewire.on('contentChanged', () => {
                setTimeout(() => {
                    initCharts();
                }, 100);
            });
        }

        // Função para copiar URL
        function copiarURL() {
            const urlInput = document.getElementById('campaign-url');
            const url = urlInput.value;

            // Tenta usar a API moderna do Clipboard
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(function() {
                    window.$wireui.notify({
                        title: 'Sucesso!',
                        description: 'URL copiada para a área de transferência',
                        icon: 'success'
                    });
                }).catch(function(err) {
                    console.error('Erro ao copiar:', err);
                    // Fallback
                    copiarURLFallback(urlInput);
                });
            } else {
                // Fallback para navegadores mais antigos
                copiarURLFallback(urlInput);
            }
        }

        function copiarURLFallback(input) {
            try {
                input.select();
                input.setSelectionRange(0, 99999);
                const success = document.execCommand('copy');

                if (success) {
                    window.$wireui.notify({
                        title: 'Sucesso!',
                        description: 'URL copiada para a área de transferência',
                        icon: 'success'
                    });
                } else {
                    window.$wireui.notify({
                        title: 'Erro!',
                        description: 'Não foi possível copiar a URL. Tente selecionar e copiar manualmente.',
                        icon: 'error'
                    });
                }
            } catch (err) {
                console.error('Erro no fallback:', err);
                window.$wireui.notify({
                    title: 'Erro!',
                    description: 'Não foi possível copiar a URL. Tente selecionar e copiar manualmente.',
                    icon: 'error'
                });
            }
        }
    </script>

    {{-- Modal de Clonagem de Campanha (admin only) --}}
    @if ($showClonarModal && isAdmin())
        <x-modal.card title="Clonar Campanha" wire:model.defer="showClonarModal" max-width="lg">
            <div class="space-y-4">
                @if ($clonarStep === 1)
                    {{-- Passo 1: primeira confirmação --}}
                    <div class="flex items-start gap-3 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-indigo-900">Você está prestes a clonar a campanha:</p>
                            <p class="text-base font-bold text-indigo-800 mt-1">{{ $campaign->name }}</p>
                        </div>
                    </div>
                    <ul class="text-sm text-gray-600 space-y-1 pl-4 list-disc">
                        <li>Todos os textos e configurações serão copiados.</li>
                        <li>As imagens <strong>não</strong> serão copiadas.</li>
                        <li>O status da nova campanha será <span class="font-semibold text-gray-800">Rascunho</span>.</li>
                        <li>As perguntas do questionário serão clonadas.</li>
                    </ul>
                    <p class="text-sm text-gray-500">Deseja continuar?</p>
                @else
                    {{-- Passo 2: confirmação final --}}
                    <div class="flex items-start gap-3 p-4 bg-orange-50 border border-orange-300 rounded-lg">
                        <svg class="w-6 h-6 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-orange-900">Confirmação final</p>
                            <p class="text-sm text-orange-800 mt-1">
                                Confirme que deseja criar um clone de <strong>{{ $campaign->name }}</strong> com status <strong>Rascunho</strong> e sem imagens.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <x-slot name="footer">
                <div class="flex justify-end items-center gap-2 w-full">
                    <x-button flat label="Cancelar" wire:click="$set('showClonarModal', false)" />
                    @if ($clonarStep === 1)
                        <x-button primary label="Continuar" wire:click="clonarStep2" />
                    @else
                        <x-button style="background-color: #4f46e5; color: white;" label="Confirmar Clonagem" wire:click="clonarCampanha" spinner="clonarCampanha" />
                    @endif
                </div>
            </x-slot>
        </x-modal.card>
    @endif

    {{-- Modal de QR Code --}}
    @if ($showQrCodeModal)
        <x-modal.card title="QR Code da Campanha" wire:model.defer="showQrCodeModal" max-width="2xl">
            <div class="space-y-6">
                {{-- QR Code --}}
                <div class="flex justify-center items-center py-2">
                    <div class="bg-white p-6 rounded-lg border-2 border-gray-200 shadow-sm">
                        {!! QrCode::size(300)->generate(campanhaUrl($campaign->customer_organization_slug, $campaign->slug)) !!}
                    </div>
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-between items-center w-full gap-2">
                    <button onclick="downloadQRCode()"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Baixar QR Code
                    </button>
                    <x-button flat label="Fechar" wire:click="closeQrCodeModal" />
                </div>
            </x-slot>
        </x-modal.card>
    @endif

    <br>

    {{-- Scripts para QR Code (sempre disponíveis) --}}
    <script>
        function copiarURLModal() {
            const input = document.getElementById('qrcode-campaign-url');
            const url = input.value;

            // Tenta usar a API moderna do Clipboard
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(function() {
                    window.$wireui.notify({
                        title: 'Sucesso!',
                        description: 'URL copiada para a área de transferência',
                        icon: 'success'
                    });
                }).catch(function(err) {
                    console.error('Erro ao copiar:', err);
                    // Fallback
                    copiarModalFallback(input);
                });
            } else {
                // Fallback para navegadores mais antigos
                copiarModalFallback(input);
            }
        }

        function copiarModalFallback(input) {
            try {
                input.select();
                input.setSelectionRange(0, 99999);
                const success = document.execCommand('copy');

                if (success) {
                    window.$wireui.notify({
                        title: 'Sucesso!',
                        description: 'URL copiada para a área de transferência',
                        icon: 'success'
                    });
                } else {
                    window.$wireui.notify({
                        title: 'Erro!',
                        description: 'Não foi possível copiar. Tente selecionar e copiar manualmente.',
                        icon: 'error'
                    });
                }
            } catch (err) {
                console.error('Erro no fallback:', err);
                window.$wireui.notify({
                    title: 'Erro!',
                    description: 'Não foi possível copiar. Tente selecionar e copiar manualmente.',
                    icon: 'error'
                });
            }
        }

        function downloadQRCode() {
            try {
                // Pega o SVG do QR Code - busca de forma mais específica
                const qrContainer = document.querySelector('.bg-white.p-6.rounded-lg.border-2');
                if (!qrContainer) {
                    console.error('Container do QR Code não encontrado');
                    window.$wireui.notify({
                        title: 'Erro!',
                        description: 'QR Code não encontrado',
                        icon: 'error'
                    });
                    return;
                }

                const svg = qrContainer.querySelector('svg');
                if (!svg) {
                    console.error('SVG do QR Code não encontrado');
                    window.$wireui.notify({
                        title: 'Erro!',
                        description: 'QR Code SVG não encontrado',
                        icon: 'error'
                    });
                    return;
                }

                console.log('QR Code SVG encontrado:', svg);

                // Pega as dimensões do SVG
                const svgWidth = svg.width.baseVal.value || 300;
                const svgHeight = svg.height.baseVal.value || 300;

                // Serializa o SVG
                const svgData = new XMLSerializer().serializeToString(svg);
                console.log('SVG serializado');

                // Cria canvas
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                // Define tamanho maior para melhor qualidade
                const scale = 2; // 2x para melhor qualidade
                canvas.width = svgWidth * scale;
                canvas.height = svgHeight * scale;

                // Cria imagem
                const img = new Image();

                img.onerror = function(err) {
                    console.error('Erro ao carregar imagem:', err);
                    window.$wireui.notify({
                        title: 'Erro!',
                        description: 'Erro ao processar QR Code',
                        icon: 'error'
                    });
                };

                img.onload = function() {
                    console.log('Imagem carregada, dimensões:', img.width, 'x', img.height);

                    // Preenche com fundo branco
                    ctx.fillStyle = 'white';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);

                    // Desenha o QR Code escalado
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    console.log('QR Code desenhado no canvas');

                    // Converte para PNG e baixa
                    canvas.toBlob(function(blob) {
                        if (!blob) {
                            console.error('Erro ao criar blob');
                            window.$wireui.notify({
                                title: 'Erro!',
                                description: 'Erro ao gerar imagem',
                                icon: 'error'
                            });
                            return;
                        }

                        console.log('Blob criado, tamanho:', blob.size);

                        const url = URL.createObjectURL(blob);
                        const link = document.createElement('a');

                        // Monta o nome do arquivo: qrcode-organizador-campanha.png
                        @php
                            $organizerSlug = $campaign->customer_organization_slug ?? 'organizador';
                            $campaignSlug = $campaign->slug ?? 'campanha';
                            $fileName = "qrcode-{$organizerSlug}-{$campaignSlug}.png";
                        @endphp
                        link.download = @js($fileName);
                        link.href = url;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        // Aguarda um pouco antes de revogar a URL
                        setTimeout(() => {
                            URL.revokeObjectURL(url);
                        }, 100);

                        console.log('Download iniciado');
                        window.$wireui.notify({
                            title: 'Sucesso!',
                            description: 'QR Code baixado com sucesso',
                            icon: 'success'
                        });
                    }, 'image/png');
                };

                // Codifica o SVG para base64
                const svgBlob = new Blob([svgData], {
                    type: 'image/svg+xml;charset=utf-8'
                });
                const url = URL.createObjectURL(svgBlob);
                img.src = url;

            } catch (error) {
                console.error('Erro no download:', error);
                window.$wireui.notify({
                    title: 'Erro!',
                    description: 'Erro ao baixar QR Code: ' + error.message,
                    icon: 'error'
                });
            }
        }
    </script>
</div>
