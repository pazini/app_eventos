<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Middleware\EnsureSuperAdmin;
use App\Models\Customer;
use App\Models\CustomerOrganization;
use App\Models\CustomerPayGateway;
use App\Models\ModCampaign\Campaign;
use App\Models\ModCampaign\CampaignOrganizer;
use App\Models\ModCampaign\CampaignOrderAnswer;
use App\Models\ModCampaign\CampaignPaymentAttempt;
use App\Models\ModCampaign\CampaignPaymentWebhook;
use App\Models\ModCampaign\CampaignQuestion;
use App\Models\ModCampaign\CampaignSubscription;
use App\Models\ModCampaign\CampaignSubscriptionCycle;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class CampanhaNova extends Component
{
    use WithFileUploads, Actions;

    public $customer;
    public $organization;
    public $campaign_id;
    public $organizer_id;

    // Dados da campanha
    public $name;
    public $name_short;
    public $slug;
    public $customer_organization_slug;
    public $description;
    public $about;
    public $status = 'draft';
    public $campaign_type = 'doacao'; // permanente, doacao, arrecadacao, ajuda, projetos, vaquinha
    public $visibility_public = true; // Sempre público
    public $datetime_start;
    public $datetime_finish;

    // Metas
    public $goal_amount;
    public $goal_amount_input; // Valor formatado para exibição
    public $goal_leads = null;
    public $goal_conversions = 1;
    public $amount_min; // Valor em centavos (int)
    public $amount_min_input; // Valor formatado para exibição

    // Exibição pública das metas
    public $show_goal_amount = true;
    public $show_goal_leads = true;
    public $show_goal_conversions = true;
    public $show_progress = true;

    // Visual
    public $color_primary = '#3B82F6';
    public $color_secondary = '#10B981';

    // Upload de imagens
    public $image_banner; // Arquivo temporário do upload
    public $image_thumb;  // Arquivo temporário do upload
    public $url_image_banner; // URL final salva no banco
    public $url_image_thumb;  // URL final salva no banco
    public $preview_banner; // Preview temporário
    public $preview_thumb;  // Preview temporário

    // Pagamento
    public $pay_gateway_id;
    public $pay_sandbox = false;
    public $pay_pix = true;
    public $pay_boleto = true;
    public $pay_card_credit = true;
    public $pay_card_credit_installment_max = 12;
    public $pay_card_credit_installment_fee_payer = 'customer'; // customer ou merchant

    public $gateways;

    // Perguntas do quiz
    public $questions = [];
    public $newQuestion = [
        'question_type' => 'text',
        'question_text' => '',
        'question_options' => '',
        'is_required' => false,
        'placeholder' => '',
        'help_text' => '',
    ];

    // Privacidade e configurações
    public $enable_questions = true;
    public $require_doc = true;
    public $allow_anonymous = false;
    public $allow_recurring = false;

    // Modal de apagar-campanha
    public $showDeleteModal = false;
    public $deleteConfirmation = '';
    public $deleteSummary = [];

    protected function rules()
    {
        $campaignId = $this->campaign_id ?: 'NULL';

        return [
            'name' => 'required|string|max:255',
            'name_short' => 'nullable|string|max:100',
            'slug' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($campaignId) {
                    // Verifica se já existe uma campanha com o mesmo slug e mesmo organizador
                    $query = Campaign::where('slug', $value)
                        ->where('organizer_id', $this->organizer_id);

                    // Se estiver editando, exclui a própria campanha da verificação
                    if ($this->campaign_id) {
                        $query->where('id', '!=', $this->campaign_id);
                    }

                    if ($query->exists()) {
                        $fail('Já existe uma campanha com este nome para o organizador selecionado.');
                    }
                },
            ],
            'description' => 'nullable|string|min:10',
            'about' => 'nullable|string',
            'status' => 'required|in:draft,active,active_direct,paused,finished,cancelled',
            'campaign_type' => 'required|in:permanente,doacao,arrecadacao,ajuda,projetos,vaquinha',
            'visibility_public' => 'boolean',
            'datetime_start' => 'required|date',
            'datetime_finish' => 'nullable|date|after_or_equal:datetime_start',
            'goal_amount' => 'nullable|integer|min:1000', // Se informado, mínimo R$ 10,00 (1000 centavos)
            'goal_leads' => 'nullable|integer|min:1',
            'goal_conversions' => 'nullable|integer|min:1',
            'amount_min' => 'required|integer|min:1000', // Mínimo R$ 10,00 (1000 centavos)
            'organizer_id' => 'nullable|uuid|exists:tbc_campaign_organizer,id',
            'pay_gateway_id' => 'nullable|uuid|exists:tb_customers_pay_gateways,id',
            'pay_card_credit_installment_max' => 'nullable|integer|min:1|max:12',
            'url_image_banner' => 'nullable|string|max:500',
            'url_image_thumb' => 'nullable|string|max:500',
            'allow_recurring' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'O nome da campanha é obrigatório',
        'datetime_start.required' => 'A data de início é obrigatória',
        'description.min' => 'A descrição deve ter no mínimo 10 caracteres',
        'amount_min.required' => 'O valor mínimo de participação é obrigatório',
        'amount_min.min' => 'O valor mínimo deve ser no mínimo R$ 10,00',
        'amount_min.integer' => 'O valor mínimo deve ser um número inteiro (centavos)',
        'goal_amount.min' => 'Se informada, a meta de receita deve ser no mínimo R$ 10,00',
        'goal_amount.integer' => 'A meta de receita deve ser um número inteiro (centavos)',
        'goal_leads.min' => 'Se informada, a meta de leads deve ser no mínimo 1',
    ];

    public function mount($campaign_id = null)
    {
        $customer = sessionCustomer();
        $organization = sessionOrganization();

        // Se não houver customer na sessão, mas for super-admin e estiver editando, buscar pelo campaign_id
        if (!$customer && $campaign_id && isAdmin()) {
            $campaign = Campaign::find($campaign_id);
            if ($campaign) {
                $customer = Customer::find($campaign->customer_id);
                if ($customer) {
                    sessionCustomer($customer->id); // seta a sessão para navegação consistente
                }
            }
        }

        if (!$customer) {
            session()->flash('error', 'Selecione primeiro um organizador em Campanhas para iniciar a criação.');
            return redirect()->route('dashboard-campanhas');
        }

        $this->customer = $customer ? Customer::find($customer->id) : null;

        if (! $this->customer) {
            session()->flash('error', 'Cliente não encontrado ou sem permissão para esse cliente. Selecione novamente o organizador.');
            return redirect()->route('dashboard-campanhas');
        }

        // SEGURANÇA: Se não é admin/owner, deve estar associado a pelo menos 1 organizador de campanha
        if (!isAdmin() && !isOwner()) {
            $allowedOrganizers = sessionCampaignOrganizers($this->customer->id);

            if ($allowedOrganizers->isEmpty()) {
                session()->flash('error', 'Você não está associado a nenhum organizador de campanhas.');
                return redirect()->route('dashboard-campanhas');
            }
        }
        $this->organization = $organization
            ? CustomerOrganization::find($organization->id)
            : null;

        // Organização: se for super-admin, busca sem bloquear
        if (
            $organization &&
            isAdmin() &&
            Auth::user()->app->first()->pivot->user_role === 'super-admin'
        ) {
            $this->organization = CustomerOrganization::withoutGlobalScopes()->with('organizers')->find($organization->id);
        } else {
            $this->organization = $organization
                ? CustomerOrganization::with('organizers')->find($organization->id)
                : null;
        }
        // Não bloqueia se organização não encontrada para super-admin
        if ($organization && ! $this->organization && (!isAdmin() || Auth::user()->app->first()->pivot->user_role !== 'super-admin')) {
            session()->flash('error', 'Organização não encontrada ou sessão expirada. Selecione novamente a instituição.');
            return redirect()->route('dashboard-campanhas');
        }

        // Carrega gateways disponíveis para o cliente
        $this->gateways = CustomerPayGateway::where('customer_id', $this->customer->id)
            ->with('appGateway')
            ->get();

        // Gera o slug da empresa/filial se não estiver editando
        if (! $campaign_id) {
            $this->generateCustomerOrganizationSlug();
            // Metas opcionais iniciam em branco
            $this->goal_amount = null;
            $this->goal_amount_input = null;
            $this->amount_min = 1000; // R$ 10,00
            $this->amount_min_input = toMoney(1000);

            // Define organizador automaticamente (primeiro disponível para o customer/organization)
            $organizerQuery = \App\Models\ModCampaign\CampaignOrganizer::where('customer_id', $this->customer->id);
            if ($this->organization) {
                $organizerQuery->where('organization_id', $this->organization->id);
            }
            $organizer = $organizerQuery->first();
            $this->organizer_id = $organizer->id ?? null;
            $this->generateSlugFromOrganizer();
        }

        // Se está editando uma campanha existente
        if ($campaign_id) {
            $campaign = Campaign::where('customer_id', $this->customer->id)
                ->where('id', $campaign_id)
                ->firstOrFail();

            $this->campaign_id = $campaign->id;
            $this->organizer_id = $campaign->organizer_id;
            $this->name = $campaign->name;
            $this->name_short = $campaign->name_short;
            $this->slug = $campaign->slug;
            $this->customer_organization_slug = $campaign->customer_organization_slug;
            $this->description = $campaign->description;
            $this->about = $campaign->about;
            $this->status = $campaign->status;
            $this->campaign_type = $campaign->campaign_type ?? 'doacao';
            $this->visibility_public = true; // Sempre público
            $this->datetime_start = $campaign->datetime_start ? \Carbon\Carbon::parse($campaign->datetime_start)->format('Y-m-d') : null;
            $this->datetime_finish = $campaign->datetime_finish ? \Carbon\Carbon::parse($campaign->datetime_finish)->format('Y-m-d') : null;
            // Valores em centavos (int) - converte para exibição
            $this->goal_amount = $campaign->goal_amount;
            $this->goal_amount_input = !is_null($campaign->goal_amount) ? toMoney($campaign->goal_amount) : null;
            $this->goal_leads = $campaign->goal_leads;
            $this->goal_conversions = $campaign->goal_conversions;
            $this->show_goal_amount = $campaign->show_goal_amount ?? true;
            $this->show_goal_leads = $campaign->show_goal_leads ?? true;
            $this->show_goal_conversions = $campaign->show_goal_conversions ?? true;
            $this->show_progress = $campaign->show_progress ?? true;
            $this->color_primary = $campaign->color_primary ?? '#3B82F6';
            $this->color_secondary = $campaign->color_secondary ?? '#10B981';
            $this->url_image_banner = $campaign->url_image_banner;
            $this->url_image_thumb = $campaign->url_image_thumb;
            $this->preview_banner = $campaign->url_image_banner;
            $this->preview_thumb = $campaign->url_image_thumb;
            $this->pay_gateway_id = $campaign->pay_gateway_id;
            $this->pay_sandbox = $campaign->pay_sandbox;
            $this->pay_pix = $campaign->pay_pix;
            $this->pay_boleto = $campaign->pay_boleto;
            $this->pay_card_credit = $campaign->pay_card_credit;
            $this->pay_card_credit_installment_max = $campaign->pay_card_credit_installment_max ?? 12;
            $this->pay_card_credit_installment_fee_payer = $campaign->pay_card_credit_installment_fee_payer ?? 'customer';
            $this->amount_min = $campaign->amount_min ?? 1000; // 1000 centavos = R$ 10,00
            $this->amount_min_input = toMoney($this->amount_min);
            $this->enable_questions = $campaign->enable_questions ?? true;
            $this->require_doc = $campaign->require_doc ?? true;
            $this->allow_anonymous = $campaign->allow_anonymous ?? false;
            $this->allow_recurring = $campaign->allow_recurring ?? false;

            // Carrega perguntas da campanha
            $this->questions = $campaign->questions()
                ->orderBy('order')
                ->get()
                ->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'question_type' => $question->question_type,
                        'question_text' => $question->question_text,
                        'question_options' => is_array($question->question_options) ? implode("\n", $question->question_options) : '',
                        'is_required' => $question->is_required,
                        'placeholder' => $question->placeholder,
                        'help_text' => $question->help_text,
                        'order' => $question->order,
                    ];
                })
                ->toArray();
        }
    }

    public function addQuestion()
    {
        if (empty($this->newQuestion['question_text'])) {
            $this->notification()->error('Preencha o texto da pergunta');
            return;
        }

        $this->questions[] = array_merge($this->newQuestion, [
            'order' => count($this->questions),
        ]);

        // Reset form
        $this->newQuestion = [
            'question_type' => 'text',
            'question_text' => '',
            'question_options' => '',
            'is_required' => false,
            'placeholder' => '',
            'help_text' => '',
        ];

        $this->notification()->success('Pergunta adicionada!');
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions); // Reindexar array

        // Atualiza ordem
        foreach ($this->questions as $key => $question) {
            $this->questions[$key]['order'] = $key;
        }

        $this->notification()->success('Pergunta removida!');
    }

    public function moveQuestionUp($index)
    {
        if ($index > 0) {
            $temp = $this->questions[$index - 1];
            $this->questions[$index - 1] = $this->questions[$index];
            $this->questions[$index] = $temp;

            // Atualiza ordem
            foreach ($this->questions as $key => $question) {
                $this->questions[$key]['order'] = $key;
            }
        }
    }

    public function moveQuestionDown($index)
    {
        if ($index < count($this->questions) - 1) {
            $temp = $this->questions[$index + 1];
            $this->questions[$index + 1] = $this->questions[$index];
            $this->questions[$index] = $temp;

            // Atualiza ordem
            foreach ($this->questions as $key => $question) {
                $this->questions[$key]['order'] = $key;
            }
        }
    }

    protected function generateCustomerOrganizationSlug()
    {
        if (! $this->customer) {
            return;
        }

        // Fallback quando não há organizador selecionado.
        if ($this->organization) {
            $this->customer_organization_slug = Str::slug(
                $this->organization->organization_slug ?: $this->organization->organization_name
            );
            return;
        }

        $this->customer_organization_slug = Str::slug(
            $this->customer->name_corporate ?? $this->customer->name_fantasy
        );
    }

    protected function generateSlugFromOrganizer()
    {
        // Se não tem organizador selecionado, usa fallback por empresa/filial.
        if (!$this->organizer_id) {
            $this->generateCustomerOrganizationSlug();
            return;
        }

        $organizer = CampaignOrganizer::where('id', $this->organizer_id)
            ->when($this->customer?->id, function ($query) {
                $query->where('customer_id', $this->customer->id);
            })
            ->first();

        if (!$organizer) {
            $this->generateCustomerOrganizationSlug();
            return;
        }

        // Regra da URL pública: sempre usar o organizer_slug.
        $this->customer_organization_slug = Str::slug(
            $organizer->organizer_slug ?: ($organizer->organizer_name_full ?: $organizer->organizer_name)
        );
    }

    // Removidos os métodos updatedName() e updatedOrganizerId()
    // O slug agora será gerado apenas no momento de salvar

    public function updatedGoalAmountInput($value)
    {
        // Quando o valor formatado é atualizado, converte para centavos (int)
        // Exemplo: "1.234,56" -> 123456 (centavos)
        $cents = convertDecimalInt($value);
        if ($cents === '' || $cents === null || $cents === false) {
            $this->goal_amount = null;
            return;
        }

        if ($cents !== null && $cents !== false) {
            $this->goal_amount = $cents;
        }
    }

    public function updatedAmountMinInput($value)
    {
        // Quando o valor formatado é atualizado, converte para centavos (int)
        // Exemplo: "1.234,56" -> 123456 (centavos)
        $cents = convertDecimalInt($value);
        if ($cents !== null && $cents !== false) {
            $this->amount_min = $cents;
        }
    }


    public $debug_log = []; // Log temporário para debug

    public function updated($name, $value)
    {
        $this->debug_log[] = "updated() chamado. Name: {$name}, Value: " . ($value ? 'tem valor' : 'null');

        if ($name === 'organizer_id') {
            $this->generateSlugFromOrganizer();
        }

        // UPLOADS LIST - mesma abordagem do LayoutPagina
        $uploads = ['image_banner', 'image_thumb'];

        // SE UPLOADS
        if (in_array($name, $uploads) && $value ?? false)
        {
            $this->debug_log[] = "Processando upload de {$name}";

            try {
                $this->validate([
                    $name => ['image', 'max:2048'],
                ]);

                $this->debug_log[] = "Validação passou para {$name}";

                // Define o path folder baseado no tipo (isolado por tenant)
                $subFolder = ($name === 'image_banner') ? 'banner' : 'thumb';
                $campaignId = $this->campaign_id ?? 'temp';

                // Path relativo dentro do storage do tenant (mantém em inglês como padrão)
                $relativePath = "campaigns/{$campaignId}/{$subFolder}";

                // Path completo isolado por tenant (adiciona automaticamente o UUID do app)
                $fullPath = tenantStoragePath($relativePath);

                $this->debug_log[] = "Path tenant: {$fullPath}";

                // Garante que o diretório existe
                if (!file_exists($fullPath)) {
                    mkdir($fullPath, 0755, true);
                }

                // Nome do arquivo com timestamp para evitar conflitos
                $extension = $value->getClientOriginalExtension();
                $filename = time() . '_' . Str::random(10) . '.' . $extension;

                // Pega o app ID para construir o path completo para storeAs
                $app = currentApp();
                $appId = $app->id ?? 1;

                // Move o arquivo para o storage isolado do tenant (disk 'public' salva em storage/app/public)
                $value->storeAs("{$appId}/{$relativePath}", $filename, 'public');

                // Path relativo para salvar no banco (sem appId na frente, pois tenantAsset adiciona)
                $dbPath = "{$relativePath}/{$filename}";

                $this->debug_log[] = "Arquivo salvo: {$dbPath}";

                if ($dbPath) {
                    if ($name === 'image_banner') {
                        $this->url_image_banner = $dbPath;
                        $this->preview_banner = $dbPath; // Salva apenas o path, a view usa tenantAsset()
                        $this->image_banner = null; // Limpa o arquivo temporário para mostrar o preview final
                        $this->debug_log[] = "Banner salvo. URL: {$dbPath}";
                    } else {
                        $this->url_image_thumb = $dbPath;
                        $this->preview_thumb = $dbPath; // Salva apenas o path, a view usa tenantAsset()
                        $this->image_thumb = null; // Limpa o arquivo temporário para mostrar o preview final
                        $this->debug_log[] = "Thumb salvo. URL: {$dbPath}";
                    }

                    $this->notification()->success('Imagem carregada com sucesso!');
                } else {
                    throw new \Exception('Store retornou null');
                }
            }
            catch (\Throwable $th)
            {
                $errorMsg = "Erro ao fazer upload de {$name}: " . $th->getMessage();
                $this->debug_log[] = $errorMsg;
                $this->debug_log[] = "Arquivo: " . $th->getFile() . ":" . $th->getLine();
                \Log::error($errorMsg, [
                    'file' => $th->getFile(),
                    'line' => $th->getLine(),
                    'trace' => $th->getTraceAsString()
                ]);
                $this->notification()->error($errorMsg);

                // Limpa os valores
                if ($name === 'image_banner') {
                    $this->image_banner = null;
                    $this->preview_banner = $this->campaign_id ? Campaign::find($this->campaign_id)->url_image_banner : null;
                    $this->url_image_banner = null;
                } else {
                    $this->image_thumb = null;
                    $this->preview_thumb = $this->campaign_id ? Campaign::find($this->campaign_id)->url_image_thumb : null;
                    $this->url_image_thumb = null;
                }
            }
        }
    }

    public function removerBanner()
    {
        $this->image_banner = null;
        $this->preview_banner = null;
        $this->url_image_banner = null;
    }

    public function removerThumb()
    {
        $this->image_thumb = null;
        $this->preview_thumb = null;
        $this->url_image_thumb = null;
    }

    private function nullIfBlank($value)
    {
        if (is_string($value) && trim($value) === '') {
            return null;
        }

        return $value;
    }

    public function salvar()
    {
        // Gera o slug do nome da campanha sempre ao salvar (criar ou editar)
        if ($this->name) {
            $this->slug = Str::slug($this->name);
        }

        // Gera o slug da URL baseado no organizador e organização
        $this->generateSlugFromOrganizer();

        // Campos opcionais: vazios devem ser salvos como null
        $this->goal_amount = $this->nullIfBlank($this->goal_amount);
        $this->goal_leads = $this->nullIfBlank($this->goal_leads);

        // Validação automática - deixa o Livewire/WireUI tratar os erros
        try
        {
            $validatedData = $this->validate();
        }
        catch (\Illuminate\Validation\ValidationException $e)
        {

            // Emite evento para mostrar notificação de erro
            $this->emit('validationErrors', $this->getErrorBag()->toArray());

            // Mostra notificação de erro
            $errorMessage = " Um ou mais campos precisam ser corrigidos";

            // $this->notification()->error(
            //     'Atenção!',
            //     $errorMessage
            // );

            throw $e; // Re-lança a exceção para manter o comportamento normal do Livewire
        }

        // Upload de imagens se houver novos arquivos (já foram feitos em updatedImageBanner/updatedImageThumb)
        // Apenas garante que os valores estão corretos para salvar no banco
        if ($this->image_banner && !$this->url_image_banner) {
            // Se ainda não foi feito upload, faz agora (isolado por tenant)
            $campaignId = $this->campaign_id ?? 'temp';
            $relativePath = "campaigns/{$campaignId}/banner";

            // Path completo isolado por tenant
            $fullPath = tenantStoragePath($relativePath);

            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            // Pega o app ID para construir o path completo para storeAs
            $app = currentApp();
            $appId = $app->id ?? 1;

            $extension = $this->image_banner->getClientOriginalExtension();
            $filename = time() . '_' . Str::random(10) . '.' . $extension;
            $this->image_banner->storeAs("{$appId}/{$relativePath}", $filename, 'public');
            $this->url_image_banner = "{$relativePath}/{$filename}";
        }

        if ($this->image_thumb && !$this->url_image_thumb) {
            // Se ainda não foi feito upload, faz agora (isolado por tenant)
            $campaignId = $this->campaign_id ?? 'temp';
            $relativePath = "campaigns/{$campaignId}/thumb";

            // Path completo isolado por tenant
            $fullPath = tenantStoragePath($relativePath);

            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            // Pega o app ID para construir o path completo para storeAs
            $app = currentApp();
            $appId = $app->id ?? 1;

            $extension = $this->image_thumb->getClientOriginalExtension();
            $filename = time() . '_' . Str::random(10) . '.' . $extension;
            $this->image_thumb->storeAs("{$appId}/{$relativePath}", $filename, 'public');
            $this->url_image_thumb = "{$relativePath}/{$filename}";
        }

        $data = [
            'customer_id' => $this->customer->id,
            'organization_id' => $this->organization->id ?? null,
            'organizer_id' => $this->organizer_id ?? null,
            'name' => $this->name,
            'name_short' => $this->name_short,
            'slug' => $this->slug,
            'customer_organization_slug' => $this->customer_organization_slug,
            'description' => $this->description,
            'about' => $this->about,
            'status' => $this->status,
            'campaign_type' => $this->campaign_type,
            'visibility_public' => $this->visibility_public,
            'datetime_start' => $this->datetime_start,
            'datetime_finish' => $this->datetime_finish,
            'goal_amount' => !is_null($this->goal_amount) ? (int) $this->goal_amount : null, // Campo opcional
            'goal_leads' => !is_null($this->goal_leads) ? (int) $this->goal_leads : null, // Campo opcional
            'goal_conversions' => $this->goal_conversions,
            'amount_min' => (int)$this->amount_min, // Garante que seja inteiro (centavos)
            'show_goal_amount' => $this->show_goal_amount,
            'show_goal_leads' => $this->show_goal_leads,
            'show_goal_conversions' => $this->show_goal_conversions,
            'show_progress' => $this->show_progress,
            'color_primary' => $this->color_primary,
            'color_secondary' => $this->color_secondary,
            'pay_gateway_id' => $this->pay_gateway_id,
            'pay_sandbox' => $this->pay_sandbox,
            'pay_pix' => $this->pay_pix,
            'pay_boleto' => $this->pay_boleto,
            'pay_card_credit' => $this->pay_card_credit,
            'pay_card_credit_installment_max' => $this->pay_card_credit_installment_max,
            'pay_card_credit_installment_fee_payer' => $this->pay_card_credit_installment_fee_payer,
            'url_image_banner' => $this->url_image_banner,
            'url_image_thumb' => $this->url_image_thumb,
            'enable_questions' => $this->enable_questions,
            'require_doc' => $this->require_doc,
            'allow_anonymous' => $this->allow_anonymous,
            'allow_recurring' => $this->allow_recurring,
        ];

        if ($this->campaign_id) {
            $campaign = Campaign::findOrFail($this->campaign_id);
            $campaign->update($data);

            // Atualiza perguntas
            $this->saveQuestions($campaign);

            session()->flash('message', 'Campanha atualizada com sucesso!');
            session()->flash('type', 'success');

            return redirect()->route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id]);
        } else {
            $campaign = Campaign::create($data);

            // Salva perguntas
            $this->saveQuestions($campaign);

            session()->flash('message', 'Campanha criada com sucesso!');
            session()->flash('type', 'success');

            return redirect()->route('dashboard-campanhas-detalhes', ['campaign_id' => $campaign->id]);
        }
    }

    protected function saveQuestions($campaign)
    {
        // Remove perguntas antigas
        $campaign->questions()->delete();

        // Salva novas perguntas
        foreach ($this->questions as $index => $questionData) {
            $options = !empty($questionData['question_options'])
                ? array_filter(array_map('trim', explode("\n", $questionData['question_options'])))
                : null;

            CampaignQuestion::create([
                'campaign_id' => $campaign->id,
                'order' => $index,
                'question_type' => $questionData['question_type'],
                'question_text' => $questionData['question_text'],
                'question_options' => $options,
                'is_required' => $questionData['is_required'] ?? false,
                'placeholder' => $questionData['placeholder'] ?? null,
                'help_text' => $questionData['help_text'] ?? null,
            ]);
        }
    }

    private function ensureSuperAdmin(): bool
    {
        if (!EnsureSuperAdmin::check()) {
            $this->addError('deleteConfirmationStatus', 'Apenas super-admin pode apagar dados por etapas.');
            return false;
        }

        return true;
    }

    private function loadDeleteSummary(): void
    {
        $this->deleteSummary = [
            'webhooks' => 0,
            'attempts' => 0,
            'payments' => 0,
            'slips' => 0,
            'orders' => 0,
            'order_answers' => 0,
            'subscriptions' => 0,
            'subscription_cycles' => 0,
            'metrics' => 0,
            'questions' => 0,
        ];

        if (!$this->campaign_id) {
            return;
        }

        $campaign = Campaign::find($this->campaign_id);
        if (!$campaign) {
            return;
        }

        $paymentIds = $campaign->campaignPayments()->pluck('id');
        $orderIds = $campaign->orders()->pluck('id');
        $subscriptionIds = CampaignSubscription::where('campaign_id', $campaign->id)->pluck('id');

        $webhookQuery = CampaignPaymentWebhook::where('campaign_id', $campaign->id);
        if ($paymentIds->isNotEmpty()) {
            $webhookQuery->orWhereIn('campaign_payment_id', $paymentIds);
        }

        $this->deleteSummary = [
            'webhooks' => $webhookQuery->count(),
            'attempts' => CampaignPaymentAttempt::where('campaign_id', $campaign->id)->count(),
            'payments' => $paymentIds->count(),
            'slips' => $campaign->paymentSlips()->count(),
            'orders' => $orderIds->count(),
            'order_answers' => $orderIds->isNotEmpty()
                ? CampaignOrderAnswer::whereIn('campaign_order_id', $orderIds)->count()
                : 0,
            'subscriptions' => $subscriptionIds->count(),
            'subscription_cycles' => $subscriptionIds->isNotEmpty()
                ? CampaignSubscriptionCycle::whereIn('subscription_id', $subscriptionIds)->count()
                : 0,
            'metrics' => $campaign->metrics()->count(),
            'questions' => $campaign->questions()->count(),
        ];
    }

    public function getOrdersCountProperty()
    {
        if (!$this->campaign_id) {
            return 0;
        }

        $campaign = Campaign::find($this->campaign_id);
        return $campaign ? $campaign->orders()->count() : 0;
    }

    public function openDeleteModal()
    {
        $this->showDeleteModal = true;
        $this->deleteConfirmation = '';
        $this->loadDeleteSummary();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteConfirmation = '';
        $this->deleteSummary = [];
    }

    public $deleteConfirmationStatus;
    public function apagarCampanha()
    {
        // Validação simples
        if (trim($this->deleteConfirmation) !== 'apagar-campanha') {
            $this->addError('deleteConfirmation', 'É preciso digitar "apagar-campanha" para apagar');
            return;
        }

        if (!$this->campaign_id) {
            $this->addError('deleteConfirmationStatus', 'Campanha não encontrada.');
            return;
        }

        $campaign = Campaign::find($this->campaign_id);
        if (!$campaign) {
            $this->addError('deleteConfirmationStatus', 'Campanha não encontrada.');
            return;
        }

        // Verifica doações
        $ordersCount = $campaign->orders()->count();
        if ($ordersCount > 0) {
            $this->addError('deleteConfirmationStatus', "Impossível apagar. Já existem {$ordersCount} doação(ões).");
            return;
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Deleta webhooks relacionados aos pagamentos
            $paymentIds = $campaign->campaignPayments()->pluck('id');
            if ($paymentIds->isNotEmpty()) {
                \App\Models\ModCampaign\CampaignPaymentWebhook::whereIn('campaign_payment_id', $paymentIds)->delete();
            }

            // Deleta tentativas de pagamento
            \App\Models\ModCampaign\CampaignPaymentAttempt::where('campaign_id', $campaign->id)->delete();

            // Deleta pagamentos
            $campaign->campaignPayments()->delete();

            // Deleta payment slips
            $campaign->paymentSlips()->delete();

            // Deleta métricas e perguntas
            $campaign->metrics()->delete();
            $campaign->questions()->delete();

            // Deleta a campanha
            $campaign->delete();

            \Illuminate\Support\Facades\DB::commit();

            $this->notification()->success('Sucesso!', 'Campanha apagada.');
            $this->closeDeleteModal();
            return redirect()->route('dashboard-campanhas');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->addError('deleteConfirmationStatus', $e->getMessage());
        }
    }

    public function apagarWebhooks()
    {
        if (!$this->ensureSuperAdmin()) {
            return;
        }

        $campaign = Campaign::find($this->campaign_id);
        if (!$campaign) {
            $this->addError('deleteConfirmationStatus', 'Campanha não encontrada.');
            return;
        }

        $this->loadDeleteSummary();
        if (($this->deleteSummary['webhooks'] ?? 0) === 0) {
            $this->addError('deleteConfirmationStatus', 'Sem webhooks para apagar.');
            return;
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $paymentIds = $campaign->campaignPayments()->pluck('id');
            $webhookQuery = CampaignPaymentWebhook::where('campaign_id', $campaign->id);
            if ($paymentIds->isNotEmpty()) {
                $webhookQuery->orWhereIn('campaign_payment_id', $paymentIds);
            }
            $webhookQuery->delete();

            \Illuminate\Support\Facades\DB::commit();
            $this->loadDeleteSummary();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->addError('deleteConfirmationStatus', $e->getMessage());
        }
    }

    public function apagarTentativas()
    {
        if (!$this->ensureSuperAdmin()) {
            return;
        }

        $campaign = Campaign::find($this->campaign_id);
        if (!$campaign) {
            $this->addError('deleteConfirmationStatus', 'Campanha não encontrada.');
            return;
        }

        $this->loadDeleteSummary();
        if (($this->deleteSummary['webhooks'] ?? 0) > 0) {
            $this->addError('deleteConfirmationStatus', 'Apague os webhooks antes de apagar as tentativas.');
            return;
        }

        if (($this->deleteSummary['attempts'] ?? 0) === 0) {
            $this->addError('deleteConfirmationStatus', 'Sem tentativas para apagar.');
            return;
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            CampaignPaymentAttempt::where('campaign_id', $campaign->id)->delete();
            \Illuminate\Support\Facades\DB::commit();
            $this->loadDeleteSummary();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->addError('deleteConfirmationStatus', $e->getMessage());
        }
    }

    public function apagarTransacoes()
    {
        if (!$this->ensureSuperAdmin()) {
            return;
        }

        $campaign = Campaign::find($this->campaign_id);
        if (!$campaign) {
            $this->addError('deleteConfirmationStatus', 'Campanha não encontrada.');
            return;
        }

        $this->loadDeleteSummary();
        if (($this->deleteSummary['webhooks'] ?? 0) > 0 || ($this->deleteSummary['attempts'] ?? 0) > 0) {
            $this->addError('deleteConfirmationStatus', 'Apague webhooks e tentativas antes de apagar as transações.');
            return;
        }

        if (($this->deleteSummary['payments'] ?? 0) === 0 && ($this->deleteSummary['slips'] ?? 0) === 0) {
            $this->addError('deleteConfirmationStatus', 'Sem transações para apagar.');
            return;
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            $campaign->campaignPayments()->delete();
            $campaign->paymentSlips()->delete();
            \Illuminate\Support\Facades\DB::commit();
            $this->loadDeleteSummary();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->addError('deleteConfirmationStatus', $e->getMessage());
        }
    }

    public function apagarRecorrencias()
    {
        if (!$this->ensureSuperAdmin()) {
            return;
        }

        $campaign = Campaign::find($this->campaign_id);
        if (!$campaign) {
            $this->addError('deleteConfirmationStatus', 'Campanha não encontrada.');
            return;
        }

        $this->loadDeleteSummary();
        if (($this->deleteSummary['webhooks'] ?? 0) > 0 ||
            ($this->deleteSummary['attempts'] ?? 0) > 0 ||
            ($this->deleteSummary['payments'] ?? 0) > 0 ||
            ($this->deleteSummary['slips'] ?? 0) > 0) {
            $this->addError('deleteConfirmationStatus', 'Apague transações antes de apagar recorrências.');
            return;
        }

        if (($this->deleteSummary['subscriptions'] ?? 0) === 0 && ($this->deleteSummary['subscription_cycles'] ?? 0) === 0) {
            $this->addError('deleteConfirmationStatus', 'Sem recorrências para apagar.');
            return;
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $subscriptionIds = CampaignSubscription::where('campaign_id', $campaign->id)->pluck('id');
            if ($subscriptionIds->isNotEmpty()) {
                CampaignSubscriptionCycle::whereIn('subscription_id', $subscriptionIds)->delete();
            }
            CampaignSubscription::where('campaign_id', $campaign->id)->delete();

            \Illuminate\Support\Facades\DB::commit();
            $this->loadDeleteSummary();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->addError('deleteConfirmationStatus', $e->getMessage());
        }
    }

    public function apagarDoacoes()
    {
        if (!$this->ensureSuperAdmin()) {
            return;
        }

        $campaign = Campaign::find($this->campaign_id);
        if (!$campaign) {
            $this->addError('deleteConfirmationStatus', 'Campanha não encontrada.');
            return;
        }

        $this->loadDeleteSummary();
        if (($this->deleteSummary['webhooks'] ?? 0) > 0 ||
            ($this->deleteSummary['attempts'] ?? 0) > 0 ||
            ($this->deleteSummary['payments'] ?? 0) > 0 ||
            ($this->deleteSummary['slips'] ?? 0) > 0 ||
            ($this->deleteSummary['subscriptions'] ?? 0) > 0 ||
            ($this->deleteSummary['subscription_cycles'] ?? 0) > 0) {
            $this->addError('deleteConfirmationStatus', 'Apague transações e recorrências antes de apagar as doações.');
            return;
        }

        if (($this->deleteSummary['orders'] ?? 0) === 0 && ($this->deleteSummary['order_answers'] ?? 0) === 0) {
            $this->addError('deleteConfirmationStatus', 'Sem doações para apagar.');
            return;
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $orderIds = $campaign->orders()->pluck('id');
            if ($orderIds->isNotEmpty()) {
                CampaignOrderAnswer::whereIn('campaign_order_id', $orderIds)->delete();
            }
            $campaign->orders()->delete();

            \Illuminate\Support\Facades\DB::commit();
            $this->loadDeleteSummary();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->addError('deleteConfirmationStatus', $e->getMessage());
        }
    }

    public function apagarCampanhaFinal()
    {
        if (!$this->ensureSuperAdmin()) {
            return;
        }

        $campaign = Campaign::find($this->campaign_id);
        if (!$campaign) {
            $this->addError('deleteConfirmationStatus', 'Campanha não encontrada.');
            return;
        }

        $this->loadDeleteSummary();
        if (($this->deleteSummary['webhooks'] ?? 0) > 0 ||
            ($this->deleteSummary['attempts'] ?? 0) > 0 ||
            ($this->deleteSummary['payments'] ?? 0) > 0 ||
            ($this->deleteSummary['slips'] ?? 0) > 0 ||
            ($this->deleteSummary['subscriptions'] ?? 0) > 0 ||
            ($this->deleteSummary['subscription_cycles'] ?? 0) > 0 ||
            ($this->deleteSummary['orders'] ?? 0) > 0 ||
            ($this->deleteSummary['order_answers'] ?? 0) > 0) {
            $this->addError('deleteConfirmationStatus', 'Ainda existem dados vinculados. Finalize as etapas anteriores.');
            return;
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            $campaign->metrics()->delete();
            $campaign->questions()->delete();
            $campaign->delete();
            \Illuminate\Support\Facades\DB::commit();

            $this->notification()->success('Sucesso!', 'Campanha apagada.');
            $this->closeDeleteModal();
            return redirect()->route('dashboard-campanhas');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->addError('deleteConfirmationStatus', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.dashboard.campanha-nova')
            ->layout('layouts.app-pep-auth');
    }
}
