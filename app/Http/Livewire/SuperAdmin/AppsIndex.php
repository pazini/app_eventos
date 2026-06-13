<?php

namespace App\Http\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\App;
use App\Models\Customer;
use Illuminate\Support\Str;

/**
 * Listagem e gerenciamento de aplicações white label
 *
 * Funcionalidades:
 * - Listar todas as aplicações
 * - Filtrar por status (ativa/inativa/expirada)
 * - Buscar por nome/domínio
 * - Ações rápidas: ativar/desativar, editar, clonar
 * - Informações estatísticas por app
 */
class AppsIndex extends Component
{
    use WithPagination;

    // Filtros
    public $search = '';
    public $statusFilter = 'all'; // all, active, inactive, expired
    public $moduleFilter = 'all'; // all, campaigns, events, analytics
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Paginação
    public $perPage = 10;

    // Ações em lote
    public $selectedApps = [];
    public $selectAll = false;

    // Modais
    public $showDeleteModal = false;
    public $showBulkActionModal = false;
    public $appToDelete = null;
    public $bulkAction = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'moduleFilter' => ['except' => 'all'],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        //
    }

    public function render()
    {
        $apps = $this->getApps();

        return view('livewire.super-admin.apps-index', [
            'apps' => $apps,
            'total' => App::count(),
            'totalActive' => App::where('app_active', true)->count(),
            'totalInactive' => App::where('app_active', false)->count(),
            'totalExpired' => App::where('app_limit_date', '<', now())->count(),
        ])->layout('layouts.app-pep-auth');
    }

    /**
     * Busca aplicações com filtros aplicados
     */
    private function getApps()
    {
        $query = App::withCount(['customers', 'campaigns']);

        // Filtro por busca
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('app_name', 'like', '%' . $this->search . '%')
                  ->orWhere('domain_primary', 'like', '%' . $this->search . '%')
                  ->orWhere('domain_aliases', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por status
        switch ($this->statusFilter) {
            case 'active':
                $query->where('app_active', true)
                      ->where(function ($q) {
                          $q->whereNull('app_limit_date')
                            ->orWhere('app_limit_date', '>=', now());
                      });
                break;
            case 'inactive':
                $query->where('app_active', false);
                break;
            case 'expired':
                $query->where('app_limit_date', '<', now());
                break;
        }

        // Filtro por módulos
        if ($this->moduleFilter !== 'all') {
            $query->where(function ($q) {
                $q->where("settings->features->{$this->moduleFilter}", true)
                  ->orWhere("settings->features->{$this->moduleFilter}", 'true');
            });
        }

        // Ordenação
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    /**
     * Alterar ordenação
     */
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    /**
     * Filtros
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedModuleFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    /**
     * Seleção de aplicações para ações em lote
     */
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedApps = $this->getApps()->pluck('id')->toArray();
        } else {
            $this->selectedApps = [];
        }
    }

    public function toggleAppSelection($appId)
    {
        if (in_array($appId, $this->selectedApps)) {
            $this->selectedApps = array_diff($this->selectedApps, [$appId]);
        } else {
            $this->selectedApps[] = $appId;
        }

        // Atualizar select all
        $totalApps = $this->getApps()->count();
        $this->selectAll = count($this->selectedApps) === $totalApps;
    }

    /**
     * Confirmar ação em lote
     */
    public function confirmBulkAction($action)
    {
        if (empty($this->selectedApps)) {
            $this->emit('notify', 'Selecione pelo menos uma aplicação.', 'warning');
            return;
        }

        $this->bulkAction = $action;
        $this->showBulkActionModal = true;
    }

    /**
     * Executar ação em lote
     */
    public function executeBulkAction()
    {
        if (empty($this->selectedApps) || empty($this->bulkAction)) {
            $this->cancelBulkAction();
            return;
        }

        $count = 0;
        $errors = [];

        foreach ($this->selectedApps as $appId) {
            try {
                $app = App::findOrFail($appId);

                switch ($this->bulkAction) {
                    case 'activate':
                        $app->app_active = true;
                        $app->save();
                        \Cache::forget("app_domain_{$app->domain_primary}");
                        $count++;
                        break;

                    case 'deactivate':
                        // Não permitir desativar a app principal
                        if ($app->id !== '018d64ef-5f37-7f72-9c39-e5c6b312dbe0') {
                            $app->app_active = false;
                            $app->save();
                            \Cache::forget("app_domain_{$app->domain_primary}");
                            $count++;
                        } else {
                            $errors[] = "Aplicação principal '{$app->app_name}' não pode ser desativada.";
                        }
                        break;

                    case 'extend_30_days':
                        $currentLimit = $app->app_limit_date ? \Carbon\Carbon::parse($app->app_limit_date) : now();
                        $app->app_limit_date = $currentLimit->addDays(30);
                        $app->save();
                        $count++;
                        break;
                }

            } catch (\Exception $e) {
                $errors[] = "Erro na aplicação {$appId}: {$e->getMessage()}";
            }
        }

        // Limpar caches gerais
        \Cache::forget('super_admin_stats');
        \Cache::forget('super_admin_apps');

        // Feedback
        $actionNames = [
            'activate' => 'ativadas',
            'deactivate' => 'desativadas',
            'extend_30_days' => 'licenças estendidas'
        ];

        $actionName = $actionNames[$this->bulkAction] ?? 'processadas';

        if ($count > 0) {
            $this->emit('notify', "{$count} aplicação(ões) foram {$actionName} com sucesso!", 'success');
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->emit('notify', $error, 'warning');
            }
        }

        $this->cancelBulkAction();
    }

    /**
     * Cancelar ação em lote
     */
    public function cancelBulkAction()
    {
        $this->selectedApps = [];
        $this->selectAll = false;
        $this->bulkAction = '';
        $this->showBulkActionModal = false;
    }

    /**
     * Exportar dados das aplicações
     */
    public function exportData($format = 'csv')
    {
        $apps = $this->getApps();

        $data = $apps->map(function ($app) {
            return [
                'ID' => $app->id,
                'Nome' => $app->app_name,
                'Domínio' => $app->domain_primary,
                'Status' => $app->app_active ? 'Ativo' : 'Inativo',
                'Clientes' => $app->customers_count ?? 0,
                'Campanhas' => $app->campaigns_count ?? 0,
                'Criado em' => $app->created_at->format('d/m/Y H:i'),
                'Expira em' => $app->app_limit_date ? $app->app_limit_date->format('d/m/Y H:i') : 'Sem limite',
            ];
        })->toArray();

        if ($format === 'csv') {
            $filename = 'aplicacoes_' . now()->format('Y-m-d_H-i-s') . '.csv';

            $handle = fopen('php://temp', 'r+');
            fputcsv($handle, array_keys($data[0] ?? []));

            foreach ($data as $row) {
                fputcsv($handle, $row);
            }

            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response()->streamDownload(
                fn() => print($csv),
                $filename,
                ['Content-Type' => 'text/csv']
            );
        }

        $this->emit('notify', 'Exportação iniciada!', 'success');
    }

    /**
     * Ativar/desativar aplicação
     */
    public function toggleAppStatus($appId)
    {
        $app = App::findOrFail($appId);
        $app->app_active = !$app->app_active;
        $app->save();

        $status = $app->app_active ? 'ativada' : 'desativada';
        $this->emit('notify', "Aplicação '{$app->app_name}' foi {$status} com sucesso!", 'success');

        // Limpar cache relacionado
        \Cache::forget("app_domain_{$app->domain_primary}");
        \Cache::forget('super_admin_stats');
        \Cache::forget('super_admin_apps');
    }

    /**
     * Confirmar exclusão de aplicação
     */
    public function confirmDelete($appId)
    {
        $this->appToDelete = App::findOrFail($appId);
        $this->showDeleteModal = true;
    }

    /**
     * Cancelar exclusão
     */
    public function cancelDelete()
    {
        $this->appToDelete = null;
        $this->showDeleteModal = false;
    }

    /**
     * Deletar aplicação (com verificações de segurança)
     */
    public function deleteApp()
    {
        if (!$this->appToDelete) {
            return;
        }

        $app = $this->appToDelete;

        // Verificar se tem dados vinculados
        $customersCount = Customer::withoutTenantScope()->where('app_id', $app->id)->count();

        if ($customersCount > 0) {
            $this->emit('notify',
                "Não é possível excluir a aplicação '{$app->app_name}' pois ela possui {$customersCount} cliente(s) vinculado(s).
                Primeiro mova ou remova os clientes.",
                'error'
            );
            $this->cancelDelete();
            return;
        }

        // Verificar se é a aplicação principal
        if ($app->id === '018d64ef-5f37-7f72-9c39-e5c6b312dbe0') {
            $this->emit('notify',
                "Não é possível excluir a aplicação principal '{$app->app_name}'.
                Esta aplicação é fundamental para o funcionamento do sistema.",
                'error'
            );
            $this->cancelDelete();
            return;
        }

        try {
            // Limpar cache antes de deletar
            \Cache::forget("app_domain_{$app->domain_primary}");
            \Cache::forget('super_admin_stats');
            \Cache::forget('super_admin_apps');

            $appName = $app->app_name;
            $app->delete();

            $this->emit('notify', "Aplicação '{$appName}' foi excluída com sucesso!", 'success');
            $this->cancelDelete();

        } catch (\Exception $e) {
            $this->emit('notify',
                "Erro ao excluir a aplicação: {$e->getMessage()}",
                'error'
            );
            $this->cancelDelete();
        }
    }

    /**
     * Clonar aplicação (criar uma cópia com configurações similares)
     */
    public function cloneApp($appId)
    {
        $originalApp = App::findOrFail($appId);

        try {
            $newApp = $originalApp->replicate();
            $newApp->id = (string) Str::uuid();
            $newApp->app_name = $originalApp->app_name . ' (Cópia)';
            $newApp->domain_primary = null; // Deve ser definido manualmente
            $newApp->domain_aliases = null;
            $newApp->app_active = false; // Começa inativa
            $newApp->created_at = now();
            $newApp->updated_at = now();
            $newApp->branding_updated_at = now();

            $newApp->save();

            $this->emit('notify',
                "Aplicação '{$newApp->app_name}' foi criada com base em '{$originalApp->app_name}'.
                Configure o domínio e ative quando estiver pronta.",
                'success'
            );

            return redirect()->route('super-admin.apps.edit', $newApp->id);

        } catch (\Exception $e) {
            $this->emit('notify',
                "Erro ao clonar a aplicação: {$e->getMessage()}",
                'error'
            );
        }
    }

    /**
     * Estender licença por 30 dias
     */
    public function extendLicense($appId)
    {
        $app = App::findOrFail($appId);

        $currentLimit = $app->app_limit_date ? \Carbon\Carbon::parse($app->app_limit_date) : now();
        $newLimit = $currentLimit->addDays(30);

        $app->app_limit_date = $newLimit;
        $app->save();

        $this->emit('notify',
            "Licença da aplicação '{$app->app_name}' foi estendida até " .
            $newLimit->format('d/m/Y H:i') . ".",
            'success'
        );
    }
}
