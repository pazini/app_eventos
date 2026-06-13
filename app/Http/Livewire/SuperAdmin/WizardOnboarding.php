<?php

namespace App\Http\Livewire\SuperAdmin;

use App\Models\App;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class WizardOnboarding extends Component
{
    use WithFileUploads;

    // Estado do wizard
    public $currentStep = 1;
    public $totalSteps = 6;
    public $completedSteps = [];

    // Step 1: Informações Básicas
    public $name = '';
    public $description = '';
    public $admin_email = '';

    // Step 2: Branding
    public $logo_file;
    public $logo_dark_file;
    public $favicon_file;
    public $logo_preview;
    public $logo_dark_preview;
    public $favicon_preview;
    public $color_primary = '#1a202c';
    public $color_secondary = '#2d3748';
    public $color_accent = '#3182ce';

    // Step 3: Domínio
    public $domain_primary = '';
    public $domain_aliases = '';

    // Step 4: Módulos
    public $features = [
        'campaigns' => true,
        'events' => true,
        'subscriptions' => false,
        'analytics' => false,
        'reports' => false,
        'integrations' => false
    ];

    // Step 5: Primeiro Usuário Admin
    public $user_name = '';
    public $user_email = '';
    public $user_password = '';
    public $user_password_confirmation = '';

    // Step 6: Dados finais
    public $app_limit_date = '';
    public $storage_mb = 5120;
    public $campaigns_per_customer = 50;
    public $events_per_customer = 50;

    // Estado interno
    public $isProcessing = false;
    public $createdApp = null;

    public function mount()
    {
        // Pré-preencher com data de expiração para 1 ano
        $this->app_limit_date = now()->addYear()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.super-admin.wizard-onboarding')
            ->layout('layouts.app-pep-auth');
    }

    // Navegação entre steps
    public function nextStep()
    {
        if ($this->validateCurrentStep()) {
            $this->completedSteps[$this->currentStep] = true;
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= $this->totalSteps) {
            // Só permite ir para steps já completados ou o próximo
            if (isset($this->completedSteps[$step - 1]) || $step == 1) {
                $this->currentStep = $step;
            }
        }
    }

    // Validação por step
    private function validateCurrentStep()
    {
        switch ($this->currentStep) {
            case 1:
                return $this->validateStep1();
            case 2:
                return $this->validateStep2();
            case 3:
                return $this->validateStep3();
            case 4:
                return $this->validateStep4();
            case 5:
                return $this->validateStep5();
            case 6:
                return true; // Step de confirmação
            default:
                return false;
        }
    }

    private function validateStep1()
    {
        $rules = [
            'name' => 'required|string|min:3|max:255|unique:tb_app,name',
            'description' => 'nullable|string|max:500',
            'admin_email' => 'required|email|unique:users,email'
        ];

        try {
            $this->validate($rules);
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            return false;
        }
    }

    private function validateStep2()
    {
        $rules = [
            'logo_file' => 'nullable|image|max:2048|dimensions:max_width=500,max_height=500',
            'logo_dark_file' => 'nullable|image|max:2048|dimensions:max_width=500,max_height=500',
            'favicon_file' => 'nullable|image|max:512|dimensions:max_width=64,max_height=64',
            'color_primary' => 'required|regex:/^#[0-9a-fA-F]{6}$/',
            'color_secondary' => 'required|regex:/^#[0-9a-fA-F]{6}$/',
            'color_accent' => 'required|regex:/^#[0-9a-fA-F]{6}$/'
        ];

        try {
            $this->validate($rules);
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            return false;
        }
    }

    private function validateStep3()
    {
        $rules = [
            'domain_primary' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9]*\.[a-zA-Z]{2,}$/',
                Rule::unique('tb_app', 'domain_primary')
            ]
        ];

        try {
            $this->validate($rules);
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            return false;
        }
    }

    private function validateStep4()
    {
        // Features são opcionais, então sempre válido
        return true;
    }

    private function validateStep5()
    {
        $rules = [
            'user_name' => 'required|string|min:3|max:255',
            'user_email' => 'required|email|unique:users,email',
            'user_password' => 'required|string|min:8|confirmed'
        ];

        try {
            $this->validate($rules);
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            return false;
        }
    }

    // Upload handlers
    public function updatedLogoFile()
    {
        if ($this->logo_file) {
            $this->logo_preview = $this->logo_file->temporaryUrl();
        }
    }

    public function updatedLogoDarkFile()
    {
        if ($this->logo_dark_file) {
            $this->logo_dark_preview = $this->logo_dark_file->temporaryUrl();
        }
    }

    public function updatedFaviconFile()
    {
        if ($this->favicon_file) {
            $this->favicon_preview = $this->favicon_file->temporaryUrl();
        }
    }

    public function removePreview($type)
    {
        switch ($type) {
            case 'logo':
                $this->logo_file = null;
                $this->logo_preview = null;
                break;
            case 'logo_dark':
                $this->logo_dark_file = null;
                $this->logo_dark_preview = null;
                break;
            case 'favicon':
                $this->favicon_file = null;
                $this->favicon_preview = null;
                break;
        }
    }

    // Formatação de domínios
    public function updatedDomainPrimary()
    {
        $this->domain_primary = strtolower(trim($this->domain_primary));
    }

    public function updatedDomainAliases()
    {
        if ($this->domain_aliases) {
            $aliases = explode("\n", $this->domain_aliases);
            $aliases = array_map('trim', $aliases);
            $aliases = array_map('strtolower', $aliases);
            $aliases = array_filter($aliases);
            $this->domain_aliases = implode("\n", $aliases);
        }
    }

    // Finalizar criação
    public function completeWizard()
    {
        if (!$this->validateAllSteps()) {
            $this->addError('wizard', 'Há erros nos dados fornecidos. Verifique todos os passos.');
            return;
        }

        $this->isProcessing = true;

        try {
            // Criar o app
            $appData = $this->prepareAppData();
            $this->createdApp = App::create($appData);

            // Upload de arquivos
            $this->handleFileUploads();

            // Criar estrutura de storage
            ensureTenantDirectory('', $this->createdApp->id);

            // Criar primeiro usuário admin
            $this->createAdminUser();

            // Limpar cache
            $this->clearRelatedCache();

            session()->flash('success', 'Aplicação criada com sucesso!');
            return redirect()->route('super-admin.apps.index');

        } catch (\Exception $e) {
            $this->isProcessing = false;
            $this->addError('wizard', 'Erro ao criar aplicação: ' . $e->getMessage());
        }
    }

    private function validateAllSteps()
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->currentStep = $i;
            if (!$this->validateCurrentStep()) {
                return false;
            }
        }
        $this->currentStep = 6;
        return true;
    }

    private function prepareAppData()
    {
        // Preparar aliases
        $aliases = [];
        if ($this->domain_aliases) {
            $aliases = explode("\n", trim($this->domain_aliases));
            $aliases = array_filter(array_map('trim', $aliases));
        }

        return [
            'name' => $this->name,
            'description' => $this->description,
            'domain_primary' => $this->domain_primary,
            'domain_aliases' => $aliases,
            'color_primary' => $this->color_primary,
            'color_secondary' => $this->color_secondary,
            'color_accent' => $this->color_accent,
            'app_active' => true,
            'app_limit_date' => $this->app_limit_date,
            'email_from_name' => $this->name,
            'email_from_address' => 'noreply@' . $this->domain_primary,
            'email_reply_to' => $this->admin_email,
            'meta_title' => $this->name,
            'meta_description' => $this->description ?: "Plataforma de eventos e campanhas - {$this->name}",
            'settings' => json_encode([
                'features' => $this->features,
                'limits' => [
                    'storage_mb' => $this->storage_mb,
                    'campaigns_per_customer' => $this->campaigns_per_customer,
                    'events_per_customer' => $this->events_per_customer
                ],
                'timezone' => 'America/Sao_Paulo',
                'locale' => 'pt_BR',
                'currency' => 'BRL'
            ])
        ];
    }

    private function handleFileUploads()
    {
        $uploads = [
            'logo_file' => 'url_image_logo',
            'logo_dark_file' => 'url_image_logo_dark',
            'favicon_file' => 'url_image_favicon'
        ];

        foreach ($uploads as $fileProperty => $dbField) {
            if ($this->$fileProperty instanceof UploadedFile) {
                $relativePath = "branding";
                $extension = $this->$fileProperty->getClientOriginalExtension();
                $filename = strtolower($dbField) . '_' . time() . '.' . $extension;

                // Criar diretório específico do app no storage isolado
                $appStoragePath = storage_path("app/{$this->createdApp->id}/{$relativePath}");
                if (!file_exists($appStoragePath)) {
                    mkdir($appStoragePath, 0755, true);
                }

                // Store no storage isolado usando o path completo
                $this->$fileProperty->storeAs("{$this->createdApp->id}/{$relativePath}", $filename, 'public');

                // Atualizar no banco com path relativo
                $this->createdApp->update([
                    $dbField => "{$relativePath}/{$filename}"
                ]);
            }
        }
    }

    private function createAdminUser()
    {
        User::create([
            'name' => $this->admin_name,
            'email' => $this->admin_email,
            'password' => bcrypt($this->admin_password),
            'email_verified_at' => now(),
            'app_id' => $this->createdApp->id,
            'role' => 'admin'
        ]);
    }

    private function clearRelatedCache()
    {
        // Limpar caches relacionados
        Cache::tags(['apps', 'domains'])->flush();
        Cache::forget("app_domain_{$this->domain_primary}");
        if ($this->domain_aliases) {
            $aliases = explode("\n", trim($this->domain_aliases));
            foreach ($aliases as $alias) {
                Cache::forget("app_domain_" . trim($alias));
            }
        }
    }

    public function getProgressPercentageProperty()
    {
        return ($this->currentStep / $this->totalSteps) * 100;
    }

    public function getStepTitleProperty()
    {
        $titles = [
            1 => 'Informações Básicas',
            2 => 'Branding',
            3 => 'Configuração de Domínio',
            4 => 'Módulos Habilitados',
            5 => 'Primeiro Usuário Admin',
            6 => 'Confirmação e Ativação'
        ];

        return $titles[$this->currentStep] ?? 'Desconhecido';
    }
}
