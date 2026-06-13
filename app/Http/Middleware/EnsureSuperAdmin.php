<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Middleware para verificar se o usuário tem permissão de super-admin
 *
 * O super-admin é um usuário que tem acesso total ao sistema white label,
 * podendo gerenciar todas as aplicações, usuários e configurações.
 *
 * Critérios para ser super-admin:
 * 1. Estar autenticado
 * 2. Ter role 'super-admin' em qualquer app OU
 * 3. Ser admin do app com ID 1 (ProEventPay - app principal)
 */
class EnsureSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Verificar se usuário está autenticado
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Acesso negado. Autenticação requerida.'], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Você precisa fazer login para acessar esta área.');
        }

        // Verificar se o usuário é super-admin
        if (!$this->isSuperAdmin($user)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Acesso negado. Permissão de super-admin requerida.'], 403);
            }

            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar o painel de administração.');
        }

        return $next($request);
    }

    /**
     * Verificar se o usuário é super-admin
     *
     * @param \App\Models\User $user
     * @return bool
     */
    private function isSuperAdmin($user): bool
    {
        // Verificar se tem role 'super-admin' em qualquer app
        $hasSuperAdminRole = $user->app()
            ->wherePivot('user_role', 'super-admin')
            ->wherePivot('user_active', true)
            ->exists();

        if ($hasSuperAdminRole) {
            return true;
        }

        // Verificar se é admin ou owner do app principal (ProEventPay)
        $isMainAppAdmin = $user->app()
            ->where('tb_app.id', '29b92490-8129-4c28-8e3e-c0bae5dee0b8') // UUID do app ProEventPay
            ->wherePivot('user_active', true)
            ->whereIn('users_app.user_role', ['admin', 'owner', 'super-admin'])
            ->exists();

        return $isMainAppAdmin;
    }

    /**
     * Verificar se um usuário específico é super-admin (método estático para uso em outras partes do sistema)
     *
     * @param \App\Models\User|null $user
     * @return bool
     */
    public static function check($user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        $middleware = new self();
        return $middleware->isSuperAdmin($user);
    }
}
