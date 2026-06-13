<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="width:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title>Pagamento realizado com sucesso</title>
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

            <h1 style="margin:10px 0 20px 0; text-align:center;text-transform:uppercase;">
                <div style="font-size:30px; color:#4CAF50;">Pagamento Confirmado</div>
                <div style="font-size:15px; color:#1c3a1d;">{{$payment->order->event->event_name ?? null}}</div>
            </h1>

            @if ($payment->slip ?? false)
                <!-- Informações Carnê -->
                <table style="box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);margin-bottom:10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);width: 100% !important; border-collapse:collapse; margin-bottom:20px; border:1px solid #ddd; border-radius:8px; overflow:hidden;">
                    <tr>
                        <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; background-color:#4CAF50; text-align:left; color:#fff;text-transform:uppercase;">CARNÊ ONLINE {{ __($payment->slip->installment_pay_type ?? null) }}</th>
                        <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; background-color:#4CAF50; text-align:right; color:#fff;"></th>
                    </tr>

                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">DESCRIÇÃO</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <div style="font-weight:bold; color:#333;">{{ mb_strtoupper(__($payment->slip->installment_description ?? '---')) }}</div>
                        </td>
                    </tr>
                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">VENCIMENTO</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <div style="font-weight:bold; color:#333;">{{ dataData($payment->slip->installment_date_due) }}</div>
                        </td>
                    </tr>
                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">VALOR PARCELA</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <div style="font-weight:bold; color:#333;">{{ toMoney($payment->slip->installment_value,'R$ ') }}</div>
                        </td>
                    </tr>
                </table>
            @endif

            <!-- Pagamento -->
            <table style="box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);width: 100% !important; border-collapse:collapse; margin-bottom:20px; border:1px solid #ddd; border-radius:8px; overflow:hidden;">
                <tr>
                    <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; background-color:#4CAF50; text-align:left; color:#fff;">
                        <div>{{ mb_strtoupper($payment->paid_description ?? ($payment->pay_type ?? '---')) }}</div>
                    </th>
                    <th style="border-top:1px solid rgb(177, 177, 177); padding:10px; background-color:#4CAF50; text-align:right; color:#fff;">
                        <div>{{$payment->order->order_control ?? null}}</div>
                    </th>
                </tr>
                <tr style="background-color:#F5F5F5;">
                    <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">SITUAÇÃO</td>
                    <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                        <div style="font-weight:bold; color:#333;">{{ mb_strtoupper(__($payment->status ?? '---')) }}</div>
                    </td>
                </tr>
                <tr style="background-color:#F5F5F5;">
                    <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">TRANSAÇÃO</td>
                    <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                        <div style="color:#333;">{{ mb_strtoupper(($payment->pay_nsu ?? false) ? $payment->pay_nsu : '---') }}</div>
                    </td>
                </tr>
                <tr style="background-color:#F5F5F5;">
                    <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">QUANDO</td>
                    <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                        <div style="color:#333;">{{ ($payment->pay_datetime ?? false) ? dataDataHora($payment->pay_datetime) : 'Não informada' }}</div>
                    </td>
                </tr>

                @if ($payment->value_fees ?? false)
                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">VALOR</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <div style="color:#333;">{{ convertMoney($payment->value_liquid, 'R$ ') }}</div>
                        </td>
                    </tr>
                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">ENCARGOS</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <div style="color:#333;">{{ convertMoney($payment->value_fees, 'R$ ') }}</div>
                        </td>
                    </tr>
                @endif

                <tr style="background-color:#F5F5F5;">
                    <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">VALOR PAGO</td>
                    <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                        <div>
                            <span style="color:#333;">{{ convertMoney($payment->value_paid, 'R$ ') }}</span>
                            @if ($payment->pay_installments_number > 1)
                            <span style="color:#949494;">({{ $payment->pay_installments_number ?? null }}x {{ convertMoney($payment->pay_installment_value,'R$ ') }})</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @if ($payment->pay_card_first ?? FALSE)
                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#4CAF50; text-align:left;">CARTÃO</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <span style="color:#333;">{{ $payment->pay_card_first ?? '****' }}</span>
                            <span style="color:#333;">**** ****</span>
                            <span style="color:#333;">{{ $payment->pay_card_last ?? '****' }}</span>
                        </td>
                    </tr>
                @endif
            </table>

            <!-- Botão de Voucher -->
            <div style="text-align:center; margin:20px 0;">
                <a href="{{ route('compra-exibir', ['localizador' => $payment->order->order_control]) }}"
                    target="_blank"
                    style="display:inline-block; padding:10px 20px; background-color:#4CAF50; color:#fff; text-decoration:none; border-radius:5px; font-weight:bold; text-transform:uppercase;">VISUALIZAR ONLINE</a>
            </div>

            <!-- Contato -->
            <div style="text-align:center; font-size:14px; margin-top:20px; border:1px solid #ddd; border-radius:8px; padding:10px;">
                @if (($payment->order->event->organizer ?? false) && $payment->order->event->organizer->owner_phone_ddd && $payment->order->event->organizer->owner_phone_num)
                    <p>Dúvidas? Fale com o organizador pelo Telefone / WhatsApp <strong>{{ putMask($payment->order->event->organizer->owner_phone_ddd.$payment->order->event->organizer->owner_phone_num,'telefone') }}</strong></p>
                @else
                    <p>Dúvidas? Fale com a empresa pelo Telefone / WhatsApp <strong>{{ putMask($payment->order->event->customer->comercial_contact_ddd.$payment->order->event->customer->comercial_contact_num,'telefone') }}</strong></p>
                @endif
            </div>
        </div>
    </div>

</body>
</html>
