<div class="w-full max-w-7xl mx-auto mb-10" x-data="{
    interval: @entangle('pollInterval'),
    countdown: Number(@js((int) $pollInterval)) > 0 ? Number(@js((int) $pollInterval)) : 0,
    modalOpen: @entangle('showOrderModal'),
    intervalMenuOpen: false,
    timer: null,
    init() {
        this.restartTimer();
        this.$watch('interval', () => {
            const intervalValue = Number(this.interval) || 0;
            this.countdown = intervalValue > 0 ? intervalValue : 0;
        });
    },
    restartTimer() {
        if (this.timer) clearInterval(this.timer);
        this.timer = setInterval(() => {
            if (this.modalOpen) return;

            const intervalValue = Number(this.interval) || 0;
            if (intervalValue <= 0) return;

            if (this.countdown <= 1) {
                this.countdown = intervalValue;
                $wire.refreshOrders();
            } else {
                this.countdown--;
            }
        }, 1000);
    }
}" x-init="init()">

    @php
        $statusLabels = [
            'paid' => 'Pago',
            'pago' => 'Pago',
            'paid_cupom_full' => 'Pago com Cupom',
            'sucesso' => 'Sucesso',
            'pagamento_ok' => 'Pagamento OK',
            'pagamento_realizado' => 'Pagamento Realizado',
            'pago_parcial' => 'Pago Parcial',
            'pending' => 'Pendente',
            'pendente' => 'Pendente',
            'pending_pix' => 'Pendente PIX',
            'canceled' => 'Cancelado',
            'cancelado' => 'Cancelado',
            'cancelado_no_pagamento' => 'Cancelado no Pagamento',
            'expired_order' => 'Pedido Expirado',
        ];

        $payTypeLabels = [
            'credit_card' => 'Cartao de Credito',
            'CREDIT_CARD' => 'Cartao de Credito',
            'debit_card' => 'Cartao de Debito',
            'DEBIT_CARD' => 'Cartao de Debito',
            'boleto' => 'Boleto',
            'BOLETO' => 'Boleto',
            'pix' => 'PIX',
            'PIX' => 'PIX',
            'slip_pix' => 'PIX Parcelado',
        ];

        $chipClassMap = [
            'Cliente' => 'bg-blue-50 text-blue-700 border-blue-200',
            'Situacao' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'Pagamento' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'Busca' => 'bg-amber-50 text-amber-700 border-amber-200',
            'Data' => 'bg-violet-50 text-violet-700 border-violet-200',
            'Registros' => 'bg-slate-100 text-slate-700 border-slate-200',
            'Filial' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
            'Organizador' => 'bg-rose-50 text-rose-700 border-rose-200',
        ];

        $filterDateLabel = $filterDate ? date('d/m/Y', strtotime($filterDate)) : null;

        $customerLabel = collect($customers ?? [])->firstWhere('id', $customerId)->name_corporate ?? null;
        $organizationLabel =
            collect($organizations ?? [])->firstWhere('id', $organizationId)->organization_name ?? null;
        $organizerLabel = collect($organizers ?? [])->firstWhere('id', $organizerId)->organizer_name_full ?? null;

        $activeParams = [];

        if ($filterSearch) {
            $activeParams[] = ['label' => 'Busca', 'value' => $filterSearch, 'class' => $chipClassMap['Busca']];
        }

        if ($filterStatus) {
            $statusKey = (string) $filterStatus;
            $activeParams[] = [
                'label' => 'Situacao',
                'value' => $statusLabels[$statusKey] ?? $statusKey,
                'class' => $chipClassMap['Situacao'],
            ];
        }

        if ($filterPayType) {
            $payTypeKey = (string) $filterPayType;
            $activeParams[] = [
                'label' => 'Pagamento',
                'value' => $payTypeLabels[$payTypeKey] ?? $payTypeKey,
                'class' => $chipClassMap['Pagamento'],
            ];
        }

        if ($filterDateLabel) {
            $activeParams[] = ['label' => 'Data', 'value' => $filterDateLabel, 'class' => $chipClassMap['Data']];

            $activeParams[] = [
                'label' => 'Registros',
                'value' => $filterRows === 'all' ? 'TODOS' : $filterRows,
                'class' => $chipClassMap['Registros'],
            ];
        }

        if ($customerLabel) {
            $activeParams[] = ['label' => 'Cliente', 'value' => $customerLabel, 'class' => $chipClassMap['Cliente']];
        }

        if ($organizationLabel) {
            $activeParams[] = ['label' => 'Filial', 'value' => $organizationLabel, 'class' => $chipClassMap['Filial']];
        }

        if ($organizerLabel) {
            $activeParams[] = [
                'label' => 'Organizador',
                'value' => $organizerLabel,
                'class' => $chipClassMap['Organizador'],
            ];
        }

        // Prioriza os 3 parametros mais relevantes e resume o restante.
        $priorityOrder = ['Cliente', 'Situacao', 'Pagamento'];
        $orderedActiveParams = [];

        foreach ($priorityOrder as $priorityLabel) {
            foreach ($activeParams as $param) {
                if ($param['label'] === $priorityLabel) {
                    $orderedActiveParams[] = $param;
                }
            }
        }

        foreach ($activeParams as $param) {
            if (!in_array($param['label'], $priorityOrder, true)) {
                $orderedActiveParams[] = $param;
            }
        }

        $primaryParams = array_slice($orderedActiveParams, 0, 3);
        $extraParamsCount = max(count($orderedActiveParams) - 3, 0);
    @endphp

    {{-- HEADER MODERNO COM GRADIENTE --}}
    <div
        class="mb-6 bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 rounded-lg relative overflow-hidden shadow-md">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern-vendas" width="8" height="8" patternUnits="userSpaceOnUse">
                        <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern-vendas)" />
            </svg>
        </div>
        <div class="relative z-10 p-4">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-3">
                <div class="flex-1">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Últimas Vendas de Eventos</h1>
                            <p class="text-white/90 text-sm">Monitoramento em tempo real das vendas dos eventos</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block" wire:loading.remove wire:target="refreshOrders">
                        <div class="text-[11px] font-semibold uppercase tracking-wide text-white/80">Atualizacao</div>
                        <div class="text-sm font-semibold text-white">{{ $lastRefreshAt ?: '--:--:--' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <x-jet-validation-errors />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-4 items-stretch">
        <div class="md:col-span-4 bg-white rounded-lg border border-gray-200 shadow-sm px-4 py-3">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <div class="text-[11px] uppercase tracking-wide text-gray-500 font-semibold">Auto Refresh</div>
                    <div class="mt-1 flex items-end gap-2">
                        <div class="text-2xl leading-none font-bold text-gray-900"
                            x-text="Number(interval) > 0 ? `${countdown}s` : 'OFF'"></div>
                        <div class="text-xs text-gray-500 font-medium mb-0.5"
                            x-text="Number(interval) > 0 ? 'para atualizar' : 'loop desligado'"></div>
                    </div>
                </div>
                <div class="text-right min-w-[96px]">
                    <div class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold mb-1">Intervalo</div>
                    <div class="relative" x-on:click.outside="intervalMenuOpen = false">
                        <button type="button"
                            class="w-full h-8 inline-flex items-center justify-between gap-2 rounded-full border border-gray-300 bg-gray-50 px-3 text-xs font-semibold text-gray-700 shadow-sm hover:bg-white transition-colors"
                            x-on:click="intervalMenuOpen = !intervalMenuOpen"
                            aria-label="Selecionar intervalo de auto refresh">
                            <span x-text="Number(interval) > 0 ? `${interval}s` : 'OFF'"></span>
                            <x-icon name="chevron-down" class="w-4 h-4 text-gray-500" />
                        </button>

                        <div x-show="intervalMenuOpen" x-transition.origin.top.right
                            class="absolute right-0 z-30 mt-2 w-24 rounded-xl border border-gray-200 bg-white p-1 shadow-lg">
                            <button type="button"
                                class="w-full rounded-lg px-2 py-1.5 text-left text-xs font-semibold transition-colors"
                                x-bind:class="Number(interval) === 0 ? 'bg-red-50 text-red-700' : 'text-gray-700 hover:bg-gray-50'"
                                x-on:click="$wire.set('pollInterval', 0); countdown = 0; interval = 0; intervalMenuOpen = false;">
                                OFF
                            </button>
                            <button type="button"
                                class="w-full rounded-lg px-2 py-1.5 text-left text-xs font-semibold transition-colors"
                                x-bind:class="Number(interval) === 10 ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
                                x-on:click="$wire.set('pollInterval', 10); countdown = 10; interval = 10; intervalMenuOpen = false;">
                                10s
                            </button>
                            <button type="button"
                                class="w-full rounded-lg px-2 py-1.5 text-left text-xs font-semibold transition-colors"
                                x-bind:class="Number(interval) === 20 ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
                                x-on:click="$wire.set('pollInterval', 20); countdown = 20; interval = 20; intervalMenuOpen = false;">
                                20s
                            </button>
                            <button type="button"
                                class="w-full rounded-lg px-2 py-1.5 text-left text-xs font-semibold transition-colors"
                                x-bind:class="Number(interval) === 30 ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
                                x-on:click="$wire.set('pollInterval', 30); countdown = 30; interval = 30; intervalMenuOpen = false;">
                                30s
                            </button>
                            <button type="button"
                                class="w-full rounded-lg px-2 py-1.5 text-left text-xs font-semibold transition-colors"
                                x-bind:class="Number(interval) === 60 ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
                                x-on:click="$wire.set('pollInterval', 60); countdown = 60; interval = 60; intervalMenuOpen = false;">
                                60s
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-8 bg-white rounded-lg border border-gray-200 shadow-sm px-4 py-3">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                <div>
                    <div class="text-[11px] uppercase tracking-wide text-gray-500 font-semibold">Escopo Ativo do Grid
                    </div>
                    <div class="text-xs text-gray-500">Filtros atualmente aplicados nos resultados</div>
                </div>
                @if ($showAdvancedFilters)
                    <x-button sm flat gray icon="filter" wire:click="toggleAdvancedFilters" label="Ocultar Filtros" />
                @else
                    <x-button sm flat gray icon="filter" wire:click="toggleAdvancedFilters" label="Mostrar Filtros" />
                @endif
            </div>

            @if (count($orderedActiveParams))
                <div class="flex flex-wrap gap-2">
                    @foreach ($primaryParams as $param)
                        <span
                            class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs {{ $param['class'] }}">
                            <span class="font-semibold uppercase text-[10px]">{{ $param['label'] }}:</span>
                            <span class="font-medium">{{ $param['value'] }}</span>
                        </span>
                    @endforeach

                    @if ($extraParamsCount > 0)
                        <span
                            class="inline-flex items-center rounded-full bg-slate-100 border border-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">
                            +{{ $extraParamsCount }} filtro(s)
                        </span>
                    @endif
                </div>
            @else
                <div class="text-xs text-gray-500">Sem filtros ativos. Exibindo escopo padrao do perfil.</div>
            @endif
        </div>
    </div>

    {{-- FILTROS COMPACTOS --}}
    @if ($showAdvancedFilters)
        <div class="bg-white shadow-md border border-gray-200 rounded-lg mb-6">
            <div
                class="border-b border-gray-200 bg-gray-50 px-4 py-3 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-sm font-semibold text-gray-800">Filtros</h2>
                @if ($filterStatus || $filterPayType || $filterSearch || $filterDate)
                    <x-button sm flat gray icon="x"
                        wire:click="$set('filterStatus', ''); $set('filterPayType', ''); $set('filterSearch', ''); $set('filterDate', ''); $set('filterRows', '300')"
                        label="Limpar" />
                @endif
            </div>

            <div class="p-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                    <div class="md:col-span-4">
                        <label
                            class="text-[11px] text-gray-600 font-semibold uppercase tracking-wide mb-1 block">Busca</label>
                        <x-input wire:model.debounce.500ms="filterSearch" placeholder="Email, nome ou documento"
                            class="w-full" />
                    </div>
                    <div class="md:col-span-2">
                        <label
                            class="text-[11px] text-gray-600 font-semibold uppercase tracking-wide mb-1 block">Situacao</label>
                        <x-native-select wire:model="filterStatus" class="w-full uppercase">
                            <option value="">Todas as Situações</option>
                            <option value="paid">Pago</option>
                            <option value="pago">Pago</option>
                            <option value="paid_cupom_full">Pago com Cupom</option>
                            <option value="sucesso">Sucesso</option>
                            <option value="pagamento_ok">Pagamento OK</option>
                            <option value="pagamento_realizado">Pagamento Realizado</option>
                            <option value="pago_parcial">Pago Parcial</option>
                            <option value="pending">Pendente</option>
                            <option value="pendente">Pendente</option>
                            <option value="pending_pix">Pendente PIX</option>
                            <option value="canceled">Cancelado</option>
                            <option value="cancelado">Cancelado</option>
                            <option value="cancelado_no_pagamento">Cancelado no Pagamento</option>
                            <option value="expired_order">Pedido Expirado</option>
                        </x-native-select>
                    </div>
                    <div class="md:col-span-2">
                        <label
                            class="text-[11px] text-gray-600 font-semibold uppercase tracking-wide mb-1 block">Pagamento</label>
                        <x-native-select wire:model="filterPayType" class="w-full uppercase">
                            <option value="">Todas as Formas</option>
                            <option value="credit_card">Cartão de Crédito</option>
                            <option value="CREDIT_CARD">Cartão de Crédito</option>
                            <option value="debit_card">Cartão de Débito</option>
                            <option value="DEBIT_CARD">Cartão de Débito</option>
                            <option value="boleto">Boleto</option>
                            <option value="BOLETO">Boleto</option>
                            <option value="pix">PIX</option>
                            <option value="PIX">PIX</option>
                            <option value="slip_pix">PIX Parcelado</option>
                        </x-native-select>
                    </div>
                    <div class="md:col-span-2">
                        <x-button class="w-full" wire:click="refreshOrders(true)" icon="refresh" primary
                            label="Atualizar" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end border-t border-gray-100 pt-3">
                    <div class="md:col-span-3">
                        <label
                            class="text-[11px] text-gray-600 font-semibold uppercase tracking-wide mb-1 block">Data</label>
                        <x-input type="date" wire:model="filterDate" class="w-full" />
                    </div>

                    <div class="md:col-span-3">
                        <label
                            class="text-[11px] text-gray-600 font-semibold uppercase tracking-wide mb-1 block">Registros</label>
                        @if (!$filterDate)
                            <x-native-select wire:model="filterRows" class="w-full uppercase" disabled>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="300">300</option>
                                <option value="500">500</option>
                                <option value="1000">1000</option>
                                <option value="all">TODOS</option>
                            </x-native-select>
                        @else
                            <x-native-select wire:model="filterRows" class="w-full uppercase">
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="300">300</option>
                                <option value="500">500</option>
                                <option value="1000">1000</option>
                                <option value="all">TODOS</option>
                            </x-native-select>
                        @endif
                    </div>

                    <div class="md:col-span-6">
                        @if (!$filterDate)
                            <div
                                class="h-10 flex items-center rounded-md border border-amber-200 bg-amber-50 px-3 text-xs font-medium text-amber-700">
                                Para usar quantidade de registros (incluindo TODOS), selecione primeiro uma data.
                            </div>
                        @endif
                    </div>
                </div>

                @if (count($customers ?? []) && $showAdvancedFilters)
                    <div class="border-t border-gray-200 pt-4">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                            <div class="md:col-span-4">
                                <label
                                    class="text-[11px] text-gray-600 font-semibold uppercase tracking-wide mb-1 block">Cliente</label>
                                <x-native-select wire:model="customerId" class="w-full uppercase">
                                    <option value="">TODOS</option>
                                    @foreach ($customers as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_corporate }}</option>
                                    @endforeach
                                </x-native-select>
                            </div>
                            <div class="md:col-span-4">
                                <label
                                    class="text-[11px] text-gray-600 font-semibold uppercase tracking-wide mb-1 block">Filial</label>
                                <x-native-select wire:model="organizationId" class="w-full uppercase">
                                    <option value="">--</option>
                                    @foreach ($organizations ? $organizations->sortBy('organization_name') : [] as $item)
                                        <option value="{{ $item->id }}">{{ $item->organization_name }}</option>
                                    @endforeach
                                </x-native-select>
                            </div>

                            <div class="md:col-span-4">
                                <label
                                    class="text-[11px] text-gray-600 font-semibold uppercase tracking-wide mb-1 block">
                                    @if (count($organizers ?? []))
                                        {{ count($organizers ?? []) }} ORGANIZADOR(ES)
                                    @else
                                        ORGANIZADOR
                                    @endif
                                </label>
                                <x-native-select wire:model="organizerId" class="w-full uppercase">
                                    <option value="">--</option>
                                    @foreach (collect($organizers ?? [])->sortBy('organizer_name_full') as $item)
                                        <option value="{{ $item->id }}">{{ $item->organizer_name_full }}</option>
                                    @endforeach
                                </x-native-select>
                            </div>
                        </div>

                        @if (!(count($organizers ?? []) ?? false))
                            <div
                                class="mt-3 px-3 py-2 bg-amber-50 border border-amber-200 rounded-md text-xs font-medium text-amber-800">
                                Selecione uma Empresa/Filial para listar organizadores.
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- LISTA DE VENDAS --}}
    <div class="bg-white shadow-md border border-gray-200 rounded-lg overflow-hidden">
        @if (($orders ?? collect())->isEmpty())
            <div class="p-12 text-center">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Sem pedidos para exibir</h3>
                    <p class="text-sm text-gray-500">Não há vendas registradas no momento</p>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Localizador</th>
                            <th class="px-4 py-3 text-left">Cliente</th>
                            <th class="px-4 py-3 text-left">Evento</th>
                            <th class="px-4 py-3 text-right">Valor</th>
                            <th class="px-4 py-3 text-left">Situação</th>
                            <th class="px-4 py-3 text-left">Pagamento</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($orders ?? [] as $order_item)
                            @php
                                $statusClass = 'bg-amber-100 text-amber-700';
                                if (in_array($order_item->status, listOrderStatusPaid())) {
                                    $statusClass = 'bg-green-100 text-green-700';
                                } elseif (in_array($order_item->status, listOrderStatusCancelada())) {
                                    $statusClass = 'bg-red-100 text-red-700';
                                }
                                $paymentType = optional($order_item->payments->first())->pay_type ?? '';
                                $paymentLabel = match (strtolower($paymentType)) {
                                    'credit_card',
                                    'creditcard',
                                    'cartao_credito',
                                    'cartao-credito',
                                    'cartao_de_credito'
                                        => 'Credito',
                                    'debit_card', 'debitcard', 'cartao_debito' => 'Cartao de debito',
                                    'boleto' => 'Boleto',
                                    'pix' => 'PIX',
                                    'slip_pix' => 'PIX parcelado',
                                    default => $paymentType ? strtoupper($paymentType) : '--',
                                };
                                $statusLabelRaw = (string) __($order_item->status ?? '--');
                                $statusLabelGrid =
                                    mb_strtolower($statusLabelRaw) === 'aguardando pagamento'
                                        ? 'Aguardando Pg'
                                        : $statusLabelRaw;
                                $displayValue = $order_item->order_amount_pay ?: $order_item->order_amount;
                            @endphp
                            <tr class="hover:bg-gray-50 cursor-pointer"
                                wire:click="selectOrder('{{ $order_item->id }}')">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-800 font-mono">
                                        {{ $order_item->order_control }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ dataDataHora($order_item->created_at, false, true) }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-800 uppercase">
                                        {{ $order_item->buyer_name }}</div>
                                    <div class="text-xs text-gray-500 break-all lowercase">
                                        {{ $order_item->buyer_email }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-700">{{ $order_item->event->event_name ?? '--' }}
                                    </div>
                                    <div class="text-xs text-gray-500 uppercase">
                                        {{ $order_item->event->organizer->organizer_name_full ?? '--' }}</div>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap font-semibold text-gray-800">
                                    {{ toMoney($displayValue ?? 0, 'R$ ') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusLabelGrid }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="text-xs font-semibold uppercase text-gray-700">{{ $paymentLabel }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <x-modal.card wire:model="showOrderModal" title="Detalhes da venda" max-width="6xl">
        @if ($selectedOrder)
            <div class="space-y-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase">Localizador</div>
                        <div class="text-xl font-bold font-mono text-gray-900">{{ $selectedOrder->order_control }}
                        </div>
                        <div class="text-sm text-gray-500">{{ dataDataHora($selectedOrder->created_at, false, true) }}
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-xs font-semibold text-gray-500 uppercase">Status</div>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            {{ in_array($selectedOrder->status, listOrderStatusPaid()) ? 'bg-green-100 text-green-700' : (in_array($selectedOrder->status, listOrderStatusCancelada()) ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ __($selectedOrder->status ?? '--') }}
                        </span>
                        <x-button sm primary outline icon="link"
                            href="{{ route('compra-exibir', ['localizador' => $selectedOrder->order_control]) }}"
                            target="_blank" label="Abrir" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="text-xs font-medium text-gray-500 uppercase mb-1">Organizador</div>
                        <div class="text-base font-semibold text-gray-900">
                            {{ $selectedOrder->event->organizer->organizer_name_full ?? '--' }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="text-xs font-medium text-gray-500 uppercase mb-1">Evento</div>
                        <div class="text-base font-semibold text-gray-900">
                            {{ $selectedOrder->event->event_name ?? '--' }}</div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Dados do Comprador
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <div class="text-xs font-medium text-gray-500 uppercase mb-1">
                                {{ $selectedOrder->buyer_doc_type ?? 'Documento' }}</div>
                            <div class="text-sm font-semibold text-gray-900">
                                {{ putMask($selectedOrder->buyer_doc_num, $selectedOrder->buyer_doc_type) }}</div>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <div class="text-xs font-medium text-gray-500 uppercase mb-1">Nome</div>
                            <div class="text-sm font-semibold text-gray-900">{{ $selectedOrder->buyer_name }}</div>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <div class="text-xs font-medium text-gray-500 uppercase mb-1">E-mail</div>
                            <div class="text-sm font-semibold text-gray-900 break-all">
                                {{ $selectedOrder->buyer_email }}</div>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <div class="text-xs font-medium text-gray-500 uppercase mb-1">Contato</div>
                            <div class="text-sm font-semibold text-gray-900">
                                {{ putMask($selectedOrder->buyer_contact_ddd . $selectedOrder->buyer_contact_num, 'telefone') }}
                            </div>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <div class="text-xs font-medium text-gray-500 uppercase mb-1">Data de Nascimento</div>
                            <div class="text-sm font-semibold text-gray-900">
                                {{ dataData($selectedOrder->buyer_birth_date) }}</div>
                        </div>
                        @if ($selectedOrder->code_promo_label)
                            <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                                <div class="text-xs font-medium text-amber-600 uppercase mb-1">Cupom</div>
                                <div class="text-sm font-semibold text-amber-900">
                                    {{ $selectedOrder->code_promo_label }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Valores</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <div class="text-xs font-medium text-gray-500 uppercase mb-1">Valor Total</div>
                            <div class="text-base font-bold text-gray-900">
                                {{ toMoney($selectedOrder->order_amount, 'R$ ') }}</div>
                        </div>
                        @if ($selectedOrder->code_promo_price_less > 0)
                            <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                                <div class="text-xs font-medium text-red-600 uppercase mb-1">Desconto</div>
                                <div class="text-base font-bold text-red-900">
                                    {{ toMoney($selectedOrder->code_promo_price_less, '- R$ ') }}</div>
                            </div>
                        @endif
                        <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                            <div class="text-xs font-medium text-green-600 uppercase mb-1">
                                @if ($selectedOrder->code_promo_id)
                                    Valor Final
                                @else
                                    Valor Total
                                @endif
                            </div>
                            <div class="text-base font-bold text-green-900">
                                @if ($selectedOrder->code_promo_id)
                                    {{ toMoney($selectedOrder->code_promo_price_new, 'R$ ') }}
                                @else
                                    {{ toMoney($selectedOrder->order_amount, 'R$ ') }}
                                @endif
                            </div>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                            <div class="text-xs font-medium text-blue-600 uppercase mb-1">Valor Pago</div>
                            <div class="text-base font-bold text-blue-900">
                                {{ toMoney($selectedOrder->order_amount_pay, 'R$ ') }}</div>
                        </div>
                    </div>
                </div>

                @if ($selectedOrder->payments && $selectedOrder->payments->count() > 0)
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Pagamentos</h3>
                        <div class="space-y-3">
                            @foreach ($selectedOrder->payments as $payment_item)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                                        <div class="md:col-span-3">
                                            <div class="text-xs font-medium text-gray-500 uppercase mb-1">Data /
                                                Gateway</div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ dataDataHora($payment_item->updated_at) }}
                                            </div>
                                            <div class="text-xs text-gray-600">
                                                {{ $payment_item->gateway_slug ?? '--' }}</div>
                                        </div>
                                        <div class="md:col-span-2">
                                            <div class="text-xs font-medium text-gray-500 uppercase mb-1">Parcelas
                                            </div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $payment_item->pay_installments_number }}x
                                                {{ toMoney($payment_item->pay_installment_value ?? 0, 'R$ ') }}
                                            </div>
                                        </div>
                                        <div class="md:col-span-2">
                                            <div class="text-xs font-medium text-gray-500 uppercase mb-1">Tipo</div>
                                            <div class="text-sm font-semibold text-gray-900 uppercase">
                                                {{ __($payment_item->pay_type ?? '--') }}</div>
                                        </div>
                                        <div class="md:col-span-2">
                                            <div class="text-xs font-medium text-gray-500 uppercase mb-1">NSU / Status
                                            </div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $payment_item->pay_nsu ?? '--' }}</div>
                                            <div class="text-xs text-gray-600 uppercase">
                                                {{ __($payment_item->status ?? '--') }}</div>
                                        </div>
                                        <div class="md:col-span-3">
                                            <div class="text-xs font-medium text-gray-500 uppercase mb-1">Descrição
                                            </div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $payment_item->description ?? '--' }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="border-t border-gray-200 pt-4">
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                            <div class="text-sm text-amber-800 font-medium">Não possui pagamentos registrados</div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <x-slot name="footer">
            <div class="flex justify-end">
                <x-button flat label="Fechar" wire:click="closeOrderModal" />
            </div>
        </x-slot>
    </x-modal.card>

</div>
