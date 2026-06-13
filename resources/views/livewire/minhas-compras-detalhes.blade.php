<div class="min-h-screen bg-white">

    {{-- Navbar App-Version: Voltar + Nome Empresa + Sair --}}
    @if($isAppVersion)
    <div class="bg-white sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm">
        <div class="max-w-[480px] mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                {{-- Voltar --}}
                <button
                    wire:click="voltar"
                    class="flex items-center gap-1 text-sm text-gray-600 active:text-gray-900 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="font-medium">Voltar</span>
                </button>

                {{-- Nome da empresa --}}
                <span class="text-sm font-bold text-gray-900 uppercase truncate mx-3 flex-1 text-center">
                    {{ $appCustomerName ?? '' }}
                </span>

                {{-- Sair --}}
                <button
                    wire:click="sair"
                    class="px-3 py-2 text-xs border border-red-400 rounded-lg bg-white text-red-500 font-semibold active:bg-red-50 transition-colors flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Sair</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Navbar Web (oculta no modo app-version) --}}
    @if(!$isAppVersion)
    <div class="bg-white sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 md:px-10 py-3 md:py-4">
            <div class="flex items-center justify-between">
                {{-- Logo --}}
                <a href="{{ route('eventos-home') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                    <img src="{{ appLogo(true) }}" alt="{{ appName() }}" class="h-8 md:h-10">
                </a>

                {{-- Botões --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('eventos-home') }}" class="px-3 py-2 text-xs md:text-sm font-medium text-blue-600 bg-white border-2 border-blue-600 hover:bg-blue-50 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class="sm:hidden">Eventos</span>
                        <span class="hidden sm:inline">Voltar aos Eventos</span>
                    </a>

                    <button
                        wire:click="sair"
                        class="px-3 py-2 text-xs md:text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition flex items-center gap-1.5"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Sair</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <main class="max-w-7xl mx-auto px-6 md:px-8 pt-6 pb-12">

        @if($order)
            {{-- Header: Voltar + Título + Acessar Pedido --}}
            <div class="flex items-center justify-between gap-2 mb-6">
                <button
                    wire:click="voltar"
                    class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition inline-flex items-center gap-1.5 flex-shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Voltar</span>
                </button>

                <h1 class="text-base md:text-2xl lg:text-3xl font-bold text-gray-900 text-center flex-1">Detalhes da Compra</h1>

                @php
                    $isPaid = in_array($order->status, ['paid', 'approved', 'concluido']);
                    $pedidoUrl = $isPaid
                        ? config('domains.eventos') . "/pedido/{$order->order_control}"
                        : config('domains.eventos') . "/pedido/{$order->order_control}?referer=realizarPagamento";
                @endphp

                <a
                    href="{{ $pedidoUrl }}"
                    target="_blank"
                    class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition inline-flex items-center gap-1.5 flex-shrink-0"
                >
                    <span class="hidden md:inline">ACESSAR PEDIDO</span>
                    <span class="md:hidden">VER</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
            </div>

            {{-- Layout Responsivo: 2 colunas no Web, 1 no Mobile --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Card: Dados do Evento --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Dados do Evento
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($order->event)
                            @php
                                $eventImage = null;
                                if ($order->event->url_image) {
                                    $eventImage = tenantAsset($order->event->url_image, true);
                                }
                            @endphp
                            @if($eventImage)
                                <div class="mb-4">
                                    <img src="{{ $eventImage }}" alt="{{ $order->event->event_name }}" class="w-full h-64 object-cover rounded-lg">
                                </div>
                            @endif

                            @if($order->event->organizer)
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Organizado por</label>
                                    <p class="text-base font-semibold text-gray-900">{{ $order->event->organizer->organizer_name_full }}</p>
                                </div>
                            @endif

                            <div>
                                <label class="text-sm font-semibold text-gray-600">Nome do Evento</label>
                                <p class="text-lg font-bold text-gray-900">{{ $order->event->event_name }}</p>
                            </div>

                            @if($order->event->event_description)
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Descrição</label>
                                    <p class="text-gray-900">{{ $order->event->event_description }}</p>
                                </div>
                            @endif

                            @if($order->event->event_datetime_start)
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Data do Evento</label>
                                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($order->event->event_datetime_start)->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif

                            @if($order->event->event_address_full)
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Local</label>
                                    <p class="text-gray-900">{{ $order->event->event_address_full }}</p>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 italic">Informações do evento não disponíveis</p>
                        @endif
                    </div>
                </div>

                {{-- Card: Dados do Comprador --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Dados do Comprador
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Nome Completo</label>
                            <p class="text-lg font-bold text-gray-900">{{ $order->buyer_name }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-gray-600">E-mail</label>
                                <p class="text-gray-900">{{ $order->buyer_email }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-gray-600">CPF</label>
                                <p class="text-gray-900 font-mono">{{ $order->buyer_doc_num }}</p>
                            </div>
                        </div>

                        @if($order->buyer_contact_ddd && $order->buyer_contact_num)
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Telefone</label>
                                <p class="text-gray-900">({{ $order->buyer_contact_ddd }}) {{ $order->buyer_contact_num }}</p>
                            </div>
                        @endif

                        @if($order->buyer_birth_date)
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Data de Nascimento</label>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($order->buyer_birth_date)->format('d/m/Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Card: Dados da Compra --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Dados da Compra
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Número do Pedido</label>
                            <p class="text-lg font-mono font-bold text-gray-900">{{ $order->order_control }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-600">Data da Compra</label>
                            <p class="text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-600">Status do Pedido</label>
                            <div class="mt-1">
                                @php
                                    $statusConfig = [
                                        'paid' => ['label' => 'PAGO', 'color' => 'green'],
                                        'approved' => ['label' => 'APROVADO', 'color' => 'green'],
                                        'fase_pagamento' => ['label' => 'FASE DE PAGAMENTO', 'color' => 'orange'],
                                        'pending_payment' => ['label' => 'PAGAMENTO PENDENTE', 'color' => 'orange'],
                                        'pending' => ['label' => 'PENDENTE', 'color' => 'orange'],
                                        'canceled' => ['label' => 'CANCELADO', 'color' => 'red'],
                                        'expired_order' => ['label' => 'EXPIRADO', 'color' => 'red'],
                                    ];
                                    $currentStatus = $statusConfig[$order->status] ?? ['label' => strtoupper($order->status), 'color' => 'gray'];
                                @endphp
                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-{{ $currentStatus['color'] }}-100 text-{{ $currentStatus['color'] }}-700">
                                    {{ $currentStatus['label'] }}
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 border-t">
                            <label class="text-sm font-semibold text-gray-600">Valor Total</label>
                            <p class="text-3xl font-black text-green-600">{{ toMoney($order->order_amount, 'R$ ') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card: Dados do Pagamento --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Dados do Pagamento
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($order->payments && $order->payments->count() > 0)
                            @foreach($order->payments as $payment)
                                <div class="border-b pb-4 last:border-b-0 last:pb-0">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-sm font-semibold text-gray-600">Pagamento #{{ $loop->iteration }}</span>
                                        @php
                                            $paymentStatusConfig = [
                                                'paid' => ['label' => 'PAGO', 'color' => 'green'],
                                                'approved' => ['label' => 'APROVADO', 'color' => 'green'],
                                                'pending' => ['label' => 'PENDENTE', 'color' => 'orange'],
                                                'processing' => ['label' => 'PROCESSANDO', 'color' => 'blue'],
                                                'canceled' => ['label' => 'CANCELADO', 'color' => 'red'],
                                                'refused' => ['label' => 'RECUSADO', 'color' => 'red'],
                                            ];
                                            $payStatus = $paymentStatusConfig[$payment->status] ?? ['label' => strtoupper($payment->status), 'color' => 'gray'];
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-{{ $payStatus['color'] }}-100 text-{{ $payStatus['color'] }}-700">
                                            {{ $payStatus['label'] }}
                                        </span>
                                    </div>

                                    @if($payment->pay_type)
                                        <p class="text-sm text-gray-600">
                                            <span class="font-semibold">Forma:</span>
                                            {{ strtoupper($payment->pay_type) }}
                                        </p>
                                    @endif

                                    @if($payment->pay_datetime)
                                        <p class="text-sm text-gray-600">
                                            <span class="font-semibold">Data:</span>
                                            {{ \Carbon\Carbon::parse($payment->pay_datetime)->format('d/m/Y H:i') }}
                                        </p>
                                    @endif

                                    @if($payment->value_paid)
                                        <p class="text-sm text-gray-600">
                                            <span class="font-semibold">Valor:</span>
                                            <span class="font-bold text-green-600">{{ toMoney($payment->value_paid, 'R$ ') }}</span>
                                        </p>
                                    @endif

                                    @if($payment->pay_card_brand && $payment->pay_card_last)
                                        <p class="text-sm text-gray-600">
                                            <span class="font-semibold">Cartão:</span>
                                            {{ strtoupper($payment->pay_card_brand) }} •••• {{ $payment->pay_card_last }}
                                        </p>
                                    @endif

                                    @if($payment->pay_boleto_url)
                                        <a href="{{ $payment->pay_boleto_url }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:underline mt-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Ver Boleto
                                        </a>
                                    @endif

                                    @if($payment->pay_pix_qr_code_url)
                                        <div class="mt-2">
                                            <p class="text-sm font-semibold text-gray-600 mb-2">QR Code PIX:</p>
                                            <img src="{{ $payment->pay_pix_qr_code_url }}" alt="QR Code PIX" class="w-32 h-32 border rounded">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500 italic">Nenhuma informação de pagamento disponível</p>
                        @endif
                    </div>
                </div>

                {{-- Card: Ingressos/Participações --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                            {{ $order->event->event_tickets_nomenclature ?? 'Ingressos' }}
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($order->tickets && $order->tickets->count() > 0)
                            @php
                                $isPaid = in_array($order->status, ['paid', 'approved', 'concluido']);
                            @endphp

                            @foreach($order->tickets as $ticket)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <div class="flex flex-col md:flex-row justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <p class="font-bold text-gray-900 uppercase">{{ $ticket->user_name ?? $ticket->ticket_owner_name ?? 'Participante' }}</p>
                                            @if($ticket->event_ticket_name)
                                                <p class="text-sm text-gray-600 uppercase">{{ $ticket->event_ticket_name }}</p>
                                            @endif
                                        </div>

                                        @php
                                            $ticketStatusConfig = [
                                                'disponivel'   => ['label' => 'DISPONÍVEL', 'color' => 'green'],
                                                'reserva_temp' => ['label' => 'RESERVA TEMPORÁRIA', 'color' => 'yellow'],
                                                'ativo'        => ['label' => 'ATIVO', 'color' => 'green'],
                                                'usado'        => ['label' => 'USADO', 'color' => 'blue'],
                                                'cancelado'    => ['label' => 'CANCELADO', 'color' => 'red'],
                                                'expirado'     => ['label' => 'EXPIRADO', 'color' => 'red'],
                                            ];
                                            $tktStatus = $ticketStatusConfig[$ticket->ticket_status] ?? ['label' => strtoupper($ticket->ticket_status), 'color' => 'gray'];
                                        @endphp
                                        <div class="mt-1">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full shadow bg-{{ $tktStatus['color'] }}-100 text-{{ $tktStatus['color'] }}-700">
                                                {{ $tktStatus['label'] }}
                                            </span>
                                        </div>
                                    </div>

                                    @if($ticket->ticket_control)
                                        <p class="text-xs text-gray-500 font-mono mt-1">{{ $ticket->ticket_control }}</p>
                                    @endif

                                    @if($isPaid && $ticket->ticket_access_code)
                                        <div class="mt-3 pt-3 border-t">
                                            <p class="text-xs text-gray-600 mb-1">Código de Acesso</p>
                                            <p class="font-mono font-bold text-lg text-gray-900">{{ $ticket->ticket_access_code }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                            @if($isPaid && $order->order_control)
                                <div class="mt-4 pt-4 border-t">
                                    <a
                                        href="{{ route('evento-ingressos', ['order_control' => $order->order_control, 'order_id' => $order->id]) }}"
                                        target="_blank"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Ver/Baixar Todos os {{ $order->event->event_tickets_nomenclature ?? 'Ingressos' }}
                                    </a>
                                </div>
                            @elseif(!$isPaid)
                                <div class="mt-4 pt-4 border-t">
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <p class="text-sm font-semibold text-yellow-800 uppercase">{{ $order->event->event_tickets_nomenclature ?? 'Ingressos' }} bloqueada(o)</p>
                                        <p class="text-xs text-yellow-700 mt-1">Complete o pagamento para acessar</p>
                                    </div>
                                </div>
                            @endif
                        @elseif($order->itens && $order->itens->count() > 0)
                            @foreach($order->itens as $item)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <p class="font-bold text-gray-900">{{ $item->item_name ?? 'Item' }}</p>
                                    <p class="text-sm text-gray-600">Quantidade: {{ $item->item_qtd ?? 1 }}</p>
                                    @if($item->item_amount)
                                        <p class="text-sm text-gray-600">Valor: {{ toMoney($item->item_amount, 'R$ ') }}</p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500 italic">Nenhum {{ strtolower($order->event->event_tickets_nomenclature ?? 'ingresso') }} disponível</p>
                        @endif
                    </div>
                </div>

            </div>
        @endif

    </main>
</div>
