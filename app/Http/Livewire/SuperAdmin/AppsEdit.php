<?php

namespace App\Http\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\App;
use App\Models\Customer;
use App\Models\AppModule;
use App\Models\AppPayGateway;
use App\Models\UserApp;
use App\Models\CustomerAppModule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

/**
 * Edição de aplicação white label existente
 *
 * Funcionalidades:
 * - Editar todas as configurações da aplicação
 * - Upload de novos logos/favicon
 * - Gerenciar configurações avançadas
 * - Visualizar estatísticas da aplicação
 * - Gerenciar usuários da aplicação
 */
class AppsEdit extends Component
{
    use WithFileUploads;

    public $app;
    public $appId;

    // Abas ativas
    public $activeTab = 'stats'; // basic, branding, settings, users, customers, safe2pay, stats

    // Informações básicas
    public $app_name;
    public $domain_primary;
    public $domain_aliases;
    public $app_active;
    public $app_limit_date;

    // Branding
    public $color_primary;
    public $color_secondary;
    public $color_accent;
    public $new_logo;
    public $new_logo_dark;
    public $new_favicon;
    public $new_default_thumb;

    // Previews de uploads
    public $logo_preview;
    public $logo_dark_preview;
    public $favicon_preview;
    public $default_thumb_preview;

    // Contador para forçar refresh das imagens após upload
    public $branding_version = 0;

    // E-mails e SEO
    public $email_from_name;
    public $email_from_address;
    public $email_reply_to;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;

    // Configurações
    public $features = [];
    public $settings = [];
    public $limits = [];

    // Safe2Pay - Tokens Master
    public $safe2pay_token_live;
    public $safe2pay_token_test;
    public $safe2pay_token_live_pass;
    public $safe2pay_token_test_pass;
    public $safe2pay_active = false;
    public $safe2pay_test_mode = true;
    public $safe2pay_settings = [];

    // Estatísticas
    public $stats = [];

    // Customers
    public $customersList = [];
    public $customersCount = 0;
    public $activeCustomersList = [];
    public $activeCustomersCount = 0;

    // Exclusão
    public $showDeleteModal = false;
    public $deleteSummary = [
        'customers' => 0,
        'gateways' => 0,
        'campaigns' => 0,
        'events' => 0,
    ];
    public $deleteBlocked = true;

    public function mount($app)
    {
        $this->app = App::findOrFail($app);
        $this->appId = $this->app->id;
        $this->loadAppData();
        $this->loadStats();
        $this->refreshDeleteSummary();
    }

    public function render()
    {
        return view('livewire.super-admin.apps-edit')
            ->layout('layouts.app-pep-auth');
    }

    /**
     * Carrega dados da aplicação nos campos do formulário
     */
    private function loadAppData()
    {
        // Informações básicas
        $this->app_name = $this->app->app_name;
        $this->domain_primary = $this->app->domain_primary;
        $this->domain_aliases = $this->app->domain_aliases ? implode(', ', is_array($this->app->domain_aliases) ? $this->app->domain_aliases : json_decode($this->app->domain_aliases, true)) : '';
        $this->app_active = $this->app->app_active;
        $this->app_limit_date = $this->app->app_limit_date ? $this->app->app_limit_date->format('Y-m-d\TH:i') : '';

        // Branding
        $this->color_primary = $this->app->color_primary ?? '#1a202c';
        $this->color_secondary = $this->app->color_secondary ?? '#2d3748';
        $this->color_accent = $this->app->color_accent ?? '#ed8936';

        // E-mails e SEO
        $this->email_from_name = $this->app->email_from_name;
        $this->email_from_address = $this->app->email_from_address;
        $this->email_reply_to = $this->app->email_reply_to;
        $this->meta_title = $this->app->meta_title;
        $this->meta_description = $this->app->meta_description;
        $this->meta_keywords = $this->app->meta_keywords;

        // Configurações
        $this->settings = $this->app->settings ?? [];
        $this->features = $this->settings['features'] ?? [
            'campaigns' => true,
            'events' => true,
            'subscriptions' => false,
            'analytics' => true,
            'reports' => true,
            'integrations' => false,
        ];
        $this->limits = $this->settings['limits'] ?? [
            'storage_mb' => 5120,
            'campaigns_per_customer' => 50,
            'events_per_customer' => 50,
            'users_per_customer' => 10,
        ];

        // Safe2Pay
        $this->safe2pay_token_live = $this->app->safe2pay_token_live;
        $this->safe2pay_token_test = $this->app->safe2pay_token_test;
        $this->safe2pay_token_live_pass = $this->app->safe2pay_token_live_pass;
        $this->safe2pay_token_test_pass = $this->app->safe2pay_token_test_pass;
        $this->safe2pay_active = $this->app->safe2pay_active ?? false;
        $this->safe2pay_test_mode = $this->app->safe2pay_test_mode ?? true;
        $this->safe2pay_settings = $this->app->safe2pay_settings ?? [];
    }

