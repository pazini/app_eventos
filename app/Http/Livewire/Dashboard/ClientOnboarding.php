<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Customer;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class ClientOnboarding extends Component
{
    use WithFileUploads;

    // Estado do wizard
    public $currentStep = 1;
    public $totalSteps = 10;
    public $completedSteps = [];

    // Step 1: Dados da Empresa/Organização
    public $company_name = '';
    public $company_type = 'empresa'; // empresa, ong, pessoa_fisica
    public $document = ''; // CNPJ ou CPF
    public $company_description = '';
    public $phone = '';
    public $website = '';
    public $address = '';
    public $city = '';
    public $state = '';
    public $zipcode = '';

    // Step 2: Configurações Personalizadas
    public $timezone = 'America/Sao_Paulo';
    public $currency = 'BRL';
    public $notification_email = true;
    public $notification_sms = false;
    public $notification_push = true;
    public $max_campaigns = 10;
    public $max_events = 5;
    public $max_users = 3;

    // Step 3: Upload de Logo e Materiais
    public $logo_file;
    public $banner_file;
    public $materials_files = [];
    public $logo_preview;
    public $banner_preview;
    public $materials_preview = [];

    // Step 4: Domínio Personalizado
    public $custom_subdomain = '';
    public $subdomain_enabled = false;

    // Step 5: Métodos de Pagamento
    public $payment_methods = [
        'credit_card' => true,
        'pix' => true,
        'boleto' => false,
        'debit_card' => false
    ];
    public $payment_fee = 3.5;
    public $bank_account = '';
    public $pix_key = '';

    // Step 6: Usuário Administrador
    public $admin_name = '';
    public $admin_email = '';
    public $admin_password = '';
    public $admin_password_confirmation = '';
    public $admin_phone = '';

    // Step 7: Textos Personalizados
    public $custom_welcome_message = '';
    public $custom_thank_you_message = '';
    public $custom_email_signature = '';
    public $custom_footer_text = '';

    // Step 8: Tour Guiado
    public $skip_tour = false;
    public $tour_preferences = [
        'dashboard' => true,
        'campaigns' => true,
        'events' => true,
        'reports' => false
    ];

    // Step 9: Configuração de Teste
    public $create_test_content = true;
    public $test_type = 'campaign'; // campaign ou event
    public $test_campaign_title = '';
    public $test_event_title = '';

    // Step 10: Dados finais
    public $send_welcome_email = true;
    public $activate_immediately = true;

    // Estado interno
    public $isProcessing = false;
    public $createdCustomer = null;
    public $currentApp = null;

    public function mount()
    {
        $this->currentApp = currentApp();

        if (!$this->currentApp) {
            abort(404, 'App não encontrado');
        }

        // Verificar se o usuário tem permissão de admin
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Acesso negado');
        }

        // Pré-preencher alguns campos baseado nas configurações do app
        $this->timezone = appConfig('timezone', 'America/Sao_Paulo');
        $this->currency = appConfig('currency', 'BRL');

        // Verificar se subdomain está disponível para este app
        $this->subdomain_enabled = appHasFeature('custom_domains') || appConfig('features.custom_subdomains', false);

        // Configurar limites padrão
        $this->max_campaigns = appConfig('limits.campaigns_per_customer', 10);
        $this->max_events = appConfig('limits.events_per_customer', 5);
        $this->max_users = appConfig('limits.users_per_customer', 3);
    }

    public function render()
    {
        return view('livewire.dashboard.client-onboarding')
            ->layout('layouts.app-pep-auth', [
                'title' => 'Onboarding de Cliente - ' . appName()
            ]);
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
            case 1: return $this->validateStep1();
            case 2: return $this->validateStep2();
            case 3: return $this->validateStep3();
            case 4: return $this->validateStep4();
            case 5: return $this->validateStep5();
            case 6: return $this->validateStep6();
            case 7: return $this->validateStep7();
            case 8: return $this->validateStep8();
            case 9: return $this->validateStep9();
            case 10: return true; // Step de confirmação
            default: return false;
        }
    }

    private function validateStep1()
    {
        $rules = [
            'company_name' => 'required|string|min:3|max:255',
            'company_type' => 'required|in:empresa,ong,pessoa_fisica',
            'document' => [
                'required',
                'string',
                $this->company_type == 'pessoa_fisica' ? 'min:11|max:14' : 'min:14|max:18'
            ],
            'phone' => 'required|string|min:10|max:15',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:2'
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
            'timezone' => 'required|string',
            'currency' => 'required|in:BRL,USD,EUR',
            'max_campaigns' => 'required|integer|min:1|max:999',
            'max_events' => 'required|integer|min:1|max:999',
            'max_users' => 'required|integer|min:1|max:100'
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
            'logo_file' => 'nullable|image|max:2048|dimensions:max_width=500,max_height=500',
            'banner_file' => 'nullable|image|max:5120|dimensions:max_width=1920,max_height=1080'
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
        if ($this->subdomain_enabled && $this->custom_subdomain) {
            $rules = [
                'custom_subdomain' => [
                    'string',
                    'min:3',
                    'max:50',
                    'regex:/^[a-zA-Z0-9\-]+$/',
                    Rule::unique('tb_customers', 'subdomain')->where('app_id', $this->currentApp->id)
                ]
            ];

            try {
                $this->validate($rules);
                return true;
            } catch (\Illuminate\Validation\ValidationException $e) {
                return false;
            }
        }
        return true;
    }

    private function validateStep5()
    {
        $hasPaymentMethod = in_array(true, $this->payment_methods, true);

        if (!$hasPaymentMethod) {
            $this->addError('payment_methods', 'Selecione pelo menos um método de pagamento.');
            return false;
        }

        $rules = [
            'payment_fee' => 'required|numeric|min:0|max:20'
        ];

        try {
            $this->validate($rules);
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            return false;
        }
    }

    private function validateStep6()
    {
        $rules = [
            'admin_name' => 'required|string|min:3|max:255',
            'admin_email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
            ],
            'admin_password' => 'required|string|min:8|confirmed',
            'admin_phone' => 'nullable|string|min:10|max:15'
        ];

        try {
            $this->validate($rules);
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            return false;
        }
    }

    private function validateStep7()
    {
        // Textos personalizados são opcionais
        return true;
    }

    private function validateStep8()
    {
        // Tour é opcional
        return true;
    }

    private function validateStep9()
    {
        if ($this->create_test_content) {
            $rules = [];

            if ($this->test_type == 'campaign') {
                $rules['test_campaign_title'] = 'required|string|min:5|max:100';
            } elseif ($this->test_type == 'event') {
                $rules['test_event_title'] = 'required|string|min:5|max:100';
            }

            try {
                $this->validate($rules);
                return true;
            } catch (\Illuminate\Validation\ValidationException $e) {
                return false;
            }
        }
        return true;
    }

    // Upload handlers
    public function updatedLogoFile()
    {
        if ($this->logo_file) {
            $this->logo_preview = $this->logo_file->temporaryUrl();
        }
    }

    public function updatedBannerFile()
    {
        if ($this->banner_file) {
            $this->banner_preview = $this->banner_file->temporaryUrl();
        }
    }

    public function removePreview($type)
    {
        switch ($type) {
            case 'logo':
                $this->logo_file = null;
                $this->logo_preview = null;
                break;
            case 'banner':
                $this->banner_file = null;
                $this->banner_preview = null;
                break;
        }
    }

    // Formatação de campos
    public function updatedCustomSubdomain()
    {
        $this->custom_subdomain = strtolower(str_replace(' ', '-', trim($this->custom_subdomain)));
    }

    public function updatedDocument()
    {
        // Remover caracteres não numéricos
        $this->document = preg_replace('/[^0-9]/', '', $this->document);
    }

    public function updatedZipcode()
    {
        // Remover caracteres não numéricos
        $this->zipcode = preg_replace('/[^0-9]/', '', $this->zipcode);
    }

    // Finalizar onboarding
    public function completeOnboarding()
    {
        if (!$this->validateAllSteps()) {
            $this->addError('wizard', 'Há erros nos dados fornecidos. Verifique todos os passos.');
            return;
        }

        $this->isProcessing = true;

        try {
            // Criar o cliente
            $customerData = $this->prepareCustomerData();
            $this->createdCustomer = Customer::create($customerData);

            // Upload de arquivos
            $this->handleFileUploads();

            // Criar usuário administrador
            $this->createAdminUser();

            // Criar conteúdo de teste se solicitado
            if ($this->create_test_content) {
                $this->createTestContent();
            }

            // Enviar e-mail de boas-vindas se solicitado
            if ($this->send_welcome_email) {
                $this->sendWelcomeEmail();
            }

            // Limpar caches relacionados
            $this->clearRelatedCache();

            session()->flash('success', 'Cliente criado com sucesso! Onboarding finalizado.');

            if (!$this->skip_tour) {
                session()->flash('start_tour', true);
            }

            return redirect()->route('dashboard-organizadores');

        } catch (\Exception $e) {
            $this->isProcessing = false;
            $this->addError('wizard', 'Erro ao criar cliente: ' . $e->getMessage());
        }
    }

    private function validateAllSteps()
    {
        for ($i = 1; $i <= 9; $i++) {
            $this->currentStep = $i;
            if (!$this->validateCurrentStep()) {
                return false;
            }
        }
        $this->currentStep = 10;
        return true;
    }

    private function prepareCustomerData()
    {
        return [
            'name' => $this->company_name,
            'description' => $this->company_description,
            'customer_type' => $this->company_type,
            'document' => $this->document,
            'phone' => $this->phone,
            'website' => $this->website,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zipcode' => $this->zipcode,
            'subdomain' => $this->custom_subdomain ?: null,
            'timezone' => $this->timezone,
            'currency' => $this->currency,
            'settings' => json_encode([
                'notifications' => [
                    'email' => $this->notification_email,
                    'sms' => $this->notification_sms,
                    'push' => $this->notification_push
                ],
                'limits' => [
                    'campaigns' => $this->max_campaigns,
                    'events' => $this->max_events,
                    'users' => $this->max_users
                ],
                'payment' => [
                    'methods' => array_keys(array_filter($this->payment_methods)),
                    'fee' => $this->payment_fee,
                    'bank_account' => $this->bank_account,
                    'pix_key' => $this->pix_key
                ],
                'texts' => [
                    'welcome_message' => $this->custom_welcome_message,
                    'thank_you_message' => $this->custom_thank_you_message,
                    'email_signature' => $this->custom_email_signature,
                    'footer_text' => $this->custom_footer_text
                ],
                'onboarding' => [
                    'completed_at' => now()->toISOString(),
                    'created_by' => auth()->id(),
                    'skip_tour' => $this->skip_tour,
                    'test_content_created' => $this->create_test_content
                ]
            ]),
            'app_id' => $this->currentApp->id,
            // 'active' => $this->activate_immediately // Campo não existe na tabela
        ];
    }

    private function handleFileUploads()
    {
        if ($this->logo_file instanceof UploadedFile) {
            $path = "customers/{$this->createdCustomer->id}/logo";
            $filename = 'logo_' . time() . '.' . $this->logo_file->getClientOriginalExtension();

            // Path completo isolado por tenant
            $fullPath = tenantStoragePath($path);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            // Pega o app ID para construir o path completo para storeAs
            $app = currentApp();
            $appId = $app->id ?? 1;

            $this->logo_file->storeAs("{$appId}/{$path}", $filename, 'local');
            $this->createdCustomer->update(['url_image_logo' => "{$path}/{$filename}"]);
        }

        if ($this->banner_file instanceof UploadedFile) {
            $path = "customers/{$this->createdCustomer->id}/banner";
            $filename = 'banner_' . time() . '.' . $this->banner_file->getClientOriginalExtension();

            // Path completo isolado por tenant
            $fullPath = tenantStoragePath($path);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            // Pega o app ID para construir o path completo para storeAs
            $app = currentApp();
            $appId = $app->id ?? 1;

            $this->banner_file->storeAs("{$appId}/{$path}", $filename, 'local');
            $this->createdCustomer->update(['banner_image' => "{$path}/{$filename}"]);
        }
    }

    private function createAdminUser()
    {
        User::create([
            'name' => $this->admin_name,
            'email' => $this->admin_email,
            'password' => Hash::make($this->admin_password),
            'phone' => $this->admin_phone,
            'email_verified_at' => now(),
            'app_id' => $this->currentApp->id,
            'customer_id' => $this->createdCustomer->id,
            'role' => 'admin'
        ]);
    }

    private function createTestContent()
    {
        // Esta funcionalidade seria implementada para criar conteúdo de teste
        // Por enquanto vamos deixar como placeholder
    }

    private function sendWelcomeEmail()
    {
        // Esta funcionalidade seria implementada para enviar e-mail de boas-vindas
        // Por enquanto vamos deixar como placeholder
    }

    private function clearRelatedCache()
    {
        Cache::tags(['customers', 'users'])->flush();
        tenantCacheFlush();
    }

    public function getProgressPercentageProperty()
    {
        return ($this->currentStep / $this->totalSteps) * 100;
    }

    public function getStepTitleProperty()
    {
        $titles = [
            1 => 'Dados da Empresa',
            2 => 'Configurações',
            3 => 'Logo e Materiais',
            4 => 'Domínio Personalizado',
            5 => 'Métodos de Pagamento',
            6 => 'Usuário Administrador',
            7 => 'Textos Personalizados',
            8 => 'Tour Guiado',
            9 => 'Configuração de Teste',
            10 => 'Ativação e Confirmação'
        ];

        return $titles[$this->currentStep] ?? 'Desconhecido';
    }
}
