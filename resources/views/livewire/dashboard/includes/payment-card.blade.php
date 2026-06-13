@php
    $pcStatus = $paymentItem->status ?? '--';
    $pcColors = [
        'paid'             => ['bg' => '#dcfce7', 'text' => '#16a34a'],
        'paid_cupom_full'  => ['bg' => '#dcfce7', 'text' => '#16a34a'],
        'paid_after_deadline' => ['bg' => '#dcfce7', 'text' => '#16a34a'],
        'autorizado'       => ['bg' => '#dcfce7', 'text' => '#16a34a'],
        'captured'         => ['bg' => '#dcfce7', 'text' => '#16a34a'],
        'pending_pix'      => ['bg' => '#fef9c3', 'text' => '#ca8a04'],
        'pending_boleto'   => ['bg' => '#fef9c3', 'text' => '#ca8a04'],
        'pending_slip_pix' => ['bg' => '#fef9c3', 'text' => '#ca8a04'],
        'pending'          => ['bg' => '#fef9c3', 'text' => '#ca8a04'],
        'sending_provider' => ['bg' => '#dbeafe', 'text' => '#2563eb'],
        'canceled'         => ['bg' => '#fee2e2', 'text' => '#dc2626'],
        'cancelled'        => ['bg' => '#fee2e2', 'text' => '#dc2626'],
        'refunded'         => ['bg' => '#f3e8ff', 'text' => '#9333ea'],
        'estornado'        => ['bg' => '#f3e8ff', 'text' => '#9333ea'],
        'pix_expired'      => ['bg' => '#f3f4f6', 'text' => '#6b7280'],
    ];
    $pcColor = $pcColors[$pcStatus] ?? ['bg' => '#f3f4f6', 'text' => '#6b7280'];
    $pcDate  = $paymentItem->pay_datetime ?? $paymentItem->created_at ?? null;

    // Marcador lateral: verde=pago, vermelho=cancelado, roxo=estornado, azul=sending, amarelo=pendente, cinza=outros
    if (in_array($pcStatus, ['paid','paid_cupom_full','paid_after_deadline','autorizado','captured'])) {
        $pcBarColor = '#16a34a';
    } elseif (in_array($pcStatus, ['canceled','cancelled'])) {
        $pcBarColor = '#dc2626';
    } elseif (in_array($pcStatus, ['refunded','estornado'])) {
        $pcBarColor = '#9333ea';
    } elseif ($pcStatus === 'sending_provider') {
        $pcBarColor = '#2563eb';
    } elseif (str_starts_with($pcStatus, 'pending') || $pcStatus === 'pending') {
        $pcBarColor = '#ca8a04';
    } else {
        $pcBarColor = '#d1d5db';
    }

    // Forma: tipo + detalhe
    $pcForma = __($paymentItem->pay_type ?? '—');
    if ($paymentItem->pay_card_last ?? false) $pcForma .= ' · ' . $paymentItem->pay_card_last;
@endphp

<div class="grid grid-cols-[4px_repeat(7,1fr)] items-center bg-white border-t border-gray-100 px-3 py-2.5 gap-x-3">

    {{-- Marcador lateral --}}
    <div class="self-stretch rounded-full" style="background:{{ $pcBarColor }};width:4px;"></div>

    {{-- Data / Hora --}}
    <div>
        @if ($pcDate)
            <div class="text-xs font-semibold text-gray-700">{{ $pcDate->format('d/m/Y H:i') }}</div>
        @else
            <div class="text-xs text-gray-300">—</div>
        @endif
    </div>

    {{-- Status --}}
    <div class="col-span-2">
        <span class="inline-flex items-center text-[10px] font-bold uppercase px-2 py-0.5 rounded-full whitespace-nowrap"
              style="background:{{ $pcColor['bg'] }};color:{{ $pcColor['text'] }};">
            {{ __($pcStatus) }}
        </span>
        @if (in_array($paymentItem->pay_integration_type ?? '', ['sandbox']))
            <span class="inline-flex items-center text-[9px] font-bold uppercase px-1.5 py-0.5 rounded-full ml-1 bg-red-100 text-red-700">TESTE</span>
        @endif
    </div>

    {{-- Forma --}}
    <div class="text-xs font-semibold uppercase text-gray-500 truncate">
        {{ $pcForma }}
    </div>

    {{-- NSU --}}
    <div class="text-[10px] font-mono text-gray-400 truncate">
        {{ $paymentItem->pay_nsu ?? '—' }}
    </div>

    {{-- Valor --}}
    <div class="text-right">
        @if ($paymentItem->value_paid ?? false)
            <div class="text-sm font-extrabold text-gray-800">{{ toMoney($paymentItem->value_paid, 'R$ ') }}</div>
            @if (($paymentItem->fee_percentage_used ?? 0) > 0)
                <div class="text-[9px] text-gray-400">liq. {{ toMoney($paymentItem->value_liquid ?? 0, 'R$ ') }}</div>
            @endif
            @if (($paymentItem->pay_installments_number ?? 1) > 1)
                <div class="text-[9px] text-gray-400">{{ $paymentItem->pay_installments_number }}x {{ toMoney($paymentItem->pay_installment_value ?? 0, 'R$ ') }}</div>
            @endif
        @else
            <div class="text-xs text-gray-300">—</div>
        @endif
    </div>

    {{-- Ações --}}
    <div class="flex items-center gap-2 border-l border-gray-200 pl-3 pr-1">
        @if (isAdmin())
            <button wire:click="abrirEditarPagamentoNoExibir('{{ $paymentItem->id }}')"
                title="Editar"
                class="w-7 h-7 flex items-center justify-center rounded-lg transition"
                style="background:#dbeafe;color:#2563eb;"
                onmouseover="this.style.background='#bfdbfe'" onmouseout="this.style.background='#dbeafe'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
            </button>
            <button wire:click="$set('logTrasacao','{{ $paymentItem->id }}')"
                title="Ver Log"
                class="w-7 h-7 flex items-center justify-center rounded-lg transition"
                style="background:#f3f4f6;color:#6b7280;"
                onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            </button>
        @endif
        <button wire:click.prevent="paymentCheckProcessed('{{ $paymentItem->id }}')"
            wire:loading.attr="disabled" wire:target="paymentCheckProcessed"
            title="Verificar pagamento"
            class="w-7 h-7 flex items-center justify-center rounded-lg transition"
            style="background:#dcfce7;color:#16a34a;"
            onmouseover="this.style.background='#bbf7d0'" onmouseout="this.style.background='#dcfce7'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
        </button>
    </div>

</div>
