<?php

namespace App\Http\Middleware;

use App\Models\App;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class IdentifyTenant
{
    /**
     * Handle an incoming request e identificar o tenant (App) pelo domínio.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Pega o host da requisição (ex: campanhas.proeventpay.com)
        $host = $request->getHost();

        // Tenta identificar o app pelo domínio com cache de 1 hora
        $app = $this->identifyAppByDomain($host);

        if (!$app) {
            // Fallback: tenta buscar app padrão (primeiro ativo)
            $app = $this->getDefaultApp();

            if (!$app) {
                // Caso crítico: nenhum app encontrado
                Log::error("IdentifyTenant: Nenhum app encontrado", [
                    'host' => $host,
                    'url' => $request->fullUrl()
                ]);

                abort(503, 'Aplicação não encontrada. Entre em contato com o suporte.');
            }

            Log::warning("IdentifyTenant: Usando app padrão", [
                'host' => $host,
                'app_id' => $app->id,
                'app_name' => $app->app_name
            ]);
        }

        // Verifica se o app está ativo
        if (!$app->app_active) {
            Log::warning("IdentifyTenant: App inativo", [
                'host' => $host,
                'app_id' => $app->id,
                'app_name' => $app->app_name
            ]);

            abort(503, 'Esta aplicação está temporariamente indisponível.');
        }

        // Verifica se a licença expirou
        if ($app->app_limit_date && $app->app_limit_date->isPast()) {
            Log::warning("IdentifyTenant: Licença expirada", [
                'host' => $host,
                'app_id' => $app->id,
                'app_name' => $app->app_name,
                'limit_date' => \Carbon\Carbon::parse($app->app_limit_date)->format('Y-m-d')
            ]);

            abort(402, 'A licença desta aplicação expirou. Entre em contato para renovar.');
        }

        // Injeta o app no request (disponível em controllers via $request->app)
        $request->attributes->set('app', $app);

        // Injeta o app na sessão (compatibilidade com sessionApp() existente)
        session(['app' => $app]);
        session(['app_id' => $app->id]);

        // Injeta variáveis globais para views
        view()->share('app', $app);
        view()->share('appName', $app->app_name);
        view()->share('appLogo', $app->logo_url);
        view()->share('appColors', [
            'primary' => $app->color_primary,
            'secondary' => $app->color_secondary,
            'accent' => $app->color_accent,
        ]);

        // Tenta identificar customer pelo domínio/subdomínio
        $customer = $this->identifyCustomerByDomain($host, $app);
        if ($customer) {
            $request->attributes->set('customer', $customer);
            session(['tenant_customer' => $customer]);
            session(['tenant_customer_id' => $customer->id]);
            view()->share('tenantCustomer', $customer);

            if (config('app.debug')) {
                Log::debug("IdentifyTenant: Customer identificado", [
                    'host' => $host,
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name_fantasy ?? $customer->name_corporate
                ]);
            }
        }

        // Log de sucesso (apenas em debug)
        if (config('app.debug')) {
            Log::channel('identify_tenant')->debug("IdentifyTenant: App identificado", [
                'host' => $host,
                'app_id' => $app->id,
                'app_name' => $app->app_name,
                'domain' => $app->domain_primary
            ]);
        }

        return $next($request);
    }

    /**
     * Identifica o app pelo domínio com cache
     *
     * @param string $host
     * @return App|null
     */
    protected function identifyAppByDomain(string $host): ?App
    {
        // Cache key baseado no host
        $cacheKey = "tenant_app_{$host}";

        // Tenta buscar do cache (1 hora)
        return Cache::remember($cacheKey, 3600, function () use ($host) {
            // Remove www. se existir
            $cleanHost = str_replace('www.', '', $host);

            // Estratégia 1: Busca exata por domain_primary
            $app = App::where('app_active', true)
                ->where('domain_primary', $cleanHost)
                ->first();

            if ($app) {
                return $app;
            }

            // Estratégia 2: Busca por domain_primary com like (para subdomínios)
            // Ex: campanhas.proeventpay.com deve encontrar proeventpay.com
            $app = App::where('app_active', true)
                ->where(function ($query) use ($cleanHost) {
                    $query->where('domain_primary', 'like', "%{$cleanHost}%")
                        ->orWhereRaw("? LIKE '%' || domain_primary || '%'", [$cleanHost]);
                })
                ->first();

            if ($app) {
                return $app;
            }

            // Estratégia 3: Busca em domain_aliases (JSON array)
            $app = App::where('app_active', true)
                ->whereRaw("domain_aliases::text LIKE ?", ["%{$cleanHost}%"])
                ->get()
                ->first(function ($app) use ($cleanHost) {
                    // Valida se realmente é um alias válido
                    $aliases = $app->domain_aliases ?? [];
                    foreach ($aliases as $alias) {
                        if (str_contains($cleanHost, $alias) || str_contains($alias, $cleanHost)) {
                            return true;
                        }
                    }
                    return false;
                });

            return $app;
        });
    }

    /**
     * Retorna o app padrão (fallback)
     *
     * @return App|null
     */
    protected function getDefaultApp(): ?App
    {
        // Cache do app padrão por 1 hora
        return Cache::remember('tenant_app_default', 3600, function () {
            // Tenta buscar o ProEventPay (original)
            $app = App::where('app_active', true)
                ->where('app_name', 'ProEventPay')
                ->first();

            if ($app) {
                return $app;
            }

            // Fallback: primeiro app ativo
            return App::where('app_active', true)
                ->orderBy('created_at', 'asc')
                ->first();
        });
    }

    /**
     * Identifica o customer pelo domínio/subdomínio
     *
     * @param string $host
     * @param App $app
     * @return \App\Models\Customer|null
     */
    protected function identifyCustomerByDomain(string $host, App $app): ?\App\Models\Customer
    {
        // Remove www. se existir
        $host = preg_replace('/^www\./i', '', $host);

        // Extrai o subdomínio (primeira parte antes do primeiro ponto)
        $parts = explode('.', $host);
        $subdomain = $parts[0] ?? null;

        if (!$subdomain) {
            return null;
        }

        // Busca customer pelo prefix_url ou customer_slug dentro do app atual
        return \App\Models\Customer::where('app_id', $app->id)
            ->where(function ($query) use ($subdomain) {
                $query->where('prefix_url', $subdomain)
                      ->orWhere('customer_slug', $subdomain);
            })
            ->first();
    }

    /**
     * Limpa o cache de um domínio específico
     *
     * @param string $host
     * @return void
     */
    public static function clearDomainCache(string $host): void
    {
        $cleanHost = str_replace('www.', '', $host);
        Cache::forget("tenant_app_{$cleanHost}");
    }

    /**
     * Limpa todo o cache de tenants
     *
     * @return void
     */
    public static function clearAllCache(): void
    {
        Cache::forget('tenant_app_default');
        // Nota: Cache individual por domínio será limpo conforme expirar
    }
}
