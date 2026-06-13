@component('mail::message',['subject' => $subject])

{{--  --}}
<div class="form-value capitalize" style="margin-bottom: 15px">
    <p style="padding: 0; margin: 0; text-transform: uppercase; font-weight: 600;">
        {{ $data['evento']['nome'] . ' ' . $data['evento']['nomeData'] }}
    </p>
    <p style="padding: 0; margin: 0; text-transform: uppercase; font-weight: 300;">
        {{ $data['evento']['descricao'] ?? '' }} {{ $data['evento']['descricao_pos_1'] ?? '' }}
    </p>
</div>
<div class="form-label">LOCAL</div>
<div class="form-value capitalize" style="margin-bottom: 15px">
    <p style="padding: 0;margin:0; text-transform: uppercase; font-weight: 600;">
        {{ $data['campus']['igreja']['nome'] . ' ' . $data['campus']['nome'] }}
    </p>
    <p style="padding: 0; margin-bottom:5px; text-transform: uppercase; font-weight: 400;">
        {{ $data['evento']['local'] }}
    </p>
</div>
{{--  --}}
<div class="form-label">PEDIDO DE INSCRIÇÃO</div>
<div class="form-value capitalize" style="margin-bottom: 15px">
    <p style="padding: 0;margin:0; text-transform: uppercase; font-weight: 600;">
        #{{ $data['pedido']['id'] }} <span style="font-weight: 400;">[{{$data['pedido']['controle_sequencial']}} PayId:{{ $data['pedido']['pay_id']}}]</span>
    </p>
</div>
{{--  --}}
<p style="color:rgb(162, 0, 0); font-weight: 600; padding: 10px 0;">ATENÇÃO: VOUCHER VÁLIDO SOMENTE APÓS EFETUAR O PAGAMENTO</p>
{{--  --}}
@if (count($data['vouchers']))
<div class="form-label" style="margin-bottom: 5px">SEUS VOUCHER(S)</div>
<div class="form-value capitalize" style="margin-bottom: 15px">
    @php
        $voucherKeyOld=0;
    @endphp
    @foreach ($data['vouchers'] as $voucherKey => $voucher)
    <table style="width: 100%;">
        @if ($voucherKeyOld != explode('.',$voucherKey)[0])
            <tr style="background-color: #000; color: #f5f5f5; border-radius: 3px; margin-bottom: 10px; padding: 0 10px; font-weight: 600;">
                <td colspan="2" style="padding: 0 10px; font-weight: 600;">
                    COMPRA {{ explode('.',$voucherKey)[0] }}
                </td>
            </tr>
        @endif
        @php
            $voucherKeyOld = explode('.',$voucherKey)[0];
        @endphp
        <tr style="background-color: #f5f5f5; border-radius: 3px; margin-bottom: 3px; padding: 0 10px;">
            <td>
                <p style="text-align: center; padding: 0;margin: 0;font-weight: 300; font-size: large;">
                    <span style="color: rgb(84, 84, 84); font-weight: 600; text-transform: uppercase;">{{ $voucher->voucher_titulo }}</span>
                    @if ($voucher->voucher_descricao ?? false)
                        <span style="color: rgb(133, 133, 133); font-weight: 600; text-transform: uppercase;">{{ $voucher->voucher_descricao }}</span>
                    @endif
                </p>
                <p style="text-align: center; text-transform: uppercase;">
                    <span>{{ $voucher->voucher_nome }}</span> -
                    <span>{{ $voucher->voucher_telefone }}</span>
                </p>
            </td>
            <td style="padding: 0 !important;margin: 0 !important; text-align: center;">
                @component('mail::button', ['url' => env('APP_URL')."evento/{$voucher->evento_data_slug}/voucher/{$voucher->voucher_controle_sequencial}"])
                VER VOUCHER <br> {{ $voucher->voucher_descricao }}
                @endcomponent
            </td>
        </tr>
    </table>
    <br>
    @endforeach
</div>
{{--  --}}
@else
    <h3>SEM VOUCHERS PARA EXIBIR</h3>
@endif

@endcomponent
