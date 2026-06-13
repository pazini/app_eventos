<?php

namespace App\Http\Livewire\SuperAdmin;

use App\Models\App;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class DomainManager extends Component
{
    public $apps;
    public $selectedAppId;
    public $domain_primary;
    public $domain_aliases = [];
    public $newAlias = '';

    protected $rules = [
        'domain_primary' => 'required|string|max:255',
        'domain_aliases' => 'nullable|array',
        'domain_aliases.*' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->loadApps();
    }

    public function loadApps()
    {
        $this->apps = App::orderBy('app_name')->get();
    }

    public function selectApp($appId)
    {
        $this->selectedAppId = $appId;
        $app = App::find($appId);

        if ($app) {
            $this->domain_primary = $app->domain_primary;
            $this->domain_aliases = is_array($app->domain_aliases)
                ? $app->domain_aliases
                : ($app->domain_aliases ? json_decode($app->domain_aliases, true) : []);
        }
    }

    public function addAlias()
    {
        if (empty($this->newAlias)) {
            return;
        }

        $cleanAlias = trim($this->newAlias);

        if (!in_array($cleanAlias, $this->domain_aliases)) {
            $this->domain_aliases[] = $cleanAlias;
        }

        $this->newAlias = '';
    }

    public function removeAlias($index)
    {
        unset($this->domain_aliases[$index]);
        $this->domain_aliases = array_values($this->domain_aliases);
    }

    public function saveDomains()
    {
        $this->validate();

        if (!$this->selectedAppId) {
            session()->flash('error', 'Selecione uma aplicação primeiro.');
            return;
        }

        $app = App::find($this->selectedAppId);

        if (!$app) {
            session()->flash('error', 'Aplicação não encontrada.');
            return;
        }

        try {
            // Limpar aliases vazios
            $cleanAliases = array_filter($this->domain_aliases, function($alias) {
                return !empty(trim($alias));
            });

            $app->domain_primary = $this->domain_primary;
            $app->domain_aliases = array_values($cleanAliases);
            $app->save();

            // Limpar cache de todos os domínios relacionados
            $this->clearDomainCache($app);

            session()->flash('success', 'Domínios atualizados com sucesso!');
            $this->emit('notify', 'Domínios atualizados com sucesso!', 'success');

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar: ' . $e->getMessage());
            $this->emit('notify', 'Erro ao salvar domínios', 'error');
        }
    }

    public function clearAllCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');

            // Limpar cache de domínios
            foreach ($this->apps as $app) {
                $this->clearDomainCache($app);
            }

            session()->flash('success', 'Cache limpo com sucesso!');
            $this->emit('notify', 'Cache limpo com sucesso!', 'success');

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao limpar cache: ' . $e->getMessage());
            $this->emit('notify', 'Erro ao limpar cache', 'error');
        }
    }

    private function clearDomainCache(App $app)
    {
        // Limpar cache do domínio principal
        Cache::forget("tenant_app_{$app->domain_primary}");

        // Limpar cache de todos os aliases
        if (is_array($app->domain_aliases)) {
            foreach ($app->domain_aliases as $alias) {
                Cache::forget("tenant_app_{$alias}");
            }
        }

        // Limpar cache geral do app
        Cache::forget("app_domain_{$app->domain_primary}");
        Cache::forget("app_{$app->id}");
    }

    public function render()
    {
        return view('livewire.super-admin.domain-manager')->layout('layouts.app-pep-auth');
    }
}
