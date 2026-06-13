<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class LegacyDomainRoutesTest extends TestCase
{
    public function test_home_legada_de_eventos_resolve_rota_de_eventos()
    {
        $route = Route::getRoutes()->match(
            Request::create('https://eventos.proeventpay.com.br/', 'GET')
        );

        $this->assertSame('eventos-home', $route->getName());
    }

    public function test_home_legada_de_campanhas_resolve_rota_de_campanhas()
    {
        $route = Route::getRoutes()->match(
            Request::create('https://campanhas.proeventpay.com.br/', 'GET')
        );

        $this->assertSame('campanhas-home', $route->getName());
    }

    public function test_evento_legado_resolve_rota_publica_de_evento()
    {
        $route = Route::getRoutes()->match(
            Request::create('https://eventos.proeventpay.com.br/country-party-1779713107', 'GET')
        );

        $this->assertSame('evento-home', $route->getName());
    }

    public function test_campanha_legada_resolve_rota_publica_de_campanha()
    {
        $route = Route::getRoutes()->match(
            Request::create('https://campanhas.proeventpay.com.br/organizador/campanha-ativa', 'GET')
        );

        $this->assertSame('campanha-publica', $route->getName());
    }

    public function test_rota_invalida_do_painel_legado_resolve_fallback_do_painel_antigo()
    {
        $route = Route::getRoutes()->match(
            Request::create('https://painel.proeventpay.com.br/country-party-1779713107', 'GET')
        );

        $this->assertSame('painel.proeventpay.com.br', $route->getDomain());
        $this->assertNull($route->getName());
    }

    public function test_home_do_painel_legado_resolve_no_painel_antigo()
    {
        $route = Route::getRoutes()->match(
            Request::create('https://painel.proeventpay.com.br/', 'GET')
        );

        $this->assertSame('painel.proeventpay.com.br', $route->getDomain());
        $this->assertSame('dashboard', $route->getName());
    }
}
