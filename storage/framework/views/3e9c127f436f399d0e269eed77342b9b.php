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
            <img src="<?php echo e(appUrl()); ?>/<?php echo e(appLogo()); ?>" alt="<?php echo e(appName()); ?>" style="max-width:150px; height:auto;">
        </div>

        <!-- Body -->
        <div style="padding:10px 20px;">

            <style>
                @media (max-width: 600px) {div {padding: 5px !important;}}
            </style>

            <div style="margin:10px 0 20px 0;">
                <?php if(($slip->installment_date_due ?? false) && (dataDiferencaDias($slip->installment_date_due) < 0)): ?>
                    <h1 style="font-size:30px; text-align:center; color:#dd0218; text-transform:uppercase;">
                        Pagamento em Atraso
                    </h1>
                <?php else: ?>
                    <h1 style="font-size:30px; text-align:center; color:#07ace6; text-transform:uppercase;">
                        Pagamento Próximo
                    </h1>
                <?php endif; ?>
            </div>

            <?php if($slip ?? false): ?>
                <!-- Informações do Comprador -->
                <table style="margin-bottom:10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);width: 100% !important; border-collapse:collapse; margin-bottom:20px; border:1px solid #ddd; border-radius:8px; overflow:hidden;">
                    <tr>
                        <th colspan="2" style="border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; background-color:#07ace6; text-align:left; color:#fff;text-transform:uppercase;">
                            <span>CARNÊ ONLINE</span>
                            <span><?php echo e(__($slip->installment_pay_type ?? null)); ?></span>
                        </th>
                    </tr>

                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#07ace6; text-align:left;">LOCALIZADOR</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <div style="font-weight:bold; color:#333;"><?php echo e(mb_strtoupper($slip->order->order_control)); ?></div>
                        </td>
                    </tr>
                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#07ace6; text-align:left;">DESCRIÇÃO</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <div style="font-weight:bold; color:#333;"><?php echo e(mb_strtoupper(__($slip->installment_description ?? '---'))); ?></div>
                        </td>
                    </tr>
                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#07ace6; text-align:left;">VENCIMENTO</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <div style="font-weight:bold; color:#333;"><?php echo e(dataData($slip->installment_date_due,ago:true)); ?></div>
                        </td>
                    </tr>
                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#07ace6; text-align:left;">VALOR</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <div style="font-weight:bold; color:#333;"><?php echo e(toMoney($slip->installment_value,'R$ ')); ?></div>
                        </td>
                    </tr>
                    <tr style="background-color:#F5F5F5;">
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; font-weight:bold; color:#07ace6; text-align:left;">DESCRIÇÃO</td>
                        <td style="background-color:#F5F5F5; border-top:1px solid rgb(177, 177, 177); padding:10px; text-align:right;">
                            <div style="font-weight:bold; color:#333;"><?php echo e($slip->order->slip_description ?? '---'); ?></div>
                        </td>
                    </tr>
                </table>
            <?php else: ?>
                <div>NÃO POSSUI PAGAMENTOS...</div>
            <?php endif; ?>

            <!-- Botão de Voucher -->
            <div style="text-align:center; margin:20px 0;">
                <a href="<?php echo e(route('compra-exibir', ['localizador' => $slip->order->order_control])); ?>"
                    target="_blank"
                    style="display:inline-block; padding:10px 20px; background-color:#07ace6; color:#fff; text-decoration:none; border-radius:5px; font-weight:bold; text-transform:uppercase;">VISUALIZAR ONLINE</a>
            </div>

            <!-- Contato -->
            <div style="text-align:center; font-size:14px; margin-top:20px; border:1px solid #ddd; border-radius:8px; padding:10px;">
                <?php if(($slip->order->event->organizer ?? false) && $slip->order->event->organizer->owner_phone_ddd && $slip->order->event->organizer->owner_phone_num): ?>
                    <p>Dúvidas? Fale com o organizador pelo Telefone / WhatsApp <strong><?php echo e(putMask($slip->order->event->organizer->owner_phone_ddd.$slip->order->event->organizer->owner_phone_num,'telefone')); ?></strong></p>
                <?php else: ?>
                    <p>Dúvidas? Fale com a empresa pelo Telefone / WhatsApp <strong><?php echo e(putMask($slip->order->event->customer->comercial_contact_ddd.$slip->order->event->customer->comercial_contact_num,'telefone')); ?></strong></p>
                <?php endif; ?>
            </div>

            <div style="text-align:center; font-size:10px; margin:10px 0;">
                * Compras com Pagamentos atrasados mais de 60 dias serão canceladas e a vaga será disponibilizada para outro comprador. Esse email é apenas um lembrete. Caso já tenha realizado o referido pagamento, favor desconsiderar. =)
            </div>
        </div>
    </div>

</body>
</html>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/_email/compra/pagamento-lembrete.blade.php ENDPATH**/ ?>