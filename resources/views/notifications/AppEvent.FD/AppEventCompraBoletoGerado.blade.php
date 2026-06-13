@component('mail::message')
    <div style="color: #000">
        <div style="margin-bottom: 25px;">
            <div style="text-align: center; font-size: x-large; text-transform: uppercase; font-weight: bold">{{ $data['payment']['description'] }}</span></div>
            <div style="text-align: center; font-size: medium; font-weight: 600;">BOLETO GERADO</div>
            <div style="text-align: center; font-weight: 400;">EFETUE O PAGAMENTO PARA CONLUIR A COMPRA</div>
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
        <div class="form-value capitalize" style="text-transform: uppercase; margin-bottom: 5px;">
            <p style="padding: 0; margin: 0 0 5px 0; font-size: x-small; font-weight: 300;">
                Detalhes da compra
            </p>
            <p style="padding: 0; margin: 0;">
                <div style=" font-weight: 400; font-size: large; text-transform: uppercase;">
                    <span style="font-weight: bold">{{ $data['order_control'] }}</span>
                </div>
                <div style=" font-weight: 400; font-size: large; text-transform: uppercase;">{{ $data['order_generation_datetime'] }}</div>
                <div style=" font-weight: 400; font-size: large; text-transform: uppercase;">Boleto valor R$ {{ number_format($data['payment']['value_paid']  / 100, 2, ',', '.') }}</div>
                <div style=" font-weight: 400; font-size: large; text-transform: uppercase;">VENCIMENTO: <strong>{{ $data['payment']['pay_boleto_expiration_date'] }}</strong></div>
            </p>
        </div>
        <div style="text-align: left; margin: 10px 0 20px 0;">
            <a href="{{ $data['payment']['pay_boleto_url'] }}" target="_blank" style="background-color:#6fa44b;border:1px solid #6fa44b;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:180px">IMPRIMIR BOLETO</a>
        </div>
        <hr>
        <div style="text-transform: uppercase; margin-bottom: 15px;">
            <div style="padding: 0; font-size: x-small; font-weight: 400; margin-bottom: 5px;">CODIGO DE BARRAS</div>
            <div style="padding: 0; margin: 0; font-weight: 400; font-size: medium;">{{ $data['payment']['pay_boleto_barcode'] }}</div>
        </div>
        <hr>
        <div style="text-align: center; margin-top: 10px;">

        </div>
    </div>
@endcomponent
