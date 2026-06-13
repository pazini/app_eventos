<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pagamento Aprovado</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        .success-box {
            background-color: #d1fae5;
            border: 2px solid #10b981;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .success-box h2 {
            color: #065f46;
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
            <h1>🎉 Pagamento Aprovado!</h1>
        </div>

        <div class="content">
            <p style="color: font-size: 18px;">Olá, <strong>{{ ucwords(strtolower($buyer_name)) }}</strong>!</p>

            <div class="success-box">
                <h2>✅ Seu pagamento foi aprovado com sucesso!</h2>
                <p style="margin: 0; color: #065f46;">Obrigado pela sua participação em <strong>{{ mb_strtoupper($campaign_name) }}</strong>.</p>
            </div>

            <div style="margin: 20px 0;">
                <div class="info-row">
                    <span class="info-label">Localizador &nbsp;</span>
                    <span class="info-value"><strong>{{ $order_control }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Valor pago &nbsp;</span>
                    <span class="info-value"><strong style="color: #10b981;">R$ {{ $amount_paid }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Data/hora &nbsp;</span>
                    <span class="info-value">{{ $paid_at }}</span>
                </div>
            </div>

            <p>Seu comprovante de participação foi gerado. Guarde este e-mail para consultas futuras.</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $payment_url }}" style="display: inline-block; background-color: #10b981; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                    🎁 Acessar Doação
                </a>
            </div>

            <p>Atenciosamente,<br><strong>Equipe {{ $company_name ?? config('app.name') }}</strong></p>
        </div>

        <div class="footer">
            Este é um e-mail automático. Por favor, não responda.
        </div>
    </div>
</body>
</html>
