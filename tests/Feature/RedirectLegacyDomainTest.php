<?php

namespace Tests\Feature;

use App\Http\Middleware\RedirectLegacyDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RedirectLegacyDomainTest extends TestCase
{
    public function test_redireciona_dominio_legado_para_https_quando_cliente_novos_comecos_existe()
    {
        $this->mockRedirectAllowed('novos-comecos', 'eventos', 'evento-novos-comecos', true);

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.proeventpay.com.br/evento-novos-comecos?x=1'),
            fn () => new Response('nao deveria executar')
        );

        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame(
            'https://eventos.igrejanovoscomecos.com.br/evento-novos-comecos?x=1',
            $response->headers->get('Location')
        );
    }

    public function test_redireciona_campanhas_legado_para_https_quando_cliente_novos_comecos_existe()
    {
        $this->mockRedirectAllowed('novos-comecos', 'campanhas', 'campanha-ativa', true);

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://campanhas.proeventpay.com.br/campanha-ativa?x=1'),
            fn () => new Response('nao deveria executar')
        );

        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame(
            'https://campanhas.igrejanovoscomecos.com.br/campanha-ativa?x=1',
            $response->headers->get('Location')
        );
    }

    public function test_nao_redireciona_evento_legado_quando_evento_nao_pertence_ao_cliente_novos_comecos()
    {
        $this->mockRedirectAllowed('novos-comecos', 'eventos', 'country-party-1779713107', false);

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.proeventpay.com.br/country-party-1779713107'),
            fn () => new Response('', 302, [
                'Location' => 'https://eventos.igrejanovoscomecos.com.br/country-party-1779713107',
            ])
        );

        $this->assertSame(404, $response->getStatusCode());
        $this->assertNull($response->headers->get('Location'));
    }

    public function test_nao_redireciona_home_legada_de_eventos_e_nao_consulta_regra_do_cliente()
    {
        Cache::shouldReceive('remember')->never();

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.proeventpay.com.br/'),
            fn () => new Response('', 302, [
                'Location' => 'https://eventos.igrejanovoscomecos.com.br/',
            ])
        );

        $this->assertSame(404, $response->getStatusCode());
        $this->assertNull($response->headers->get('Location'));
    }

    public function test_nao_redireciona_home_legada_de_eventos_com_query_string()
    {
        Cache::shouldReceive('remember')->never();

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.proeventpay.com.br/?utm_source=teste'),
            fn () => new Response('', 302, [
                'Location' => 'https://eventos.igrejanovoscomecos.com.br/?utm_source=teste',
            ])
        );

        $this->assertSame(404, $response->getStatusCode());
        $this->assertNull($response->headers->get('Location'));
    }

    public function test_nao_redireciona_home_legada_de_campanhas_e_nao_consulta_regra_do_cliente()
    {
        Cache::shouldReceive('remember')->never();

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://campanhas.proeventpay.com.br/'),
            fn () => new Response('', 302, [
                'Location' => 'https://eventos.igrejanovoscomecos.com.br/',
            ])
        );

        $this->assertSame(404, $response->getStatusCode());
        $this->assertNull($response->headers->get('Location'));
    }

    public function test_nao_redireciona_painel_legado_e_nao_consulta_regra_do_cliente()
    {
        Cache::shouldReceive('remember')->never();

        $nextWasCalled = false;

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://painel.proeventpay.com.br/'),
            function () use (&$nextWasCalled) {
                $nextWasCalled = true;

                return new Response('painel no dominio antigo', 200, ['Content-Type' => 'text/plain']);
            }
        );

        $this->assertTrue($nextWasCalled);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('painel no dominio antigo', $response->getContent());
        $this->assertNull($response->headers->get('Location'));
    }

    public function test_links_internos_do_painel_antigo_preservam_host_atual()
    {
        Cache::shouldReceive('remember')->never();

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://painel.proeventpay.com.br/'),
            fn () => new Response(
                '<a href="https://painel.igrejanovoscomecos.com.br/eventos">Eventos</a>',
                200,
                ['Content-Type' => 'text/html; charset=UTF-8']
            )
        );

        $this->assertStringContainsString(
            'https://painel.proeventpay.com.br/eventos',
            $response->getContent()
        );
        $this->assertStringNotContainsString(
            'painel.igrejanovoscomecos.com.br/eventos',
            $response->getContent()
        );
    }

    public function test_links_internos_do_painel_da_igreja_preservam_host_atual()
    {
        Cache::shouldReceive('remember')->never();

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://painel.igrejanovoscomecos.com.br/'),
            fn () => new Response(
                '<a href="https://painel.proeventpay.com.br/eventos">Eventos</a>',
                200,
                ['Content-Type' => 'text/html; charset=UTF-8']
            )
        );

        $this->assertStringContainsString(
            'https://painel.igrejanovoscomecos.com.br/eventos',
            $response->getContent()
        );
        $this->assertStringNotContainsString(
            'painel.proeventpay.com.br/eventos',
            $response->getContent()
        );
    }

    public function test_location_interno_do_painel_preserva_host_atual()
    {
        Cache::shouldReceive('remember')->never();

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://painel.proeventpay.com.br/'),
            fn () => new Response('', 302, [
                'Location' => 'https://painel.igrejanovoscomecos.com.br/eventos',
            ])
        );

        $this->assertSame(
            'https://painel.proeventpay.com.br/eventos',
            $response->headers->get('Location')
        );
    }

    public function test_nao_redireciona_dominio_legado_quando_cliente_novos_comecos_nao_existe()
    {
        $this->mockRedirectAllowed('novos-comecos', 'eventos', 'evento-novos-comecos', false);

        $nextWasCalled = false;

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.proeventpay.com.br/evento-novos-comecos?x=1'),
            function () use (&$nextWasCalled) {
                $nextWasCalled = true;

                return new Response('fluxo normal', 200, ['Content-Type' => 'text/plain']);
            }
        );

        $this->assertTrue($nextWasCalled);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('fluxo normal', $response->getContent());
        $this->assertNull($response->headers->get('Location'));
    }

    public function test_reescreve_location_legado_quando_cliente_novos_comecos_existe()
    {
        $this->mockRedirectAllowed('the-school', 'eventos', 'evento-novos-comecos', false);
        $this->mockRedirectAllowed('novos-comecos', 'eventos', 'evento-novos-comecos', true);

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.igrejanovoscomecos.com.br/evento-novos-comecos'),
            fn () => new Response('', 302, [
                'Location' => 'https://eventos.proeventpay.com.br/pedido/ABC123',
            ])
        );

        $this->assertSame(
            'https://eventos.igrejanovoscomecos.com.br/pedido/ABC123',
            $response->headers->get('Location')
        );
    }

    public function test_nao_reescreve_location_do_painel_legado()
    {
        $this->mockRedirectAllowed('the-school', 'eventos', 'evento-novos-comecos', false);
        $this->mockRedirectAllowed('novos-comecos', 'eventos', 'evento-novos-comecos', true);

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.igrejanovoscomecos.com.br/evento-novos-comecos'),
            fn () => new Response('', 302, [
                'Location' => 'https://painel.proeventpay.com.br/',
            ])
        );

        $this->assertSame(
            'https://painel.proeventpay.com.br/',
            $response->headers->get('Location')
        );
    }

    public function test_nao_reescreve_location_legado_quando_cliente_novos_comecos_nao_existe()
    {
        $this->mockRedirectAllowed('the-school', 'eventos', 'evento-novos-comecos', false);
        $this->mockRedirectAllowed('novos-comecos', 'eventos', 'evento-novos-comecos', false);

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.igrejanovoscomecos.com.br/evento-novos-comecos'),
            fn () => new Response('', 302, [
                'Location' => 'https://eventos.proeventpay.com.br/pedido/ABC123',
            ])
        );

        $this->assertSame(
            'https://eventos.proeventpay.com.br/pedido/ABC123',
            $response->headers->get('Location')
        );
    }

    public function test_reescreve_conteudo_textual_quando_cliente_novos_comecos_existe()
    {
        $this->mockRedirectAllowed('the-school', 'eventos', 'evento-novos-comecos', false);
        $this->mockRedirectAllowed('novos-comecos', 'eventos', 'evento-novos-comecos', true);

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.igrejanovoscomecos.com.br/evento-novos-comecos'),
            fn () => new Response(
                '<a href="https://eventos.proeventpay.com.br/minhas-compras">Minhas compras</a>',
                200,
                ['Content-Type' => 'text/html; charset=UTF-8']
            )
        );

        $this->assertStringContainsString('https://eventos.igrejanovoscomecos.com.br/minhas-compras', $response->getContent());
        $this->assertStringNotContainsString('eventos.proeventpay.com.br', $response->getContent());
    }

    public function test_nao_reescreve_conteudo_textual_do_painel_legado()
    {
        $this->mockRedirectAllowed('the-school', 'eventos', 'evento-novos-comecos', false);
        $this->mockRedirectAllowed('novos-comecos', 'eventos', 'evento-novos-comecos', true);

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.igrejanovoscomecos.com.br/evento-novos-comecos'),
            fn () => new Response(
                '<a href="https://painel.proeventpay.com.br/">Painel</a>',
                200,
                ['Content-Type' => 'text/html; charset=UTF-8']
            )
        );

        $this->assertStringContainsString('https://painel.proeventpay.com.br/', $response->getContent());
        $this->assertStringNotContainsString('painel.igrejanovoscomecos.com.br', $response->getContent());
    }

    public function test_nao_reescreve_conteudo_textual_quando_cliente_novos_comecos_nao_existe()
    {
        $this->mockRedirectAllowed('the-school', 'eventos', 'evento-novos-comecos', false);
        $this->mockRedirectAllowed('novos-comecos', 'eventos', 'evento-novos-comecos', false);

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.igrejanovoscomecos.com.br/evento-novos-comecos'),
            fn () => new Response(
                '<a href="https://eventos.proeventpay.com.br/minhas-compras">Minhas compras</a>',
                200,
                ['Content-Type' => 'text/html; charset=UTF-8']
            )
        );

        $this->assertStringContainsString('https://eventos.proeventpay.com.br/minhas-compras', $response->getContent());
    }

    public function test_nao_reescreve_resposta_binaria()
    {
        $this->mockRedirectAllowed('the-school', 'eventos', 'arquivo', false);
        $this->mockRedirectAllowed('novos-comecos', 'eventos', 'arquivo', true);

        $file = tempnam(sys_get_temp_dir(), 'legacy-domain-test-');
        file_put_contents($file, 'https://eventos.proeventpay.com.br/arquivo');

        try {
            $binaryResponse = new BinaryFileResponse($file);
            $binaryResponse->headers->set('Location', 'https://eventos.proeventpay.com.br/arquivo');

            $response = (new RedirectLegacyDomain())->handle(
                Request::create('https://eventos.igrejanovoscomecos.com.br/arquivo'),
                fn () => $binaryResponse
            );

            $this->assertSame('https://eventos.proeventpay.com.br/arquivo', $response->headers->get('Location'));
        } finally {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function test_redireciona_evento_da_igreja_para_proeventpay_quando_cliente_the_school()
    {
        $this->mockRedirectAllowed('the-school', 'eventos', 'evento-the-school', true);

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.igrejanovoscomecos.com.br/evento-the-school?x=1'),
            fn () => new Response('nao deveria executar')
        );

        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame(
            'https://eventos.proeventpay.com.br/evento-the-school?x=1',
            $response->headers->get('Location')
        );
    }

    public function test_redireciona_campanha_da_igreja_para_proeventpay_quando_cliente_the_school()
    {
        $this->mockRedirectAllowed('the-school', 'campanhas', 'campanha-the-school', true);

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://campanhas.igrejanovoscomecos.com.br/organizacao/campanha-the-school?x=1'),
            fn () => new Response('nao deveria executar')
        );

        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame(
            'https://campanhas.proeventpay.com.br/organizacao/campanha-the-school?x=1',
            $response->headers->get('Location')
        );
    }

    public function test_nao_redireciona_dominio_da_igreja_quando_recurso_nao_pertence_ao_cliente_the_school()
    {
        $this->mockRedirectAllowed('the-school', 'eventos', 'evento-novos-comecos', false);
        $this->mockRedirectAllowed('novos-comecos', 'eventos', 'evento-novos-comecos', false);

        $nextWasCalled = false;

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.igrejanovoscomecos.com.br/evento-novos-comecos'),
            function () use (&$nextWasCalled) {
                $nextWasCalled = true;

                return new Response('fluxo normal', 200, ['Content-Type' => 'text/plain']);
            }
        );

        $this->assertTrue($nextWasCalled);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('fluxo normal', $response->getContent());
        $this->assertNull($response->headers->get('Location'));
    }

    public function test_nao_redireciona_home_da_igreja_e_nao_consulta_regra_do_cliente()
    {
        Cache::shouldReceive('remember')->never();

        $nextWasCalled = false;

        $response = (new RedirectLegacyDomain())->handle(
            Request::create('https://eventos.igrejanovoscomecos.com.br/'),
            function () use (&$nextWasCalled) {
                $nextWasCalled = true;

                return new Response('home igreja', 200, ['Content-Type' => 'text/plain']);
            }
        );

        $this->assertTrue($nextWasCalled);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('home igreja', $response->getContent());
        $this->assertNull($response->headers->get('Location'));
    }

    private function mockRedirectAllowed(string $customerSlug, string $subdomain, string $resourceSlug, bool $allowed): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->with("domain_redirect_customer:{$customerSlug}:{$subdomain}:{$resourceSlug}", 300, Mockery::type('Closure'))
            ->andReturn($allowed);
    }
}
