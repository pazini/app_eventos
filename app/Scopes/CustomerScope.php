<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * CustomerScope - Global Scope para isolamento por customer_id
 *
 * Aplica automaticamente filtro WHERE customer_id = session('customer_id')
 * em todas as queries dos models que dependem de customer.
 *
 * Usado principalmente em:
 * - Campaigns
 * - CampaignOrders
 * - Events
 * - Outros recursos específicos do customer
 */
class CustomerScope implements Scope
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
        // Pega o customer_id da sessão (injetado ao fazer login)
        $customerId = session('customer_id');

        // Se não tem customer_id na sessão, não aplica filtro
        // (necessário para listagens gerais, admin, etc)
        if (!$customerId) {
            return;
        }

        // Aplica o filtro WHERE customer_id = $customerId
        $builder->where($model->getTable() . '.customer_id', $customerId);
    }
}
