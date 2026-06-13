<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comprovante de Participação</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .highlight {
            background-color: #f0f7ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Comprovante de Participação</h1>
        </div>

        <div class="content">
            <p style="color: font-size: 18px;">Olá, <strong>{{ ucwords(strtolower($buyer_name)) }}</strong>!</p>

            <p>Obrigado por participar da campanha <strong>{{ $campaign_name }}</strong>.</p>

            <div class="highlight">
                <div class="info-row">
                    <span class="info-label">Localizador&nbsp;</span>
                    <span class="info-value"><strong>{{ $order_control }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Valor&nbsp;</span>
                    <span class="info-value">R$ {{ $amount_total }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status&nbsp;</span>
                    <span class="info-value">{{ $status }}</span>
                </div>
                @if($paid_at !== '-')
                    <div class="info-row">
                        <span class="info-label">Data do pagamento&nbsp;</span>
                        <span class="info-value">{{ $paid_at }}</span>
                    </div>
                @endif
            </div>

            <p>Guarde este comprovante para consultas futuras.</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $payment_url }}" style="display: inline-block; background-color: #667eea; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                    📝 Ver Comprovante Online
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
