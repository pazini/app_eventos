<?php

namespace App\Services;

use App\Models\AppModule;
use App\Models\Customer;
use App\Models\User;

class ModuleAccessService
{
    /**
     * Verifica se o usuário tem acesso ao módulo de eventos para um determinado customer.
     */
    public static function userCanAccessEvents(User $user, Customer $customer): bool
    {
        return self::userCanAccessModuleBySlug($user, $customer, 'eventos', 'can_events');
    }

    /**
     * Verifica se o usuário tem acesso ao módulo de campanhas para um determinado customer.
     */
    public static function userCanAccessCampaigns(User $user, Customer $customer): bool
    {
        return self::userCanAccessModuleBySlug($user, $customer, 'campanhas', 'can_campaigns');
    }

    /**
     * Verifica se o usuário tem acesso ao módulo de assinaturas para um determinado customer.
     */
    public static function userCanAccessSubscriptions(User $user, Customer $customer): bool
    {
        return self::userCanAccessModuleBySlug($user, $customer, 'assinaturas', 'can_subscriptions');
    }

    /**
     * Regra genérica: verifica se o customer possui o módulo e se o usuário tem o flag de acesso.
     */
    protected static function userCanAccessModuleBySlug(
        User $user,
        Customer $customer,
        string $moduleSlug,
        string $pivotFlagField
    ): bool {
        // Administrador da aplicação tem acesso TOTAL, independente dos módulos do cliente
        if (self::isAppAdmin($user)) {
            return true;
        }

        // Customer precisa ter o módulo ativo cadastrado
        // Usa consulta direta para evitar ORDER BY herdado da relação (PostgreSQL)
        $hasModule = \DB::table('tb_app_modules')
            ->join(
                'tb_customers_app_modules',
                'tb_customers_app_modules.module_id',
                '=',
                'tb_app_modules.id'
            )
            ->where('tb_customers_app_modules.customer_id', $customer->id)
            ->where('tb_app_modules.slug', $moduleSlug)
            ->where('tb_app_modules.module_active', 1)
            ->exists();

        if (! $hasModule) {
            return false;
        }

        // Buscar pivot user x customer com os campos extras
        // Garante que os campos de permissões sejam carregados
        $pivot = $user->customers()
            ->where('tb_customers.id', $customer->id)
            ->withPivot(['user_active', 'user_role', 'can_events', 'can_campaigns', 'can_subscriptions'])
            ->first()?->pivot;

        if (! $pivot) {
            return false;
        }

        if (! $pivot->user_active) {
            return false;
        }

        $flagValue = (int) ($pivot->{$pivotFlagField} ?? 0);

        // Se o flag estiver explicitamente ligado (1), dá acesso
        if ($flagValue === 1) {
            return true;
        }

        // Se o flag estiver explicitamente desligado (0), não dá acesso
        // Não há mais comportamento padrão que dá acesso automático para owners/admins
        // Agora é necessário ter o flag explicitamente marcado
        return false;
    }

    /**
     * Verifica se o usuário é administrador da aplicação (nível global).
     * Inclui tanto 'admin' quanto 'super-admin'.
     */
    protected static function isAppAdmin(User $user): bool
    {
        // Usa consulta direta na tabela de pivot para evitar conflitos de ORDER BY
        // e garantir compatibilidade com diferentes bancos (PostgreSQL, etc.).
        return \DB::table('users_app')
            ->where('user_id', $user->id)
            ->where('user_active', 1)
            ->whereIn('user_role', ['admin', 'super-admin'])
            ->exists();
    }

    /**
     * Versão pública do check de admin global, para uso em componentes/blades.
     */
    public static function userIsAppAdmin(User $user): bool
    {
        return self::isAppAdmin($user);
    }
}


