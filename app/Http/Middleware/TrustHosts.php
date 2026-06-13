<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts()
    {
        return [
            'campanhas.igrejanovoscomecos.com.br',
            'eventos.igrejanovoscomecos.com.br',
            'assinaturas.igrejanovoscomecos.com.br',
            'painel.igrejanovoscomecos.com.br',
            'home.igrejanovoscomecos.com.br',
            'campanhas.proeventpay.com.br',
            'eventos.proeventpay.com.br',
            'painel.proeventpay.com.br',
            'campanhas.proeventpay.com',
            'campanhas-dev.proeventpay.com',
            'eventos.proeventpay.com',
            'eventos-dev.proeventpay.com',
            'assinaturas.proeventpay.com',
            'assinaturas-dev.proeventpay.com',
            'painel.proeventpay.com',
            'painel-dev.proeventpay.com',
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
