<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Domínios da Aplicação
    |--------------------------------------------------------------------------
    |
    | Configuração dos domínios para campanhas, eventos e painel.
    | Configure manualmente no .env de cada ambiente (produção/homologação).
    |
    | Produção:
    | DOMAIN_PAINEL=https://painel.proeventpay.com
    | DOMAIN_EVENTOS=https://eventos.proeventpay.com
    | DOMAIN_CAMPANHAS=https://campanhas.proeventpay.com
    | DOMAIN_ASSINATURAS=https://assinaturas.proeventpay.com
    |
    | Homologação:
    | DOMAIN_PAINEL=https://painel-dev.proeventpay.com
    | DOMAIN_EVENTOS=https://eventos-dev.proeventpay.com
    | DOMAIN_CAMPANHAS=https://campanhas-dev.proeventpay.com
    | DOMAIN_ASSINATURAS=https://assinaturas-dev.proeventpay.com
    |
    */

    'home' => env('DOMAIN_HOME'),
    'campanhas' => env('DOMAIN_CAMPANHAS'),
    'eventos' => env('DOMAIN_EVENTOS'),
    'painel' => env('DOMAIN_PAINEL'),
    'assinaturas' => env('DOMAIN_ASSINATURAS'),
    ];

