@component('mail::message')
    <div style="color: #000">
        <div style="margin-bottom: 25px;">
            <div style="text-align: center; font-size: x-large; text-transform: uppercase; font-weight: bold">{{ $data['payment']['description'] }}</span></div>
            <div style="text-align: center; font-size: medium; font-weight: 600;">COMPRA REALIZADA COM SUCESSO</div>
        </div>
        <hr>
        <div class="form-value capitalize" style="text-transform: uppercase; margin-bottom: 25px;">
            <p style="padding: 0; margin: 0 0 5px 0; font-size: x-small; font-weight: 300;">
                Comprador
            </p>
            <p style="padding: 0; margin: 0;">
                <div style=" font-weight: 400; font-size: large; text-transform: uppercase;">{{ $data['buyer_name'] }}</div>
                <div style=" font-weight: 400; font-size: medium; ">({{ $data['buyer_contact_ddd'] }}) {{ $data['buyer_contact_num'] }}</div>
                <div style=" font-weight: 400; font-size: medium; text-transform: lowercase;">{{ $data['buyer_email'] }}</div>
            </p>
        </div>
        <hr>
        <div class="form-value capitalize" style="text-transform: uppercase; margin-bottom: 25px;">
            <p style="padding: 0; margin: 0 0 5px 0; font-size: x-small; font-weight: 300;">
                Detalhes da compra
            </p>
            <p style="padding: 0; margin: 0;">
                <div style=" font-weight: 400; font-size: large; text-transform: uppercase;">
                    <span style="font-weight: bold">{{ $data['order_control'] }}</span>
                </div>
                <div style=" font-weight: 400; font-size: large; text-transform: uppercase;">{{ $data['order_generation_datetime'] }}</div>
                <div style=" font-weight: 400; font-size: large; text-transform: uppercase;">Valor pago R$ {{ number_format($data['payment']['value_paid']  / 100, 2, ',', '.') }}</div>
                <div style=" font-weight: 400; font-size: large; text-transform: uppercase;">
                    @if ($data['payment']['pay_type'] == "CREDIT_CARD")
                        <div>
                            <span>CARTÃO </span>
                            <span>{{ $data['payment']['pay_card_brand'] }}</span>
                            <span>{{ $data['payment']['pay_card_first'] }}</span>
                            <span>* * * *</span>
                            <span>{{ $data['payment']['pay_card_last'] }}</span>
                        </div>
                        <div>
                            <span>NSU: </span>
                            <span>{{ $data['payment']['pay_nsu'] }}</span>
                        </div>
                    @elseif ($data['payment']['pay_type'] == "BOLETO")
                        <div>
                            <span>PAGAMENTO BOLETO: </span>
                            <span>{{ $data['payment']['pay_boleto_barcode'] }}</span>
                        </div>
                        <div>
                            <span>VENCIMENTO: </span>
                            <span>{{ $data['payment']['pay_boleto_expiration_date'] }}</span>
                        </div>
                    @else
                        <div>
                            <span>NENHUMA FORMA DE PAGAMENTO PARA ESSA COMPRA</span>
                        </div>
                    @endif
                </div>
            </p>
        </div>
        <hr>
        @if (count($data['tickets']))
            <div style="text-transform: uppercase; margin-bottom: 10px;">
                <p style="padding: 0; margin: 0 0 5px 0; font-size: x-small; font-weight: 300;">
                    Seus Ingressos
                </p>
                <p style="padding: 0; margin: 0;">
                    @foreach ($data['tickets'] as $ticketKey => $ticketValues)
                        <table width="100%" cellspading="0" style="margin: 5px 0; border-radius: 3px; border: solid 1px #000;">
                            <tr style="background-color: #000; color: #f5f5f5; font-weight: 600;">
                                <td style="font-weight: 600; padding: 5px">
                                    <table width="100%">
                                        <tr>
                                            <td width="100%">
                                                <div style="font-weight: bold; font-size: medium;">{{ $ticketValues['event_name'] }}</div>
                                                <div style="font-weight: 400; font-size: medium;">{{ $ticketValues['event_datetime'] }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="100%">
                                                <hr style="padding: 0; margin: 0;">
                                                {{ $ticketValues['ticket_control'] }}
                                            </td>
                                        </tr>
                                    </table>
                                    <table width="100%" style="background: #fff; color: #000;">
                                        <tr>
                                            <td width="100%" style="font-weight: 400; font-size: large; text-transform: uppercase; padding: 5px;">
                                                @if ($ticketValues['user_name'] ?? false)
                                                    <p style="padding: 0; margin: 0; text-transform: uppercase;">
                                                        {{ $ticketValues['user_name'] }}
                                                    </p>
                                                @endif
                                                @if ($ticketValues['user_email'] ?? false)
                                                    <p style="padding: 0; margin: 0; text-transform: lowercase;">
                                                        {{ $ticketValues['user_email'] }}
                                                    </p>
                                                @endif
                                                @if ($ticketValues['user_contact_ddd'] ?? false && $ticketValues['user_contact_num'] ?? false)
                                                    <p style="padding: 0; margin: 0; text-transform: lowercase;">
                                                        <span>({{ $ticketValues['user_contact_ddd'] }})</span>
                                                        <span>{{ $ticketValues['user_contact_num'] }}</span>
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </p>
                <hr>
                <div style="text-align: center; margin-top: 10px;">
                    <a href="{{ route('evento-ingressos', ['order_control' => $data['order_control'], 'order_id' => $data['id']]) }}" style="font-weight: 600; text-decoration: none;">Clique aqui</a> para acessar ou imprimir seus ingressos
                </div>
            </div>
        @endif
        <hr>
        <div style="text-align: center; margin-top: 10px;">

        </div>
    </div>
@endcomponent