    /**
     * Carrega estatísticas da aplicação
     */
    private function loadStats()
    {
        $this->stats = [
            'customers' => [
                'total' => Customer::withoutTenantScope()->where('app_id', $this->appId)->count(),
                'active' => Customer::withoutTenantScope()->where('app_id', $this->appId)->count(), // Todos os customers são considerados ativos
            ],
            'campaigns' => [
                'total' => \DB::table('tbc_campaign as c')
                    ->join('tb_customers as cu', 'c.customer_id', '=', 'cu.id')
                    ->where('cu.app_id', $this->appId)
                    ->count(),
                'active' => \DB::table('tbc_campaign as c')
                    ->join('tb_customers as cu', 'c.customer_id', '=', 'cu.id')
                    ->where('cu.app_id', $this->appId)
                    ->where('c.status', 'active')
                    ->count(),
            ],
            'storage' => [
                'used_mb' => $this->calculateStorageUsage(),
                'limit_mb' => $this->settings['limits']['storage_mb'] ?? 5120,
            ],
            'last_activity' => Customer::withoutTenantScope()->where('app_id', $this->appId)
                ->orderBy('updated_at', 'desc')
                ->value('updated_at'),
        ];
    }

    /**
     * Calcula uso de storage da aplicação
     */
    private function calculateStorageUsage(): float
    {
        try {
            $appStoragePath = storage_path("app/{$this->appId}");
            if (!is_dir($appStoragePath)) {
                return 0;
            }

            return round($this->getFolderSize($appStoragePath) / 1024 / 1024, 2);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calcula tamanho de pasta recursivamente
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
     * Alterar aba ativa
     */
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;

        if ($tab === 'stats') {
            $this->loadStats(); // Recarregar estatísticas
        }
        if ($tab === 'customers') {
            $this->loadCustomersList();
        }
        if ($tab === 'active-customers') {
            $this->loadActiveCustomersList();
        }
        if ($tab === 'settings') {
            $this->refreshDeleteSummary();
        }
    }

    /**
     * Salvar informações básicas
     */
    public function saveBasicInfo()
    {
        $appSlug = Str::slug($this->app_name);
        $rules = [
            'app_name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($appSlug) {
                    if ($appSlug === '') {
                        $fail('O nome da aplicação é inválido.');
                        return;
                    }
                    if (App::where('app_slug', $appSlug)->where('id', '!=', $this->appId)->exists()) {
                        $fail('Já existe uma aplicação com este nome.');
                    }
                },
            ],
            'domain_primary' => 'required|string|max:255|unique:tb_app,domain_primary,' . $this->appId . ',id',
            'app_limit_date' => 'nullable|date',
        ];

