<?php

namespace App\Http\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\App;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * Criação de nova aplicação white label
 *
 * Wizard com múltiplas etapas:
 * 1. Informações básicas
 * 2. Branding (logo, cores)
 * 3. Domínios
 * 4. Configurações (módulos, limites)
 * 5. Confirmação e ativação
 */
class AppsCreate extends Component
{
    use WithFileUploads;

    // Controle do wizard
    public $currentStep = 1;
    public $totalSteps = 5;

    // Etapa 1: Informações básicas
    public $app_name = '';
    public $app_slug = '';
    public $app_description = '';
    public $name = '';
    public $description = '';
    public $domain_primary = '';
    public $domain_aliases = '';
    public $app_active = true;
    public $app_limit_date = '';

    // Etapa 2: Branding
    public $color_primary = '#1a202c';
    public $color_secondary = '#2d3748';
    public $color_accent = '#ed8936';
    public $logo_file;
    public $logo_dark_file;
    public $favicon_file;
    public $logo_preview;
    public $logo_dark_preview;
    public $favicon_preview;

    // Etapa 3: Configurações e módulos
    public $features = [
        'campaigns' => true,
        'events' => true,
        'subscriptions' => false,
        'analytics' => true,
        'reports' => true,
        'integrations' => false,
    ];
    public $storage_limit_mb = 5120; // 5GB padrão
    public $campaigns_limit = 50;
    public $events_limit = 50;

    // E-mails e SEO
    public $email_from_name = '';
    public $email_from_address = '';
    public $email_reply_to = '';
    public $meta_title = '';
    public $meta_description = '';
    public $meta_keywords = '';

    // Etapa 5: Confirmação
    public $create_first_admin = true;
    public $admin_name = '';
    public $admin_email = '';
    public $admin_password = '';
    public $admin_password_confirmation = '';

    public function mount()
    {
        // Valores padrão baseados no app atual
        $currentApp = currentApp();
        if ($currentApp) {
            $this->color_primary = $currentApp->color_primary ?? '#1a202c';
            $this->color_secondary = $currentApp->color_secondary ?? '#2d3748';
            $this->color_accent = $currentApp->color_accent ?? '#ed8936';
        }

        // Data limite padrão: 1 ano
        $this->app_limit_date = Carbon::now()->addYear()->format('Y-m-d\TH:i');

        $this->name = $this->app_name;
        $this->description = $this->app_description;
    }

    public function render()
    {
        return view('livewire.super-admin.apps-create')
            ->layout('layouts.app-pep-auth');
    }

