<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Serviço para gerenciar cache isolado por tenant
 * Garante que cada app tenha seu próprio namespace de cache
 */
class TenantCacheService
{
    /**
     * Prefixo base para todas as chaves de cache de tenant
     */
    protected const PREFIX = 'tenant';

    /**
     * TTL padrão em segundos (1 hora)
     */
    protected const DEFAULT_TTL = 3600;

    /**
     * Obtém o ID do tenant atual
     *
     * @return string|null
     */
    protected static function getTenantId(): ?string
    {
        $app = currentApp();
        return $app ? (string) $app->id : null;
    }

    /**
     * Gera chave de cache com prefixo do tenant
     *
     * @param string $key Chave original
     * @param string|null $tenantId ID do tenant (null = usa atual)
     * @return string
     */
    protected static function generateKey(string $key, ?string $tenantId = null): string
    {
        $tenantId = $tenantId ?? self::getTenantId();

        if (!$tenantId) {
            // Sem tenant, usa prefixo global
            return self::PREFIX . '_global_' . $key;
        }

        return self::PREFIX . '_' . $tenantId . '_' . $key;
    }

    /**
     * Armazena um valor no cache do tenant
     *
     * @param string $key Chave
     * @param mixed $value Valor
     * @param int|null $ttl Tempo de vida em segundos (null = padrão 1h)
     * @return bool
     */
    public static function put(string $key, $value, ?int $ttl = null): bool
    {
        $cacheKey = self::generateKey($key);
        $ttl = $ttl ?? self::DEFAULT_TTL;

        return Cache::put($cacheKey, $value, $ttl);
    }

    /**
     * Obtém um valor do cache do tenant
     *
     * @param string $key Chave
     * @param mixed $default Valor padrão se não encontrar
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = self::generateKey($key);
        return Cache::get($cacheKey, $default);
    }

    /**
     * Obtém valor ou armazena resultado de callback (cache remember)
     *
     * @param string $key Chave
     * @param \Closure $callback Função que retorna o valor
     * @param int|null $ttl Tempo de vida em segundos
     * @return mixed
     */
    public static function remember(string $key, \Closure $callback, ?int $ttl = null)
    {
        $cacheKey = self::generateKey($key);
        $ttl = $ttl ?? self::DEFAULT_TTL;

        return Cache::remember($cacheKey, $ttl, $callback);
    }

    /**
     * Verifica se uma chave existe no cache do tenant
     *
     * @param string $key Chave
     * @return bool
     */
    public static function has(string $key): bool
    {
        $cacheKey = self::generateKey($key);
        return Cache::has($cacheKey);
    }

    /**
     * Remove um valor do cache do tenant
     *
     * @param string $key Chave
     * @return bool
     */
    public static function forget(string $key): bool
    {
        $cacheKey = self::generateKey($key);
        return Cache::forget($cacheKey);
    }

    /**
     * Remove múltiplas chaves do cache do tenant
     *
     * @param array $keys Array de chaves
     * @return void
     */
    public static function forgetMany(array $keys): void
    {
        foreach ($keys as $key) {
            self::forget($key);
        }
    }

    /**
     * Limpa todo o cache de um tenant específico
     * ATENÇÃO: Operação pesada! Use com cautela.
     *
     * @param string|null $tenantId UUID do tenant (null = tenant atual)
     * @return int Número de chaves removidas
     */
    public static function flush(?string $tenantId = null): int
    {
        $tenantId = $tenantId ?? self::getTenantId();

        if (!$tenantId) {
            return 0;
        }

        $prefix = self::PREFIX . '_' . $tenantId . '_';
        $removed = 0;

        // Busca todas as chaves com o prefixo do tenant
        // Nota: Implementação varia conforme driver de cache
        if (config('cache.default') === 'file') {
            // Para file cache, precisamos iterar manualmente
            $path = storage_path('framework/cache/data');
            if (is_dir($path)) {
                $files = glob($path . '/*');
                foreach ($files as $file) {
                    if (is_file($file) && is_readable($file)) {
                        try {
                            $content = file_get_contents($file);
                            if ($content && strpos($content, $prefix) !== false) {
                                @unlink($file);
                                $removed++;
                            }
                        } catch (\Exception $e) {
                            // Ignora arquivos que não podem ser lidos/removidos
                            continue;
                        }
                    }
                }
            }
        } else {
            // Para Redis/Memcached, usa flush com pattern
            // Implementação específica por driver
            Cache::flush(); // Fallback: limpa tudo (use com cuidado!)
        }

        return $removed;
    }

