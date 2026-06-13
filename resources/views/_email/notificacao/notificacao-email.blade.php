<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $data['header'] ?? null }}</title>
        <style>
            .shadow{
                --tw-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
                --tw-shadow-colored: 0 1px 3px 0 var(--tw-shadow-color), 0 1px 2px -1px var(--tw-shadow-color);
                box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
                box-shadow: #000000;
            }
            .table_email {
                max-width:750px;
                padding: 20px
            }

            @media only screen and (min-width: 600px) {
                .table_email {
                    max-width:90%;
                    padding: 5px
                }
            }
        </style>
    </head>
    <body style="color:#000000; font:600 18px Segoe UI,Arial; background-color: {{ ($data['colorBg'] ?? false) ? $data['colorBg'] : '#f8f8f4' }}; padding: 20px 0;">

        <table cellpadding="0" cellspacing="0" align="center" width="100%" style="padding: 10px 0 20px 0;">
            <thead>
                <tr>
                    <th>
                        @if ($data['urlLogo'] ?? false)
                            <img src="{{ $data['urlLogo'] }}" width="250px" style="padding:0px 10px;">
                        @else
                            <img src="{{ appUrl() }}/{{ appLogo() }}" width="250px" style="padding:0px 10px;" alt="{{ appName() }}">
                        @endif
                    </th>
                </tr>
            </thead>
        </table>
        {{--  --}}
        <table cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" style="border-radius: 10px;" class="table_email">
            <tbody>
                <tr>
                    <td>
                        @if ($data['header'] ?? false)
                            <div class="text-center">
                                <table border="0" cellpadding="1" cellspacing="0" align="center" width="95%">
                                    <tr>
                                        <td>
                                            <div style="font-weight: 700; margin: 0; padding-top: 10px; font-size: x-large; text-align: center; text-transform: capitalize;">{!! $data['header'] !!}</div>
                                            <br>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                        {{--  --}}
                        @if ($data['body'] ?? false)
                            <div class="text-center">
                                <table border="0" cellpadding="1" cellspacing="0" align="center" width="95%">
                                    <tr>
                                        <td>
                                            <div style="font-weight: 400; margin: 0; padding: 5px 0 15px 0; text-align: justify;">{!! $data['body'] !!}</div>
                                            <br>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                        {{--  --}}
                        <!-- <pre>{{ print_r($data) }}</pre> -->
                        {{--  --}}
                    </td>
                </tr>
                @if ($data['footer'] ?? false)
                    <tr>
                        <td>
                            <hr>
                                <div style="font-weight: 400; margin: 0; padding: 5px 5px 5px 5px; text-align: justify;">{!! $data['footer'] !!}</div>
                            <hr>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td valign="center" align="center" height="50" style="color:#000000;font:600 13px/18px Segoe UI,Arial">
                        {{ appName() }} - Copyright © {{ now()->format('Y') }} - Todos os direitos reservados
                    </td>
                </tr>
            </tbody>
        </table>
        {{--  --}}
        <br>
    </body>
</html>