    /**
     * Avançar para próxima etapa
     */
    public function nextStep()
    {
        $this->validateCurrentStep();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
            $this->dispatchBrowserEvent('scroll-top');
        }
    }

    /**
     * Voltar para etapa anterior
     */
    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatchBrowserEvent('scroll-top');
        }
    }

    /**
     * Ir diretamente para uma etapa específica
     */
    public function goToStep($step)
    {
        if ($step >= 1 && $step <= $this->totalSteps && $step <= $this->currentStep) {
            $this->currentStep = $step;
        }
    }

    /**
     * Validar etapa atual
     */
    private function validateCurrentStep()
    {
        $rules = [];
        $messages = [];

        switch ($this->currentStep) {
            case 1: // Informações básicas
                $rules = [
                    'app_name' => [
                        'required',
                        'string',
                        'max:255',
                        function ($attribute, $value, $fail) {
                            $slug = Str::slug($value);
                            if ($slug === '') {
                                $fail('O nome da aplicação é inválido.');
                                return;
                            }
                            if (App::where('app_slug', $slug)->exists()) {
                                $fail('Já existe uma aplicação com este nome.');
                            }
                        },
                    ],
                    'app_description' => 'nullable|string|max:255',
                    'admin_email' => 'required|email|max:255',
                    'app_limit_date' => 'nullable|date|after:today',
                ];
                $messages = [
                    'app_name.required' => 'O nome da aplicação é obrigatório.',
                    'admin_email.required' => 'O e-mail do administrador é obrigatório.',
                    'admin_email.email' => 'O e-mail do administrador deve ser válido.',
                    'app_limit_date.after' => 'A data limite deve ser no futuro.',
                ];
                break;

            case 2: // Branding
                $rules = [
                    'color_primary' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
                    'color_secondary' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
                    'color_accent' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
                    'logo_file' => ['nullable', 'image', 'max:2048'],
                    'logo_dark_file' => ['nullable', 'image', 'max:2048'],
                    'favicon_file' => ['nullable', 'image', 'max:512'],
                ];
                $messages = [
                    'color_primary.regex' => 'A cor primária deve ser um código hexadecimal válido.',
                    'color_secondary.regex' => 'A cor secundária deve ser um código hexadecimal válido.',
                    'color_accent.regex' => 'A cor de destaque deve ser um código hexadecimal válido.',
                    'logo_file.max' => 'O logo deve ter no máximo 2MB.',
                    'favicon_file.max' => 'O favicon deve ter no máximo 512KB.',
                ];
                break;

            case 3: // Domínios
                $rules = [
                    'domain_primary' => 'required|string|max:255|unique:tb_app,domain_primary|regex:/^[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?)*$/',
                    'domain_aliases' => 'nullable|string',
                ];
                $messages = [
                    'domain_primary.required' => 'O domínio principal é obrigatório.',
                    'domain_primary.unique' => 'Este domínio já está sendo usado por outra aplicação.',
                    'domain_primary.regex' => 'O domínio deve ter um formato válido.',
                ];
                break;

            case 4: // Configurações
                $rules = [
                    'storage_limit_mb' => 'required|integer|min:1|max:102400', // Máximo 100GB
                    'campaigns_limit' => 'required|integer|min:1|max:1000',
                    'events_limit' => 'required|integer|min:1|max:1000',
                ];
                $messages = [
                    'storage_limit_mb.min' => 'O limite de storage deve ser de pelo menos 1MB.',
                    'storage_limit_mb.max' => 'O limite de storage não pode exceder 100GB.',
                ];
                break;

            case 5: // E-mails, SEO e confirmação
                $rules = [
                    'email_from_name' => 'nullable|string|max:255',
                    'email_from_address' => 'nullable|email|max:255',
                    'email_reply_to' => 'nullable|email|max:255',
                    'meta_title' => 'nullable|string|max:255',
                    'meta_description' => 'nullable|string|max:500',
                ];
                if ($this->create_first_admin) {
                    $rules = array_merge($rules, [
                        'admin_name' => 'required|string|max:255',
                        'admin_email' => 'required|email|max:255|unique:users,email',
                        'admin_password' => 'required|string|min:6|confirmed',
                    ]);
                    $messages = array_merge($messages, [
                        'admin_name.required' => 'O nome do administrador é obrigatório.',
                        'admin_email.required' => 'O e-mail do administrador é obrigatório.',
                        'admin_email.unique' => 'Este e-mail já está em uso.',
                        'admin_password.required' => 'A senha do administrador é obrigatória.',
                        'admin_password.min' => 'A senha deve ter pelo menos 6 caracteres.',
                        'admin_password.confirmed' => 'As senhas não conferem.',
                    ]);
                }
                break;
        }

        if (!empty($rules)) {
            $validator = Validator::make($this->all(), $rules, $messages);

            if ($validator->fails()) {
                $this->emit('notify', $validator->errors()->first(), 'error');
                foreach ($validator->errors()->all() as $error) {
                    $this->addError('validation', $error);
                }

                throw new \Illuminate\Validation\ValidationException($validator);
            }
        }
    }

    /**
     * Criar a aplicação
     */
    public function createApp()
    {
        $this->validateCurrentStep();

        try {
            $app = new App();
            $app->id = (string) Str::uuid();

            // Etapa 1: Informações básicas
            if (!$this->app_slug) {
                $this->app_slug = Str::slug($this->app_name);
            }
            $app->app_name = $this->app_name;
            $app->app_slug = $this->app_slug;
            $app->app_description = $this->app_description;
            $app->domain_primary = $this->domain_primary;
            if ($this->domain_aliases) {
                $aliases = preg_split('/[\r\n,]+/', $this->domain_aliases);
                $aliases = array_values(array_filter(array_map('trim', $aliases)));
                $app->domain_aliases = $aliases ?: null;
            } else {
                $app->domain_aliases = null;
            }
            $app->app_active = $this->app_active;
            $app->app_limit_date = $this->app_limit_date ? Carbon::parse($this->app_limit_date) : null;

            // Etapa 2: Branding
            $app->color_primary = $this->color_primary;
            $app->color_secondary = $this->color_secondary;
            $app->color_accent = $this->color_accent;
            $app->branding_updated_at = now();

            // Upload de arquivos
            if ($this->logo_file) {
                $app->url_image_logo = $this->uploadFile($this->logo_file, 'logos');
            }
            if ($this->logo_dark_file) {
                $app->url_image_logo_dark = $this->uploadFile($this->logo_dark_file, 'logos');
            }
            if ($this->favicon_file) {
                $app->url_image_favicon = $this->uploadFile($this->favicon_file, 'favicons');
            }

            // E-mails e SEO
            $app->email_from_name = $this->email_from_name ?: $this->app_name;
            $app->email_from_address = $this->email_from_address;
            $app->email_reply_to = $this->email_reply_to;
            $app->meta_title = $this->meta_title ?: $this->app_name;
            $app->meta_description = $this->meta_description;
            $app->meta_keywords = $this->meta_keywords;

            // Configurações e módulos
            $settings = [
                'features' => $this->features,
                'limits' => [
                    'storage_mb' => $this->storage_limit_mb,
                    'campaigns_per_customer' => $this->campaigns_limit,
                    'events_per_customer' => $this->events_limit,
                ],
                'locale' => 'pt_BR',
                'timezone' => 'America/Sao_Paulo',
                'currency' => 'BRL',
            ];
            $app->settings = $settings;

            $app->created_at = now();
            $app->updated_at = now();

            $app->save();

            // Criar estrutura de storage
            ensureTenantDirectory('customers', $app->id);
            ensureTenantDirectory('campaigns', $app->id);
            ensureTenantDirectory('events', $app->id);
            ensureTenantDirectory('exports', $app->id);

            // Criar Customer padrão com o nome do APP
            $defaultCustomer = $this->createDefaultCustomer($app);

            // Registrar customer padrão no settings para facilitar gestão
            $settings = $app->settings;
            if (is_string($settings)) {
                $settings = json_decode($settings, true) ?? [];
            }
            if (!is_array($settings)) {
                $settings = [];
            }
            $settings['default_customer_id'] = $defaultCustomer->id;
            $app->settings = $settings;
            $app->save();

            // Criar primeiro admin e associar ao APP e ao Customer padrão
            if ($this->create_first_admin && $this->admin_name && $this->admin_email && $this->admin_password) {
                $this->createFirstAdmin($app, $defaultCustomer);
            }

            // Limpar caches
            \Cache::forget('super_admin_stats');
            \Cache::forget('super_admin_apps');

            session()->flash('success', "Aplicação '{$app->app_name}' foi criada com sucesso!");

            return redirect()->route('super-admin.apps.edit', $app->id);

        } catch (\Exception $e) {
            $this->addError('creation', 'Erro ao criar aplicação: ' . $e->getMessage());
        }
    }

    /**
     * Upload de arquivo para storage específico do app
     */
    private function uploadFile($file, $type)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . Str::random(10) . '.' . $extension;

        // Salva na pasta de branding da aplicação
        $path = $file->storeAs('branding/' . $type, $filename, 'public');

        return $path;
    }

    /**
     * Auto-preencher dados com base no nome
     */
    public function updatedAppName()
    {
        if (empty($this->email_from_name)) {
            $this->email_from_name = $this->app_name;
        }
        if (empty($this->meta_title)) {
            $this->meta_title = $this->app_name;
        }
        if ($this->app_name) {
            $this->app_slug = Str::slug($this->app_name);
        }
        if (empty($this->domain_primary) && $this->app_name) {
            // Sugerir domínio baseado no nome
            $slug = Str::slug($this->app_name);
            $this->domain_primary = $slug . '.exemplo.com.br';
        }

        if ($this->name !== $this->app_name) {
            $this->name = $this->app_name;
        }
    }

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

    public function removePreview(string $type): void
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

    public function updatedName($value)
    {
        if ($this->app_name !== $value) {
            $this->app_name = $value;
        }
        if ($value) {
            $this->app_slug = Str::slug($value);
        }
    }

    public function updatedDescription($value)
    {
        if ($this->app_description !== $value) {
            $this->app_description = $value;
        }
    }

    public function updatedAppDescription()
    {
        if ($this->description !== $this->app_description) {
            $this->description = $this->app_description;
        }
    }

    /**
     * Criar Customer padrão para o APP
     * Este customer serve como conta administrativa principal do APP
     */
    private function createDefaultCustomer($app)
    {
        $customer = new \App\Models\Customer();
        $customer->id = (string) Str::uuid();
        $customer->app_id = $app->id;
        $customer->customer_slug = Str::slug($app->app_name);
        $customer->prefix_url = Str::slug($app->app_name);
        $customer->name_corporate = $app->app_name;
        $customer->name_fantasy = $app->app_name;
        $customer->name_short = $app->app_name;
        $customer->doc_type = 'CNPJ';
        $customer->doc_num = '00000000000000'; // Placeholder
        $customer->comercial_contact_name = $app->owner_name ?? 'Administrador';
        $customer->comercial_contact_email = $app->owner_email ?? $this->admin_email;
        $customer->comercial_contact_country = 55;
        $customer->comercial_contact_ddd = $app->owner_phone_ddd ?? 11;
        $customer->comercial_contact_num = $app->owner_phone_num ?? 999999999;
        $customer->generate_invoice = 0;
        $customer->created_at = now();
        $customer->updated_at = now();

        $customer->save();

        return $customer;
    }

    /**
     * Criar primeiro usuário administrador
     * Associa ao APP via users_app e ao Customer padrão via users_customer
     */
    private function createFirstAdmin($app, $defaultCustomer)
    {
        // 1. Criar o usuário
        $user = new \App\Models\User();
        $user->id = (string) Str::uuid();
        $user->name = $this->admin_name;
        $user->email = $this->admin_email;
        $user->password = bcrypt($this->admin_password);
        $user->email_verified_at = now();
        $user->doc_type = 'CPF';
        $user->doc_num = '00000000000'; // Placeholder
        $user->contact_country = 55;
        $user->contact_ddd = 11;
        $user->contact_num = 999999999;
        $user->created_at = now();
        $user->updated_at = now();
        $user->save();

        // 2. Associar usuário ao APP como 'admin'
        \App\Models\UserApp::create([
            'id' => (string) Str::uuid(),
            'app_id' => $app->id,
            'user_id' => $user->id,
            'user_active' => true,
            'user_role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Associar usuário ao Customer padrão como 'admin' com todas as permissões
        \App\Models\UserCustomer::create([
            'id' => (string) Str::uuid(),
            'customer_id' => $defaultCustomer->id,
            'user_id' => $user->id,
            'user_active' => true,
            'user_role' => 'admin',
            'can_events' => true,
            'can_campaigns' => true,
            'can_subscriptions' => (bool) ($this->features['subscriptions'] ?? false),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $user;
    }
}