        $validator = Validator::make([
            'app_name' => $this->app_name,
            'domain_primary' => $this->domain_primary,
            'app_limit_date' => $this->app_limit_date,
        ], $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->addError('basic', $error);
            }
            return;
        }

        try {
            $this->app->app_name = $this->app_name;
            $this->app->app_slug = $appSlug;
            $this->app->domain_primary = $this->domain_primary;

            // Validar e formatar domain_aliases
            $validAliases = $this->validateDomainAliases();
            $this->app->domain_aliases = !empty($validAliases) ? $validAliases : null;

            $this->app->app_active = $this->app_active;
            $this->app->app_limit_date = $this->app_limit_date ? Carbon::parse($this->app_limit_date) : null;

            $this->app->save();

            // Limpar cache do domínio
            \Cache::forget("app_domain_{$this->domain_primary}");

            // Limpar cache dos aliases antigos
            if ($this->app->getOriginal('domain_aliases')) {
                $oldAliases = $this->app->getOriginal('domain_aliases');
                $oldAliases = is_array($oldAliases) ? $oldAliases : json_decode($oldAliases, true);
                if ($oldAliases) {
                    foreach ($oldAliases as $alias) {
                        \Cache::forget("app_domain_{$alias}");
                    }
                }
            }

            \Cache::forget('super_admin_stats');
            \Cache::forget('super_admin_apps');

            $this->emit('notify', 'Informações básicas salvas com sucesso!', 'success');

        } catch (\Exception $e) {
            $this->addError('basic', 'Erro ao salvar: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza o resumo de dependências para exclusão
     */
    public function refreshDeleteSummary(): void
    {
        $this->deleteSummary = $this->getDeletionSummary();
        $this->deleteBlocked = collect($this->deleteSummary)->sum() > 0;
    }

    /**
     * Abre modal de confirmação de exclusão
     */
    public function openDeleteModal(): void
    {
        $this->refreshDeleteSummary();
        $this->showDeleteModal = true;
    }

    /**
     * Fecha modal de exclusão
     */
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
    }

    /**
     * Remove a aplicação (apenas se não houver dependências)
     */
    public function deleteApp(): void
    {
        $summary = $this->getDeletionSummary();
        $defaultCustomerIds = $this->getDefaultCustomerIds();

        if (collect($summary)->sum() > 0) {
            $this->deleteSummary = $summary;
            $this->deleteBlocked = true;
            $this->emit('notify', 'Não é possível excluir esta aplicação enquanto houver clientes, gateways, campanhas ou eventos vinculados.', 'error');
            return;
        }

        if ($this->app_active) {
            $this->deleteBlocked = true;
            $this->emit('notify', 'Desative a aplicação antes de excluí-la.', 'error');
            return;
        }

        DB::transaction(function () use ($defaultCustomerIds) {
            // Remove módulos, gateways e vínculos com usuários antes de excluir a aplicação
            $moduleIds = AppModule::where('app_id', $this->appId)->pluck('id');
            if ($moduleIds->count()) {
                CustomerAppModule::whereIn('module_id', $moduleIds)->delete();
            }
            AppModule::where('app_id', $this->appId)->delete();
            $this->deleteCustomersByIds($defaultCustomerIds);
            AppPayGateway::where('app_id', $this->appId)->delete();
            UserApp::where('app_id', $this->appId)->delete();

            $this->app->delete();
        });

        $this->emit('notify', 'Aplicação removida com sucesso.', 'success');
        $this->showDeleteModal = false;

        redirect()->route('super-administrador.apps.index');
    }

    /**
     * Resumo de itens vinculados que bloqueiam a exclusão
     */
    private function getDeletionSummary(): array
    {
        $defaultCustomerIds = $this->getDefaultCustomerIds();
        $customersQuery = Customer::withoutTenantScope()->where('app_id', $this->appId);
        if (!empty($defaultCustomerIds)) {
            $customersQuery->whereNotIn('id', $defaultCustomerIds);
        }
        $customers = $customersQuery->count();

        $gateways = AppPayGateway::where('app_id', $this->appId)->count();

        $campaigns = DB::table('tbc_campaign as c')
            ->join('tb_customers as cu', 'c.customer_id', '=', 'cu.id')
            ->where('cu.app_id', $this->appId)
            ->count();

        $events = 0;
        if (Schema::hasTable('app_events') && Schema::hasTable('customer_organizers')) {
            $events = DB::table('app_events as e')
                ->join('customer_organizers as o', 'o.id', '=', 'e.organizer_id')
                ->join('tb_customers as cu', 'o.customer_id', '=', 'cu.id')
                ->where('cu.app_id', $this->appId)
                ->count();
        }

        return [
            'customers' => $customers,
            'gateways' => $gateways,
            'campaigns' => $campaigns,
            'events' => $events,
        ];
    }

    /**
     * Carrega customers da aplicação ignorando o tenant scope.
     */
    private function loadCustomersList(): void
    {
        $this->customersList = Customer::withoutTenantScope()
            ->where('app_id', $this->appId)
            ->orderBy('created_at')
            ->get();
        $this->customersCount = $this->customersList->count();
    }

    /**
     * Carrega customers ativos (com ao menos um usuário ativo).
     */
    private function loadActiveCustomersList(): void
    {
        $defaultCustomerIds = $this->getDefaultCustomerIds();
        $this->activeCustomersList = Customer::withoutTenantScope()
            ->where('app_id', $this->appId)
            ->when(!empty($defaultCustomerIds), function ($query) use ($defaultCustomerIds) {
                $query->whereNotIn('id', $defaultCustomerIds);
            })
            ->whereIn('id', function ($query) {
                $query->select('customer_id')
                    ->from('users_customer')
                    ->where('user_active', true);
            })
            ->orderBy('created_at')
            ->get();
        $this->activeCustomersCount = $this->activeCustomersList->count();
    }

    /**
     * Remove customers e vínculos básicos usados pelo app.
     */
    private function deleteCustomersByIds(array $customerIds): void
    {
        if (empty($customerIds)) {
            return;
        }

        $organizerIds = DB::table('tb_customers_organizers')
            ->whereIn('customer_id', $customerIds)
            ->pluck('id');
        $campaignOrganizerIds = DB::table('tbc_campaign_organizer')
            ->whereIn('customer_id', $customerIds)
            ->pluck('id');
        $orgSubIds = DB::table('tb_customers_organizations_subs')
            ->whereIn('customer_id', $customerIds)
            ->pluck('id');
        $orgIds = DB::table('tb_customers_organizations')
            ->whereIn('customer_id', $customerIds)
            ->pluck('id');
        $payGatewayIds = DB::table('tb_customers_pay_gateways')
            ->whereIn('customer_id', $customerIds)
            ->pluck('id');

        DB::table('tb_customers_app_modules')->whereIn('customer_id', $customerIds)->delete();

        if ($payGatewayIds->count()) {
            DB::table('tb_customers_pay_gateways_fees')->whereIn('pay_gateway_id', $payGatewayIds)->delete();
        }
        DB::table('tb_customers_pay_gateways')->whereIn('customer_id', $customerIds)->delete();

        DB::table('users_customer')->whereIn('customer_id', $customerIds)->delete();
        if ($organizerIds->count()) {
            DB::table('users_customer_organizer')->whereIn('organizer_id', $organizerIds)->delete();
        }
        if ($campaignOrganizerIds->count()) {
            DB::table('users_campaign_organizer')->whereIn('organizer_id', $campaignOrganizerIds)->delete();
        }
        if ($orgIds->count()) {
            DB::table('users_customer_organization')->whereIn('organization_id', $orgIds)->delete();
        }
        if ($orgSubIds->count()) {
            DB::table('users_customer_organization_sub')->whereIn('organization_sub_id', $orgSubIds)->delete();
        }

        if ($organizerIds->count()) {
            DB::table('tb_customers_organizers')->whereIn('id', $organizerIds)->delete();
        }
        if ($campaignOrganizerIds->count()) {
            DB::table('tbc_campaign_organizer')->whereIn('id', $campaignOrganizerIds)->delete();
        }
        if ($orgIds->count()) {
            DB::table('tb_customers_organizations_places')->whereIn('organization_id', $orgIds)->delete();
        }
        if ($orgSubIds->count()) {
            DB::table('tb_customers_organizations_subs')->whereIn('id', $orgSubIds)->delete();
        }
        if ($orgIds->count()) {
            DB::table('tb_customers_organizations')->whereIn('id', $orgIds)->delete();
        }

        DB::table('tb_customers')->whereIn('id', $customerIds)->delete();
    }

    /**
     * Identifica o customer padrão criado automaticamente para o app.
     */
    private function getDefaultCustomerIds(): array
    {
        $settings = $this->app->settings ?? [];
        if (is_string($settings)) {
            $settings = json_decode($settings, true) ?? [];
        }
        if (!is_array($settings)) {
            $settings = [];
        }

        $defaultId = $settings['default_customer_id'] ?? null;
        if ($defaultId) {
            $exists = Customer::withoutTenantScope()
                ->where('app_id', $this->appId)
                ->where('id', $defaultId)
                ->exists();
            if ($exists) {
                return [$defaultId];
            }
        }

        $slug = Str::slug($this->app->app_name ?? '');
        if ($slug) {
            $ids = Customer::withoutTenantScope()
                ->where('app_id', $this->appId)
                ->where(function ($query) use ($slug) {
                    $query->where('customer_slug', $slug)
                        ->orWhere('prefix_url', $slug);
                })
                ->pluck('id')
                ->toArray();
            if (!empty($ids)) {
                return $ids;
            }
        }

        return Customer::withoutTenantScope()
            ->where('app_id', $this->appId)
            ->where('doc_num', '00000000000000')
            ->where(function ($query) {
                $query->where('name_corporate', $this->app->app_name)
                    ->orWhere('name_fantasy', $this->app->app_name);
            })
            ->pluck('id')
            ->toArray();
    }

    /**
     * Salvar configurações de branding
     */
    public function saveBranding()
    {
        // Validação personalizada para cores hexadecimais
        $rules = [
            'color_primary' => ['required', function ($attribute, $value, $fail) {
                if (!$this->isValidHexColor($value)) {
                    $fail('O campo ' . $attribute . ' deve ser uma cor hexadecimal válida (#000000 ou #000).');
                }
            }],
            'color_secondary' => ['required', function ($attribute, $value, $fail) {
                if (!$this->isValidHexColor($value)) {
                    $fail('O campo ' . $attribute . ' deve ser uma cor hexadecimal válida (#000000 ou #000).');
                }
            }],
            'color_accent' => ['required', function ($attribute, $value, $fail) {
                if (!$this->isValidHexColor($value)) {
                    $fail('O campo ' . $attribute . ' deve ser uma cor hexadecimal válida (#000000 ou #000).');
                }
            }],
            'new_logo' => 'nullable|image|max:2048|dimensions:min_width=200,min_height=50',
            'new_logo_dark' => 'nullable|image|max:2048|dimensions:min_width=200,min_height=50',
            'new_favicon' => 'nullable|image|max:512|dimensions:min_width=16,max_width=256',
            'new_default_thumb' => 'nullable|image|max:2048|dimensions:min_width=100,min_height=100',
        ];

        $validator = Validator::make([
            'color_primary' => $this->color_primary,
            'color_secondary' => $this->color_secondary,
            'color_accent' => $this->color_accent,
            'new_logo' => $this->new_logo,
            'new_logo_dark' => $this->new_logo_dark,
            'new_favicon' => $this->new_favicon,
            'new_default_thumb' => $this->new_default_thumb,
        ], $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->addError('branding', $error);
            }
            return;
        }

        try {
            $this->app->color_primary = $this->color_primary;
            $this->app->color_secondary = $this->color_secondary;
            $this->app->color_accent = $this->color_accent;

            // Upload de novos arquivos com storage isolado
            if ($this->new_logo) {
                $this->app->url_image_logo = $this->uploadFile($this->new_logo, 'logos');
                $this->new_logo = null;
                $this->logo_preview = null;
            }
            if ($this->new_logo_dark) {
                $this->app->url_image_logo_dark = $this->uploadFile($this->new_logo_dark, 'logos');
                $this->new_logo_dark = null;
                $this->logo_dark_preview = null;
            }
            if ($this->new_favicon) {
                $this->app->url_image_favicon = $this->uploadFile($this->new_favicon, 'favicons');
                $this->new_favicon = null;
                $this->favicon_preview = null;
            }
            if ($this->new_default_thumb) {
                $this->app->url_image_default_thumb = $this->uploadFile($this->new_default_thumb, 'thumbs');
                $this->new_default_thumb = null;
                $this->default_thumb_preview = null;
            }

            $this->app->branding_updated_at = now();
            $this->app->save();

            // Recarregar o app do banco para pegar o branding_updated_at atualizado
            $this->app->refresh();

            // Incrementar versão para forçar re-render das imagens
            $this->branding_version++;

            // Limpar cache relacionado
            \Cache::forget("app_domain_{$this->app->domain_primary}");
            tenantCacheFlush($this->app->id); // Usar UUID do app

            $this->emit('notify', 'Branding atualizado com sucesso!', 'success');

        } catch (\Exception $e) {
            $this->addError('branding', 'Erro ao salvar: ' . $e->getMessage());
        }
    }

    /**
     * Salvar configurações e SEO
     */
    public function saveSettings()
    {
        try {
            // E-mails e SEO
            $this->app->email_from_name = $this->email_from_name;
            $this->app->email_from_address = $this->email_from_address;
            $this->app->email_reply_to = $this->email_reply_to;
            $this->app->meta_title = $this->meta_title;
            $this->app->meta_description = $this->meta_description;
            $this->app->meta_keywords = $this->meta_keywords;

            // Configurações avançadas - garantir que settings seja sempre um array
            $settings = $this->app->settings ?? [];

            // Se settings for string JSON, decodificar para array
            if (is_string($settings)) {
                $settings = json_decode($settings, true) ?? [];
            }

            // Garantir que seja array
            if (!is_array($settings)) {
                $settings = [];
            }

            $settings['features'] = $this->features;
            $settings['limits'] = $this->limits;
            $this->app->settings = $settings;

            $this->app->save();

            // Limpar cache
            tenantCacheFlush($this->appId);

            $this->emit('notify', 'Configurações salvas com sucesso!', 'success');

        } catch (\Exception $e) {
            $this->addError('settings', 'Erro ao salvar: ' . $e->getMessage());
        }
    }

    /**
     * Toggle de ativação da aplicação
     */
    public function toggleAppActive()
    {
        try {
            $this->app->app_active = !$this->app->app_active;
            $this->app->save();

            $this->app_active = $this->app->app_active;

            // Limpar caches relacionados
            \Cache::forget("app_domain_{$this->app->domain_primary}");
            \Cache::forget('super_admin_stats');
            \Cache::forget('super_admin_apps');

            $status = $this->app_active ? 'ativada' : 'desativada';
            $this->emit('notify', "Aplicação {$status} com sucesso!", 'success');

        } catch (\Exception $e) {
            $this->addError('basic', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    /**
     * Atualizar features/módulos
     */
    public function updateFeatures($feature, $enabled)
    {
        $this->features[$feature] = $enabled;

        // Auto-salvar configurações ao alterar feature
        $this->saveSettings();
    }

    /**
     * Validar e formatar domínios aliases
     */
    private function validateDomainAliases(): array
    {
        if (empty($this->domain_aliases)) {
            return [];
        }

        $domains = array_map('trim', explode(',', $this->domain_aliases));
        $validDomains = [];

        foreach ($domains as $domain) {
            // Validação básica de domínio
            if (filter_var('http://' . $domain, FILTER_VALIDATE_URL) && !empty($domain)) {
                $validDomains[] = $domain;
            }
        }

        return array_unique($validDomains);
    }

    /**
     * Upload de arquivo com storage isolado
     */
    private function uploadFile($file, $type)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . Str::random(10) . '.' . $extension;

        // Storage isolado por app usando helper
        $relativePath = "branding/{$type}";
        ensureTenantDirectory($relativePath);

        // Usar UUID do app atual para storage isolado
        $app = currentApp() ?? $this->app;
        $appId = $app->id;
        $diskPath = "{$appId}/{$relativePath}";

        // Armazenar o arquivo no diretório isolado
        $file->storeAs($diskPath, $filename, 'public');

        return "{$relativePath}/{$filename}";
    }

    /**
     * Listener para mudança nos uploads com preview
     */
    public function updatedNewLogo()
    {
        $this->validateOnly('new_logo', [
            'new_logo' => 'image|max:2048|dimensions:min_width=200,min_height=50'
        ]);

        if ($this->new_logo) {
            $this->logo_preview = $this->new_logo->temporaryUrl();
        }
    }

    public function updatedNewLogoDark()
    {
        $this->validateOnly('new_logo_dark', [
            'new_logo_dark' => 'image|max:2048|dimensions:min_width=200,min_height=50'
        ]);

        if ($this->new_logo_dark) {
            $this->logo_dark_preview = $this->new_logo_dark->temporaryUrl();
        }
    }

    public function updatedNewFavicon()
    {
        $this->validateOnly('new_favicon', [
            'new_favicon' => 'image|max:512|dimensions:min_width=16,max_width=256'
        ]);

        if ($this->new_favicon) {
            $this->favicon_preview = $this->new_favicon->temporaryUrl();
        }
    }

    public function updatedNewDefaultThumb()
    {
        $this->validateOnly('new_default_thumb', [
            'new_default_thumb' => 'image|max:2048|dimensions:min_width=100,min_height=100'
        ]);

        if ($this->new_default_thumb) {
            $this->default_thumb_preview = $this->new_default_thumb->temporaryUrl();
        }
    }

    /**
     * Remover preview de upload
     */
    public function removePreview($type)
    {
        switch ($type) {
            case 'logo':
                $this->new_logo = null;
                $this->logo_preview = null;
                break;
            case 'logo_dark':
                $this->new_logo_dark = null;
                $this->logo_dark_preview = null;
                break;
            case 'favicon':
                $this->new_favicon = null;
                $this->favicon_preview = null;
                break;
            case 'default_thumb':
                $this->new_default_thumb = null;
                $this->default_thumb_preview = null;
                break;
        }
    }

    /**
     * Limpar cache da aplicação
     */
    public function clearAppCache()
    {
        \Cache::forget("app_domain_{$this->app->domain_primary}");

        // Limpar cache de configurações da app
        tenantCacheFlush($this->appId);

        $this->emit('notify', 'Cache da aplicação limpo com sucesso!', 'success');
    }

    /**
     * Duplicar aplicação
     */
    public function duplicateApp()
    {
        try {
            $newApp = $this->app->replicate();
            $newApp->id = (string) Str::uuid();
            $newApp->app_name = $this->app->app_name . ' (Cópia)';
            $newApp->domain_primary = null;
            $newApp->domain_aliases = null;
            $newApp->app_active = false;
            $newApp->created_at = now();
            $newApp->updated_at = now();
            $newApp->branding_updated_at = now();

            $newApp->save();

            $this->emit('notify', "Aplicação duplicada com sucesso! Acesse a edição para configurar o domínio.", 'success');

            return redirect()->route('super-admin.apps.edit', $newApp->id);

        } catch (\Exception $e) {
            $this->addError('duplicate', 'Erro ao duplicar: ' . $e->getMessage());
        }
    }

    /**
     * Salvar configurações do Safe2Pay (conta master do APP)
     */
    public function saveSafe2Pay()
    {
        try {
            $this->app->safe2pay_token_live = $this->safe2pay_token_live;
            $this->app->safe2pay_token_test = $this->safe2pay_token_test;
            $this->app->safe2pay_token_live_pass = $this->safe2pay_token_live_pass;
            $this->app->safe2pay_token_test_pass = $this->safe2pay_token_test_pass;
            $this->app->safe2pay_active = $this->safe2pay_active;
            $this->app->safe2pay_test_mode = $this->safe2pay_test_mode;
            $this->app->safe2pay_settings = $this->safe2pay_settings;

            $this->app->save();

            // Se o Safe2Pay estiver ativo, garante que existe um gateway padrão na tabela tb_app_pay_gateways
            if ($this->safe2pay_active) {
                $gateway = AppPayGateway::firstOrCreate(
                    [
                        'app_id' => $this->appId,
                        'gateway_slug' => 'safe2pay-master',
                    ],
                    [
                        'gateway_name' => 'Safe2Pay (Conta Master)',
                        'gateway_description' => 'Gateway Safe2Pay configurado no nível da aplicação',
                        'token_live' => $this->safe2pay_token_live,
                        'token_live_secret' => $this->safe2pay_token_live_pass,
                        'token_test' => $this->safe2pay_token_test,
                        'token_test_secret' => $this->safe2pay_token_test_pass,
                        'pay_boleto' => true,
                        'pay_pix' => true,
                        'pay_card_credit' => true,
                        'pay_card_debit' => true,
                        'pay_slip_pix' => true,
                    ]
                );

                // Se já existe, atualiza os tokens
                if (!$gateway->wasRecentlyCreated) {
                    $gateway->update([
                        'gateway_name' => 'Safe2Pay (Conta Master)',
                        'gateway_description' => 'Gateway Safe2Pay configurado no nível da aplicação',
                        'token_live' => $this->safe2pay_token_live,
                        'token_live_secret' => $this->safe2pay_token_live_pass,
                        'token_test' => $this->safe2pay_token_test,
                        'token_test_secret' => $this->safe2pay_token_test_pass,
                    ]);
                }
            }

            // Limpar caches
            \Cache::forget('super_admin_stats');
            \Cache::forget('super_admin_apps');
            \Cache::forget("app_safe2pay_{$this->appId}");

            $this->emit('notify', 'Configurações do Safe2Pay salvas com sucesso!', 'success');

        } catch (\Exception $e) {
            $this->addError('safe2pay', 'Erro ao salvar configurações: ' . $e->getMessage());
        }
    }

    /**
     * Valida se uma string é uma cor hexadecimal válida
     */
    private function isValidHexColor($color)
    {
        if (!is_string($color)) {
            return false;
        }

        // Remove espaços em branco
        $color = trim($color);

        // Verifica se começa com #
        if (!str_starts_with($color, '#')) {
            return false;
        }

        // Remove o #
        $hex = substr($color, 1);

        // Verifica se tem o tamanho correto (3 ou 6 caracteres)
        if (strlen($hex) !== 3 && strlen($hex) !== 6) {
            return false;
        }

        // Verifica se todos os caracteres são hexadecimais válidos
        return ctype_xdigit($hex);
    }
}
