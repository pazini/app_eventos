<div class="w-full max-w-7xl mx-auto mb-6">

    @php
        $grandTotalEstimado = ($sponsorship_orders ?? collect())->whereNotIn('status', ['cancelled', 'cancelled_customer'])->sum('order_amount');
        $grandTotalPago = ($sponsorship_orders ?? collect())->where('status', 'paid')->sum('order_amount');
    @endphp

    <div class="mb-3">
        <x-jet-validation-errors />
    </div>

    @include('_includes.alertas')

    {{-- HEADER MODERNO COM GRADIENTE --}}
    <div class="mb-4 bg-gradient-to-r from-violet-500 via-purple-500 to-indigo-500 rounded-lg relative overflow-hidden shadow-md">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern-patrocinios" width="8" height="8" patternUnits="userSpaceOnUse">
                        <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern-patrocinios)"/>
            </svg>
        </div>
        <div class="relative z-10 p-4">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-3">
                <div class="flex-1">
                    <div class="flex items-center space-x-2">
                        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <div>
                            <h1 class="text-lg font-bold text-white">Patrocínios</h1>
                            <p class="text-white/90 text-xs mt-0.5">{{ $target->event_name ?? '--' }} — {{ formatDateStartFinish($target->event_datetime_start, $target->event_datetime_finish) ?? '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    @if ($grandTotalEstimado > 0)
                        <div class="flex items-center gap-4 bg-white/10 rounded-lg px-4 py-2">
                            <div class="text-right">
                                <div class="text-[9px] font-bold uppercase tracking-widest text-white/60">TOTAL ESTIMADO</div>
                                <div class="text-base font-extrabold text-white/80">{{ toMoney($grandTotalEstimado, 'R$ ') }}</div>
                            </div>
                            <div class="text-right border-l border-white/20 pl-4">
                                <div class="text-[9px] font-bold uppercase tracking-widest text-white/70">TOTAL PAGO</div>
                                <div class="text-base font-extrabold text-white">{{ toMoney($grandTotalPago, 'R$ ') }}</div>
                            </div>
                        </div>
                    @endif
                    <x-button flat white xs icon="external-link" label="PÁGINA DE PATROCÍNIO" href="{{ route('evento-patrocinicar', ['slug' => $target->event_slug]) }}" target="_blank" class="bg-white/20 hover:bg-white/40" />
                    <x-button flat white xs icon="reply" label="VOLTAR" href="{{ route('dashboard-evento') }}" class="bg-white/20 hover:bg-white/40" />
                </div>
            </div>
        </div>
    </div>

    @php
        $statusColors = [
            'paid'       => ['bg' => '#dcfce7', 'text' => '#16a34a', 'label' => 'PAGO'],
            'pending'    => ['bg' => '#fef9c3', 'text' => '#ca8a04', 'label' => 'PENDENTE'],
            'cancelled'  => ['bg' => '#fee2e2', 'text' => '#dc2626', 'label' => 'CANCELADO'],
            'cancelled_customer' => ['bg' => '#fee2e2', 'text' => '#dc2626', 'label' => 'CANCELADO'],
            'refunded'   => ['bg' => '#f3e8ff', 'text' => '#9333ea', 'label' => 'ESTORNADO'],
        ];
        $payStatusColors = [
            'paid'          => ['bg' => '#dcfce7', 'text' => '#16a34a'],
            'pending_pix'   => ['bg' => '#fef9c3', 'text' => '#ca8a04'],
            'pending'       => ['bg' => '#fef9c3', 'text' => '#ca8a04'],
            'cancelled'     => ['bg' => '#fee2e2', 'text' => '#dc2626'],
            'refunded'      => ['bg' => '#f3e8ff', 'text' => '#9333ea'],
        ];
        $byBuyer = ($sponsorship_orders ?? collect())->sortByDesc('created_at')->groupBy('buyer_doc_num');
    @endphp

    <div class="w-full max-w-7xl mx-auto">
    <div class="flex flex-col gap-6 mt-4">

    @forelse ($byBuyer as $buyerDocNum => $buyerOrders)

        @php
            $rep = $buyerOrders->first();
            $buyerLogoUrl = null;
            if ($rep->buyer_url_logo ?? false)
                $buyerLogoUrl = str_starts_with($rep->buyer_url_logo, '/storage/')
                    ? asset($rep->buyer_url_logo)
                    : tenantAsset($rep->buyer_url_logo, true);
            $totalEstimado = $buyerOrders->whereNotIn('status', ['cancelled', 'cancelled_customer'])->sum('order_amount');
            $totalPago = $buyerOrders->where('status', 'paid')->sum('order_amount');
            $isCnpj = strlen(preg_replace('/\D/', '', $buyerDocNum ?? '')) > 11;
        @endphp

        <div class="rounded-2xl overflow-hidden bg-white" style="border:1px solid #d1d5db;box-shadow:0 4px 24px rgba(0,0,0,.08);">

            {{-- Header do Patrocinador --}}
            <div class="flex items-start gap-5 px-6 py-5" style="background:#f8fafc;border-bottom:2px solid #6366f1;">

                {{-- Logo + link editar --}}
                <div class="flex-shrink-0 flex flex-col items-center gap-1.5">
                    <div class="w-[72px] h-[72px] rounded-xl overflow-hidden bg-white flex items-center justify-center" style="border:1px solid #e5e7eb;box-shadow:0 2px 8px rgba(0,0,0,.06);">
                        @if ($buyerLogoUrl)
                            @php $downloadName = strtoupper(preg_replace('/\D/', '', $buyerDocNum) . '-' . \Illuminate\Support\Str::slug($rep->buyer_name ?? 'logo') . '.' . (pathinfo($buyerLogoUrl, PATHINFO_EXTENSION) ?: 'png')); @endphp
                            <div x-data="{ modalLogo: false }" class="relative w-full h-full">
                                <img src="{{ $buyerLogoUrl }}" class="w-full h-full object-cover cursor-pointer" @click="modalLogo = true" title="Ver imagem" />
                                {{-- Modal da Logo --}}
                                <div x-show="modalLogo" x-cloak
                                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                     @click="modalLogo = false"
                                     style="position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.6);display:flex;align-items:center;justify-content:center;">
                                    <div @click.stop style="background:#fff;border-radius:16px;padding:24px;max-width:480px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.3);text-align:center;">
                                        <img src="{{ $buyerLogoUrl }}" style="max-height:320px;width:100%;object-fit:contain;border-radius:8px;margin-bottom:16px;" />
                                        <div style="display:flex;gap:12px;justify-content:center;">
                                            <a href="{{ $buyerLogoUrl }}" download="{{ $downloadName }}"
                                               style="display:inline-flex;align-items:center;gap:6px;padding:8px 20px;background:#4f46e5;color:#fff;font-size:12px;font-weight:700;text-transform:uppercase;border-radius:8px;text-decoration:none;transition:background .15s;"
                                               onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                                BAIXAR
                                            </a>
                                            <button @click="modalLogo = false"
                                                    style="padding:8px 20px;background:#f3f4f6;color:#6b7280;font-size:12px;font-weight:700;text-transform:uppercase;border-radius:8px;border:none;cursor:pointer;transition:background .15s;"
                                                    onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                                                FECHAR
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <x-icon name="photograph" class="w-7 h-7 text-gray-300" />
                        @endif
                    </div>
                    <button wire:click="editarPatrocinio('{{ $rep->id }}')"
                            class="text-[10px] font-semibold uppercase transition hover:underline"
                            style="color:#7c3aed;background:none;border:none;padding:0;">
                        editar
                    </button>
                </div>

                {{-- Info do patrocinador --}}
                <div class="flex-1 min-w-0">
                    <div class="font-extrabold text-gray-800 text-xl uppercase leading-tight tracking-wide">{{ $rep->buyer_name ?? '--' }}</div>
                    <div class="flex items-center gap-2 flex-wrap mt-1.5">
                        <span class="text-[10px] font-bold uppercase px-2.5 py-0.5 rounded" style="background:{{ $isCnpj ? '#f0fdf4' : '#fef3c7' }};color:{{ $isCnpj ? '#15803d' : '#92400e' }};">{{ $isCnpj ? 'PESSOA JURÍDICA' : 'PESSOA FÍSICA' }}</span>
                        @if ($rep->buyer_segment ?? false)
                            <span class="text-[10px] font-bold uppercase px-2.5 py-0.5 rounded" style="background:#eef2ff;color:#4f46e5;">{{ $rep->buyer_segment }}</span>
                        @endif
                        <span class="text-[11px] font-bold uppercase tracking-widest text-gray-400">{{ putMask($rep->buyer_doc_num, $rep->buyer_doc_type) }}</span>
                    </div>
                    @if ($rep->buyer_description ?? false)
                        <div class="text-xs text-gray-500 mt-1.5 uppercase">{{ $rep->buyer_description }}</div>
                    @endif
                    @php
                        $contactParts = [];
                        if ($rep->buyer_contact_name ?? false) $contactParts[] = strtoupper($rep->buyer_contact_name);
                        if ($rep->buyer_email ?? false) $contactParts[] = $rep->buyer_email;
                        if ($rep->buyer_contact_ddd ?? false) {
                            $contactParts[] = $rep->buyer_contact_ddd . '.' . $rep->buyer_contact_num;
                        }
                        if ($rep->buyer_url_instagram ?? false) $contactParts[] = '@' . $rep->buyer_url_instagram;
                    @endphp
                    @if (count($contactParts))
                        <div class="mt-2 text-xs text-gray-400">{{ implode(' | ', $contactParts) }}</div>
                    @endif
                </div>

                {{-- Contagem + Ações --}}
                <div class="flex-shrink-0 flex flex-col items-end gap-2">
                    <div class="text-right">
                        <div class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Patrocínios</div>
                        <div class="text-3xl font-extrabold text-gray-800">{{ $buyerOrders->count() }}</div>
                    </div>
                    <button wire:click="abrirNovoPatrocinioManual('{{ $rep->buyer_doc_num }}')"
                            class="text-[10px] font-bold uppercase py-1.5 px-3 rounded-lg transition hover:bg-indigo-50"
                            style="color:#4f46e5;border:1.5px solid #c7d2fe;background:transparent;">
                        NOVO PATROCÍNIO
                    </button>
                </div>

            </div>

            {{-- Sub-tabela dos patrocínios --}}
            {{-- Cards de adesão --}}
                    <div class="flex flex-col gap-3 px-4 py-4">
                    @foreach ($buyerOrders->sortByDesc('created_at') as $order_item)
                        @php
                            $statusInfo    = $statusColors[$order_item->status] ?? ['bg' => '#f3f4f6', 'text' => '#6b7280', 'label' => strtoupper($order_item->status ?? '?')];
                            $paidStatuses   = ['paid', 'paid_cupom_full', 'paid_after_deadline', 'autorizado', 'captured'];
                            $totalPagoOrder = collect($order_item->payments ?? [])->whereIn('status', $paidStatuses)->sum('value_paid');
                        @endphp

                        {{-- CARD DA ADESÃO --}}
                        <div class="rounded-xl bg-white overflow-hidden" style="border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,.06);">
                        <div class="px-5 py-4">

                            {{-- Cabeçalho do card --}}
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-xs font-black text-gray-700 font-mono tracking-wide">{{ $order_item->order_control ?? '' }}</span>
                                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full whitespace-nowrap" style="background:{{ $statusInfo['bg'] }};color:{{ $statusInfo['text'] }};">{{ $statusInfo['label'] }}</span>
                                    </div>
                                    @if ($order_item->order_description ?? false)
                                        <div class="text-[11px] text-gray-400 uppercase mt-0.5">{{ $order_item->order_description }}</div>
                                    @endif
                                    <div class="text-[11px] text-gray-400 mt-1">Contratado em {{ $order_item->created_at->format('d/m/Y H:i') }}</div>
                                </div>

                                {{-- Valores --}}
                                <div class="flex items-center gap-4 flex-shrink-0 w-56 justify-end">
                                    <div class="text-right">
                                        <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400">Adesão</div>
                                        <div class="text-base font-extrabold text-gray-700">{{ toMoney($order_item->order_amount, 'R$ ') }}</div>
                                    </div>
                                    @if ($totalPagoOrder > 0)
                                        <div class="text-right border-l border-gray-200 pl-4">
                                            <div class="text-[9px] font-bold uppercase tracking-widest" style="color:#16a34a;">Pago</div>
                                            <div class="text-base font-extrabold" style="color:#15803d;">{{ toMoney($totalPagoOrder, 'R$ ') }}</div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Ações --}}
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    @if ($order_item->status === 'paid')
                                        <button wire:click="adicionarPagamento('{{ $order_item->id }}')" title="Editar Pagamento"
                                                class="w-7 h-7 flex items-center justify-center rounded-lg transition"
                                                style="background:#dbeafe;color:#2563eb;"
                                                onmouseover="this.style.background='#bfdbfe'" onmouseout="this.style.background='#dbeafe'">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                        </button>
                                    @else
                                        <button wire:click="adicionarPagamento('{{ $order_item->id }}')" title="Lançar Pagamento"
                                                class="w-7 h-7 flex items-center justify-center rounded-lg transition"
                                                style="background:#dcfce7;color:#15803d;"
                                                onmouseover="this.style.background='#bbf7d0'" onmouseout="this.style.background='#dcfce7'">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-6-6h12"/></svg>
                                        </button>
                                    @endif
                                    @if (!($order_item->payments && $order_item->payments->count() > 0) && $order_item->status !== 'paid')
                                        <button x-data="{ confirm: false }"
                                                x-on:click="confirm ? ($wire.cancelarPatrocinio('{{ $order_item->id }}'), confirm = false) : confirm = true"
                                                x-on:mouseleave="confirm = false"
                                                title="Excluir"
                                                class="w-7 h-7 flex items-center justify-center rounded-lg transition"
                                                :style="confirm ? 'background:#dc2626;color:#fff;' : 'background:#fef2f2;color:#dc2626;'"
                                                style="background:#fef2f2;color:#dc2626;"
                                                wire:loading.attr="disabled">
                                            <svg x-show="!confirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                            <span x-show="confirm" x-cloak style="font-size:10px;font-weight:800;">?</span>
                                        </button>
                                    @endif
                                    <a href="{{ route('pagamento', ['targetType' => 'evento_patrocinador', 'localizador' => $order_item->order_control]) }}" target="_blank"
                                       title="Acessar"
                                       class="w-7 h-7 flex items-center justify-center rounded-lg transition"
                                       style="background:#dbeafe;color:#2563eb;"
                                       onmouseover="this.style.background='#bfdbfe'" onmouseout="this.style.background='#dbeafe'">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                    </a>
                                </div>
                            </div>

                            {{-- Pagamentos desta adesão --}}
                            @if (($order_item->payments ?? collect())->count() > 0)
                                <div class="mt-3 rounded-lg border border-gray-200 overflow-hidden shadow-md">

                                    {{-- Header das colunas --}}
                                    <div class="grid grid-cols-[4px_repeat(7,1fr)] items-center bg-gray-100 px-3 py-1.5 gap-x-3">
                                        <div></div>
                                        <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400">Data / Hora</div>
                                        <div class="col-span-2 text-[9px] font-bold uppercase tracking-widest text-gray-400">Status</div>
                                        <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400">Forma</div>
                                        <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400">NSU</div>
                                        <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400 text-right">Valor</div>
                                        <div class="self-stretch border-l border-gray-300 pl-3 pr-1"></div>
                                    </div>

                                    {{-- Linhas --}}
                                    @foreach ($order_item->payments->sortByDesc('created_at') as $payment_item)
                                        @php
                                            $pColor = $payStatusColors[$payment_item->status] ?? ['bg' => '#f3f4f6', 'text' => '#6b7280'];
                                            $pDate  = $payment_item->pay_datetime ?? $payment_item->created_at ?? null;
                                            $pBarColors = [
                                                'paid'             => '#16a34a',
                                                'paid_cupom_full'  => '#16a34a',
                                                'paid_after_deadline' => '#16a34a',
                                                'autorizado'       => '#16a34a',
                                                'captured'         => '#16a34a',
                                                'canceled'         => '#dc2626',
                                                'cancelled'        => '#dc2626',
                                                'refunded'         => '#9333ea',
                                                'estornado'        => '#9333ea',
                                                'sending_provider' => '#2563eb',
                                            ];
                                            $pBarColor = $pBarColors[$payment_item->status] ?? (str_starts_with($payment_item->status ?? '', 'pending') ? '#ca8a04' : '#d1d5db');
                                            $pForma = __($payment_item->pay_type ?? '—');
                                            if ($payment_item->pay_card_last ?? false) $pForma .= ' · ' . $payment_item->pay_card_last;
                                        @endphp
                                        <div class="grid grid-cols-[4px_repeat(7,1fr)] items-center bg-white border-t border-gray-100 px-3 py-2.5 gap-x-3">

                                            {{-- Marcador lateral --}}
                                            <div class="self-stretch rounded-full" style="background:{{ $pBarColor }};width:4px;"></div>

                                            {{-- Data / Hora --}}
                                            <div>
                                                @if ($pDate)
                                                    <div class="text-xs font-semibold text-gray-700">{{ $pDate->format('d/m/Y H:i') }}</div>
                                                @else
                                                    <div class="text-xs text-gray-300">—</div>
                                                @endif
                                            </div>

                                            {{-- Status --}}
                                            <div class="col-span-2 flex items-center gap-1 flex-wrap">
                                                <span class="inline-flex items-center text-[10px] font-bold uppercase px-2 py-0.5 rounded-full whitespace-nowrap" style="background:{{ $pColor['bg'] }};color:{{ $pColor['text'] }};">{{ __($payment_item->status ?? '') }}</span>
                                                @if (in_array($payment_item->pay_integration_type ?? '', ['sandbox']))
                                                    <span class="inline-flex items-center text-[9px] font-bold uppercase px-1.5 py-0.5 rounded-full bg-red-100 text-red-700">TESTE</span>
                                                @endif
                                            </div>

                                            {{-- Forma --}}
                                            <div class="text-xs font-semibold uppercase text-gray-500 truncate">
                                                {{ $pForma }}
                                            </div>

                                            {{-- NSU --}}
                                            <div class="text-[10px] font-mono text-gray-400 truncate">
                                                {{ $payment_item->pay_nsu ?? '—' }}
                                            </div>

                                            {{-- Valor --}}
                                            <div class="text-right">
                                                @if ($payment_item->value_paid ?? false)
                                                    <div class="text-sm font-extrabold text-gray-800">{{ toMoney($payment_item->value_paid, 'R$ ') }}</div>
                                                    @if (($payment_item->fee_percentage_used ?? 0) > 0)
                                                        <div class="text-[9px] text-gray-400">liq. {{ toMoney($payment_item->value_liquid ?? 0, 'R$ ') }}</div>
                                                    @endif
                                                    @if (($payment_item->pay_installments_number ?? 1) > 1)
                                                        <div class="text-[9px] text-gray-400">{{ $payment_item->pay_installments_number }}x {{ toMoney($payment_item->pay_installment_value ?? 0, 'R$ ') }}</div>
                                                    @endif
                                                @else
                                                    <div class="text-xs text-gray-300">—</div>
                                                @endif
                                            </div>

                                            {{-- Ações --}}
                                            <div class="flex items-center gap-2 border-l border-gray-200 pl-3 pr-1">
                                                <button wire:click="adicionarPagamento('{{ $order_item->id }}','{{ $payment_item->id }}')" title="Editar"
                                                        class="w-7 h-7 flex items-center justify-center rounded-lg transition"
                                                        style="background:#dbeafe;color:#2563eb;"
                                                        onmouseover="this.style.background='#bfdbfe'" onmouseout="this.style.background='#dbeafe'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                                </button>
                                                <button wire:click.prevent="paymentCheckProcessed('{{ $payment_item->id }}')"
                                                        wire:loading.attr="disabled" wire:target="paymentCheckProcessed"
                                                        title="Verificar pagamento"
                                                        class="w-7 h-7 flex items-center justify-center rounded-lg transition"
                                                        style="background:#dcfce7;color:#16a34a;"
                                                        onmouseover="this.style.background='#bbf7d0'" onmouseout="this.style.background='#dcfce7'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                                </button>
                                            </div>

                                        </div>
                                    @endforeach

                                </div>
                            @endif

                        </div>
                        </div>{{-- FIM CARD DA ADESÃO --}}

                    @endforeach
                    </div>

            {{-- Totalizador --}}
            <div class="flex items-center justify-end gap-6 px-5 py-3" style="border-top:2px solid #e5e7eb;background:#f9fafb;">
                <div class="text-right">
                    <div class="text-[9px] font-bold uppercase tracking-widest text-gray-400">TOTAL ESTIMADO</div>
                    <div class="text-sm font-extrabold text-gray-500">{{ toMoney($totalEstimado, 'R$ ') }}</div>
                </div>
                <div class="text-right" style="border-left:1px solid #e5e7eb;padding-left:24px;">
                    <div class="text-[9px] font-bold uppercase tracking-widest" style="color:#16a34a;">TOTAL PAGO</div>
                    <div class="text-xl font-extrabold" style="color:#15803d;">{{ toMoney($totalPago, 'R$ ') }}</div>
                </div>
            </div>

        </div>

    @empty
        <div class="bg-white rounded-2xl py-12 text-center text-gray-400 font-semibold uppercase tracking-wide" style="border:1px solid #e5e7eb;">
            Nenhum patrocínio cadastrado
        </div>
    @endforelse

    </div>
    </div>


    {{-- MODAL NOVO PATROCÍNIO MANUAL --}}
    <x-modal.card title="NOVO PATROCÍNIO MANUAL" blur wire:model.defer="novo_patrocinio_manual">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Separador Patrocínio --}}
            <div class="col-span-full">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">PATROCÍNIO</span>
                    <div class="flex-1" style="height:1px;background:#e5e7eb;"></div>
                </div>
            </div>

            @if ($sponsorship_plans && $sponsorship_plans->count() > 0)
                <div class="col-span-full">
                    <x-native-select label="PLANO (opcional)" wire:model.live="manual_plan_id">
                        <option value="">-- Nenhum / Personalizado --</option>
                        @foreach ($sponsorship_plans as $plan_item)
                            <option value="{{ $plan_item->id }}">{{ strtoupper($plan_item->slug ?? $plan_item->name ?? $plan_item->id) }} — {{ toMoney($plan_item->price, 'R$ ') }}</option>
                        @endforeach
                    </x-native-select>
                </div>
            @endif

            <div class="col-span-full">
                <x-input label="* DESCRIÇÃO / PLANO" wire:model.defer="manual_description" class="uppercase" placeholder="Ex: PATROCÍNIO OURO" />
            </div>

            <div class="col-span-full">
                <x-inputs.currency label="* VALOR DO PATROCÍNIO" prefix="R$ " class="pl-10" thousands="." decimal="," wire:model.defer="manual_amount" />
            </div>

            {{-- Separador Pagamento --}}
            <div class="col-span-full">
                <div class="flex items-center gap-2 mt-2 mb-1">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">PAGAMENTO</span>
                    <div class="flex-1" style="height:1px;background:#e5e7eb;"></div>
                    <span class="font-black px-1.5 py-0.5 rounded font-mono text-[10px]" style="background:#1d4ed8;color:#fff;">-M</span>
                </div>
            </div>

            <x-native-select label="* TIPO" wire:model="pay_type">
                <option value="">--</option>
                <option value="transfer_pix">PIX</option>
                <option value="boleto">BOLETO</option>
                <option value="card_credit">CARTÃO CRÉDITO</option>
                <option value="transfer_ted">TRANSFERÊNCIA TED</option>
                <option value="transfer_doc">TRANSFERÊNCIA DOC</option>
            </x-native-select>

            <x-input label="NSU" wire:model.defer="pay_nsu" />

            <x-input label="* DATA PAGAMENTO" type="date" wire:model.defer="pay_datetime" />

            <x-inputs.currency label="* VALOR PAGO" prefix="R$ " class="pl-10" thousands="." decimal="," wire:model.defer="value_paid" />

            <div class="col-span-full">
                <x-input label="OBSERVAÇÃO" wire:model.defer="paid_description" />
            </div>

        </div>

        <x-slot name="footer">
            <div class="flex justify-between gap-x-4">
                <div>
                    <x-button flat label="Cancelar" x-on:click="close" />
                </div>
                <div class="flex">
                    <x-button primary label="SALVAR" wire:click="salvarNovoPatrocinioManual" />
                </div>
            </div>
        </x-slot>

    </x-modal.card>
    {{--  --}}

    {{--  --}}
    <x-modal.card title="PAGAMENTO" blur wire:model.defer="adicionar_pagamento">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <x-native-select label="TIPO" wire:model="pay_type">
                <option value="">--</option>
                <option value="transfer_pix">PIX</option>
                <option value="boleto">BOLETO</option>
                <option value="card_credit">CARTÃO CRÉDITO</option>
                <option value="transfer_ted">TRANSFERÊNCIA TED</option>
                <option value="transfer_doc">TRANSFERÊNCIA DOC</option>
            </x-native-select>

            <x-input label="NSU" wire:model.defer="pay_nsu" />

            <x-input label="DATA PAGAMENTO" type="date" wire:model.defer="pay_datetime" />

            <x-inputs.currency label="VALOR" prefix="R$ " class="pl-10" thousands="." decimal="," wire:model.defer="value_paid" />

            <div class="col-span-full">
                <x-input label="OBSERVAÇÃO" wire:model.defer="paid_description" />
            </div>

        </div>

        <x-slot name="footer">
            <div class="flex justify-between gap-x-4">
                <div>
                    <x-button flat negative label="Remover" wire:click="removerPagamento('{{ $order_id }}','{{ $pagamento_id }}')" />
                    {{-- <x-button flat label="Cancelar" x-on:click="close" /> --}}
                </div>

                <div class="flex">
                    <x-button primary label="SALVAR" wire:click="registrarPagamento('{{ $order_id }}','{{ $pagamento_id ?? false }}')" />
                </div>
            </div>
        </x-slot>

    </x-modal.card>
    {{--  --}}

    {{-- MODAL GERAR BOLETO --}}
    <x-modal.card title="GERAR BOLETO" blur wire:model.defer="gerar_boleto">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <x-input label="VENCIMENTO" type="date" wire:model.defer="pay_datetime" />

            <x-inputs.currency label="VALOR" prefix="R$ " class="pl-10" thousands="." decimal="," wire:model.defer="value_paid" />

            <div class="col-span-full">
                <x-input label="OBSERVAÇÃO" wire:model.defer="paid_description" />
            </div>

        </div>

        <x-slot name="footer">
            <div class="flex justify-between gap-x-4">
                <div>
                    {{-- <x-button flat negative label="Delete" wire:click="delete" /> --}}
                    <x-button flat label="Cancelar" x-on:click="close" />
                </div>

                <div class="flex">
                    <x-button primary label="SALVAR" wire:click="registrarPagamento('{{ $order_id }}','{{ $pagamento_id ?? false }}')" />
                </div>
            </div>
        </x-slot>

    </x-modal.card>
    {{--  --}}

    {{-- MODAL EDITAR PATROCINADOR --}}
    <x-modal.card title="EDITAR DADOS DO PATROCINADOR" blur wire:model.defer="editar_patrocinio">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- LOGO --}}
            <div class="col-span-full">
                <div class="{{ setClass('divContentLabel') }} mb-1">Logo do Patrocinador <span class="{{ setClass('divContentLabelSmall') }}">Tamanho máx: 5Mb</span></div>

                @if ($edit_buyer_url_logo ?? false)
                    @php $editLogoUrl = str_starts_with($edit_buyer_url_logo, '/storage/') ? asset($edit_buyer_url_logo) : tenantAsset($edit_buyer_url_logo, true); @endphp
                    <div class="flex items-center gap-4">
                        <img src="{{ $editLogoUrl }}" class="h-16 w-auto object-contain rounded border shadow-sm" />
                        <div class="flex gap-2">
                            <a href="{{ $editLogoUrl }}" download="{{ strtoupper(preg_replace('/\D/', '', $edit_buyer_doc_num ?? '') . '-' . \Illuminate\Support\Str::slug($edit_buyer_name ?? 'logo') . '.' . (pathinfo($editLogoUrl, PATHINFO_EXTENSION) ?: 'png')) }}">
                                <x-button outline xs indigo icon="download" label="Baixar" />
                            </a>
                            <x-button outline xs negative icon="trash" label="Remover" wire:click="removerLogoPatrocinio" />
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <x-input wire:model="edit_url_logo_file" type="file" />
                        </div>
                        <div wire:loading wire:target="edit_url_logo_file" class="text-sm text-gray-500">Carregando...</div>
                    </div>
                @endif
            </div>

            <div class="col-span-full"><hr></div>

            {{-- DOC --}}
            <div class="sm:col-span-1">
                <x-input label="{{ strtoupper($edit_buyer_doc_type ?? 'CNPJ/CPF') }}" wire:model.defer="edit_buyer_doc_num" class="uppercase" readonly />
            </div>

            {{-- NOME --}}
            <div class="sm:col-span-1">
                <x-input label="* Nome Patrocinador" wire:model.defer="edit_buyer_name" class="uppercase" />
            </div>

            {{-- SEGMENTO --}}
            <div class="sm:col-span-1">
                <x-input label="Segmento" wire:model.defer="edit_buyer_segment" class="uppercase" />
            </div>

            {{-- DESCRIÇÃO --}}
            <div class="sm:col-span-1">
                <x-input label="Descrição" wire:model.defer="edit_buyer_description" class="uppercase" />
            </div>

            {{-- EMAIL --}}
            <div class="sm:col-span-1">
                <x-input label="* Email Contato" type="email" wire:model.defer="edit_buyer_email" class="lowercase" />
            </div>

            {{-- CONTATO --}}
            <div class="sm:col-span-1">
                <div class="{{ setClass('divContentLabel') }}">* Telefone Contato</div>
                <div class="flex mt-1">
                    <div class="w-1/3">
                        @php $listaDdd = ['11','12','13','14','15','16','17','18','19','21','22','24','27','28','31','32','33','34','35','37','38','41','42','43','44','45','46','47','48','49','51','53','54','55','61','62','63','64','65','66','67','68','69','71','73','74','75','77','79','81','82','83','84','85','86','87','88','89','91','92','93','94','95','96','97','98','99']; @endphp
                        <x-native-select placeholder="DDD" :options="$listaDdd" wire:model.defer="edit_buyer_contact_ddd" class="rounded-r-none" />
                    </div>
                    <div class="w-2/3">
                        <x-inputs.maskable mask="['####-####','#####-####']" placeholder="Número" wire:model.defer="edit_buyer_contact_num" class="rounded-l-none" />
                    </div>
                </div>
            </div>

            {{-- NOME CONTATO --}}
            <div class="sm:col-span-1">
                <x-input label="* Nome do Contato" wire:model.defer="edit_buyer_contact_name" class="uppercase" />
            </div>

            {{-- WEBSITE --}}
            <div class="sm:col-span-1">
                <x-input label="Website" wire:model.defer="edit_buyer_url_website" class="lowercase" />
            </div>

            {{-- INSTAGRAM --}}
            <div class="sm:col-span-1">
                <x-input label="Instagram" prefix="@" wire:model.defer="edit_buyer_url_instagram" class="lowercase" />
            </div>

        </div>

        <x-slot name="footer">
            <div class="flex justify-between gap-x-4">
                <div>
                    <x-button flat label="Cancelar" x-on:click="close" />
                </div>
                <div>
                    <x-button primary icon="save" label="SALVAR" wire:click="salvarEdicaoPatrocinio('{{ $edit_order_id }}')" />
                </div>
            </div>
        </x-slot>

    </x-modal.card>
    {{-- MODAL EDITAR PATROCINADOR - FIM --}}

</div>
