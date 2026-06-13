<?php

namespace App\Http\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\App;
use App\Models\Customer;
use App\Models\User;
use App\Models\ModCampaign\Campaign;
use Illuminate\Support\Facades\Cache;

/**
 * Dashboard principal do Super Admin
 *
 * Exibe estatísticas gerais do sistema white label:
 * - Aplicações ativas/inativas
 * - Usuários por app
 * - Campanhas/eventos por app
 * - Storage utilizado
 * - Métricas de performance
 */
class Dashboard extends Component
{
    public $stats = [];
    public $apps = [];
    public $recentActivities = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadApps();
        $this->loadRecentActivities();
    }

    public function render()
    {
        return view('livewire.super-admin.dashboard')
            ->layout('layouts.app-pep-auth');
    }

    /**
     * Carrega estatísticas gerais do sistema
     */
    private function loadStats()
    {
        // Cache por 5 minutos para não impactar performance
        $this->stats = Cache::remember('super_admin_stats', 300, function () {
            return [
                'apps' => [
                    'total' => App::count(),
                    'active' => App::where('app_active', true)->count(),
                    'inactive' => App::where('app_active', false)->count(),
                    'expired' => App::where('app_limit_date', '<', now())->count(),
                ],
                'users' => [
                    'total' => User::count(),
                    'verified' => User::whereNotNull('email_verified_at')->count(),
                    'unverified' => User::whereNull('email_verified_at')->count(),
                ],
                'customers' => [
                    'total' => Customer::count(),
                    'this_month' => Customer::where('created_at', '>=', now()->startOfMonth())->count(),
                ],
                'campaigns' => [
                    'total' => Campaign::count(),
                    'active' => Campaign::whereIn('status', ['active', 'active_direct'])->count(),
                    'this_month' => Campaign::where('created_at', '>=', now()->startOfMonth())->count(),
                ],
                'storage' => [
                    'used_mb' => $this->calculateStorageUsage(),
                    'apps_with_storage' => App::whereNotNull('settings->limits->storage_mb')->count(),
                ],
            ];
        });
    }

    /**
     * Carrega informações básicas das aplicações
     */
    private function loadApps()
    {
        $this->apps = Cache::remember('super_admin_apps', 300, function () {
            return App::withCount(['customers', 'campaigns'])
                ->orderBy('app_active', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($app) {
                    return [
                        'id' => $app->id,
                        'name' => $app->app_name,
                        'domain' => $app->domain_primary,
                        'active' => $app->app_active,
                        'customers_count' => $app->customers_count ?? 0,
                        'campaigns_count' => $app->campaigns_count ?? 0,
                        'created_at' => $app->created_at,
                        'limit_date' => $app->app_limit_date,
                        'colors' => [
                            'primary' => $app->color_primary,
                            'secondary' => $app->color_secondary,
                        ],
                    ];
                })->toArray();
        });
    }

    /**
     * Carrega atividades recentes do sistema
     */
    private function loadRecentActivities()
    {
        $this->recentActivities = Cache::remember('super_admin_activities', 300, function () {
            $activities = collect();

            // Apps criadas recentemente
            $recentApps = App::where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            foreach ($recentApps as $app) {
                $activities->push([
                    'type' => 'app_created',
                    'title' => 'Nova aplicação criada',
                    'description' => "App '{$app->app_name}' foi criada",
                    'date' => $app->created_at,
                    'icon' => 'heroicon-o-plus-circle',
                    'color' => 'green',
                ]);
            }

            // Campanhas criadas recentemente
            $recentCampaigns = Campaign::with('customer.app')
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            foreach ($recentCampaigns as $campaign) {
                $appName = $campaign->customer->app->app_name ?? 'N/A';
                $activities->push([
                    'type' => 'campaign_created',
                    'title' => 'Nova campanha criada',
                    'description' => "'{$campaign->name}' em {$appName}",
                    'date' => $campaign->created_at,
                    'icon' => 'heroicon-o-megaphone',
                    'color' => 'blue',
                ]);
            }

            return $activities->sortByDesc('date')->take(20)->values()->toArray();
        });
    }

    /**
     * Calcula uso total de storage (em MB)
     */
    private function calculateStorageUsage(): float
    {
        try {
            $totalSize = 0;
            $storagePath = storage_path('app');

            // Percorre cada diretório de app
            foreach (glob($storagePath . '/[0-9]*', GLOB_ONLYDIR) as $appDir) {
                $totalSize += $this->getFolderSize($appDir);
            }

            return round($totalSize / 1024 / 1024, 2); // Converte para MB
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calcula tamanho de uma pasta recursivamente
     */
    private function getFolderSize(string $path): int
    {
        $size = 0;

        if (is_dir($path)) {
            foreach (glob(rtrim($path, '/') . '/*', GLOB_NOSORT) as $file) {
                if (is_file($file)) {
                    $size += filesize($file);
                } elseif (is_dir($file)) {
                    $size += $this->getFolderSize($file);
                }
            }
        }

        return $size;
    }

    /**
     * Refresh dos dados do dashboard
     */
    public function refreshData()
    {
        Cache::forget('super_admin_stats');
        Cache::forget('super_admin_apps');
        Cache::forget('super_admin_activities');

        $this->loadStats();
        $this->loadApps();
        $this->loadRecentActivities();

        $this->emit('notify', 'Dados atualizados com sucesso!', 'success');
    }

    /**
     * Limpar cache do sistema
     */
    public function clearCache()
    {
        Cache::flush();
        $this->refreshData();

        $this->emit('notify', 'Cache do sistema limpo com sucesso!', 'success');
    }
}
