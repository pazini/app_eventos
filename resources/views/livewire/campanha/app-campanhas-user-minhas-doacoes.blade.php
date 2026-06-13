<div class="min-h-screen bg-white" x-data="{ openDetails: false, detailsUrl: null, detailsTitle: 'Mais Detalhes' }">

    <div class="max-w-5xl mx-auto px-4 md:px-4 py-4">
        {{-- <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Minhas doacoes</h1>
                <p class="text-gray-600">Essas são as minhas contribuicoes.</p>
            </div>

            @if ($buyer && ($buyer->name ?? false))
                <div class="text-sm text-gray-500">
                    Olá, <span class="font-medium text-gray-700 capitalize">{{ $buyer->name }}</span>
                </div>
            @endif
        </div> --}}

        @if (($orders ?? collect())->count())
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm divide-y divide-gray-200">
                @foreach ($orders as $order)
                    @php
                        $status = $order->status ?? 'desconhecido';
                        $statusLabel = strtoupper($status);
                        $statusClass = 'bg-gray-100 text-gray-700';

                        if ($status === 'paid') {
                            $statusLabel = 'PAGO';
                            $statusClass = 'bg-green-100 text-green-700';
                        } elseif ($status === 'pending') {
                            $statusLabel = 'PENDENTE';
                            $statusClass = 'bg-orange-100 text-orange-700';
                        } elseif (in_array($status, ['canceled', 'cancelled'])) {
                            $statusLabel = 'CANCELADO';
                            $statusClass = 'bg-red-100 text-red-700';
                        }

                        $detailsUrl = null;
                        if ($order->campaign) {
                            $detailsUrl = campanhaUrl(
                                $order->campaign->customer_organization_slug,
                                $order->campaign->slug,
                                $order->id,
                                $appUserUuid,
                                $appSource ?? null
                            );
                        }
                    @endphp

                    <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $order->campaign->name ?? 'Campanha nao encontrada' }}
                            </div>

                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mt-1">
                                <span>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '---' }}</span>
                                <span class="font-mono">{{ $order->order_control ?? '---' }}</span>
                                @if ($order->is_recurring ?? false)
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">RECORRENTE</span>
                                @endif
                            </div>

                            @if ($order->campaign && $order->campaign->customer)
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $order->campaign->customer->name_corporate ?? '' }}
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col items-start md:items-end gap-y-2 w-full md:w-auto">
                            <div class="text-left md:text-right">
                                <div class="text-xs text-gray-600">Valor</div>
                                <div class="text-lg md:text-xl font-bold text-green-600">
                                    {{ toMoney($order->amount_total, 'R$ ') }}
                                </div>
                            </div>

                            <div class="flex items-center gap-3 justify-between md:justify-end w-full">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>

                                @if ($detailsUrl)
                                    <button
                                        type="button"
                                        class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline"
                                        @click.prevent="detailsUrl = @js($detailsUrl); detailsTitle = 'Detalhes da doacao'; openDetails = true"
                                    >
                                        Ver detalhes
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Poxa, parece que voce ainda nao tem nenhuma doacao!</h3>
                <p class="text-gray-600">Que tal apoiar uma das nossas campanha!?</p>

                @php
                    $homeUrl = $appUserUuid
                        ? route('app-campanhas-user-home', ['appUserUuid' => $appUserUuid, 'appSource' => $appSource ?? null])
                        : route('app-campanhas-home', ['appSource' => $appSource ?? null]);
                @endphp

                <button
                    type="button"
                    class="inline-flex items-center gap-2 mt-6 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition"
                    @click.prevent="detailsUrl = @js($homeUrl); detailsTitle = 'Campanhas'; openDetails = true"
                >
                    Ver campanhas
                </button>
            </div>
        @endif
    </div>

    <div
        x-cloak
        x-show="openDetails"
        class="fixed inset-0 z-50"
        aria-modal="true"
        role="dialog"
        @keydown.escape.window="openDetails = false; detailsUrl = null; detailsTitle = 'Mais Detalhes'"
    >
        <div class="absolute inset-0 bg-black/40" @click="openDetails = false; detailsUrl = null; detailsTitle = 'Mais Detalhes'"></div>

        <div class="absolute inset-y-0 right-0 w-full max-w-3xl bg-white shadow-2xl overflow-hidden flex flex-col h-full max-h-screen"
            x-show="openDetails"
            x-transition:enter="transition transform duration-200"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition transform duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
        >
            <div
                class="flex items-center justify-between px-4 py-2 border-b"
                style="background-color: {{ appColor('primary') }}22; border-color: {{ appColor('primary') }}33;"
            >
                <div class="text-sm font-medium uppercase" style="color: {{ appColor('primary') }};" x-text="detailsTitle"></div>
                <div class="flex items-center gap-3">
                    {{-- <a
                        x-show="detailsUrl"
                        :href="detailsUrl"
                        target="_blank"
                        class="text-xs text-gray-500 hover:text-gray-700"
                    >
                        Abrir em nova aba
                    </a> --}}
                    <button
                        type="button"
                        class="text-xs font-semibold rounded px-2 py-1"
                        style="border: 1px solid {{ appColor('primary') }}55; color: {{ appColor('primary') }}; background-color: #fff;"
                        @click="openDetails = false; detailsUrl = null; detailsTitle = 'Mais Detalhes'"
                    >
                        FECHAR
                    </button>
                </div>
            </div>

            <div class="flex-1 min-h-0">
                <iframe
                    class="w-full h-full"
                    :src="detailsUrl"
                    frameborder="0"
                ></iframe>
            </div>
        </div>
    </div>

</div>
