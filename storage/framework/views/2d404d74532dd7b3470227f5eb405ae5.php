<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Aguardando Pagamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .pending-box {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .pending-box h2 {
            color: #92400e;
            margin: 0 0 10px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            gap: 15px;
            font-size: 18px;
        }
        .info-label {
            color: #555;
            text-transform: uppercase;
            font-size: 14px;
        }
        .info-value {
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⏳ Aguardando Pagamento</h1>
        </div>

        <div class="content">
            <p style="color: font-size: 18px;">Olá, <strong><?php echo e(ucwords(strtolower($buyer_name))); ?></strong>!</p>

            <div class="pending-box">
                <h2>⏱️ Seu pedido está aguardando pagamento</h2>
                <p style="margin: 0; color: #92400e;">Complete o pagamento para confirmar sua participação em <strong><?php echo e(mb_strtoupper($campaign_name)); ?></strong>.</p>
            </div>

            <div style="margin: 20px 0;">
                <div class="info-row">
                    <span class="info-label">Localizador &nbsp;</span>
                    <span class="info-value"><strong><?php echo e($order_control); ?></strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Valor &nbsp;</span>
                    <span class="info-value"><strong style="color: #f59e0b;">R$ <?php echo e($amount_total); ?></strong></span>
                </div>
            </div>

            <?php if(($payment_type ?? '') === 'boleto' && ($boleto_url ?? false)): ?>
                
                <div style="background-color: #fef3c7; border: 2px solid #f59e0b; border-radius: 6px; padding: 20px; margin: 20px 0;">
                    <h3 style="color: #92400e; margin: 0 0 15px 0; text-align: center;">
                        <span>Boleto Gerado</span>
                        <?php if($boleto_expiration): ?>
                            <span style="color: #92400e;">- Pague até
                                <strong>
                                    <?php echo e(\Carbon\Carbon::parse($boleto_expiration)->format('d/m/Y')); ?>

                                </strong>
                            </span>
                        <?php endif; ?>
                    </h3>

                    <?php if($boleto_barcode): ?>
                        <div style="margin: 15px 0;">
                            <p style="color: #92400e; font-size: 12px; font-weight: bold; margin: 0 0 5px 0; text-transform: uppercase;">Linha Digitável:</p>
                            <div style="background-color: #ffffff; border: 1px solid #d97706; border-radius: 4px; padding: 10px; font-family: 'Courier New', monospace; font-size: 13px; color: #333; word-break: break-all; text-align: center;">
                                <?php echo e($boleto_barcode); ?>

                            </div>
                        </div>
                    <?php endif; ?>

                    <div style="text-align: center; margin: 20px 0 10px 0;">
                        <a href="<?php echo e($boleto_url); ?>" target="_blank" style="display: inline-block; background-color: #16a34a; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                            Imprimir Boleto
                        </a>
                    </div>
                </div>

                <div style="text-align: center; margin: 30px 0;">
                    <a href="<?php echo e($payment_url); ?>" style="display: inline-block; background-color: #3b82f6; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                        Acessar Adesão
                    </a>
                </div>
            <?php else: ?>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="<?php echo e($payment_url); ?>" style="display: inline-block; background-color: #f59e0b; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                        Concluir Pagamento
                    </a>
                </div>

                <p style="text-align: center; color: #777; font-size: 14px;">
                    Ou copie e cole este link no seu navegador:<br>
                    <a href="<?php echo e($payment_url); ?>" style="color: #f59e0b; word-break: break-all;"><?php echo e($payment_url); ?></a>
                </p>
            <?php endif; ?>

            <p>Assim que seu pagamento for confirmado, você receberá um novo e-mail com o comprovante de participação.</p>

            <p>Atenciosamente,<br><strong>Equipe <?php echo e($company_name ?? config('app.name')); ?></strong></p>
        </div>

        <div class="footer">
            Este é um e-mail automático. Por favor, não responda.
        </div>
    </div>
</body>
</html>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/emails/campanha/pagamento-pendente.blade.php ENDPATH**/ ?>