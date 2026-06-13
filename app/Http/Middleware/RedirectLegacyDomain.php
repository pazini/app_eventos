<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\ModCampaign\Campaign;
use App\Models\ModEvent\Event;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RedirectLegacyDomain
{
    private const LEGACY_DOMAIN_SUFFIX = 'proeventpay.com.br';
    private const TARGET_DOMAIN_SUFFIX = 'igrejanovoscomecos.com.br';
    private const REDIRECT_CUSTOMER_SLUG = 'novos-comecos';
    private const REVERSE_REDIRECT_CUSTOMER_SLUG = 'the-school';
    private const PANEL_HOSTS = [
        'painel.proeventpay.com.br',
        'painel.igrejanovoscomecos.com.br',
    ];
    private const CACHE_TTL_SECONDS = 300;
    private const REDIRECTABLE_SUBDOMAINS = [
        'eventos',
        'campanhas',
    ];

    /**
     * Redirect requests from public legacy domains to igrejanovoscomecos.com.br
     * somente quando o cliente Novos Começos existir.
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        if ($this->isRedirectableLegacyHostWithoutPublicSlug($request, $host)) {
            return $this->withoutRedirects($next($request));
        }

        $rule = $this->redirectRuleForRequest($request, $host);

        if ($rule) {
            if ($this->redirectAllowed($request, $host, $rule)) {
                return redirect()->to(
                    'https://' . $this->replaceHostForRule($host, $rule) . $request->getRequestUri(),
                    301
                );
            }

            if ($rule['block_unauthorized_redirects'] ?? false) {
                return $this->withoutRedirects($next($request));
            }
        }

        $response = $next($request);

        if ($this->isPanelHost($host)) {
            return $this->rewritePanelInternalUrlsInResponse($response, $host);
        }

        if (!$this->isRedirectableHost($host) || !$this->legacyRewriteAllowed($request, $host)) {
            return $response;
        }

        return $this->rewriteLegacyDomainInResponse($response);
    }

    private function isPanelHost(string $host): bool
    {
        return in_array(strtolower($host), self::PANEL_HOSTS, true);
    }

    private function rewritePanelInternalUrlsInResponse(Response $response, string $currentHost): Response
    {
        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return $response;
        }

        $location = $response->headers->get('Location');
        if (is_string($location)) {
            $response->headers->set(
                'Location',
                $this->replacePanelHosts($location, $currentHost)
            );
        }

        $contentType = strtolower((string) $response->headers->get('Content-Type', ''));
        $isTextResponse = str_contains($contentType, 'text/')
            || str_contains($contentType, 'application/json')
            || str_contains($contentType, 'application/javascript')
            || str_contains($contentType, 'application/xml');

        if (!$isTextResponse) {
            return $response;
        }

        $content = $response->getContent();
        if (is_string($content) && $this->containsPanelHost($content)) {
            $response->setContent(
                $this->replacePanelHosts($content, $currentHost)
            );
        }

        return $response;
    }

    private function containsPanelHost(string $value): bool
    {
        foreach (self::PANEL_HOSTS as $panelHost) {
            if (stripos($value, $panelHost) !== false) {
                return true;
            }
        }

        return false;
    }

    private function replacePanelHosts(string $value, string $currentHost): string
    {
        foreach (self::PANEL_HOSTS as $panelHost) {
            if (strtolower($panelHost) === strtolower($currentHost)) {
                continue;
            }

            $value = str_ireplace($panelHost, $currentHost, $value);
        }

        return $value;
    }

    private function withoutRedirects(Response $response): Response
    {
        if (!$response->isRedirection()) {
            return $response;
        }

        return response('Página não localizada no domínio solicitado.', 404);
    }

    protected function rewriteLegacyDomainInResponse(Response $response): Response
    {
        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return $response;
        }

        $location = $response->headers->get('Location');
        if (is_string($location)) {
            $response->headers->set(
                'Location',
                $this->replaceRedirectableUrls($location)
            );
        }

        $contentType = strtolower((string) $response->headers->get('Content-Type', ''));
        $isTextResponse = str_contains($contentType, 'text/')
            || str_contains($contentType, 'application/json')
            || str_contains($contentType, 'application/javascript')
            || str_contains($contentType, 'application/xml');

        if (!$isTextResponse) {
            return $response;
        }

        $content = $response->getContent();
        if (is_string($content) && $this->containsRedirectableLegacyUrl($content)) {
            $response->setContent(
                $this->replaceRedirectableUrls($content)
            );
        }

        return $response;
    }

    private function redirectAllowed(Request $request, string $host, array $rule): bool
    {
        $subdomain = $this->extractRedirectableSubdomain($host, $rule['source']);

        if (!$subdomain) {
            return false;
        }

        $resourceSlug = $this->extractPublicResourceSlug($subdomain, $request->path());
        if (!$resourceSlug) {
            return false;
        }

        return $this->customerOwnsPublicResource($subdomain, $resourceSlug, $rule['customer']);
    }

    private function legacyRewriteAllowed(Request $request, string $host): bool
    {
        $subdomain = $this->extractRedirectableSubdomain($host, self::LEGACY_DOMAIN_SUFFIX)
            ?? $this->extractRedirectableSubdomain($host, self::TARGET_DOMAIN_SUFFIX);

        if (!in_array($subdomain, self::REDIRECTABLE_SUBDOMAINS, true)) {
            return false;
        }

        $resourceSlug = $this->extractPublicResourceSlug($subdomain, $request->path());
        if (!$resourceSlug) {
            return false;
        }

        return $this->customerOwnsPublicResource($subdomain, $resourceSlug, self::REDIRECT_CUSTOMER_SLUG);
    }

    private function customerOwnsPublicResource(string $subdomain, string $resourceSlug, string $customerSlug): bool
    {
        $cacheKey = implode(':', [
            'domain_redirect_customer',
            $customerSlug,
            $subdomain,
            $resourceSlug,
        ]);

        return (bool) Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($subdomain, $resourceSlug, $customerSlug) {
            if ($subdomain === 'eventos') {
                return $this->eventBelongsToCustomer($resourceSlug, $customerSlug);
            }

            if ($subdomain === 'campanhas') {
                return $this->campaignBelongsToCustomer($resourceSlug, $customerSlug);
            }

            return false;
        });
    }

    private function eventBelongsToCustomer(string $eventSlug, string $customerSlug): bool
    {
        return DB::table((new Event())->getTable() . ' as events')
            ->join((new Customer())->getTable() . ' as customers', 'customers.id', '=', 'events.customer_id')
            ->where('events.event_slug', $eventSlug)
            ->where('customers.customer_slug', $customerSlug)
            ->exists();
    }

    private function campaignBelongsToCustomer(string $campaignSlug, string $customerSlug): bool
    {
        return DB::table((new Campaign())->getTable() . ' as campaigns')
            ->join((new Customer())->getTable() . ' as customers', 'customers.id', '=', 'campaigns.customer_id')
            ->where('campaigns.slug', $campaignSlug)
            ->where('customers.customer_slug', $customerSlug)
            ->exists();
    }

    private function redirectRuleForRequest(Request $request, string $host): ?array
    {
        foreach ($this->redirectRules() as $rule) {
            $subdomain = $this->extractRedirectableSubdomain($host, $rule['source']);

            if (
                in_array($subdomain, self::REDIRECTABLE_SUBDOMAINS, true)
                && $this->extractPublicResourceSlug($subdomain, $request->path())
            ) {
                return $rule;
            }
        }

        return null;
    }

    private function displayRewriteRule(): array
    {
        return [
            'source' => self::LEGACY_DOMAIN_SUFFIX,
            'target' => self::TARGET_DOMAIN_SUFFIX,
            'customer' => self::REDIRECT_CUSTOMER_SLUG,
            'block_unauthorized_redirects' => true,
        ];
    }

    private function redirectRules(): array
    {
        return [
            $this->displayRewriteRule(),
            [
                'source' => self::TARGET_DOMAIN_SUFFIX,
                'target' => self::LEGACY_DOMAIN_SUFFIX,
                'customer' => self::REVERSE_REDIRECT_CUSTOMER_SLUG,
                'block_unauthorized_redirects' => false,
            ],
        ];
    }

    private function isRedirectableLegacyHost(string $host): bool
    {
        return in_array($this->extractRedirectableSubdomain($host, self::LEGACY_DOMAIN_SUFFIX), self::REDIRECTABLE_SUBDOMAINS, true);
    }

    private function isRedirectableTargetHost(string $host): bool
    {
        return in_array($this->extractRedirectableSubdomain($host, self::TARGET_DOMAIN_SUFFIX), self::REDIRECTABLE_SUBDOMAINS, true);
    }

    private function isRedirectableHost(string $host): bool
    {
        return $this->isRedirectableLegacyHost($host) || $this->isRedirectableTargetHost($host);
    }

    private function isRedirectableLegacyHostWithoutPublicSlug(Request $request, string $host): bool
    {
        $subdomain = $this->extractRedirectableSubdomain($host, self::LEGACY_DOMAIN_SUFFIX);

        return in_array($subdomain, self::REDIRECTABLE_SUBDOMAINS, true)
            && !$this->extractPublicResourceSlug($subdomain, $request->path());
    }

    private function replaceLegacyHost(string $host): string
    {
        return $this->replaceHostForRule($host, $this->displayRewriteRule());
    }

    private function replaceHostForRule(string $host, array $rule): string
    {
        $subdomain = $this->extractRedirectableSubdomain($host, $rule['source']);

        return $subdomain ? $subdomain . '.' . $rule['target'] : $host;
    }

    private function extractPublicResourceSlug(string $subdomain, string $path): ?string
    {
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));

        if ($subdomain === 'eventos') {
            return $this->extractEventSlug($segments);
        }

        if ($subdomain === 'campanhas') {
            return $this->extractCampaignSlug($segments);
        }

        return null;
    }

    private function extractEventSlug(array $segments): ?string
    {
        if (count($segments) !== 1) {
            return null;
        }

        $reservedPaths = [
            'app-version',
            'app-version-reset',
            'checkin',
            'ingressos',
            'minhas-compras',
            'pagamento',
            'patrocinicar',
            'pedido',
            'vouchers',
        ];

        $slug = strtolower((string) $segments[0]);

        return in_array($slug, $reservedPaths, true) ? null : $slug;
    }

    private function extractCampaignSlug(array $segments): ?string
    {
        if (count($segments) >= 2) {
            return strtolower((string) $segments[1]);
        }

        if (count($segments) === 1) {
            $reservedPaths = [
                'app',
                'minhas-doacoes',
            ];

            $slug = strtolower((string) $segments[0]);

            return in_array($slug, $reservedPaths, true) ? null : $slug;
        }

        return null;
    }

    private function extractRedirectableSubdomain(string $host, string $domainSuffix): ?string
    {
        $pattern = '/^([a-z0-9-]+)\.' . preg_quote($domainSuffix, '/') . '$/i';

        if (preg_match($pattern, $host, $matches) !== 1) {
            return null;
        }

        return strtolower($matches[1]);
    }

    private function containsRedirectableLegacyUrl(string $value): bool
    {
        foreach (self::REDIRECTABLE_SUBDOMAINS as $subdomain) {
            if (stripos($value, $subdomain . '.' . self::LEGACY_DOMAIN_SUFFIX) !== false) {
                return true;
            }
        }

        return false;
    }

    private function replaceRedirectableUrls(string $value): string
    {
        foreach (self::REDIRECTABLE_SUBDOMAINS as $subdomain) {
            $value = str_ireplace(
                $subdomain . '.' . self::LEGACY_DOMAIN_SUFFIX,
                $subdomain . '.' . self::TARGET_DOMAIN_SUFFIX,
                $value
            );
        }

        return $value;
    }
}
