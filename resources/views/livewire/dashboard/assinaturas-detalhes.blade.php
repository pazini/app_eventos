@php
    $status = $product->status ?? 'draft';
    $statusLabel = 'RASCUNHO';
    $statusBadgeClass = 'bg-white/20 text-white';

    if ($status === 'active') {
        $statusLabel = 'ATIVO';
        $statusBadgeClass = 'bg-emerald-100 text-emerald-800';
    } elseif ($status === 'paused') {
        $statusLabel = 'PAUSADO';
        $statusBadgeClass = 'bg-orange-100 text-orange-800';
    } elseif ($status === 'cancelled') {
        $statusLabel = 'CANCELADO';
        $statusBadgeClass = 'bg-red-100 text-red-800';
    }
@endphp

<div>
    <div class="max-w-7xl mx-auto px-2 sm:px-2 lg:px-0">
        <div class="bg-gradient-to-r from-teal-600 to-emerald-600 shadow-lg rounded-xl p-6">
            <div class="w-full flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="text-xs font-semibold uppercase tracking-widest text-emerald-100">Assinatura</div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadgeClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <div class="text-2xl md:text-3xl font-bold text-white uppercase">{{ $product->name }}</div>
                    <div class="text-sm text-emerald-100">{{ $product->customer->name_corporate ?? 'Cliente' }}</div>
                </div>
                <div class="flex items-center gap-3">
                    <x-button white label="VOLTAR" href="{{ route('dashboard-assinaturas') }}" />
                </div>
            </div>
        </div>
    </div>

    <div class="w-full max-w-7xl mx-auto mt-4 space-y-4">
        <x-jet-validation-errors />

        @if(getAssinaturasUrl())
            <div class="bg-white border border-emerald-200 rounded-sm shadow px-4 py-3">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="flex-1">
                        <div class="text-[10px] font-semibold text-gray-600 uppercase mb-1">URL da assinatura</div>
                        <input
                            type="text"
                            value="{{ rtrim(getAssinaturasUrl(), '/') . '/' . $product->slug }}"
                            readonly
                            class="w-full px-0 py-1 text-sm font-mono bg-white border border-white rounded text-emerald-700 focus:outline-none"
                        />
                        @if(! $product->visibility_public)
                            <div class="mt-1 text-xs text-gray-500">URL privada (visibilidade desativada)</div>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <a
                            href="{{ rtrim(getAssinaturasUrl(), '/') . '/' . $product->slug }}"
                            target="_blank"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2"
                        >
                            Abrir
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white border rounded-sm shadow">
            <div class="flex border-b">
                <button
                    wire:click="setTab('analiticos')"
                    class="flex-1 px-6 py-4 text-sm font-semibold transition-colors {{ $activeTab === 'analiticos' ? 'text-emerald-700 border-b-2 border-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}"
                >
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>ANALITICO</span>
                    </div>
                </button>
                <button
                    wire:click="setTab('detalhes')"
                    class="flex-1 px-6 py-4 text-sm font-semibold transition-colors {{ $activeTab === 'detalhes' ? 'text-emerald-700 border-b-2 border-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}"
                >
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>DETALHES</span>
                    </div>
                </button>
                <button
                    wire:click="setTab('planos')"
                    class="flex-1 px-6 py-4 text-sm font-semibold transition-colors {{ $activeTab === 'planos' ? 'text-emerald-700 border-b-2 border-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}"
                >
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 8H7a2 2 0 01-2-2V6a2 2 0 012-2h6l6 6v8a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>PLANOS</span>
                    </div>
                </button>
                <button
                    wire:click="setTab('adesoes')"
                    class="flex-1 px-6 py-4 text-sm font-semibold transition-colors {{ $activeTab === 'adesoes' ? 'text-emerald-700 border-b-2 border-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}"
                >
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>ADESOES</span>
                    </div>
                </button>
                <button
                    wire:click="setTab('transacoes')"
                    class="flex-1 px-6 py-4 text-sm font-semibold transition-colors {{ $activeTab === 'transacoes' ? 'text-emerald-700 border-b-2 border-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}"
                >
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span>TRANSAÇÕES</span>
                    </div>
                </button>
            </div>
        </div>

        @if($activeTab === 'analiticos')
            <div wire:key="tab-analiticos" class="space-y-6">
                @php
                    $metrics = $metricsLast30Days ?? [];
                    $period = $periodComparison ?? [];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white border rounded-sm shadow px-4 py-4">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase mb-1">Receita (30 dias)</div>
                        <div class="text-2xl font-bold text-emerald-600">{{ toMoney(data_get($metrics, 'revenue', 0), 'R$ ') }}</div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-[10px] text-gray-500">Periodo anterior: {{ toMoney(data_get($period, 'revenue.previous', 0), 'R$ ') }}</div>
                            @if(!is_null(data_get($period, 'revenue')))
                                <div class="text-[10px] font-bold {{ data_get($period, 'revenue.percent', 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ data_get($period, 'revenue.percent', 0) >= 0 ? '▲' : '▼' }} {{ abs(data_get($period, 'revenue.percent', 0)) }}%
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-white border rounded-sm shadow px-4 py-4">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase mb-1">Novas assinaturas (30 dias)</div>
                        <div class="text-2xl font-bold text-blue-600">{{ number_format(data_get($metrics, 'new', 0), 0, ',', '.') }}</div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-[10px] text-gray-500">Periodo anterior: {{ number_format(data_get($period, 'new.previous', 0), 0, ',', '.') }}</div>
                            @if(!is_null(data_get($period, 'new')))
                                <div class="text-[10px] font-bold {{ data_get($period, 'new.percent', 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ data_get($period, 'new.percent', 0) >= 0 ? '▲' : '▼' }} {{ abs(data_get($period, 'new.percent', 0)) }}%
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-white border rounded-sm shadow px-4 py-4">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase mb-1">Alteracoes de plano (30 dias)</div>
                        <div class="text-2xl font-bold text-orange-600">{{ number_format(data_get($metrics, 'changed', 0), 0, ',', '.') }}</div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-[10px] text-gray-500">Periodo anterior: {{ number_format(data_get($period, 'changed.previous', 0), 0, ',', '.') }}</div>
                            @if(!is_null(data_get($period, 'changed')))
                                <div class="text-[10px] font-bold {{ data_get($period, 'changed.percent', 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ data_get($period, 'changed.percent', 0) >= 0 ? '▲' : '▼' }} {{ abs(data_get($period, 'changed.percent', 0)) }}%
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-white border rounded-sm shadow px-4 py-4">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase mb-1">Cancelamentos (30 dias)</div>
                        <div class="text-2xl font-bold text-red-600">{{ number_format(data_get($metrics, 'canceled', 0), 0, ',', '.') }}</div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-[10px] text-gray-500">Periodo anterior: {{ number_format(data_get($period, 'canceled.previous', 0), 0, ',', '.') }}</div>
                            @if(!is_null(data_get($period, 'canceled')))
                                <div class="text-[10px] font-bold {{ data_get($period, 'canceled.percent', 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ data_get($period, 'canceled.percent', 0) >= 0 ? '▲' : '▼' }} {{ abs(data_get($period, 'canceled.percent', 0)) }}%
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <div class="text-xs uppercase text-gray-500">Planos</div>
                        <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $stats['plans'] ?? 0 }}</div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <div class="text-xs uppercase text-gray-500">Assinaturas ativas</div>
                        <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $stats['subscriptions_active'] ?? 0 }}</div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <div class="text-xs uppercase text-gray-500">Assinaturas totais</div>
                        <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $stats['subscriptions'] ?? 0 }}</div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <div class="text-xs uppercase text-gray-500">MRR</div>
                        <div class="mt-2 text-2xl font-semibold text-gray-900">{{ toMoney($stats['mrr'] ?? 0, 'R$ ') }}</div>
                    </div>
                </div>

                <div class="bg-white border rounded-sm shadow px-4 py-4 w-full">
                    <div class="text-sm font-semibold text-gray-800 uppercase border-b pb-2 mb-3">
                        <div class="flex justify-between items-center">
                            <div>Receita Diaria (Ultimos 30 Dias)</div>
                            <div>{{ now()->subDays(30)->format('d/m/Y') }} ate {{ now()->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div class="w-full" style="position: relative; height: 280px;">
                        <canvas id="revenueChart" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>

                <div class="bg-white border rounded-sm shadow px-4 py-4 w-full">
                    <div class="text-sm font-semibold text-gray-800 uppercase border-b pb-2 mb-3">
                        Adesoes Diarias (Ultimos 30 Dias)
                    </div>
                    <div class="w-full" style="position: relative; height: 280px;">
                        <canvas id="subscriptionsChart" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>
            </div>
        @endif

        @if($activeTab === 'detalhes')
            <div wire:key="tab-detalhes" class="space-y-6">
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informacoes Basicas
                            </h3>
                            <div class="relative inline-block" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
                                <button
                                    type="button"
                                    @click.stop="open = ! open"
                                    class="inline-flex items-center justify-center p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 min-w-[36px] min-h-[36px]"
                                    aria-label="Menu de opcoes"
                                    aria-expanded="false"
                                    x-bind:aria-expanded="open"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
                                </button>
                                <div x-show="open"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-50 mt-2 w-52 rounded-md shadow-lg origin-top-right right-0"
                                    style="display: none;"
                                    @click.stop="open = false">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                        <a
                                            href="{{ route('dashboard-assinaturas-editar', ['product_id' => $product->id]) }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Editar assinatura
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Nome da Assinatura</label>
                                <div class="text-base font-bold text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    {{ $product->name }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Nome Curto</label>
                                <div class="text-base text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    {{ $product->name_short ?: '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Slug (URL)</label>
                                <div class="text-base font-mono text-emerald-700 bg-emerald-50 px-4 py-3 rounded-lg border border-emerald-200">
                                    {{ $product->slug }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Visibilidade</label>
                                <div class="text-base font-semibold px-4 py-3 rounded-lg border {{ $product->visibility_public ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                    {{ $product->visibility_public ? 'Publico' : 'Privado' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Empresa</label>
                                <div class="text-base text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    {{ $product->customer->name_corporate ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Organizador</label>
                                <div class="text-base text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    {{ $product->organizer->organizer_name ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Periodo</label>
                                <div class="text-base text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    {{ $product->datetime_start ? \Carbon\Carbon::parse($product->datetime_start)->format('d/m/Y H:i') : 'Sem inicio' }}
                                    -
                                    {{ $product->datetime_finish ? \Carbon\Carbon::parse($product->datetime_finish)->format('d/m/Y H:i') : 'Sem limite' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Valor Minimo</label>
                                <div class="text-base text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    {{ $product->amount_min ? toMoney($product->amount_min, 'R$ ') : '-' }}
                                </div>
                            </div>
                            <div class="col-span-full"><hr></div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Criado em</label>
                                <div class="text-base text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    {{ optional($product->created_at)->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Atualizado em</label>
                                <div class="text-base text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                    {{ optional($product->updated_at)->format('d/m/Y H:i') }}
                                </div>
                            </div>

                            <div class="col-span-full">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Descricao</label>
                                <div class="prose prose-sm max-w-none bg-gray-50 px-4 py-3 rounded-lg border min-h-[100px]">
                                    @if($product->description)
                                        {!! $product->description !!}
                                    @else
                                        <span class="text-gray-400 italic">Sem descricao</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-span-full">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-3">Sobre (Detalhes)</label>
                                <div class="prose prose-sm max-w-none bg-gray-50 px-4 py-3 rounded-lg border min-h-[100px]">
                                    @if($product->about)
                                        {!! $product->about !!}
                                    @else
                                        <span class="text-gray-400 italic">Sem informacoes detalhadas</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(
                    $product->color_primary ||
                    $product->color_secondary ||
                    $product->url_image_logo ||
                    $product->url_image_bg ||
                    $product->url_image_banner ||
                    $product->url_image_thumb ||
                    $product->metadata
                )
                    <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4m0 0l4 4m-4-4v12M20 4h-6a2 2 0 00-2 2v12a2 2 0 002 2h6"></path>
                                    </svg>
                                    Identidade e Midia
                                </h3>
                                <div class="relative inline-block" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
                                    <button
                                        type="button"
                                        @click.stop="open = ! open"
                                        class="inline-flex items-center justify-center p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 min-w-[36px] min-h-[36px]"
                                        aria-label="Menu de opcoes"
                                        aria-expanded="false"
                                        x-bind:aria-expanded="open"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                        </svg>
                                    </button>
                                    <div x-show="open"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute z-50 mt-2 w-52 rounded-md shadow-lg origin-top-right right-0"
                                        style="display: none;"
                                        @click.stop="open = false">
                                        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                            <div class="px-4 py-2 text-xs text-gray-500">Sem acoes no momento</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                <div>
                                    <div class="text-[10px] font-semibold text-gray-500 uppercase">Cor primaria</div>
                                    <div class="flex items-center gap-2 text-base text-gray-900">
                                        <span class="inline-flex w-4 h-4 rounded-full border border-gray-200" style="background: {{ $product->color_primary ?? '#ffffff' }}"></span>
                                        <span>{{ $product->color_primary ?? '-' }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-semibold text-gray-500 uppercase">Cor secundaria</div>
                                    <div class="flex items-center gap-2 text-base text-gray-900">
                                        <span class="inline-flex w-4 h-4 rounded-full border border-gray-200" style="background: {{ $product->color_secondary ?? '#ffffff' }}"></span>
                                        <span>{{ $product->color_secondary ?? '-' }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-semibold text-gray-500 uppercase">Logo</div>
                                    <div class="text-base text-gray-900">{{ $product->url_image_logo ?? '-' }}</div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-semibold text-gray-500 uppercase">Background</div>
                                    <div class="text-base text-gray-900">{{ $product->url_image_bg ?? '-' }}</div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-semibold text-gray-500 uppercase">Banner</div>
                                    <div class="text-base text-gray-900">{{ $product->url_image_banner ?? '-' }}</div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-semibold text-gray-500 uppercase">Thumbnail</div>
                                    <div class="text-base text-gray-900">{{ $product->url_image_thumb ?? '-' }}</div>
                                </div>
                            </div>
                            @if($product->metadata)
                                <div class="mt-4">
                                    <div class="text-[10px] font-semibold text-gray-500 uppercase">Metadata</div>
                                    <pre class="mt-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded p-3 overflow-x-auto">{{ json_encode($product->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        @endif

        @if($activeTab === 'planos')
            <div wire:key="tab-planos" class="space-y-6">
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 8H7a2 2 0 01-2-2V6a2 2 0 012-2h6l6 6v8a2 2 0 01-2 2z"></path>
                                </svg>
                                Planos
                            </h3>
                            <div class="relative inline-block" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
                                <button
                                    type="button"
                                    @click.stop="open = ! open"
                                    class="inline-flex items-center justify-center p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 min-w-[36px] min-h-[36px]"
                                    aria-label="Menu de opcoes"
                                    aria-expanded="false"
                                    x-bind:aria-expanded="open"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
                                </button>
                                <div x-show="open"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-50 mt-2 w-52 rounded-md shadow-lg origin-top-right right-0"
                                    style="display: none;"
                                    @click.stop="open = false">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                        <a
                                            href="{{ route('dashboard-assinaturas-planos-novo', ['product_id' => $product->id]) }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Novo plano
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @if(($plans ?? collect())->isEmpty())
                            <div class="flex flex-col items-center justify-center text-center py-10">
                                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 8H7a2 2 0 01-2-2V6a2 2 0 012-2h6l6 6v8a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="text-base font-semibold text-gray-800">Ainda nao existem planos</div>
                                <div class="text-sm text-gray-500 mt-1 max-w-md">
                                    Crie o primeiro plano para deixar a assinatura pronta para receber adesoes.
                                </div>
                                <a
                                    href="{{ route('dashboard-assinaturas-planos-novo', ['product_id' => $product->id]) }}"
                                    class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded transition"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Criar plano
                                </a>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($plans as $plan)
                                    @php
                                        $planStatusClass = $plan->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <div class="bg-white border rounded-lg shadow-sm px-4 md:px-6 py-4">
                                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                            <div>
                                                <div class="text-lg font-semibold text-gray-900">{{ $plan->plan_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $plan->plan_code ?? 'Sem codigo' }}</div>
                                                @if($plan->description)
                                                    <div class="mt-2 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit(strip_tags($plan->description), 160) }}</div>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $planStatusClass }}">
                                                    {{ strtoupper($plan->status ?? 'active') }}
                                                </span>
                                                <a
                                                    href="{{ route('dashboard-assinaturas-planos-editar', ['product_id' => $product->id, 'plan_id' => $plan->id]) }}"
                                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded hover:bg-emerald-100"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Alterar
                                                </a>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4 text-sm text-gray-700">
                                            <div>
                                                <div class="text-[10px] font-semibold text-gray-500 uppercase">Valor</div>
                                                <div class="text-base text-gray-900">{{ toMoney($plan->amount ?? 0, 'R$ ') }}</div>
                                            </div>
                                            <div>
                                                <div class="text-[10px] font-semibold text-gray-500 uppercase">Intervalo</div>
                                                <div class="text-base text-gray-900">{{ $plan->interval_count }} {{ $plan->interval_unit }}</div>
                                            </div>
                                            <div>
                                                <div class="text-[10px] font-semibold text-gray-500 uppercase">Trial</div>
                                                <div class="text-base text-gray-900">{{ $plan->trial_days ?? 0 }} dia(s)</div>
                                            </div>
                                            <div>
                                                <div class="text-[10px] font-semibold text-gray-500 uppercase">Padrao</div>
                                                <div class="text-base text-gray-900">{{ $plan->is_default ? 'Sim' : 'Nao' }}</div>
                                            </div>
                                        </div>
                                        @php
                                            $planGatewayLabel = data_get($gatewayMap, ($plan->pay_gateway_id ?? '') . '.label');
                                            $planGatewayDescription = data_get($gatewayMap, ($plan->pay_gateway_id ?? '') . '.description');
                                            $planInstallmentFeeLabel = '-';

                                            if ($plan->pay_card_credit_installment_fee_payer === 'customer') {
                                                $planInstallmentFeeLabel = 'Cliente';
                                            } elseif ($plan->pay_card_credit_installment_fee_payer === 'merchant') {
                                                $planInstallmentFeeLabel = 'Empresa';
                                            }

                                            $planHasPayment =
                                                ($plan->pay_gateway_id ?? false) ||
                                                ($plan->pay_pix ?? false) ||
                                                ($plan->pay_boleto ?? false) ||
                                                ($plan->pay_card_credit ?? false);
                                        @endphp
                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-gray-700">
                                                <div>
                                                    <div class="text-[10px] font-semibold text-gray-500 uppercase mb-2">Gateway Configurado</div>
                                                    <div class="text-sm font-bold text-gray-900">
                                                        {{ $planGatewayLabel ?? 'Nao configurado' }}
                                                    </div>
                                                    @if($planGatewayDescription)
                                                        <div class="text-[10px] text-gray-500">{{ $planGatewayDescription }}</div>
                                                    @endif
                                                    @if($plan->pay_sandbox)
                                                        <div class="text-[10px] mt-3">
                                                            <span class="px-2 py-1 rounded text-white bg-orange-500">MODO TESTE</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-[10px] font-semibold text-gray-500 uppercase mb-2">Formas de Pagamento</div>
                                                    <div class="space-y-2">
                                                        <div class="flex items-center gap-2 text-xs {{ $plan->pay_pix ? '' : 'opacity-40' }}">
                                                            <svg class="w-4 h-4 {{ $plan->pay_pix ? 'text-emerald-600' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                @if($plan->pay_pix)
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                @else
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                                @endif
                                                            </svg>
                                                            <span class="font-medium {{ $plan->pay_pix ? '' : 'line-through' }}">PIX</span>
                                                        </div>
                                                        <div class="flex items-center gap-2 text-xs {{ $plan->pay_boleto ? '' : 'opacity-40' }}">
                                                            <svg class="w-4 h-4 {{ $plan->pay_boleto ? 'text-emerald-600' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                @if($plan->pay_boleto)
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                @else
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                                @endif
                                                            </svg>
                                                            <span class="font-medium {{ $plan->pay_boleto ? '' : 'line-through' }}">Boleto Bancario</span>
                                                        </div>
                                                        <div class="flex items-center justify-between text-xs {{ $plan->pay_card_credit ? '' : 'opacity-40' }}">
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <svg class="w-4 h-4 {{ $plan->pay_card_credit ? 'text-emerald-600' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                    @if($plan->pay_card_credit)
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                    @else
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                                    @endif
                                                                </svg>
                                                                <span class="font-medium {{ $plan->pay_card_credit ? '' : 'line-through' }}">Cartao de Credito</span>
                                                                @if($plan->pay_card_credit && $plan->pay_card_credit_installment_max > 1)
                                                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                                                                        Ate {{ $plan->pay_card_credit_installment_max }}x
                                                                    </span>
                                                                    <span class="text-[9px] px-2 py-0.5 rounded {{ $plan->pay_card_credit_installment_fee_payer === 'customer' ? 'bg-orange-50 text-orange-700' : 'bg-emerald-50 text-emerald-700' }}">
                                                                        Juros: {{ $planInstallmentFeeLabel }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-[10px] font-semibold text-gray-500 uppercase mb-2">Parcelamento</div>
                                                    <div class="text-sm text-gray-900 space-y-2">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-gray-500">Maximo de parcelas</span>
                                                            <span class="font-semibold">{{ $plan->pay_card_credit_installment_max ?? '-' }}</span>
                                                        </div>
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-gray-500">Pagador dos juros</span>
                                                            <span class="font-semibold">{{ $planInstallmentFeeLabel }}</span>
                                                        </div>
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-gray-500">Valor minimo</span>
                                                            <span class="font-semibold">{{ $plan->pay_card_credit_installment_amount_min ? toMoney($plan->pay_card_credit_installment_amount_min, 'R$ ') : '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(! $planHasPayment)
                                                <div class="text-xs text-gray-500 mt-2">Nenhuma forma de pagamento ativa para este plano.</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if($activeTab === 'adesoes')
            <div wire:key="tab-adesoes" class="space-y-6">
                <div class="bg-white border rounded-sm shadow px-4 md:px-6 py-4">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Adesoes recentes</h3>
                    @if(($subscriptions ?? collect())->isEmpty())
                        <div class="text-sm text-gray-500">Nenhuma adesao registrada.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-gray-700">
                                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Assinante</th>
                                        <th class="px-4 py-2 text-left">Plano</th>
                                        <th class="px-4 py-2 text-left">Tipo</th>
                                        <th class="px-4 py-2 text-left">Status</th>
                                        <th class="px-4 py-2 text-left">Valor</th>
                                        <th class="px-4 py-2 text-left">Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subscriptions as $subscription)
                                        @php
                                            $meta = $subscription->metadata ?? [];
                                            $adhesionLabel = 'CONTRATADA';
                                            $adhesionClass = 'text-green-600';

                                            if ($subscription->canceled_at || $subscription->status === 'cancelled') {
                                                $adhesionLabel = 'CANCELADA';
                                                $adhesionClass = 'text-red-600';
                                            } elseif (
                                                data_get($meta, 'previous_plan_id') ||
                                                data_get($meta, 'plan_changed_at') ||
                                                data_get($meta, 'plan_change')
                                            ) {
                                                $adhesionLabel = 'ALTERADA';
                                                $adhesionClass = 'text-orange-600';
                                            }

                                            $statusClass = $subscription->status === 'active' ? 'text-green-600' : 'text-gray-600';
                                        @endphp
                                        <tr class="border-b border-gray-100">
                                            <td class="px-4 py-3 text-gray-900 font-mono">{{ $subscription->buyer_id ?? $subscription->id }}</td>
                                            <td class="px-4 py-3 text-gray-900">{{ $subscription->plan->plan_name ?? '-' }}</td>
                                            <td class="px-4 py-3 font-semibold {{ $adhesionClass }}">{{ $adhesionLabel }}</td>
                                            <td class="px-4 py-3 font-semibold {{ $statusClass }}">{{ strtoupper($subscription->status ?? '-') }}</td>
                                            <td class="px-4 py-3">{{ toMoney($subscription->amount_total ?? 0, 'R$ ') }}</td>
                                            <td class="px-4 py-3">{{ optional($subscription->created_at)->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if($activeTab === 'transacoes')
            <div wire:key="tab-transacoes" class="space-y-6">
                @php
                    $filteredTransactions = $this->getFilteredTransactions();
                @endphp

                @if(!$selectedTransactionId)
                    <div class="bg-white border rounded-lg shadow-md overflow-hidden mb-4">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 8H7a2 2 0 01-2-2V6a2 2 0 012-2h6l6 6v8a2 2 0 01-2 2z"></path>
                                </svg>
                                Pagamentos por Plano
                            </h3>
                        </div>
                        <div class="p-6 text-sm text-gray-600">
                            As formas de pagamento e o gateway sao definidos em cada plano. Para ver o plano aplicado,
                            selecione uma transacao na lista.
                        </div>
                    </div>

                    <div class="bg-white border rounded-sm shadow px-4 md:px-6 py-4 mb-4">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Transações ({{ count($filteredTransactions) }})</h3>
                            <div class="flex gap-2">
                                <button
                                    wire:click="refreshTransacoes"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Atualizar
                                </button>
                                <a href="{{ route('dashboard-assinaturas-detalhes', ['product_id' => $product->id]) }}?export=transacoes"
                                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Exportar CSV
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status</label>
                                <select wire:model="filterTransactionStatus" class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option value="">Todos</option>
                                    <option value="paid">Pago</option>
                                    <option value="pending">Pendente</option>
                                    <option value="processing">Processando</option>
                                    <option value="error">Erro</option>
                                    <option value="cancelled">Cancelado</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data Início</label>
                                <input type="date" wire:model="filterTransactionDateFrom" class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Data Fim</label>
                                <input type="date" wire:model="filterTransactionDateTo" class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Buscar</label>
                                <input type="text" wire:model="filterTransactionSearch" placeholder="Assinante, Plano, ID..." class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">Data/Hora</th>
                                        <th class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">Assinante</th>
                                        <th class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">Plano</th>
                                        <th class="text-left px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">Ciclo</th>
                                        <th class="text-center px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">Valor</th>
                                        <th class="text-center px-4 py-3 text-[10px] font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($filteredTransactions as $transaction)
                                        @php
                                            $statusClass = match($transaction->status) {
                                                'paid' => 'bg-green-100 text-green-700',
                                                'processing' => 'bg-blue-100 text-blue-700',
                                                'pending' => 'bg-orange-100 text-orange-700',
                                                'error', 'failed', 'cancelled' => 'bg-red-100 text-red-700',
                                                default => 'bg-gray-100 text-gray-700'
                                            };
                                            $statusLabel = strtoupper($transaction->status ?? 'N/D');
                                        @endphp
                                        <tr class="hover:bg-gray-50 cursor-pointer" wire:key="transaction-{{ $transaction->id }}" wire:click="selectTransaction('{{ $transaction->id }}')">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-xs font-semibold text-gray-900">{{ optional($transaction->created_at)->format('d/m/Y') }}</div>
                                                <div class="text-[10px] text-gray-500">{{ optional($transaction->created_at)->format('H:i:s') }}</div>
                                                <div class="text-[10px] text-gray-500">Cobrança: {{ $transaction->billing_date ? $transaction->billing_date->format('d/m/Y') : '-' }}</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-xs font-semibold text-gray-900 font-mono">{{ optional($transaction->subscription)->buyer_id ?? '-' }}</div>
                                                <div class="text-[10px] text-gray-500">{{ substr($transaction->subscription_id ?? $transaction->id, 0, 8) }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-900">{{ optional($transaction->subscription->plan)->plan_name ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-900">#{{ $transaction->cycle_number ?? '-' }}</td>
                                            <td class="text-center px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                {{ toMoney(optional($transaction->subscription)->amount_total ?? 0, 'R$ ') }}
                                            </td>
                                            <td class="text-center px-4 py-3 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $statusClass }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                                Nenhuma transação encontrada com os filtros aplicados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($selectedTransaction)
                    <div class="space-y-4">
                        <button
                            wire:click="closeTransactionDetails"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Voltar para Transações
                        </button>

                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="p-6 bg-gradient-to-r from-emerald-50 to-emerald-100 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Transação</h3>
                                        <p class="text-sm text-gray-600 mt-1">ID: {{ $selectedTransaction->id }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-3xl font-bold text-emerald-600">{{ toMoney(optional($selectedTransaction->subscription)->amount_total ?? 0, 'R$ ') }}</div>
                                        @php
                                            $txStatusClass = match($selectedTransaction->status) {
                                                'paid' => 'bg-green-100 text-green-700',
                                                'processing' => 'bg-blue-100 text-blue-700',
                                                'pending' => 'bg-orange-100 text-orange-700',
                                                'error', 'failed', 'cancelled' => 'bg-red-100 text-red-700',
                                                default => 'bg-gray-100 text-gray-700'
                                            };
                                        @endphp
                                        <span class="inline-block mt-2 px-3 py-1 text-sm font-semibold rounded {{ $txStatusClass }}">
                                            {{ strtoupper($selectedTransaction->status ?? '-') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <h4 class="text-sm font-bold text-gray-800 uppercase border-b pb-2">Dados da Transação</h4>
                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Ciclo</div>
                                            <div class="text-sm text-gray-900">#{{ $selectedTransaction->cycle_number ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Data de cobrança</div>
                                            <div class="text-sm text-gray-900">{{ $selectedTransaction->billing_date ? $selectedTransaction->billing_date->format('d/m/Y') : '-' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Pago em</div>
                                            <div class="text-sm text-gray-900">{{ $selectedTransaction->paid_at ? $selectedTransaction->paid_at->format('d/m/Y H:i:s') : '-' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Tentativas</div>
                                            <div class="text-sm text-gray-900">{{ $selectedTransaction->attempts_count ?? 0 }}</div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Erro</div>
                                            <div class="text-sm text-gray-900">{{ $selectedTransaction->error_message ?? '-' }}</div>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <h4 class="text-sm font-bold text-gray-800 uppercase border-b pb-2">Dados da Assinatura</h4>
                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Assinante</div>
                                            <div class="text-sm text-gray-900 font-mono">{{ optional($selectedTransaction->subscription)->buyer_id ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Plano</div>
                                            <div class="text-sm text-gray-900">{{ optional($selectedTransaction->subscription->plan)->plan_name ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Status</div>
                                            <div class="text-sm text-gray-900">{{ strtoupper(optional($selectedTransaction->subscription)->status ?? '-') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Próxima cobrança</div>
                                            <div class="text-sm text-gray-900">{{ optional(optional($selectedTransaction->subscription)->next_charge_at)->format('d/m/Y') ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-semibold text-gray-500 uppercase">Última cobrança</div>
                                            <div class="text-sm text-gray-900">{{ optional(optional($selectedTransaction->subscription)->last_charge_at)->format('d/m/Y') ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    let revenueChartInstance = null;
    let subscriptionsChartInstance = null;

    function initSubscriptionCharts() {
        const chartDataRaw = @json($chartData);
        const chartData = {
            ...chartDataRaw,
            revenue: (chartDataRaw.revenue || []).map(value => value / 100)
        };

        const revenueCanvas = document.getElementById('revenueChart');
        const subscriptionsCanvas = document.getElementById('subscriptionsChart');

        if (!revenueCanvas || !subscriptionsCanvas) {
            return;
        }

        if (revenueChartInstance) {
            revenueChartInstance.destroy();
            revenueChartInstance = null;
        }
        if (subscriptionsChartInstance) {
            subscriptionsChartInstance.destroy();
            subscriptionsChartInstance = null;
        }

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

        const revenueCtx = revenueCanvas.getContext('2d');
        revenueChartInstance = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: chartData.labels || [],
                datasets: [{
                    label: 'Receita (R$)',
                    data: chartData.revenue || [],
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.12)',
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
                }
            }
        });

        const subscriptionsCtx = subscriptionsCanvas.getContext('2d');
        subscriptionsChartInstance = new Chart(subscriptionsCtx, {
            type: 'bar',
            data: {
                labels: chartData.labels || [],
                datasets: [
                    {
                        label: 'Novas assinaturas',
                        data: chartData.new || [],
                        backgroundColor: 'rgba(59, 130, 246, 0.75)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    },
                    {
                        label: 'Cancelamentos',
                        data: chartData.canceled || [],
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgb(239, 68, 68)',
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

    document.addEventListener('DOMContentLoaded', function() {
        initSubscriptionCharts();
    });

    document.addEventListener('livewire:load', function () {
        Livewire.hook('message.processed', () => {
            setTimeout(() => {
                initSubscriptionCharts();
            }, 100);
        });
    });
</script>
