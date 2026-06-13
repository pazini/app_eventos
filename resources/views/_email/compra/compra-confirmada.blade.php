<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"
    style="width:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title>Compra realizada com sucesso</title>
    <style>
        a {text-decoration: none;}
        table {border-collapse: collapse;box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);}
    </style>
</head>

<body style="font-family:Arial, sans-serif; padding:20px 5px; margin:0; background-color:#07ace6; color:#333;">
    <style>
        @media (max-width: 600px) {body {padding: 0 !important;}}
    </style>

    <div style="max-width:600px; margin:0 auto; background-color:#fff; border:1px solid #ddd; border-radius:8px; overflow:hidden;">

        <!-- Header -->
        <div style="background-color:#F5F5F5; padding:20px; text-align:center;">
            <img src="{{ appUrl() }}/{{ appLogo() }}" alt="{{ appName() }}" style="max-width:150px; height:auto;">
        </div>

        <!-- Body -->
        <div style="padding:10px 20px;">

            <style>
                @media (max-width: 600px) {div {padding: 5px !important;}}
            </style>

            <h1 style="font-size:30px; text-align:center; color:#4CAF50; margin:10px 0 20px 0; text-transform:uppercase;">Compra Confirmada</h1>

            <!-- Informações do Comprador -->
            <table style="box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);width: 100% !important; border-collapse:collapse; margin-bottom:20px; border:1px solid #ddd; border-radius:8px; overflow:hidden;">
                <tr>
                    <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; background-color:#4CAF50; text-align:left; color:#fff;">DADOS DO COMPRADOR</th>
                    <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; background-color:#4CAF50; text-align:right; color:#fff;"></th>
                </tr>
                <tr>
                    <th style="border-top:1px solid silver; padding:10px; font-weight:bold; background-color:#F5F5F5; color:#4CAF50; text-align:left;">NOME</th>
                    <td style="border-top:1px solid silver; padding:10px; background-color:#F5F5F5; text-align:right; text-transform:uppercase; word-break: break-word;">{{ $dados->buyer_name ?? '--' }}</td>
                </tr>
                <tr>
                    <th style="border-top:1px solid silver; padding:10px; font-weight:bold; background-color:#F5F5F5; color:#4CAF50; text-align:left;">DOCUMENTO</th>
                    <td style="border-top:1px solid silver; padding:10px; background-color:#F5F5F5; text-align:right; text-transform:uppercase; word-break: break-word;">
                        {{ $dados->buyer_doc_type ?? '--' }} {{ putMask($dados->buyer_doc_num, $dados->buyer_doc_type) }}
                    </td>
                </tr>
                <tr>
                    <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; background-color:#F5F5F5; color:#4CAF50; text-align:left;">E-MAIL</th>
                    <td style="border-top:1px solid rgb(177, 177, 177); padding:10px; background-color:#F5F5F5; text-align:right; text-transform:lowercase; word-break: break-word;">{{ $dados->buyer_email ?? '--' }}</td>
                </tr>
                <tr>
                    <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; background-color:#F5F5F5; color:#4CAF50; text-align:left;">TELEFONE</th>
                    <td style="border-top:1px solid rgb(177, 177, 177); padding:10px; background-color:#F5F5F5; text-align:right; word-break: break-word;">{{ putMask($dados->buyer_contact_ddd . $dados->buyer_contact_num, 'telefone') }}</td>
                </tr>
                <tr>
                    <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; background-color:#F5F5F5; color:#4CAF50; text-align:left;">LOCALIZADOR</th>
                    <td style="border-top:1px solid rgb(177, 177, 177); padding:10px; background-color:#F5F5F5; text-align:right; text-transform:uppercase; font-weight:bold; word-break: break-word;">{{ mb_strtoupper($dados->order_control ?? 'Não definido') }}</td>
                </tr>
            </table>

            <!-- Itens da Compra -->
            <table style="box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);width: 100% !important; border-collapse:collapse; margin-bottom:20px; border:1px solid #ddd; border-radius:8px; overflow:hidden;">
                <tr>
                    <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; background-color:#4CAF50; text-align:left; color:#fff;">ITENS DA COMPRA</th>
                    <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; background-color:#4CAF50; text-align:right; color:#fff;"></th>
                </tr>
                @foreach ($dados->tickets as $ticket)
                    <tr>
                        <td style="border-top:1px solid rgb(177, 177, 177); padding:10px; background-color:#F5F5F5; text-align:left;">
                            <div style="text-transform:uppercase; font-weight:bold;">{{ mb_strtoupper($ticket->event_name) }}</div>
                            <div style="text-transform:uppercase; font-size:12px; color:#555;">{{ $ticket->event_ticket_name }} // {{ $ticket->user_name }}</div>
                        </td>
                        <td style="border-top:1px solid rgb(177, 177, 177); padding:10px; background-color:#F5F5F5; text-align:right;">{{ convertMoney($ticket->event_ticket_price, 'R$ ') }}</td>
                    </tr>
                    @php
                        $valorTotal =
                            $valorTotal ?? false
                                ? $valorTotal + $ticket->event_ticket_price
                                : $ticket->event_ticket_price;
                    @endphp
                @endforeach
                <tr>
                    <td style="border-top:1px solid rgb(177, 177, 177); padding:10px; color:#4CAF50; font-weight:bold; text-align:left; background-color:#F5F5F5;">VALOR TOTAL</td>
                    <td style="border-top:1px solid rgb(177, 177, 177); padding:10px; color:#4CAF50; font-weight:bold; text-align:right; background-color:#F5F5F5;">{{ convertMoney($valorTotal, 'R$ ') }}</td>
                </tr>
                @if ($dados->code_promo_id ?? false)
                    <tr>
                        <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; background-color:#F5F5F5; color:#4CAF50; text-align:left;">DESCONTO</th>
                        <td style="border-top:1px solid rgb(177, 177, 177); padding:10px; background-color:#F5F5F5; text-align:right;">
                            <div style="font-weight:bold; color:#333;">
                                @if ($dados->code_promo_price_less ?? false)
                                    <span>{{ toMoney($dados->code_promo_price_less,'- R$ ') }}</span>
                                @elseif ($dados->code_promo_discount_amount ?? false)
                                    <span>{{ toMoney($dados->code_promo_discount_amount,'- R$ ') }}</span>
                                @else
                                    --
                                @endif
                            </div>
                            @if ($dados->code_promo_label ?? false)
                                <div style="color:#666666;"><small>{{ mb_strtoupper($dados->code_promo_label) }}</small></div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; background-color:#F5F5F5; color:#4CAF50; text-align:left;">VALOR FINAL</th>
                        <td style="border-top:1px solid rgb(177, 177, 177); padding:10px; background-color:#F5F5F5; text-align:right;">
                            <div style="font-weight:bold; color:#333;">{{toMoney($dados->code_promo_price_new,'R$ ')}}</div>
                        </td>
                    </tr>
                @endif
            </table>

            <div style="margin: 10px 0 5px 0">
                <hr>
                @if ($dados->payments->count() == 1)
                    <h3>PAGAMENTO</h3>
                @else
                    <h3>{{$dados->payments->count()}} PAGAMENTOS</h3>
                @endif
            </div>

            <!-- Detalhes do Pagamento -->
            @forelse ($dados->payments ?? [] as $paymentItem)
                <table style="box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);width: 100% !important; border-collapse:collapse; margin-bottom:20px; border:1px solid #ddd; border-radius:8px; overflow:hidden;background-color:#F5F5F5;">
                    <tr>
                        <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; background-color:#4CAF50; text-align:left; color:#fff;">{{ mb_strtoupper(__($paymentItem->pay_type ?? '---')) }}</th>
                        <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; background-color:#4CAF50; text-align:right; color:#fff;">
                            <small>{{ $paymentItem->description ?? null }}</small>
                        </th>
                    </tr>

                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">QUANDO</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <div style="color:#333;">{{ ($paymentItem->pay_datetime ?? false) ? dataDataHora($paymentItem->pay_datetime) : 'Não informada' }}</div>
                        </td>
                    </tr>

                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">TRANSAÇÃO</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <div style="font-weight:bold; color:#333;">{{ mb_strtoupper(($paymentItem->pay_nsu ?? false) ? $paymentItem->pay_nsu : '---') }}</div>
                        </td>
                    </tr>

                    @if ($paymentItem->value_fees ?? false)
                        <tr style="background-color:#F5F5F5;">
                            <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">VALOR</td>
                            <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                                <div style="color:#333;">{{ convertMoney($paymentItem->value_liquid, 'R$ ') }}</div>
                            </td>
                        </tr>
                        <tr style="background-color:#F5F5F5;">
                            <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">ENCARGOS</td>
                            <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                                <div style="color:#333;">{{ convertMoney($paymentItem->value_fees, 'R$ ') }}</div>
                            </td>
                        </tr>
                    @endif

                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">VALOR PAGO</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <div>
                                <span style="color:#333;">{{ convertMoney($paymentItem->value_paid, 'R$ ') }}</span>
                                @if ($paymentItem->pay_installments_number > 1)
                                <span style="color:#949494;">({{ $paymentItem->pay_installments_number ?? null }}x {{ convertMoney($paymentItem->pay_installment_value,'R$ ') }})</span>
                                @endif
                            </div>
                        </td>
                    </tr>

                    @if ($paymentItem->pay_card_first ?? FALSE)
                        <tr style="background-color:#F5F5F5;">
                            <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">CARTÃO</td>
                            <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                                <span style="color:#333;">{{ $paymentItem->pay_card_first ?? '****' }}</span>
                                <span style="color:#333;">**** ****</span>
                                <span style="color:#333;">{{ $paymentItem->pay_card_last ?? '****' }}</span>
                            </td>
                        </tr>
                    @endif
                </table>
            @empty
                <tr>
                    <td colspan="2" style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; background-color:#F5F5F5; color:#251d90; text-align:left;">NÃO POSSUÍ PAGAMENTOS</td>
                </tr>
            @endforelse

            <!-- Botão de Voucher -->
            <div style="text-align:center; margin:20px 0;">
                <a href="{{ route('evento-vouchers', ['localizador' => $dados->order_control, 'order_id' => $dados->id]) }}"
                    target="_blank"
                    style="display:inline-block; padding:10px 20px; background-color:#4CAF50; color:#fff; text-decoration:none; border-radius:5px; font-weight:bold; text-transform:uppercase;">ACESSAR ONLINE</a>
            </div>

            <!-- Contato -->
            <div style="text-align:center; font-size:14px; margin-top:20px; border:1px solid #ddd; border-radius:8px; padding:10px;">
                @if ($dados->event->organizer->owner_phone_ddd && $dados->event->organizer->owner_phone_num)
                    <p>Dúvidas? Fale com o organizador pelo Telefone / WhatsApp <strong>{{ putMask($dados->event->organizer->owner_phone_ddd.$dados->event->organizer->owner_phone_num,'telefone') }}</strong></p>
                @else
                    <p>Dúvidas? Fale com a empresa pelo Telefone / WhatsApp <strong>{{ putMask($dados->event->customer->comercial_contact_ddd.$dados->event->customer->comercial_contact_num,'telefone') }}</strong></p>
                @endif
            </div>
        </div>
    </div>

</body>
</html>
