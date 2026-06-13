<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * ActiveCustomerScope - Global Scope para filtrar apenas customers ativos
 *
 * Aplica automaticamente WHERE is_active = true em todas as queries
 * do model Customer. Para ignorar este scope use:
 *   Customer::withoutGlobalScope(ActiveCustomerScope::class)->...
 */
class ActiveCustomerScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where($model->getTable() . '.is_active', true);
    }
}
