<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Teste PagSeguro</title>
    </head>
    <body>

        <script src="https://assets.pagseguro.com.br/checkout-sdk-js/rc/dist/browser/pagseguro.min.js"></script>
        <script>
            var card = PagSeguro.encryptCard({
                // publicKey: "6eaf07ea-dc5d-46f5-bfce-e070d372ed2f1f8685cc46aca40e59796f4f3e46d844b0ce-83b5-4011-9362-2c74f43fd7fa",
                // publicKey: "6C9CDFF98C5346118D2D909DB2557BBD",
                // publicKey: "PUBC8A98BA9221B4BAC8CF970A8285F644F",
                publicKey: "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmmvWZcj7cd6ZKRGJGGFUaU7CW+SaGO4wA0jU2P2QzJ2+MgUhay5tODN2wlSMuSL+Vn6Qq9fYJOfLWZI6PzfyRbO3ArSvGZ/O48jveIrjQoxD7vjet8isJ92HPCQZzdEgE/VThpbDqQu+UkIVfWir+9Em4NtCNF5D3egFLxQGyDVlVvTrJGHqY2GEb/2jKAJIXrHVeGjJSUWXzye8qx5v2xDgKtzzE7WNBHtRXe7R7AND2p2SpyTTWbHMFwhVcGq3/E2cZxdGndJJmWK3Ol72QGgPzK4mM30BBC6Y/0kAtKbO5B4G3bQWUWNUFyjfKpoqzZu9ZJ9Gr32P2oRe89gkcwIDAQAB",
                holder: "MARIA DO TESTE",
                number: "4242424242424242",
                expMonth: "12",
                expYear: "2030",
                securityCode: "123"
            });

            // F21ivFKv39c3rz6Ki0aK1JZr3wcjYwUYWMXRPrZs6TXt1sq3WErvR1C/i1Qt31AoNgo8Uw/cxgbD8fP92nNAARUpRsLOC1kSuc/3fzr21T2e+b9f4LOoTftjvI94LtrMHLatu8dAj45l8U3J/gw1SasBym6kZ2L4zgqZZXZoNi9+juITRIlpEeS36XK4NV6tVWUEdCjsvuoeJYZykAbBhmFmYad+am+Beohjwba1O8EjvsFOwe0iJH9eenbOoVXPOr+jlSDnygJpS0jlaVcuwjbtSqD7lwcV//Gmbqhx3wQGfBmwp27Br1+21kKZro5bhar1/NOkL2XUTFyALNNkfw==

            var encrypted = card.encryptedCard;

            console.log('---------------------------------------------')
            console.log(card)
            console.log(encrypted)
            console.log('---------------------------------------------')
        </script>


    </body>
</html>
