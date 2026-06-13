<?php

namespace App\Traits;

use App\Scopes\AppScope;
use App\Scopes\CustomerScope;

/**
 * HasTenantScope - Trait para aplicar isolamento automático por tenant
 *
 * Adiciona Global Scopes automaticamente para garantir isolamento de dados.
 *
 * Uso:
 *
 * class MyModel extends Model
 * {
 *     use HasTenantScope;
 *
 *     // Opcionalmente, customize o scope:
 *     protected $tenantScope = 'app'; // ou 'customer' ou 'both'
 * }
 *
 * Scopes disponíveis:
 * - 'app': Filtra por app_id (padrão)
 * - 'customer': Filtra por customer_id
 * - 'both': Filtra por app_id E customer_id
 * - false: Desabilita o scope (para models sem tenant)
 */
trait HasTenantScope
{
    /**
     * Boot do trait - adiciona os Global Scopes
     */
    protected static function bootHasTenantScope()
    {
        // Determina qual scope aplicar
        $scopeType = static::getTenantScopeType();

        // Aplica AppScope se necessário
        if (in_array($scopeType, ['app', 'both'])) {
            static::addGlobalScope(new AppScope);
        }

        // Aplica CustomerScope se necessário
        if (in_array($scopeType, ['customer', 'both'])) {
            static::addGlobalScope(new CustomerScope);
        }

        // Hook para auto-injetar app_id e customer_id ao criar
        static::creating(function ($model) use ($scopeType) {
            // Auto-injeta app_id
            if (in_array($scopeType, ['app', 'both'])) {
                if (!isset($model->app_id) && session('app_id')) {
                    $model->app_id = session('app_id');
                }
            }

            // Auto-injeta customer_id
            if (in_array($scopeType, ['customer', 'both'])) {
                if (!isset($model->customer_id) && session('customer_id')) {
                    $model->customer_id = session('customer_id');
                }
            }
        });
    }

    /**
     * Retorna o tipo de scope a ser aplicado
     *
     * @return string|false
     */
    protected static function getTenantScopeType()
    {
        // Se o model define $tenantScope, usa ele
        if (property_exists(static::class, 'tenantScope')) {
            return (new static)->tenantScope;
        }

        // Auto-detecta baseado nas colunas da tabela
        $model = new static;
        $table = $model->getTable();

        // Verifica se tem app_id E customer_id
        if (static::hasColumn($table, 'app_id') && static::hasColumn($table, 'customer_id')) {
            return 'both';
        }

        // Verifica se tem apenas app_id
        if (static::hasColumn($table, 'app_id')) {
            return 'app';
        }

        // Verifica se tem apenas customer_id
        if (static::hasColumn($table, 'customer_id')) {
            return 'customer';
        }

        // Tabela não tem tenant
        return false;
    }

    /**
     * Verifica se uma coluna existe na tabela
     *
     * @param string $table
     * @param string $column
     * @return bool
     */
    protected static function hasColumn(string $table, string $column): bool
    {
        try {
            return \Schema::hasColumn($table, $column);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Remove o scope temporariamente para uma query
     *
     * Uso: MyModel::withoutTenantScope()->get();
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function withoutTenantScope()
    {
        return (new static)->newQueryWithoutScopes();
    }

    /**
     * Remove apenas o AppScope temporariamente
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function withoutAppScope()
    {
        return (new static)->newQuery()->withoutGlobalScope(AppScope::class);
    }

    /**
     * Remove apenas o CustomerScope temporariamente
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function withoutCustomerScope()
    {
        return (new static)->newQuery()->withoutGlobalScope(CustomerScope::class);
    }
}