    /**
     * Obtém todas as chaves de cache do tenant atual
     * Útil para debug e estatísticas
     *
     * @return array
     */
    public static function keys(): array
    {
        $tenantId = self::getTenantId();

        if (!$tenantId) {
            return [];
        }

        $prefix = self::PREFIX . '_' . $tenantId . '_';
        $keys = [];

        // Implementação varia conforme driver
        if (config('cache.default') === 'file') {
            $path = storage_path('framework/cache/data');
            if (is_dir($path)) {
                $files = glob($path . '/*');
                foreach ($files as $file) {
                    if (is_file($file) && is_readable($file)) {
                        try {
                            $content = file_get_contents($file);
                            if ($content && strpos($content, $prefix) !== false) {
                                // Extrai nome da chave do conteúdo serializado
                                $keys[] = basename($file);
                            }
                        } catch (\Exception $e) {
                            // Ignora arquivos que não podem ser lidos
                            continue;
                        }
                    }
                }
            }
        }

        return $keys;
    }

    /**
     * Incrementa um contador no cache do tenant
     *
     * @param string $key Chave
     * @param int $value Valor a incrementar (padrão: 1)
     * @return int|bool Novo valor ou false em caso de erro
     */
    public static function increment(string $key, int $value = 1)
    {
        $cacheKey = self::generateKey($key);
        return Cache::increment($cacheKey, $value);
    }

    /**
     * Decrementa um contador no cache do tenant
     *
     * @param string $key Chave
     * @param int $value Valor a decrementar (padrão: 1)
     * @return int|bool Novo valor ou false em caso de erro
     */
    public static function decrement(string $key, int $value = 1)
    {
        $cacheKey = self::generateKey($key);
        return Cache::decrement($cacheKey, $value);
    }

    /**
     * Armazena permanentemente (sem TTL)
     *
     * @param string $key Chave
     * @param mixed $value Valor
     * @return bool
     */
    public static function forever(string $key, $value): bool
    {
        $cacheKey = self::generateKey($key);
        return Cache::forever($cacheKey, $value);
    }

    /**
     * Obtém estatísticas de uso de cache do tenant
     *
     * @return array
     */
    public static function stats(): array
    {
        $keys = self::keys();

        return [
            'tenant_id' => self::getTenantId(),
            'total_keys' => count($keys),
            'prefix' => self::PREFIX,
            'default_ttl' => self::DEFAULT_TTL,
            'driver' => config('cache.default'),
        ];
    }

    /**
     * Cria um lock distribuído por tenant
     * Útil para evitar race conditions em operações críticas
     *
     * @param string $key Nome do lock
     * @param int $seconds Tempo máximo do lock em segundos
     * @return \Illuminate\Contracts\Cache\Lock
     */
    public static function lock(string $key, int $seconds = 10)
    {
        $cacheKey = self::generateKey('lock_' . $key);
        return Cache::lock($cacheKey, $seconds);
    }

    /**
     * Obtém tags para cache taggable (Redis/Memcached)
     *
     * @param array $tags Tags adicionais
     * @return \Illuminate\Cache\TaggedCache
     */
    public static function tags(array $tags = [])
    {
        $tenantId = self::getTenantId();
        $allTags = array_merge(['tenant_' . $tenantId], $tags);

        return Cache::tags($allTags);
    }
}
