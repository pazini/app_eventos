<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    {{-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style> --}}


    <style>
        /* Estilos gerais */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #07ace6;
            color: #333;
            box-sizing: border-box;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .email-header {
            background-color: #F5F5F5;
            padding: 20px;
            text-align: center;
        }

        .email-header img {
            max-width: 150px;
            height: auto;
        }

        .email-body {
            padding: 20px;
        }

        .email-title {
            font-size: 30px;
            text-align: center;
            color: #4CAF50;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .email-text {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .email-table,
        .email-table-item {
            margin-bottom: 20px;
        }

        .email-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .email-table th,
        .email-table td {
            padding: 10px;
            text-align: left;
        }

        .email-table th {
            font-weight: bold;
            background-color: #f9f9f9;
            color: #4CAF50;
            border-top: 1px solid #ddd;
        }

        .email-table td {
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
            text-align: right;
        }

        .email-table td:first-child {
            text-align: left;
        }

        .email-table tr:first-child th,
        .email-table tr:first-child td {
            border-top: none;
        }

        .email-table {
            border-radius: 8px;
            border: 1px solid #ddd;
            overflow: hidden;
        }

        .email-table-item {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .email-table-item th,
        .email-table-item td {
            padding: 10px;
            text-align: left;
        }

        .email-table-item th {
            font-weight: bold;
            background-color: #4CAF50;
            color: #fff;
        }

        .email-table-item td {
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
            text-align: right;
        }

        .email-table-item td:first-child {
            text-align: left;
        }

        .email-items-table tr:first-child th {
            border-top: none;
        }

        .email-items-table {
            border-radius: 8px;
            border: 1px solid #ddd;
            overflow: hidden;
        }

        .email-highlight {
            text-transform: uppercase;
        }

        .email-localizador {
            text-transform: uppercase;
            font-weight: bold;
        }

        .email-valor-total {
            text-transform: uppercase;
            font-weight: bold;
            color: #4CAF50;
            background-color: #e0e0e0;
            text-align: right;
            font-size: 20px;
        }

        .email-valor-total-td {
            text-transform: uppercase;
            font-weight: bold;
            color: #4CAF50;
            background-color: #e0e0e0;
            text-align: right;
            font-size: 20px;
        }

        .email-note {
            font-size: 16px;
            color: #555;
        }

        .email-button-container {
            text-align: center;
            margin: 20px 0;
        }

        .email-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .email-footer {
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>

</head>

<body class="antialiased">

    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="https://eventos.proeventpay.com/images/app/proeventpay-logo-color.png" alt="Logo">
        </div>
        <!-- Body -->
        <div class="email-body">
            <h1 class="email-title">Compra Confirmada</h1>
            <p class="email-text">Pagamento realizado com sucesso!</p>

            <!-- Informações do Comprador -->
            <table class="email-table">
                <tr>
                    <th>Comprador</th>
                    <td class="email-highlight">Roberta Simão</td>
                </tr>
                <tr>
                    <th>Documento</th>
                    <td class="email-highlight">CPF: 148.074.997-42</td>
                </tr>
                <tr>
                    <th>E-mail</th>
                    <td><a href="mailto:robertasimao18@gmail.com"
                            style="color: #333; text-decoration: none;">robertasimao18@gmail.com</a></td>
                </tr>
                <tr>
                    <th>Telefone</th>
                    <td>(22) 97402-7385</td>
                </tr>
                <tr>
                    <th>Localizador</th>
                    <td class="email-localizador">EV.24120817.85BA0D71</td>
                </tr>
            </table>

            <!-- Itens da Compra -->
            <table class="email-table-item email-items-table">
                <tr>
                    <th>Itens da Compra</th>
                    <th></th>
                </tr>
                <tr>
                    <td>Camisa Voluntário<br><span style="font-size: 12px; color: #555;">Camisa M // Roberta Simão de Souza</span></td>
                    <td>R$ 40,00</td>
                </tr>
                <tr>
                    <td>Camisa Voluntário<br><span style="font-size: 12px; color: #555;">Camisa P // João da Silva</span></td>
                    <td>R$ 40,00</td>
                </tr>
                <tr>
                    <td>Boné Personalizado<br><span style="font-size: 12px; color: #555;">Tamanho Único</span></td>
                    <td>R$ 25,00</td>
                </tr>
                <tr>
                    <td class="email-valor-total">Valor total</td>
                    <td class="email-valor-total-td">R$ 25,00</td>
                </tr>
            </table>

            <!-- Detalhes do Pagamento -->
            <table class="email-table">
                <tr>
                    <th>Transação</th>
                    <td>100560253</td>
                </tr>
                <tr>
                    <th>Forma de Pagamento</th>
                    <td>Pagamento realizado via PIX</td>
                </tr>
                <tr>
                    <th>Valor Pago</th>
                    <td>1x de R$ 40,00</td>
                </tr>
                <tr>
                    <th>Data de Pagamento</th>
                    <td>08/12/2024</td>
                </tr>
            </table>

            <!-- Botão de Voucher -->
            <div class="email-button-container">
                <a href="https://eventos.proeventpay.com/vouchers/EV.24120817.85BA0D71/9ddb8806-6234-4cdb-96ba-c88ad591f3fd"
                    target="_blank" class="email-button">Acessar Online</a>
            </div>

            <hr style="border: none; border-top: 1px solid #ddd;">

            <!-- Contato -->
            <div class="email-footer">
                <p>Dúvidas? Fale com o organizador pelo Telefone / WhatsApp <strong>(21) 99145-5167</strong></p>
            </div>
        </div>
    </div>

</body>

</html>
