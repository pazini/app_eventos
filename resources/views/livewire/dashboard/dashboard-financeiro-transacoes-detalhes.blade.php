<div class="w-full max-w-7xl mx-auto mb-6">

    <div class="mb-3">
        <x-jet-validation-errors />
    </div>

    @if ($order ?? false && $target ?? false)

        {{-- HEADER MODERNO COM GRADIENTE --}}
        <div class="mb-4 bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern-detalhes" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-detalhes)"/>
                </svg>
            </div>
            <div class="relative z-10 p-4">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-3">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div>
                                <h1 class="text-lg font-bold text-white">Detalhamento da Compra</h1>
                                <p class="text-white/90 text-xs mt-0.5">{{ $target->event_name }} - {{ $order->order_control ?? '--' }}</p>
                                @if ($order->channel_user_id ?? false)
                                    <div class="mt-1 flex items-center space-x-1 text-white/80 text-xs">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span>Cadastrado por: {{ $order->userChannel->name ?? null }} - {{ $order->userChannel->email ?? null }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if (!in_array($order->status ?? false, listOrderStatusCancelada()))
                            <x-button negative xs label="CANCELAR" icon="ban" wire:click="cancelarOrder('{{ $order->id }}')" class="hover:bg-red-600" />
                        @endif
                        @if (hasRole(['owner', 'admin', 'operator']))
                            <x-button primary xs label="ATUALIZAR" icon="refresh" wire:click="atualizarOrder('{{ $order->id }}')" class="hover:bg-blue-600" />
                        @endif
                        <x-button flat white xs icon="reply" label="VOLTAR" wire:click="transacoesVoltar" class="bg-white/20 hover:bg-white/40" />
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD: INFORMAÇÕES DA COMPRA --}}
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-2">
                <h2 class="text-base font-semibold text-gray-800">Informações da Compra</h2>
            </div>
            <div class="p-4">
                @php
                    $infoStatusIsCanceled  = in_array($order->status ?? false, ['canceled']);
                    $infoStatusIsExpired   = in_array($order->status ?? false, ['expired_order']);
                    $infoStatusIsPaid      = in_array($order->status ?? '--', listOrderStatusPaid());
                    $infoStatusClass       = $infoStatusIsCanceled ? 'bg-red-100 text-red-800'
                                          : ($infoStatusIsExpired  ? 'bg-red-100 text-red-800'
                                          : ($infoStatusIsPaid     ? 'bg-green-100 text-green-800'
                                                                   : 'bg-yellow-100 text-yellow-800'));

                    $infoExpDate  = $order->reservation_expiration_date ?? null;
                    $infoHasExp   = (bool) $infoExpDate;
                    $infoExpPast  = $infoHasExp && $infoExpDate->format('YmdHi') < now()->format('YmdHi');
                @endphp
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

                    {{-- SUB-CARD: LOCALIZADOR --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 flex flex-col gap-1">
                        <div class="flex items-center gap-1.5 text-xs font-medium text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            Localizador
                        </div>
                        <div class="text-base font-bold text-gray-900 tracking-wide">{{ $order->order_control ?? '--' }}</div>
                    </div>

                    {{-- SUB-CARD: DATA DA COMPRA --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 flex flex-col gap-1">
                        <div class="flex items-center gap-1.5 text-xs font-medium text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Data da Compra
                        </div>
                        <div class="text-sm font-semibold text-gray-900">
                            {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '--' }}
                        </div>
                    </div>

                    {{-- SUB-CARD: STATUS --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 flex flex-col gap-1.5">
                        <div class="flex items-center gap-1.5 text-xs font-medium text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status
                        </div>
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold uppercase {{ $infoStatusClass }}">
                                @if ($infoStatusIsCanceled)
                                    CANCELADO
                                @else
                                    {{ __($order->status ?? '--') }}
                                @endif
                            </span>
                        </div>
                        @if ($infoStatusIsCanceled && ($order->order_cancel_description ?? false))
                            <div class="text-xs text-gray-500 leading-tight">{{ $order->order_cancel_description }}</div>
                        @endif
                        @if ($infoStatusIsCanceled && ($order->order_cancel_datetime ?? false))
                            <div class="text-xs text-gray-400">{{ $order->order_cancel_datetime->format('d/m/Y H:i') }}</div>
                        @endif
                    </div>

                    {{-- SUB-CARD: EXPIRAÇÃO --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 flex flex-col gap-1.5">
                        <div class="flex items-center gap-1.5 text-xs font-medium text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Expiração
                        </div>
                        @if ($infoStatusIsPaid)
                            <div class="text-xs text-green-600 font-medium">Pago — sem expiração</div>
                        @elseif ($infoStatusIsCanceled)
                            <div class="text-xs text-gray-400">—</div>
                        @elseif ($infoHasExp)
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-semibold {{ $infoExpPast ? 'text-red-700' : 'text-amber-700' }}">
                                    {{ $infoExpDate->format('d/m/Y H:i') }}
                                </span>
                                <span class="text-xs font-medium {{ $infoExpPast ? 'text-red-500' : 'text-gray-500' }}">
                                    {{ $infoExpPast ? 'Expirado' : 'Expira' }} {{ $infoExpDate->ago() }}
                                </span>
                            </div>
                            @if (isAdmin())
                                <div class="flex items-center gap-1 flex-wrap mt-0.5">
                                    @foreach ([1, 6, 12, 24] as $h)
                                        <button wire:click="extenderExpiracao({{ $h }})" class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors" title="Adicionar {{ $h }}h ao prazo">+{{ $h }}h</button>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="text-xs text-gray-400">Sem prazo definido</div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- CARD: DADOS DO COMPRADOR --}}
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-2">
                <h2 class="text-base font-semibold text-gray-800">Dados do Comprador</h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                    <div>
                        <div class="text-xs font-medium text-gray-500 mb-0.5">Nome</div>
                        <div class="text-sm font-semibold text-gray-900 uppercase">{{ $order->buyer_name ?? '--' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 mb-0.5">Email</div>
                        <div class="text-sm text-gray-900 lowercase truncate">{{ $order->buyer_email ?? '--' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 mb-0.5">Nascimento</div>
                        <div class="text-sm text-gray-900">
                            @if ($order->buyer_birth_date ?? false)
                                {{ $order->buyer_birth_date->format('d/m/Y') }} ({{ $order->buyer_birth_date->age }} anos)
                            @else
                                --
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 mb-0.5">Documento</div>
                        <div class="text-sm text-gray-900">
                            <span class="uppercase">{{ $order->buyer_doc_type ?? null }}:</span>
                            <span>{{ putMask($order->buyer_doc_num ?? '--', $order->buyer_doc_type ?? null) }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 mb-0.5">Contato</div>
                        <div class="text-sm text-gray-900">({{ $order->buyer_contact_ddd ?? '--' }}) {{ $order->buyer_contact_num ?? '--' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD: ITENS COMPRADOS --}}
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-2 flex justify-between items-center">
                <h2 class="text-base font-semibold text-gray-800">
                    @if (($order->itens ?? false) && $order->itens->count() > 1)
                        Itens Comprados
                    @else
                        Item Comprado
                    @endif
                </h2>
                <div class="flex gap-2">
                    @if (in_array($order->status ?? '--', listOrderStatusPaid()))
                        <x-button primary xs label="Acessar Online" right-icon="external-link"
                            href="{{ route('evento-ingressos', ['order_control' => $order->order_control, 'order_id' => $order->id]) }}"
                            target="_blank" />
                        <x-button primary xs label="Enviar Email" right-icon="mail"
                            onclick="confirm('Confirma o envio dos dados da compra para o email do comprador?') || event.stopImmediatePropagation()"
                            wire:click="enviaDetalhesCompra('{{ $order->id }}','true')" />
                    @else
                        <x-button primary xs label="Acessar" right-icon="external-link"
                            href="{{ route('pagamento', ['targetType' => $target_ref, 'localizador' => $order->order_control]) }}"
                            target="_blank" />
                    @endif
                </div>
            </div>
            <div class="p-4">
                @if ($order->tickets->count() ?? false)
                    <div class="space-y-2">
                        @foreach ($order->tickets as $ticketKey => $ticketItem)
                            <div class="border border-gray-200 rounded p-2 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-gray-900 uppercase">{{ $ticketItem->event_description ?? 'ND' }}</div>
                                        <div class="text-xs text-gray-600 mt-0.5">
                                            <span class="font-medium">{{ $ticketItem->ticket_control ?? null }}</span>
                                            <span class="ml-2 uppercase">{{ $ticketItem->user_name ?? 'PARTICIPANTE #' . ($ticketKey + 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 ml-3">
                                        @php
                                            $ticketStatusClass = match($ticketItem->ticket_status) {
                                                'utilizado' => 'bg-green-100 text-green-800',
                                                'disponivel' => 'bg-blue-100 text-blue-800',
                                                'canceled' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $ticketStatusClass }}">
                                            {{ __($ticketItem->ticket_status ?? '--') }}
                                        </span>
                                        <div class="text-sm font-bold text-gray-900">{{ toMoney($ticketItem->event_ticket_price ?? 0,'R$ ') }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    @forelse ($order->itens ?? [] as $orderKey => $orderItem)
                        <div class="border border-gray-200 rounded p-2 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-gray-900 uppercase">{{ $orderItem->item_description ?? 'ND' }}</div>
                                    <div class="text-xs text-gray-600 mt-0.5">{{ $orderItem->user_name ?? 'PARTICIPANTE #' . ($orderKey + 1) }}</div>
                                </div>
                                <div class="text-sm font-bold text-gray-900 ml-3">{{ toMoney($orderItem->item_amount ?? 0,'R$ ') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">Não possui itens</div>
                    @endforelse
                @endif
            </div>
        </div>

        {{-- CARD: VALORES --}}
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-2">
                <h2 class="text-base font-semibold text-gray-800">Valores</h2>
            </div>
            <div class="p-4">
                <div class="space-y-2">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-500">Total da Compra</div>
                        <div class="text-lg font-bold text-gray-900">{{ toMoney($order->order_amount ?? 0) }}</div>
                    </div>
                    @if ($order->code_promo_id ?? false)
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <div>
                                <div class="text-sm font-medium text-red-600">Desconto</div>
                                <div class="text-xs text-gray-500">
                                    {{ $order->codePromo->code_name ?? null }} - {{ $order->codePromo->code_description ?? null }}
                                </div>
                            </div>
                            <div class="text-base font-bold text-red-600">- {{ toMoney($order->code_promo_discount_amount ?? 0) }}</div>
                        </div>
                    @endif
                    <div class="flex justify-between items-center py-2 bg-green-50 rounded px-3">
                        <div class="text-sm font-medium text-green-700">Total para Pagamento</div>
                        <div class="text-lg font-bold text-green-700">{{ toMoney($order->order_amount_pay ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD: PAGAMENTOS --}}
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-2 flex justify-between items-center">
                <h2 class="text-base font-semibold text-gray-800">
                    @if ($order->paymentsSlip->count() ?? 0)
                        Carnê Online
                    @else
                        @if (($order->payments ?? collect())->count())
                            @if ($order->payments->count() == 1)
                                Pagamento
                            @else
                                {{ $order->payments->count() }} Pagamentos
                            @endif
                        @else
                            Pagamentos
                        @endif
                    @endif
                </h2>
                <div>
                    @if ($order->paymentsSlip->count() ?? 0)
                        @if (!($lancamentos ?? false))
                            @if ($orderPay ?? false)
                                <x-button primary xs label="ALTERAR"
                                    onclick="confirm('Existem pagamentos confirmados no valor total da compra. Realmente deseja modificar os pagamentos?!') || event.stopImmediatePropagation()"
                                    wire:click="addPagamentoManual" />
                            @else
                                <x-button primary xs label="ADICIONAR" wire:click="addPagamentoManual" />
                            @endif
                        @endif
                    @else
                        @if ($orderPay ?? false)
                            <x-button primary xs label="ALTERAR"
                                onclick="confirm('Existem pagamentos confirmados no valor total da compra. Realmente deseja modificar os pagamentos?!') || event.stopImmediatePropagation()"
                                wire:click="addPagamentoManual" />
                        @else
                            <x-button primary xs label="ADICIONAR" wire:click="addPagamentoManual" />
                        @endif
                    @endif
                </div>
            </div>
            <div class="p-4">
                {{-- LANÇAMENTOS MANUAIS --}}
                @if ($order->paymentsSlip->count() ?? 0)
                    @if ($lancamentos = $order->payments->whereNull('order_slip_id')->sortByDesc('created_at'))
                        @if ($lancamentos->count())
                            <div class="mb-4">
                                <div class="text-xs font-semibold text-yellow-800 mb-2 px-1">Pagamentos Avulsos (sem parcela associada)</div>
                                <div class="rounded-lg border border-yellow-200 overflow-hidden shadow-sm">
                                    <div class="grid grid-cols-[4px_repeat(7,1fr)] items-center bg-yellow-50 px-3 py-1.5 gap-x-3">
                                        <div></div>
                                        <div class="text-[9px] font-bold uppercase tracking-widest text-yellow-600">Data / Hora</div>
                                        <div class="col-span-2 text-[9px] font-bold uppercase tracking-widest text-yellow-600">Status</div>
                                        <div class="text-[9px] font-bold uppercase tracking-widest text-yellow-600">Forma</div>
                                        <div class="text-[9px] font-bold uppercase tracking-widest text-yellow-600">NSU</div>
                                        <div class="text-[9px] font-bold uppercase tracking-widest text-yellow-600 text-right">Valor</div>
                                        <div class="self-stretch border-l border-yellow-200 pl-3 pr-1"></div>
                                    </div>
                                    @foreach ($lancamentos as $paymentItem)
                                        @include('livewire.dashboard.includes.payment-card', ['paymentItem' => $paymentItem])
                                    @endforeach
                                </div>
                                <div class="mt-1.5 text-xs text-yellow-700 px-1">Use o botão "Editar" em cada pagamento para associar a uma parcela do carnê.</div>
                            </div>
                        @endif
                    @endif

                    {{-- CARNÊ ONLINE --}}
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Parcelas do Carnê</h3>
                        <div class="space-y-3">
                            @foreach ($order->paymentsSlip as $slip_item)
                                <div class="border border-gray-200 rounded p-3 bg-gray-50">
                                    <div class="flex justify-between items-center mb-2">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $slip_item->installment_description }}</div>
                                            <div class="text-xs text-gray-600 mt-0.5">{{ dataData($slip_item->installment_date_due) }}</div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if (in_array($slip_item->status ?? '', ['paid','pago']))
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    PAGO
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    {{ __(strtoupper($slip_item->status ?? 'pendente')) }}
                                                </span>
                                            @endif
                                            <div class="text-base font-bold text-gray-900">{{ toMoney($slip_item->installment_value,'R$ ') }}</div>
                                        </div>
                                    </div>
                                    @if (!in_array($slip_item->status ?? '', ['paid','pago']))
                                        <div class="flex items-center gap-1.5 mt-2 mb-1">
                                            <button type="button"
                                                wire:click="addPagamentoManualSlip('{{ $slip_item->id }}')"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                                Registrar Pagamento
                                            </button>
                                            <button type="button"
                                                wire:click="abrirEditarSlip('{{ $slip_item->id }}')"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-md text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 transition-colors shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                Editar Parcela
                                            </button>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5 mt-2 mb-1">
                                            <button type="button"
                                                wire:click="abrirEditarSlip('{{ $slip_item->id }}')"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-md text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 transition-colors shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                Editar Parcela
                                            </button>
                                        </div>
                                    @endif
                                    @if (($slip_item->payments ?? collect())->count())
                                        <div class="rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                                            <div class="grid grid-cols-[4px_repeat(7,1fr)] items-center bg-gray-100 px-3 py-1.5 gap-x-3">
                                                <div></div>
                                                <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400">Data / Hora</div>
                                                <div class="col-span-2 text-[9px] font-bold uppercase tracking-widest text-gray-400">Status</div>
                                                <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400">Forma</div>
                                                <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400">NSU</div>
                                                <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400 text-right">Valor</div>
                                                <div class="self-stretch border-l border-gray-300 pl-3 pr-1"></div>
                                            </div>
                                            @foreach ($slip_item->payments->sortByDesc('created_at') as $paymentItem)
                                                @include('livewire.dashboard.includes.payment-card', ['paymentItem' => $paymentItem])
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-2 text-sm text-gray-500">Não possui pagamentos para esta parcela</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- PAGAMENTOS SIMPLES --}}
                    @php $simplePayments = $order->payments->sortByDesc('created_at'); @endphp
                    @if ($simplePayments->count())
                        <div class="rounded-lg border border-gray-200 overflow-hidden shadow-md">
                            <div class="grid grid-cols-[4px_repeat(7,1fr)] items-center bg-gray-100 px-3 py-1.5 gap-x-3">
                                <div></div>
                                <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400">Data / Hora</div>
                                <div class="col-span-2 text-[9px] font-bold uppercase tracking-widest text-gray-400">Status</div>
                                <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400">Forma</div>
                                <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400">NSU</div>
                                <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400 text-right">Valor</div>
                                <div class="self-stretch border-l border-gray-300 pl-3 pr-1"></div>
                            </div>
                            @foreach ($simplePayments as $paymentItem)
                                @include('livewire.dashboard.includes.payment-card', ['paymentItem' => $paymentItem])
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">Não possui pagamentos</div>
                    @endif
                @endif
            </div>
        </div>

        {{-- CARD: DADOS DE RASTREABILIDADE --}}
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-2">
                <h2 class="text-base font-semibold text-gray-800">Dados de Rastreabilidade</h2>
            </div>
            <div class="p-4">
                @if ($order->order_tracking_timestamp ?? false)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-0.5">Endereço IP</div>
                            <div class="text-sm text-gray-900 font-mono">{{ $order->order_ip_address ?? '--' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-0.5">Tipo de Dispositivo</div>
                            <div class="text-sm text-gray-900">
                                @if ($order->order_device_type == 'mobile')
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Mobile
                                    </span>
                                @elseif ($order->order_device_type == 'tablet')
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Tablet
                                    </span>
                                @else
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        Desktop
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-0.5">Navegador</div>
                            <div class="text-sm text-gray-900">{{ $order->order_browser ?? '--' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-0.5">Sistema Operacional</div>
                            <div class="text-sm text-gray-900">{{ $order->order_platform ?? '--' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-0.5">Data/Hora do Rastreamento</div>
                            <div class="text-sm text-gray-900">{{ $order->order_tracking_timestamp ? $order->order_tracking_timestamp->format('d/m/Y H:i:s') : '--' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-0.5">Sessão</div>
                            <div class="text-sm text-gray-900 font-mono truncate">{{ Str::limit($order->order_session_id ?? '--', 40) }}</div>
                        </div>
                    </div>
                    @if ($order->order_user_agent ?? false)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="text-xs font-medium text-gray-500 mb-1">User Agent Completo</div>
                            <div class="text-xs text-gray-700 bg-gray-50 p-2 rounded font-mono break-all">
                                {{ $order->order_user_agent }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-6 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="font-medium">Dados de rastreabilidade não disponíveis</p>
                        <p class="text-sm mt-1">Esta compra foi realizada antes da implementação do sistema de rastreamento.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- MODAL: PAGAMENTO MANUAL DE PARCELA CARNÊ --}}
        <x-modal max-width="4xl" wire:model.defer="addPayManual">
            <x-card title="CADASTRAR / ALTERAR PAGAMENTO">
                <div class="mb-3">
                    <x-jet-validation-errors />
                </div>

                @if ($slipPaymentSlipId ?? false)
                    @php
                        $slipTarget = ($order->paymentsSlip ?? collect())->find($slipPaymentSlipId);
                    @endphp
                    @if ($slipTarget ?? false)
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded">
                            <div class="text-sm font-semibold text-blue-900">{{ $slipTarget->installment_description }}</div>
                            <div class="text-xs text-blue-700 mt-0.5">Vencimento: {{ dataData($slipTarget->installment_date_due) }} — Valor: {{ toMoney($slipTarget->installment_value, 'R$ ') }}</div>
                        </div>
                    @endif
                @endif

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-native-select label="* Tipo de Pagamento" wire:model="pay_type">
                                <option value="">Selecione</option>
                                <option value="CREDIT_CARD">CARTÃO CRÉDITO</option>
                                <option value="CARD_DEBIT">DÉBITO</option>
                                <option value="transfer_pix">PIX</option>
                                <option value="transfer_ted">TED</option>
                                <option value="transfer_doc">DOC</option>
                                <option value="transfer_bank">TRANSFERÊNCIA BANCÁRIA</option>
                                <option value="deposit_bank">DEPÓSITO BANCÁRIO</option>
                                <option value="dinheiro">DINHEIRO</option>
                                <option value="doacao">DOAÇÃO</option>
                            </x-native-select>
                        </div>

                        @if ($pay_type == 'CREDIT_CARD')
                            <div>
                                <x-native-select label="Bandeira do Cartão" wire:model.defer="pay_card_brand">
                                    <option value="">Selecione</option>
                                    <option value="master">MASTER</option>
                                    <option value="visa">VISA</option>
                                    <option value="elo">ELO</option>
                                    <option value="amex">AMEX</option>
                                    <option value="hipercard">HIPERCARD</option>
                                    <option value="dinners">DINNERS</option>
                                    <option value="outra">OUTRA</option>
                                </x-native-select>
                            </div>
                            <div>
                                <x-inputs.maskable placeholder="9999 (Opcional)" label="4 Últimos Dígitos" wire:model.defer="pay_card_last" mask="####" />
                            </div>
                        @endif

                        @if ($pay_type == 'transfer_pix')
                            <div class="md:col-span-2">
                                <x-input label="Chave PIX Utilizada" wire:model.defer="pay_pix_key" />
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-inputs.currency wire:model.defer="value_paid" label="* Valor Pago" hint="Ex: 1.234,56 = 1234,56" thousands="" decimal="," precision="2" emitFormatted="true" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">* Data do Pagamento</label>
                            <input type="date" autocomplete="off" wire:model.defer="pay_datetime" name="pay_datetime" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-jet-input-error for="pay_datetime" />
                        </div>
                        <div>
                            <x-input label="NSU" placeholder="Nº Transação (Opcional)" wire:model.defer="pay_nsu" />
                        </div>
                    </div>

                    @if ($order->paymentsSlip->count() ?? 0)
                        <div>
                            <x-native-select label="Parcela do Carnê" wire:model.defer="slipPaymentSlipId">
                                <option value="">Nenhuma (avulso)</option>
                                @foreach ($order->paymentsSlip as $slipOption)
                                    <option value="{{ $slipOption->id }}">{{ $slipOption->installment_description }} - {{ dataData($slipOption->installment_date_due) }} - {{ toMoney($slipOption->installment_value, 'R$ ') }}</option>
                                @endforeach
                            </x-native-select>
                        </div>
                    @endif
                </div>

                <x-slot name="footer">
                    <div class="w-full flex justify-end gap-2">
                        <x-button flat label="CANCELAR" wire:click="$set('addPayManual',false)" />
                        <x-button primary label="REGISTRAR PAGAMENTO" wire:click="addPagamentoManualSubmit" spinner />
                    </div>
                </x-slot>
            </x-card>
        </x-modal>

        {{-- MODAL LOG --}}
        <x-modal max-width="6xl" wire:model.defer="logTrasacao">
            <x-card class="h-screen mb-24">
                <div class="flex justify-between items-center mb-4">
                    <div class="text-lg font-semibold">Log Transação - {{ $logTrasacao ?? '--' }}</div>
                    <x-button flat label="Fechar" wire:click="$set('logTrasacao',false)" />
                </div>
                <div class="overflow-y-scroll h-full w-full mx-auto mb-24 shadow">
                    @if ($logTrasacaoDetalhes ?? false)
                        {!! viewByGrid($logTrasacaoDetalhes, false) !!}
                    @else
                        <div class="text-center py-8 text-gray-500">Não localizado - {{ $logTrasacao }}</div>
                    @endif
                </div>
            </x-card>
        </x-modal>

        {{-- MODAL EDIÇÃO DE PAGAMENTO NO EXIBIR --}}
        <x-modal max-width="4xl" wire:model.defer="modalEditPagamentoExibir">
            @php
                $editingPayment = ($order->payments ?? collect())->find($editPaymentId);
                $isEditingNonManual = $editingPayment
                    && !in_array(($editingPayment->gateway_slug ?? null), ['user_dashboard', 'manual', 'presencial'], true);
            @endphp
            <x-card title="ALTERAR PAGAMENTO">
                <div class="mb-3">
                    <x-jet-validation-errors />
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-native-select label="* Tipo de Pagamento" wire:model.defer="edit_pay_type">
                                <option value="">Selecione</option>
                                <option value="CREDIT_CARD">CARTÃO CRÉDITO</option>
                                <option value="CARD_DEBIT">DÉBITO</option>
                                <option value="transfer_pix">PIX</option>
                                <option value="transfer_ted">TED</option>
                                <option value="transfer_doc">DOC</option>
                                <option value="transfer_bank">TRANSFERÊNCIA BANCÁRIA</option>
                                <option value="deposit_bank">DEPÓSITO BANCÁRIO</option>
                                <option value="dinheiro">DINHEIRO</option>
                                <option value="doacao">DOAÇÃO</option>
                            </x-native-select>
                        </div>
                        <div>
                            @php
                                $statusOptions = array_values(array_unique(array_merge(
                                    listPaymentStatusPaid(),
                                    listPaymentStatusCanceled(),
                                    ['pending_payment', 'pending_pix', 'pending_boleto', 'processing', 'em_analise', 'refused']
                                )));

                                if (($edit_status ?? false) && !in_array($edit_status, $statusOptions, true)) {
                                    $statusOptions[] = $edit_status;
                                }
                            @endphp
                            <x-native-select label="* Status" wire:model.defer="edit_status">
                                @foreach ($statusOptions as $statusOption)
                                    <option value="{{ $statusOption }}">{{ __($statusOption) }}</option>
                                @endforeach
                            </x-native-select>
                        </div>
                        <div>
                            <x-input label="NSU" wire:model.defer="edit_pay_nsu" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-inputs.currency
                                wire:model.defer="edit_value_paid"
                                label="* Valor Pago"
                                hint="Ex: 1.234,56 = 1234,56"
                                thousands=""
                                decimal=","
                                precision="2"
                                emitFormatted="true"
                            />
                        </div>
                        <div>
                            <x-inputs.currency
                                wire:model.defer="edit_value_fees"
                                label="Encargos"
                                hint="Ex: 1.234,56 = 1234,56"
                                thousands=""
                                decimal=","
                                precision="2"
                                emitFormatted="true"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $isEditingNonManual ? 'Data do Pagamento' : '* Data do Pagamento' }}</label>
                            <input
                                type="date"
                                autocomplete="off"
                                wire:model.defer="edit_pay_datetime"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                            <x-jet-input-error for="edit_pay_datetime" />
                        </div>
                        <div>
                            <x-input label="Gateway" :value="$editingPayment->gateway_slug ?? '--'" readonly />
                        </div>
                        @if ($order->paymentsSlip->count() ?? 0)
                            <div class="md:col-span-2">
                                <x-native-select label="Parcela do Carnê" wire:model.defer="edit_order_slip_id">
                                    <option value="">Nenhuma (avulso)</option>
                                    @foreach ($order->paymentsSlip as $slipOption)
                                        <option value="{{ $slipOption->id }}">{{ $slipOption->installment_description }} - {{ dataData($slipOption->installment_date_due) }} - {{ toMoney($slipOption->installment_value, 'R$ ') }}</option>
                                    @endforeach
                                </x-native-select>
                            </div>
                        @endif
                    </div>

                    @if (in_array($edit_pay_type, ['CREDIT_CARD', 'card_credit'], true))
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input label="Bandeira do Cartão" wire:model.defer="edit_pay_card_brand" />
                            </div>
                            <div>
                                <x-input label="Nome no Cartão" wire:model.defer="edit_pay_card_name" />
                            </div>
                            <div>
                                <x-input label="Final do Cartão" wire:model.defer="edit_pay_card_last" />
                            </div>
                        </div>
                    @endif

                    @if ($edit_pay_type === 'transfer_pix')
                        <div>
                            <x-input label="Chave PIX" wire:model.defer="edit_pay_pix_key" />
                        </div>
                    @endif
                </div>

                <x-slot name="footer">
                    <div class="w-full flex justify-end gap-2">
                        <x-button flat label="CANCELAR" wire:click="fecharEditarPagamentoNoExibir" />
                        <x-button primary label="SALVAR" wire:click="salvarEditarPagamentoNoExibir" spinner />
                    </div>
                </x-slot>
            </x-card>
        </x-modal>

        {{-- MODAL: EDITAR PARCELA DO CARNÊ --}}
        <x-modal max-width="3xl" wire:model.defer="modalEditSlip">
            <x-card title="EDITAR PARCELA">
                <div class="mb-3">
                    <x-jet-validation-errors />
                </div>

                <div class="space-y-4">
                    <div>
                        <x-input label="* Descrição" wire:model.defer="editSlipDescription" placeholder="Ex: Parcela 1 de 10" />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">* Data de Vencimento</label>
                            <input type="date" autocomplete="off" wire:model.defer="editSlipDateDue" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-jet-input-error for="editSlipDateDue" />
                        </div>
                        <div>
                            <x-inputs.currency wire:model.defer="editSlipValue" label="* Valor da Parcela" thousands="" decimal="," precision="2" emitFormatted="true" />
                        </div>
                        <div>
                            @php
                                $slipStatusOptions = ['aguardando_pagamento', 'pendente', 'paid', 'pago', 'vencida', 'cancelada'];
                                if (($editSlipStatus ?? false) && !in_array($editSlipStatus, $slipStatusOptions, true)) {
                                    $slipStatusOptions[] = $editSlipStatus;
                                }
                            @endphp
                            <x-native-select label="* Status" wire:model.defer="editSlipStatus">
                                @foreach ($slipStatusOptions as $slipStatusOpt)
                                    <option value="{{ $slipStatusOpt }}">{{ __($slipStatusOpt) }}</option>
                                @endforeach
                            </x-native-select>
                        </div>
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="w-full flex justify-end gap-2">
                        <x-button flat label="CANCELAR" wire:click="fecharEditarSlip" />
                        <x-button primary label="SALVAR" wire:click="salvarEditarSlip" spinner />
                    </div>
                </x-slot>
            </x-card>
        </x-modal>

    @endif

</div>
