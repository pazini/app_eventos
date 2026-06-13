<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * AppScope - Global Scope para isolamento por app_id
 *
 * Aplica automaticamente filtro WHERE app_id = session('app_id')
 * em todas as queries dos models que usam este scope.
 *
 * Uso:
 * - Via trait HasTenantScope (recomendado)
 * - Ou adicione manualmente: protected static function booted() { static::addGlobalScope(new AppScope); }
 */
class AppScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // Pega o app_id da sessão (injetado pelo middleware IdentifyTenant)
        $appId = session('app_id');

        // Se não tem app_id na sessão, não aplica filtro
        // (necessário para seeds, migrations, comandos artisan)
        if (!$appId) {
            return;
        }

        // Aplica o filtro WHERE app_id = $appId
        $builder->where($model->getTable() . '.app_id', $appId);
    }
}
