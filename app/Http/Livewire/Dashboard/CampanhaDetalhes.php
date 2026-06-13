<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\ModCampaign\Campaign;
use App\Models\ModCampaign\CampaignSubscription;
use App\Models\NotificacaoLog;
use App\Services\ModuleAccessService;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CampanhaDetalhes extends Component
{
    use WithPagination;
    private const TAB_ANALITICOS = 'analiticos';
    private const TAB_DETALHES = 'detalhes';
    private const TAB_ADESOES = 'adesoes';
    private const TAB_PARTICIPANTES = 'participantes';
    private const TAB_QUESTIONARIOS = 'questionarios';
    private const TAB_TRANSACOES = 'transacoes';

    public $campaign;
    public $campaign_id;
    public $activeTab = 'analiticos'; // Tab ativa (analiticos, detalhes, transacoes, adesoes, participantes, questionarios)

    // Dados para gráficos
    public $chartData;
    public $metricsLast30Days;
    public $periodComparison;

    // Filtros para Adesões
    public $filterStatus = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';
    public $filterSearch = '';

    // Adesão manual
    public $showManualOrderModal = false;
    public $manualBuyerName = '';
    public $manualBuyerEmail = '';
    public $manualBuyerDocNum = '';
    public $manualBuyerContactCountry = '55';
    public $manualBuyerContactDdd = '';
    public $manualBuyerContactNum = '';
    public $manualAmountTotal = '';
    public $manualAmountPaid = '';
    public $manualStatus = 'paid';
    public $manualPayType = 'manual';
    public $manualPaidAt = '';
    public $manualDescription = '';
    public $manualObservation = '';

    // Edição de adesão
    public $showOrderEditModal = false;
    public $canDeleteSelectedOrder = false;
    public $confirmDeleteOrder = false;
    public $editOrderId = null;
    public $editOrderIsManual = false;
    public $editBuyerName = '';
    public $editBuyerEmail = '';
    public $editBuyerDocNum = '';
    public $editBuyerContactCountry = '55';
    public $editBuyerContactDdd = '';
    public $editBuyerContactNum = '';
    public $editOrderAmountTotal = '';
    public $editOrderAmountPaid = '';
    public $editOrderStatus = 'pending';
    public $editOrderPayType = 'manual';
    public $editOrderPayIntegrationType = 'manual';
    public $editOrderPaidAt = '';
    public $editOrderPayDatetime = '';
    public $editOrderPayTransactionId = '';
    public $editOrderPayNsu = '';
    public $editOrderDescription = '';
    public $editOrderObservation = '';

    // Filtros para Questionários
    public $filterQuestion = '';
    public $filterQuestionDateFrom = '';
    public $filterQuestionDateTo = '';

    // Filtros para Transações
    public $filterTransactionStatus = '';
    public $filterTransactionDateFrom = '';
    public $filterTransactionDateTo = '';
    public $filterTransactionSearch = '';
    public $transactionPerPage = 100;

    public function updatedFilterTransactionStatus()
    {
        $this->resetPage('transactionPage');
    }
    public function updatedFilterTransactionDateFrom()
    {
        $this->resetPage('transactionPage');
    }
    public function updatedFilterTransactionDateTo()
    {
        $this->resetPage('transactionPage');
    }
    public function updatedFilterTransactionSearch()
    {
        $this->resetPage('transactionPage');
    }
    public function updatedTransactionPerPage()
    {
        $this->resetPage('transactionPage');
    }

    // Detalhes da transação selecionada
    public $selectedTransactionId = null;
    public $selectedTransaction = null;

    // Detalhes da adesão selecionada
    public $selectedOrderId = null;
    public $selectedOrder = null;
    public $selectedSubscription = null;

    public function hydrate()
    {
        // Recarrega a campanha apenas se ainda não foi carregada
        if ($this->campaign_id && !$this->campaign) {
            $this->campaign = Campaign::with([
                'customer' => function ($query) {
                    $query->withoutGlobalScopes();
                },
                'organization',
                'organizer',
                'gateway',
                'metrics',
                'questions',
                'orders.answers.question'
            ])
                ->find($this->campaign_id);
        }
    }

    public function mount($campaign_id, $tab = null, $order_id = null, $transaction_id = null)
    {
        $this->campaign_id = $campaign_id;
        $this->resetPage('transactionPage');

        $customer = sessionCustomer();

        if (!$customer) {
            session()->flash('error', 'Sessão expirada. Selecione novamente o cliente.');
            return redirect()->route('dashboard-campanhas');
        }

        $this->campaign = Campaign::with([
            'customer' => function ($query) {
                $query->withoutGlobalScopes();
            },
            'organization',
            'organizer',
            'gateway',
            'metrics',
            'questions',
            'orders.answers.question'
        ])
            ->where('customer_id', $customer->id)
            ->findOrFail($campaign_id);

        $routeName = request()->route()?->getName();
        $routeTabFromName = $this->getTabFromRouteName($routeName);
        $routeTab = $this->normalizeTab($tab) ?? $routeTabFromName;
        $hasExplicitRouteState = $routeTab !== null
            || $routeTabFromName !== null
            || !empty($order_id)
            || !empty($transaction_id);

        if ($routeTab !== null) {
            $this->activeTab = $routeTab;
        } elseif (!$hasExplicitRouteState) {
            $savedTab = $this->normalizeTab(session('campaign_details_tab'));
            if ($savedTab !== null) {
                $this->activeTab = $savedTab;
            }
        }

        // Verifica permissão
        if (auth()->check()) {
            $user = auth()->user();

            if (!ModuleAccessService::userIsAppAdmin($user)) {
                $hasAccess = ModuleAccessService::userCanAccessCampaigns($user, $this->campaign->customer);

                if (!$hasAccess) {
                    abort(403, 'Você não tem permissão para acessar esta campanha.');
                }
            }
        }

        // Verifica se é uma requisição de exportação
        if (request()->has('export')) {
            if (request()->get('export') === 'adesoes') {
                throw new HttpResponseException($this->exportAdesoes());
            } elseif (request()->get('export') === 'questionarios') {
                throw new HttpResponseException($this->exportQuestionarios());
            } elseif (request()->get('export') === 'transacoes') {
                throw new HttpResponseException($this->exportTransacoes());
            } elseif (request()->get('export') === 'participantes') {
                throw new HttpResponseException($this->exportParticipantes());
            }
        }

        if (!empty($transaction_id)) {
            $this->activeTab = self::TAB_TRANSACOES;
            if (!$this->selectTransaction($transaction_id)) {
                abort(404, 'Transação não encontrada para esta campanha.');
            }
        } elseif (!empty($order_id)) {
            $this->activeTab = self::TAB_ADESOES;
            if (!$this->selectOrder($order_id)) {
                abort(404, 'Adesão não encontrada para esta campanha.');
            }
        } elseif (!$hasExplicitRouteState && $this->activeTab === self::TAB_ADESOES) {
            $savedOrderId = session($this->getSelectedOrderSessionKey());
            if (!empty($savedOrderId)) {
                $this->selectOrder($savedOrderId);
            }
        }

        session(['campaign_details_tab' => $this->activeTab]);

        // Carrega métricas dos últimos 30 dias
        $this->loadMetrics();
    }

    public function loadMetrics()
    {
        $now = Carbon::now();
        $last30Days = $now->copy()->subDays(30);
        $previous30Days = $now->copy()->subDays(60);

        // Dados diários dos últimos 30 dias - ADESÕES (orders), não transações
        $dailyData = DB::table('tbc_campaign_order')
            ->selectRaw("DATE(created_at) as date,
                        COUNT(*) as orders_count,
                        SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid_count,
                        SUM(CASE WHEN status = 'paid' THEN amount_paid ELSE 0 END) as revenue")
            ->where('campaign_id', $this->campaign->id)
            ->where('created_at', '>=', $last30Days)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepara dados para gráfico (Chart.js)
        $labels = [];
        $revenueData = [];
        $ordersData = [];
        $paidData = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('d/m');

            $dayData = $dailyData->firstWhere('date', $date);
            $revenueData[] = $dayData ? (float) $dayData->revenue : 0;
            $ordersData[] = $dayData ? (int) $dayData->orders_count : 0;
            $paidData[] = $dayData ? (int) $dayData->paid_count : 0;
        }

        $this->chartData = [
            'labels' => $labels,
            'revenue' => $revenueData,
            'orders' => $ordersData,
            'paid' => $paidData,
        ];

        // Métricas consolidadas últimos 30 dias - ADESÕES (orders)
        $totalOrders = DB::table('tbc_campaign_order')
            ->where('campaign_id', $this->campaign->id)
            ->where('created_at', '>=', $last30Days)
            ->count();

        $paidOrders = DB::table('tbc_campaign_order')
            ->where('campaign_id', $this->campaign->id)
            ->where('status', 'paid')
            ->where('created_at', '>=', $last30Days)
            ->count();

        $this->metricsLast30Days = [
            'revenue' => DB::table('tbc_campaign_order')
                ->where('campaign_id', $this->campaign->id)
                ->where('status', 'paid')
                ->where('created_at', '>=', $last30Days)
                ->sum('amount_paid'),
            'orders' => $totalOrders,
            'paid_orders' => $paidOrders,
            'transactions' => DB::table('tbc_campaign_payment')
                ->where('campaign_id', $this->campaign_id)
                ->where('status', 'paid')
                ->where('created_at', '>=', $last30Days)
                ->count(),
            // Leads = Total de adesões iniciadas (todos os orders)
            'leads' => $totalOrders,
            // Conversões = Adesões pagas (orders com status 'paid')
            'conversions' => $paidOrders,
        ];

        // Comparação com período anterior (30 dias anteriores) - ADESÕES (orders)
        $previousTotalOrders = DB::table('tbc_campaign_order')
            ->where('campaign_id', $this->campaign->id)
            ->whereBetween('created_at', [$previous30Days, $last30Days])
            ->count();

        $previousPaidOrders = DB::table('tbc_campaign_order')
            ->where('campaign_id', $this->campaign->id)
            ->where('status', 'paid')
            ->whereBetween('created_at', [$previous30Days, $last30Days])
            ->count();

        $previous = [
            'revenue' => DB::table('tbc_campaign_order')
                ->where('campaign_id', $this->campaign->id)
                ->where('status', 'paid')
                ->whereBetween('created_at', [$previous30Days, $last30Days])
                ->sum('amount_paid'),
            'orders' => $previousTotalOrders,
            // Leads = Total de adesões iniciadas
            'leads' => $previousTotalOrders,
            // Conversões = Adesões pagas
            'conversions' => $previousPaidOrders,
        ];

        // Calcula % de variação
        $this->periodComparison = [
            'revenue' => [
                'current' => $this->metricsLast30Days['revenue'],
                'previous' => $previous['revenue'],
                'percent' => $previous['revenue'] > 0
                    ? round((($this->metricsLast30Days['revenue'] - $previous['revenue']) / $previous['revenue']) * 100, 2)
                    : ($this->metricsLast30Days['revenue'] > 0 ? 100 : 0),
            ],
            'orders' => [
                'current' => $this->metricsLast30Days['orders'],
                'previous' => $previous['orders'],
                'percent' => $previous['orders'] > 0
                    ? round((($this->metricsLast30Days['orders'] - $previous['orders']) / $previous['orders']) * 100, 2)
                    : ($this->metricsLast30Days['orders'] > 0 ? 100 : 0),
            ],
            'leads' => [
                'current' => $this->metricsLast30Days['leads'],
                'previous' => $previous['leads'],
                'percent' => $previous['leads'] > 0
                    ? round((($this->metricsLast30Days['leads'] - $previous['leads']) / $previous['leads']) * 100, 2)
                    : ($this->metricsLast30Days['leads'] > 0 ? 100 : 0),
            ],
            'conversions' => [
                'current' => $this->metricsLast30Days['conversions'],
                'previous' => $previous['conversions'],
                'percent' => $previous['conversions'] > 0
                    ? round((($this->metricsLast30Days['conversions'] - $previous['conversions']) / $previous['conversions']) * 100, 2)
                    : ($this->metricsLast30Days['conversions'] > 0 ? 100 : 0),
            ],
        ];
    }

    public function arquivar()
    {
        $this->campaign->update(['status' => 'cancelled']);
        session()->flash('success', 'Campanha arquivada com sucesso.');
        $this->campaign->refresh();
    }

    // --- Clonagem de campanha ---
    public $showClonarModal = false;
    public $clonarStep = 1;

    public function abrirModalClonar()
    {
        if (!isAdmin()) {
            abort(403);
        }
        $this->clonarStep = 1;
        $this->showClonarModal = true;
    }

    public function clonarStep2()
    {
        if (!isAdmin()) {
            abort(403);
        }
        $this->clonarStep = 2;
    }

    public function clonarCampanha()
    {
        if (!isAdmin()) {
            abort(403);
        }

        $original = $this->campaign;

        $novaSlug = $original->slug . '-clone-' . now()->format('ymdHis');

        $clone = Campaign::create([
            'customer_id'                             => $original->customer_id,
            'organization_id'                         => $original->organization_id,
            'organizer_id'                            => $original->organizer_id,
            'slug'                                    => $novaSlug,
            'customer_organization_slug'              => $original->customer_organization_slug,
            'name'                                    => $original->name . ' (Clone)',
            'name_short'                              => $original->name_short,
            'description'                             => $original->description,
            'about'                                   => $original->about,
            'status'                                  => 'draft',
            'campaign_type'                           => $original->campaign_type,
            'visibility_public'                       => $original->visibility_public,
            'datetime_start'                          => $original->datetime_start,
            'datetime_finish'                         => $original->datetime_finish,
            'goal_amount'                             => $original->goal_amount,
            'goal_leads'                              => $original->goal_leads,
            'goal_conversions'                        => $original->goal_conversions,
            'amount_min'                              => $original->amount_min,
            'color_primary'                           => $original->color_primary,
            'color_secondary'                         => $original->color_secondary,
            'url_image_logo'                          => null,
            'url_image_bg'                            => null,
            'url_image_banner'                        => null,
            'url_image_thumb'                         => null,
            'pay_gateway_id'                          => $original->pay_gateway_id,
            'pay_sandbox'                             => $original->pay_sandbox,
            'pay_pix'                                 => $original->pay_pix,
            'pay_pix_direto'                          => $original->pay_pix_direto,
            'pay_boleto'                              => $original->pay_boleto,
            'pay_card_credit'                         => $original->pay_card_credit,
            'pay_card_credit_installment_max'         => $original->pay_card_credit_installment_max,
            'pay_card_credit_installment_amount_min'  => $original->pay_card_credit_installment_amount_min,
            'pay_card_credit_installment_fee_payer'   => $original->pay_card_credit_installment_fee_payer,
            'show_goal_amount'                        => $original->show_goal_amount,
            'show_goal_leads'                         => $original->show_goal_leads,
            'show_goal_conversions'                   => $original->show_goal_conversions,
            'show_progress'                           => $original->show_progress,
            'enable_questions'                        => $original->enable_questions,
            'require_doc'                             => $original->require_doc,
            'allow_anonymous'                         => $original->allow_anonymous,
            'allow_recurring'                         => $original->allow_recurring,
        ]);

        // Clona as perguntas do questionário
        foreach ($original->questions as $question) {
            \App\Models\ModCampaign\CampaignQuestion::create([
                'campaign_id'      => $clone->id,
                'order'            => $question->order,
                'question_type'    => $question->question_type,
                'question_text'    => $question->question_text,
                'question_options' => $question->question_options,
                'is_required'      => $question->is_required,
                'placeholder'      => $question->placeholder,
                'help_text'        => $question->help_text,
            ]);
        }

        $this->showClonarModal = false;

        return redirect()->route('dashboard-campanhas-detalhes', ['campaign_id' => $clone->id]);
    }

    public function ativar()
    {
        $this->campaign->update(['status' => 'active']);
        session()->flash('success', 'Campanha ativada com sucesso.');
        $this->campaign->refresh();
    }

    public function pausar()
    {
        $this->campaign->update(['status' => 'paused']);
        session()->flash('success', 'Campanha pausada com sucesso.');
        $this->campaign->refresh();
    }

    public function setTab($tab)
    {
        return $this->goToTab($tab);
    }

    public function goToTab($tab)
    {
        $normalizedTab = $this->normalizeTab($tab) ?? self::TAB_ANALITICOS;
        session(['campaign_details_tab' => $normalizedTab]);

        if ($normalizedTab !== self::TAB_ADESOES) {
            session()->forget($this->getSelectedOrderSessionKey());
        }

        return $this->redirectToTab($normalizedTab);
    }

    public function goToOrder(string $orderId)
    {
        session(['campaign_details_tab' => self::TAB_ADESOES]);

        return redirect()->route('dashboard-campanhas-detalhes-adesoes-item', [
            'campaign_id' => $this->campaign_id,
            'order_id' => $orderId,
        ]);
    }

    public function goToOrderList()
    {
        $this->closeOrderEditModal();

        session(['campaign_details_tab' => self::TAB_ADESOES]);
        session()->forget($this->getSelectedOrderSessionKey());

        return $this->redirectToTab(self::TAB_ADESOES);
    }

    public function goToTransaction(string $transactionId)
    {
        session(['campaign_details_tab' => self::TAB_TRANSACOES]);

        return redirect()->route('dashboard-campanhas-detalhes-transacoes-item', [
            'campaign_id' => $this->campaign_id,
            'transaction_id' => $transactionId,
        ]);
    }

    public function goToTransactionList()
    {
        session(['campaign_details_tab' => self::TAB_TRANSACOES]);

        return $this->redirectToTab(self::TAB_TRANSACOES);
    }

    public function gotoPageAndScroll(int $page)
    {
        $this->gotoPage($page, 'transactionPage');
        $this->dispatchBrowserEvent('transactionPageChanged');
    }

    public function refreshAdesoes()
    {
        // Recarrega a campanha com os relacionamentos atualizados
        $this->campaign->refresh();
        $this->campaign->load(['orders.answers.question']);

        // Recarrega as métricas também
        $this->loadMetrics();
    }

    public function openManualOrderModal()
    {
        $this->resetManualOrderForm();
        $this->showManualOrderModal = true;
    }

    public function closeManualOrderModal()
    {
        $this->showManualOrderModal = false;
    }

    public function openOrderEditModal()
    {
        if (!$this->selectedOrder) {
            session()->flash('error', 'Nenhuma adesão selecionada.');
            return;
        }

        $this->resetOrderEditForm();

        $order = $this->selectedOrder;
        $currentPayment = $order->campaignPayments->sortByDesc('created_at')->first();
        $this->canDeleteSelectedOrder = !$this->orderHasPaidRecords($order->id);
        $this->confirmDeleteOrder = false;

        $this->editOrderId = $order->id;
        $this->editOrderIsManual = $this->isManualOrder($order);
        $this->editBuyerName = (string) ($order->buyer_name ?? '');
        $this->editBuyerEmail = (string) ($order->buyer_email ?? '');
        $this->editBuyerDocNum = (string) ($order->buyer_doc_num ?? '');
        $orderContactCountry = trim((string) ($order->buyer_contact_country ?? ''));
        if ($orderContactCountry === '' && !empty($order->buyer_id)) {
            $buyerCountry = \App\Models\AppBuyers::where('id', $order->buyer_id)->value('contact_country');
            $orderContactCountry = trim((string) ($buyerCountry ?? ''));
        }
        $this->editBuyerContactCountry = $this->sanitizeContactCountry($orderContactCountry) ?? '55';
        $this->editBuyerContactDdd = (string) ($order->buyer_contact_ddd ?? '');
        $this->editBuyerContactNum = (string) ($order->buyer_contact_num ?? '');
        $this->editOrderObservation = (string) (
            data_get($order->metadata, 'observation')
            ?? data_get($order->metadata, 'manual_observation')
            ?? ''
        );

        if ($this->editOrderIsManual) {
            $this->editOrderAmountTotal = toMoney($order->amount_total ?? 0);
            $this->editOrderAmountPaid = toMoney($order->amount_paid ?? 0);
            $this->editOrderStatus = (string) ($order->status ?? 'pending');
            $this->editOrderPayType = (string) ($currentPayment->pay_type ?? 'manual');
            $this->editOrderPayIntegrationType = (string) ($currentPayment->pay_integration_type ?? 'manual');
            $this->editOrderPaidAt = $order->paid_at
                ? Carbon::parse($order->paid_at)->format('Y-m-d\TH:i')
                : '';
            $this->editOrderPayDatetime = $currentPayment && $currentPayment->pay_datetime
                ? Carbon::parse($currentPayment->pay_datetime)->format('Y-m-d\TH:i')
                : '';
            $this->editOrderPayTransactionId = (string) ($currentPayment->pay_transaction_id ?? '');
            $this->editOrderPayNsu = (string) ($currentPayment->pay_nsu ?? '');
            $this->editOrderDescription = (string) (
                $currentPayment->description
                ?? optional($order->paymentSlips->sortByDesc('created_at')->first())->description
                ?? 'LANÇAMENTO MANUAL'
            );
        }

        $this->showOrderEditModal = true;
    }

    public function closeOrderEditModal()
    {
        $this->showOrderEditModal = false;
        $this->resetOrderEditForm();
    }

    public function beginDeleteOrderConfirmation()
    {
        $this->resetValidation();
        $this->confirmDeleteOrder = false;

        if (!$this->selectedOrderId) {
            $this->addError('editGeneral', 'Nenhuma adesão selecionada.');
            return;
        }

        $order = \App\Models\ModCampaign\CampaignOrder::query()
            ->where('campaign_id', $this->campaign->id)
            ->where('id', $this->selectedOrderId)
            ->first();

        if (!$order) {
            $this->addError('editGeneral', 'Adesão não encontrada.');
            return;
        }

        if ($this->orderHasPaidRecords($order->id)) {
            $this->canDeleteSelectedOrder = false;
            $this->addError('editGeneral', 'Não é possível excluir uma adesão que já possui pagamento.');
            return;
        }

        $this->canDeleteSelectedOrder = true;
        $this->confirmDeleteOrder = true;
    }

    public function cancelDeleteOrderConfirmation()
    {
        $this->confirmDeleteOrder = false;
    }

    public function deleteOrder()
    {
        $this->resetValidation();

        if (!$this->selectedOrderId) {
            $this->addError('editGeneral', 'Nenhuma adesão selecionada.');
            return;
        }

        if (!$this->confirmDeleteOrder) {
            $this->addError('editGeneral', 'Confirme a exclusão da adesão para continuar.');
            return;
        }

        $order = \App\Models\ModCampaign\CampaignOrder::query()
            ->where('campaign_id', $this->campaign->id)
            ->where('id', $this->selectedOrderId)
            ->first();

        if (!$order) {
            $this->addError('editGeneral', 'Adesão não encontrada.');
            return;
        }

        if ($this->orderHasPaidRecords($order->id)) {
            $this->confirmDeleteOrder = false;
            $this->canDeleteSelectedOrder = false;
            $this->addError('editGeneral', 'Não é possível excluir uma adesão que já possui pagamento.');
            return;
        }

        try {
            DB::beginTransaction();

            // Mantém o participante (app_buyers) e remove apenas os relacionamentos da adesão.
            \App\Models\ModCampaign\CampaignSubscriptionCycle::where('campaign_order_id', $order->id)
                ->update(['campaign_order_id' => null]);

            NotificacaoLog::where('target_ref', 'campaign_order')
                ->where('target_id', $order->id)
                ->delete();

            \App\Models\ModCampaign\CampaignPaymentAttempt::where('campaign_order_id', $order->id)->delete();
            \App\Models\ModCampaign\CampaignPaymentWebhook::where('campaign_order_id', $order->id)->delete();
            \App\Models\ModCampaign\CampaignPayment::where('campaign_order_id', $order->id)->delete();
            \App\Models\ModCampaign\CampaignPaymentSlip::where('campaign_order_id', $order->id)->delete();
            \App\Models\ModCampaign\CampaignOrderAnswer::where('campaign_order_id', $order->id)->delete();

            $order->delete();

            DB::commit();

            $this->confirmDeleteOrder = false;
            $this->canDeleteSelectedOrder = false;

            $this->closeOrderEditModal();
            $this->closeOrderDetails();
            $this->refreshAdesoes();

            session()->flash('success', 'Adesão excluída com sucesso.');

            return $this->redirectToTab(self::TAB_ADESOES);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Erro ao excluir adesão', [
                'campaign_id' => $this->campaign->id ?? null,
                'order_id' => $this->selectedOrderId,
                'exception' => $e->getMessage(),
            ]);

            $this->addError('editGeneral', 'Erro ao excluir adesão. Tente novamente.');
        }
    }

    public function saveOrderEdit()
    {
        $this->resetValidation();

        if (!$this->selectedOrderId) {
            $this->addError('editGeneral', 'Nenhuma adesão selecionada.');
            return;
        }

        $order = \App\Models\ModCampaign\CampaignOrder::with([
            'campaignPayments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'paymentSlips' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
        ])
            ->where('campaign_id', $this->campaign->id)
            ->where('id', $this->selectedOrderId)
            ->first();

        if (!$order) {
            $this->addError('editGeneral', 'Adesão não encontrada.');
            return;
        }

        $isManualOrder = $this->isManualOrder($order);

        $rules = [
            'editBuyerName' => ['required', 'string', 'max:255'],
            'editBuyerEmail' => ['nullable', 'email', 'max:255'],
            'editBuyerDocNum' => ['nullable', 'string', 'max:255'],
            'editBuyerContactCountry' => ['nullable', 'regex:/^[0-9]{1,5}$/'],
            'editBuyerContactDdd' => ['nullable', 'string', 'max:5'],
            'editBuyerContactNum' => ['nullable', 'string', 'max:20'],
            'editOrderObservation' => ['nullable', 'string', 'max:1000'],
        ];

        if ($isManualOrder) {
            $rules = array_merge($rules, [
                'editOrderAmountTotal' => ['required'],
                'editOrderAmountPaid' => ['nullable'],
                'editOrderStatus' => ['required', 'in:paid,pending,cancelled'],
                'editOrderPayType' => ['required', 'string', 'max:20'],
                'editOrderPayIntegrationType' => ['required', 'string', 'max:20'],
                'editOrderPaidAt' => ['nullable', 'string', 'max:30'],
                'editOrderPayDatetime' => ['nullable', 'string', 'max:30'],
                'editOrderPayTransactionId' => ['nullable', 'string', 'max:120'],
                'editOrderPayNsu' => ['nullable', 'string', 'max:120'],
                'editOrderDescription' => ['nullable', 'string', 'max:255'],
            ]);
        }

        $validated = $this->validate($rules, [
            'editBuyerName.required' => 'Informe o nome do doador.',
            'editBuyerEmail.email' => 'E-mail inválido.',
            'editBuyerContactCountry.regex' => 'Código do país (DDI) inválido.',
            'editOrderAmountTotal.required' => 'Informe o valor total.',
            'editOrderStatus.required' => 'Informe o status da adesão.',
            'editOrderObservation.max' => 'A observação pode ter no máximo 1000 caracteres.',
        ]);

        $orderData = [
            'buyer_name' => trim((string) $validated['editBuyerName']),
            'buyer_email' => !empty($validated['editBuyerEmail'])
                ? strtolower(trim((string) $validated['editBuyerEmail']))
                : null,
            'buyer_doc_num' => !empty($validated['editBuyerDocNum'])
                ? trim((string) $validated['editBuyerDocNum'])
                : null,
            'buyer_contact_country' => $this->sanitizeContactCountry($validated['editBuyerContactCountry'] ?? null),
            'buyer_contact_ddd' => !empty($validated['editBuyerContactDdd'])
                ? preg_replace('/[^0-9]/', '', (string) $validated['editBuyerContactDdd'])
                : null,
            'buyer_contact_num' => !empty($validated['editBuyerContactNum'])
                ? preg_replace('/[^0-9]/', '', (string) $validated['editBuyerContactNum'])
                : null,
        ];

        $metadata = is_array($order->metadata) ? $order->metadata : [];
        $observation = trim((string) ($validated['editOrderObservation'] ?? ''));

        if ($observation !== '') {
            $metadata['observation'] = $observation;
        } else {
            unset($metadata['observation']);
        }

        if ($isManualOrder) {
            $metadata['manual_entry'] = true;
            $metadata['manual_source'] = 'dashboard';
            $metadata['payment_gateway_marker'] = 'MANUAL';

            if ($observation !== '') {
                $metadata['manual_observation'] = $observation;
            } else {
                unset($metadata['manual_observation']);
            }
        }

        $orderData['metadata'] = $metadata;

        $amountTotal = 0;
        $amountPaid = 0;
        $orderStatus = 'pending';
        $paidAt = null;
        $payDatetime = null;
        $payType = null;
        $payIntegrationType = null;
        $payTransactionId = null;
        $payNsu = null;
        $description = null;

        if ($isManualOrder) {
            $amountTotal = $this->parseMoneyInputToCents($validated['editOrderAmountTotal'], 'editOrderAmountTotal');
            if ($amountTotal === null) {
                return;
            }

            $amountPaidInput = trim((string) ($validated['editOrderAmountPaid'] ?? ''));
            if ($amountPaidInput !== '') {
                $parsedAmountPaid = $this->parseMoneyInputToCents($amountPaidInput, 'editOrderAmountPaid');
                if ($parsedAmountPaid === null) {
                    return;
                }
                $amountPaid = $parsedAmountPaid;
            } else {
                $amountPaid = 0;
            }
            $orderStatus = (string) $validated['editOrderStatus'];

            if ($amountTotal <= 0) {
                $this->addError('editOrderAmountTotal', 'O valor total deve ser maior que zero.');
                return;
            }

            if ($orderStatus === 'paid') {
                if ($amountPaid <= 0) {
                    $amountPaid = $amountTotal;
                }
                if ($amountPaid > $amountTotal) {
                    $this->addError('editOrderAmountPaid', 'O valor pago não pode ser maior que o valor total.');
                    return;
                }
            } else {
                $amountPaid = 0;
            }

            if ($orderStatus === 'paid') {
                if (!empty($validated['editOrderPaidAt'])) {
                    $paidAt = $this->parseFlexibleDateInput($validated['editOrderPaidAt'], 'editOrderPaidAt');
                    if (!$paidAt) {
                        return;
                    }
                } else {
                    $paidAt = now();
                }

                if (!empty($validated['editOrderPayDatetime'])) {
                    $payDatetime = $this->parseFlexibleDateInput($validated['editOrderPayDatetime'], 'editOrderPayDatetime');
                    if (!$payDatetime) {
                        return;
                    }
                } else {
                    $payDatetime = $paidAt;
                }
            }

            $payType = trim((string) $validated['editOrderPayType']);
            $payIntegrationType = trim((string) $validated['editOrderPayIntegrationType']);
            $payTransactionId = !empty($validated['editOrderPayTransactionId'])
                ? trim((string) $validated['editOrderPayTransactionId'])
                : null;
            $payNsu = !empty($validated['editOrderPayNsu'])
                ? trim((string) $validated['editOrderPayNsu'])
                : null;
            $description = !empty($validated['editOrderDescription'])
                ? trim((string) $validated['editOrderDescription'])
                : 'LANÇAMENTO MANUAL';

            if ($orderStatus === 'paid' && empty($payNsu)) {
                $payNsu = 'M.' . now()->format('ymdHis');
            }

            $orderData = array_merge($orderData, [
                'amount_total' => $amountTotal,
                'amount_paid' => $amountPaid,
                'status' => $orderStatus,
                'paid_at' => $paidAt,
            ]);
        }

        try {
            DB::beginTransaction();

            $order->update($orderData);

            if ($isManualOrder) {
                $slipGroupId = $order->slip_group_id ?: (string) Str::uuid();

                if (!$order->slip_group_id) {
                    $order->update(['slip_group_id' => $slipGroupId]);
                }

                $paymentSlip = $order->paymentSlips
                    ->firstWhere('id', $order->current_payment_slip_id)
                    ?: $order->paymentSlips->sortByDesc('created_at')->first();

                if (!$paymentSlip) {
                    $paymentSlip = \App\Models\ModCampaign\CampaignPaymentSlip::create([
                        'campaign_id' => $this->campaign->id,
                        'campaign_order_id' => $order->id,
                        'slip_group_id' => $slipGroupId,
                        'description' => mb_strtoupper($description),
                        'status' => $orderStatus,
                        'paid_at' => $paidAt,
                        'total_amount' => $amountTotal,
                        'amount_paid' => $amountPaid,
                        'amount_fees' => 0,
                        'amount_liquid' => $amountPaid,
                        'installments_total' => 1,
                        'installments_paid' => $orderStatus === 'paid' ? 1 : 0,
                        'installment_control' => 1,
                        'customer_pay_gateway_id' => null,
                        'gateway_slug' => 'manual',
                        'gateway_sandbox' => false,
                    ]);

                    $order->update(['current_payment_slip_id' => $paymentSlip->id]);
                } else {
                    $installmentsTotal = max(1, (int) ($paymentSlip->installments_total ?? 1));

                    $paymentSlip->update([
                        'description' => mb_strtoupper($description),
                        'status' => $orderStatus,
                        'paid_at' => $paidAt,
                        'total_amount' => $amountTotal,
                        'amount_paid' => $amountPaid,
                        'amount_fees' => 0,
                        'amount_liquid' => $amountPaid,
                        'installments_total' => $installmentsTotal,
                        'installments_paid' => $orderStatus === 'paid' ? $installmentsTotal : 0,
                        'customer_pay_gateway_id' => null,
                        'gateway_slug' => 'manual',
                        'gateway_sandbox' => false,
                    ]);

                    if ($order->current_payment_slip_id !== $paymentSlip->id) {
                        $order->update(['current_payment_slip_id' => $paymentSlip->id]);
                    }
                }

                $payment = $order->campaignPayments
                    ->where('campaign_payment_slip_id', $paymentSlip->id)
                    ->sortByDesc('created_at')
                    ->first()
                    ?: $order->campaignPayments->sortByDesc('created_at')->first();

                $paymentPayload = [
                    'campaign_id' => $this->campaign->id,
                    'campaign_order_id' => $order->id,
                    'campaign_payment_slip_id' => $paymentSlip->id,
                    'slip_group_id' => $slipGroupId,
                    'description' => mb_strtoupper($description),
                    'customer_pay_gateway_id' => null,
                    'gateway_slug' => 'manual',
                    'gateway_sandbox' => false,
                    'pay_integration_type' => $payIntegrationType,
                    'status' => $orderStatus,
                    'pay_type' => $payType,
                    'value_paid' => $amountPaid,
                    'value_fees' => 0,
                    'value_liquid' => $amountPaid,
                    'fee_percentage_used' => 0,
                    'pay_transaction_id' => $payTransactionId,
                    'pay_nsu' => $payNsu,
                    'paid_label' => toMoney($amountPaid, 'R$ '),
                    'paid_description' => 'PAGAMENTO MANUAL via DASHBOARD',
                    'pay_installments_number' => 1,
                    'pay_installment_value' => $amountPaid,
                    'pay_datetime' => $payDatetime,
                    'paid_at' => $paidAt,
                ];

                if ($payment) {
                    $payment->update($paymentPayload);
                } else {
                    \App\Models\ModCampaign\CampaignPayment::create(array_merge([
                        'installment_number' => 1,
                    ], $paymentPayload));
                }
            }

            DB::commit();

            $this->closeOrderEditModal();
            $this->refreshAdesoes();
            $this->selectOrder($order->id);

            if ($isManualOrder) {
                session()->flash('success', 'Adesão manual alterada com sucesso.');
            } else {
                session()->flash('success', 'Dados da adesão atualizados com sucesso.');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erro ao salvar edição de adesão', [
                'campaign_id' => $this->campaign->id ?? null,
                'order_id' => $this->selectedOrderId,
                'exception' => $e->getMessage(),
            ]);

            $this->addError('editGeneral', 'Erro ao salvar adesão. Revise os dados e tente novamente.');
        }
    }

    public function saveManualOrder()
    {
        $this->resetValidation();

        $validated = $this->validate([
            'manualBuyerName' => ['required', 'string', 'max:255'],
            'manualBuyerEmail' => ['nullable', 'email', 'max:255'],
            'manualBuyerDocNum' => ['nullable', 'string', 'max:255'],
            'manualBuyerContactCountry' => ['required', 'regex:/^[0-9]{1,5}$/'],
            'manualBuyerContactDdd' => ['nullable', 'string', 'max:5'],
            'manualBuyerContactNum' => ['nullable', 'string', 'max:20'],
            'manualAmountTotal' => ['required'],
            'manualAmountPaid' => ['nullable'],
            'manualStatus' => ['required', 'in:paid,pending'],
            'manualPayType' => ['required', 'string', 'max:20'],
            'manualPaidAt' => ['nullable', 'string', 'max:30'],
            'manualDescription' => ['nullable', 'string', 'max:255'],
            'manualObservation' => ['nullable', 'string', 'max:1000'],
        ], [
            'manualBuyerName.required' => 'Informe o nome do doador.',
            'manualBuyerEmail.email' => 'E-mail inválido.',
            'manualBuyerContactCountry.required' => 'Informe o código do país (DDI).',
            'manualBuyerContactCountry.regex' => 'Código do país (DDI) inválido.',
            'manualAmountTotal.required' => 'Informe o valor total.',
            'manualStatus.required' => 'Informe o status da adesão.',
            'manualObservation.max' => 'A observação pode ter no máximo 1000 caracteres.',
        ]);

        $amountTotal = $this->parseMoneyInputToCents($validated['manualAmountTotal'], 'manualAmountTotal');
        if ($amountTotal === null) {
            return;
        }

        $amountPaidInput = (string) ($validated['manualAmountPaid'] ?? '');
        if ($amountPaidInput !== '') {
            $parsedAmountPaid = $this->parseMoneyInputToCents($amountPaidInput, 'manualAmountPaid');
            if ($parsedAmountPaid === null) {
                return;
            }
            $amountPaid = $parsedAmountPaid;
        } else {
            $amountPaid = 0;
        }

        if ($amountTotal <= 0) {
            $this->addError('manualAmountTotal', 'O valor total deve ser maior que zero.');
            return;
        }

        if ($validated['manualStatus'] === 'paid') {
            if ($amountPaid <= 0) {
                $amountPaid = $amountTotal;
            }
            if ($amountPaid > $amountTotal) {
                $this->addError('manualAmountPaid', 'O valor pago não pode ser maior que o valor total.');
                return;
            }
        } else {
            $amountPaid = 0;
        }

        $paidAt = null;
        if ($validated['manualStatus'] === 'paid') {
            if (!empty($validated['manualPaidAt'])) {
                $paidAt = $this->parseFlexibleDateInput($validated['manualPaidAt'], 'manualPaidAt');
                if (!$paidAt) {
                    return;
                }

                if (!str_contains((string) $validated['manualPaidAt'], ':') && !str_contains((string) $validated['manualPaidAt'], 'T')) {
                    $paidAt = $paidAt->setTimeFromTimeString(now()->format('H:i:s'));
                }
            } else {
                $paidAt = now();
            }
        }

        try {
            DB::beginTransaction();

            $slipGroupId = (string) Str::uuid();
            $orderControl = $this->generateManualOrderControl();
            $isPaid = $validated['manualStatus'] === 'paid';
            $observation = trim((string) ($validated['manualObservation'] ?? ''));
            $metadata = [
                'manual_entry' => true,
                'manual_source' => 'dashboard',
                'payment_gateway_marker' => 'MANUAL',
            ];

            if ($observation !== '') {
                $metadata['observation'] = $observation;
                $metadata['manual_observation'] = $observation;
            }

            $order = \App\Models\ModCampaign\CampaignOrder::create([
                'campaign_id' => $this->campaign->id,
                'order_control' => $orderControl,
                'buyer_name' => trim($validated['manualBuyerName']),
                'buyer_email' => !empty($validated['manualBuyerEmail']) ? strtolower(trim($validated['manualBuyerEmail'])) : null,
                'buyer_doc_num' => !empty($validated['manualBuyerDocNum']) ? trim($validated['manualBuyerDocNum']) : null,
                'buyer_contact_country' => $this->sanitizeContactCountry($validated['manualBuyerContactCountry'] ?? null),
                'buyer_contact_ddd' => !empty($validated['manualBuyerContactDdd']) ? preg_replace('/[^0-9]/', '', $validated['manualBuyerContactDdd']) : null,
                'buyer_contact_num' => !empty($validated['manualBuyerContactNum']) ? preg_replace('/[^0-9]/', '', $validated['manualBuyerContactNum']) : null,
                'amount_total' => $amountTotal,
                'amount_paid' => $amountPaid,
                'status' => $validated['manualStatus'],
                'paid_at' => $paidAt,
                'is_anonymous' => false,
                'slip_group_id' => $slipGroupId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'referer' => request()->fullUrl(),
                'metadata' => $metadata,
            ]);

            $description = !empty($validated['manualDescription'])
                ? trim($validated['manualDescription'])
                : 'LANÇAMENTO MANUAL';

            $slip = \App\Models\ModCampaign\CampaignPaymentSlip::create([
                'campaign_id' => $this->campaign->id,
                'campaign_order_id' => $order->id,
                'slip_group_id' => $slipGroupId,
                'description' => mb_strtoupper($description),
                'status' => $validated['manualStatus'],
                'paid_at' => $paidAt,
                'total_amount' => $amountTotal,
                'amount_paid' => $amountPaid,
                'amount_fees' => 0,
                'amount_liquid' => $amountPaid,
                'installments_total' => 1,
                'installments_paid' => $isPaid ? 1 : 0,
                'installment_control' => 1,
                'customer_pay_gateway_id' => null,
                'gateway_slug' => 'manual',
                'gateway_sandbox' => false,
            ]);

            $order->update([
                'current_payment_slip_id' => $slip->id,
            ]);

            \App\Models\ModCampaign\CampaignPayment::create([
                'campaign_id' => $this->campaign->id,
                'campaign_order_id' => $order->id,
                'campaign_payment_slip_id' => $slip->id,
                'slip_group_id' => $slipGroupId,
                'installment_number' => 1,
                'description' => mb_strtoupper($description),
                'customer_pay_gateway_id' => null,
                'gateway_slug' => 'manual',
                'gateway_sandbox' => false,
                'pay_integration_type' => 'manual',
                'status' => $validated['manualStatus'],
                'pay_type' => $validated['manualPayType'],
                'value_paid' => $amountPaid,
                'value_fees' => 0,
                'value_liquid' => $amountPaid,
                'fee_percentage_used' => 0,
                'pay_nsu' => 'M.' . now()->format('ymdHis'),
                'paid_label' => 'LANÇAMENTO MANUAL',
                'paid_description' => 'PAGAMENTO MANUAL via DASHBOARD',
                'pay_installments_number' => 1,
                'pay_installment_value' => $amountPaid,
                'pay_datetime' => $paidAt,
                'paid_at' => $paidAt,
            ]);

            DB::commit();

            $this->closeManualOrderModal();
            $this->refreshAdesoes();
            session()->flash('success', 'Adesão manual criada com sucesso. Localizador: ' . $orderControl);

            return $this->redirectToTab(self::TAB_ADESOES);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar adesão manual', [
                'campaign_id' => $this->campaign->id ?? null,
                'exception' => $e->getMessage(),
            ]);

            $this->addError('manualGeneral', 'Erro ao cadastrar adesão manual. Revise os dados e tente novamente.');
        }
    }

    public function exportAdesoes()
    {
        $orders = $this->getFilteredOrders();

        $filename = 'adesoes_campanha_' . $this->campaign->slug . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Cabeçalhos
            fputcsv($file, [
                'Localizador',
                'Data/Hora',
                'Nome',
                'E-mail',
                'CPF/CNPJ',
                'Telefone',
                'Valor Total',
                'Valor Pago',
                'Status',
                'Data Pagamento'
            ], ';');

            // Dados
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_control,
                    $order->created_at->format('d/m/Y H:i:s'),
                    $order->buyer_name,
                    $order->buyer_email ?? '',
                    $order->buyer_doc_num ?? '',
                    trim(implode(' ', array_filter([
                        !empty($order->buyer_contact_country) ? '+' . $order->buyer_contact_country : '',
                        $order->buyer_contact_ddd ?? '',
                        $order->buyer_contact_num ?? '',
                    ]))),
                    number_format($order->amount_total, 2, ',', '.'),
                    number_format($order->amount_paid, 2, ',', '.'),
                    $order->status === 'paid' ? 'Pago' : ($order->status === 'pending' ? 'Pendente' : ucfirst($order->status)),
                    $order->paid_at ? $order->paid_at->format('d/m/Y H:i:s') : '',
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportParticipantes()
    {
        $participants = $this->getParticipantsList();

        $filename = 'participantes_adesoes_' . $this->campaign->slug . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($participants) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'Nome',
                'Data de Nascimento',
                'Telefone',
                'E-mail',
                'Adesoes Geradas',
                'Adesoes Pagas',
            ], ';');

            foreach ($participants as $participant) {
                $contactCountry = $participant->contact_country ? '+' . $participant->contact_country : '';
                $contactDdd = $participant->contact_ddd ?? '';
                $contactNum = $participant->contact_num ?? '';
                $phone = trim(implode(' ', array_filter([$contactCountry, $contactDdd, $contactNum])));

                fputcsv($file, [
                    $participant->name ?? '',
                    $participant->birth_date ? dataData($participant->birth_date) : '',
                    $phone,
                    $participant->email ?? '',
                    $participant->total_orders ?? 0,
                    $participant->paid_orders ?? 0,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getParticipantsList()
    {
        return DB::table('tbc_campaign_order as o')
            ->leftJoin('app_buyers as b', 'o.buyer_id', '=', 'b.id')
            ->where('o.campaign_id', $this->campaign->id)
            ->selectRaw("
                COALESCE(b.id::text, LOWER(o.buyer_email), o.id::text) as participant_key,
                MAX(COALESCE(NULLIF(b.name, ''), NULLIF(o.buyer_name, ''), 'Nao informado')) as name,
                MAX(COALESCE(NULLIF(b.email, ''), NULLIF(o.buyer_email, ''), '')) as email,
                MAX(b.birth_date) as birth_date,
                MAX(COALESCE(NULLIF(b.contact_country::text, ''), NULLIF(o.buyer_contact_country, ''), '')) as contact_country,
                MAX(COALESCE(NULLIF(b.contact_ddd::text, ''), o.buyer_contact_ddd, '')) as contact_ddd,
                MAX(COALESCE(NULLIF(b.contact_num::text, ''), o.buyer_contact_num, '')) as contact_num,
                COUNT(*) as total_orders,
                SUM(CASE WHEN o.status = 'paid' THEN 1 ELSE 0 END) as paid_orders
            ")
            ->groupByRaw("COALESCE(b.id::text, LOWER(o.buyer_email), o.id::text)")
            ->orderBy('name')
            ->get();
    }

    public function exportQuestionarios()
    {
        $data = $this->getFilteredAnswers();
        $questions = $this->campaign->questions->sortBy('order');

        $filename = 'questionarios_campanha_' . $this->campaign->slug . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($data, $questions) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Cabeçalhos: Localizador, Data/Hora, Nome, E-mail, e depois cada pergunta
            $headers = ['Localizador', 'Data/Hora', 'Nome', 'E-mail'];
            foreach ($questions as $question) {
                $headers[] = $question->question_text;
            }
            fputcsv($file, $headers, ';');

            // Dados: uma linha por pedido
            foreach ($data as $item) {
                $order = $item['order'];
                $answers = $item['answers'];

                $row = [
                    $order->order_control,
                    $order->created_at->format('d/m/Y H:i:s'),
                    $order->buyer_name,
                    $order->buyer_email ?? '',
                ];

                // Adiciona resposta de cada pergunta ou "--"
                foreach ($questions as $question) {
                    if (isset($answers[$question->id])) {
                        $answer = $answers[$question->id];
                        $decodedAnswer = json_decode($answer->answer_value, true);
                        $answerText = is_array($decodedAnswer) ? implode('; ', $decodedAnswer) : $answer->answer_value;
                        $row[] = $answerText;
                    } else {
                        $row[] = '--';
                    }
                }

                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getFilteredOrders()
    {
        $query = $this->campaign->orders()->with([
            'answers.question',
            'campaignPayments' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
            'campaignPayments' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }
        ]);

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterDateFrom) {
            $query->whereDate('created_at', '>=', $this->filterDateFrom);
        }

        if ($this->filterDateTo) {
            $query->whereDate('created_at', '<=', $this->filterDateTo);
        }

        if ($this->filterSearch) {
            $query->where(function ($q) {
                $q->where('buyer_name', 'ilike', '%' . $this->filterSearch . '%')
                    ->orWhere('buyer_email', 'ilike', '%' . $this->filterSearch . '%')
                    ->orWhere('order_control', 'ilike', '%' . $this->filterSearch . '%')
                    ->orWhere('buyer_doc_num', 'ilike', '%' . $this->filterSearch . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getFilteredAnswers()
    {
        // Busca os pedidos filtrados
        $ordersQuery = $this->campaign->orders()->with(['answers.question']);

        if ($this->filterQuestionDateFrom) {
            $ordersQuery->whereDate('created_at', '>=', $this->filterQuestionDateFrom);
        }

        if ($this->filterQuestionDateTo) {
            $ordersQuery->whereDate('created_at', '<=', $this->filterQuestionDateTo);
        }

        $orders = $ordersQuery->orderBy('created_at', 'desc')->get();

        // Organiza as respostas por pedido e pergunta
        $result = [];
        foreach ($orders as $order) {
            $answersByQuestion = [];
            foreach ($order->answers as $answer) {
                $answersByQuestion[$answer->campaign_question_id] = $answer;
            }

            // Se há filtro de pergunta específica, verifica se o pedido tem resposta para ela
            if ($this->filterQuestion) {
                if (!isset($answersByQuestion[$this->filterQuestion])) {
                    continue; // Pula pedidos que não têm resposta para a pergunta filtrada
                }
            }

            $result[] = [
                'order' => $order,
                'answers' => $answersByQuestion,
            ];
        }

        return $result;
    }

    public function getFilteredTransactions(bool $export = false)
    {
        $query = DB::table('tbc_campaign_payment')
            ->leftJoin('tbc_campaign_order', 'tbc_campaign_payment.campaign_order_id', '=', 'tbc_campaign_order.id')
            ->leftJoin('tb_customers_pay_gateways', 'tbc_campaign_payment.customer_pay_gateway_id', '=', 'tb_customers_pay_gateways.id')
            ->where('tbc_campaign_payment.campaign_id', $this->campaign->id)
            ->distinct()
            ->select(
                'tbc_campaign_payment.id',
                'tbc_campaign_payment.campaign_id',
                'tbc_campaign_payment.campaign_order_id',
                'tbc_campaign_payment.customer_pay_gateway_id',
                'tbc_campaign_payment.pay_transaction_id as external_payment_id',
                'tbc_campaign_payment.value_paid as amount',
                'tbc_campaign_payment.status',
                'tbc_campaign_payment.paid_at',
                'tbc_campaign_payment.created_at',
                'tbc_campaign_payment.updated_at',
                'tbc_campaign_order.order_control',
                'tbc_campaign_order.buyer_name',
                'tbc_campaign_order.buyer_email',
                'tbc_campaign_order.status as order_status',
                'tb_customers_pay_gateways.pay_gateway_label',
                'tbc_campaign_payment.pay_nsu',
                'tbc_campaign_payment.pay_type',
                'tbc_campaign_payment.pay_installments_number',
                'tbc_campaign_payment.pay_pix_expires_at'
            );

        if ($this->filterTransactionStatus) {
            $query->where('tbc_campaign_payment.status', $this->filterTransactionStatus);
        }

        if ($this->filterTransactionDateFrom) {
            $query->whereDate('tbc_campaign_payment.created_at', '>=', $this->filterTransactionDateFrom);
        }

        if ($this->filterTransactionDateTo) {
            $query->whereDate('tbc_campaign_payment.created_at', '<=', $this->filterTransactionDateTo);
        }

        if ($this->filterTransactionSearch) {
            $query->where(function ($q) {
                $q->where('tbc_campaign_order.order_control', 'ilike', '%' . $this->filterTransactionSearch . '%')
                    ->orWhere('tbc_campaign_order.buyer_name', 'ilike', '%' . $this->filterTransactionSearch . '%')
                    ->orWhere('tbc_campaign_order.buyer_email', 'ilike', '%' . $this->filterTransactionSearch . '%')
                    ->orWhere('tbc_campaign_payment.pay_transaction_id', 'ilike', '%' . $this->filterTransactionSearch . '%')
                    ->orWhere('tbc_campaign_payment.pay_nsu', 'ilike', '%' . $this->filterTransactionSearch . '%');
            });
        }

        return $export
            ? $query->orderBy('tbc_campaign_payment.created_at', 'desc')->get()
            : $query->orderBy('tbc_campaign_payment.created_at', 'desc')->paginate((int) $this->transactionPerPage, ['*'], 'transactionPage');
    }

    public function exportTransacoes()
    {
        $transactions = $this->getFilteredTransactions(true);

        $filename = 'transacoes_campanha_' . $this->campaign->slug . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Cabeçalhos
            fputcsv($file, [
                'ID Transação',
                'Data/Hora',
                'Localizador Adesão',
                'Nome',
                'E-mail',
                'Gateway',
                'Método Pagamento',
                'NSU',
                'ID Externo',
                'Valor',
                'Parcelas',
                'Status Transação',
                'Status Adesão',
                'Data Pagamento'
            ], ';');

            // Dados
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    substr($transaction->id, 0, 8),
                    Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s'),
                    $transaction->order_control ?? '',
                    $transaction->buyer_name ?? '',
                    $transaction->buyer_email ?? '',
                    $transaction->pay_gateway_label ?? '',
                    strtoupper($transaction->pay_type ?? ''),
                    $transaction->pay_nsu ?? '',
                    $transaction->external_payment_id ?? '',
                    number_format($transaction->amount / 100, 2, ',', '.'),
                    $transaction->pay_installments_number ?? '1',
                    ucfirst($transaction->status ?? ''),
                    ucfirst($transaction->order_status ?? ''),
                    $transaction->paid_at ? Carbon::parse($transaction->paid_at)->format('d/m/Y H:i:s') : '',
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function selectTransaction($transactionId): bool
    {
        $this->selectedTransactionId = $transactionId;
        $this->selectedTransaction = \App\Models\ModCampaign\CampaignPayment::with([
            'order',
            'gateway',
            'attempts',
            'webhooks' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])
            ->where('id', $transactionId)
            ->where('campaign_id', $this->campaign->id)
            ->first();

        if (!$this->selectedTransaction) {
            $this->selectedTransactionId = null;
            return false;
        }

        return true;
    }

    public function closeTransactionDetails()
    {
        $this->selectedTransactionId = null;
        $this->selectedTransaction = null;
    }

    public function refreshTransacoes()
    {
        $this->resetPage('transactionPage');
        // Recarrega a campanha com os relacionamentos atualizados
        $this->campaign->refresh();
        $this->campaign->load(['campaignPayments']);

        // Recarrega as métricas também
        $this->loadMetrics();
    }

    public function selectOrder($orderId): bool
    {
        $this->selectedOrderId = $orderId;
        $this->selectedOrder = \App\Models\ModCampaign\CampaignOrder::with([
            'answers.question',
            'campaignPayments.attempts',
            'campaignPayments.gateway',
            'paymentSlips',
            'paymentSlips.payments',
            'paymentSlips.payments.attempts'
        ])
            ->where('id', $orderId)
            ->where('campaign_id', $this->campaign->id)
            ->first();

        if (!$this->selectedOrder) {
            $this->selectedOrderId = null;
            $this->selectedSubscription = null;
            session()->forget($this->getSelectedOrderSessionKey());
            return false;
        }

        $this->loadSelectedSubscription();

        session([$this->getSelectedOrderSessionKey() => $orderId]);
        return true;
    }

    public function closeOrderDetails()
    {
        $this->closeOrderEditModal();
        $this->selectedOrderId = null;
        $this->selectedOrder = null;
        $this->selectedSubscription = null;
        session()->forget($this->getSelectedOrderSessionKey());
    }

    private function loadSelectedSubscription(): void
    {
        $this->selectedSubscription = null;

        if (!$this->selectedOrder || !$this->selectedOrder->subscription_id) {
            return;
        }

        $this->selectedSubscription = CampaignSubscription::with([
            'cycles' => function ($query) {
                $query->with([
                    'order.campaignPayments.attempts',
                    'order.campaignPayments.gateway',
                    'attempts',
                ])->orderBy('cycle_number');
            }
        ])
            ->where('campaign_id', $this->campaign->id)
            ->find($this->selectedOrder->subscription_id);
    }

    public function cancelRecurring(string $subscriptionId): void
    {
        $subscription = CampaignSubscription::where('id', $subscriptionId)
            ->where('campaign_id', $this->campaign->id)
            ->first();

        if (!$subscription) {
            session()->flash('error', 'Recorrência não encontrada.');
            return;
        }

        if ($subscription->status === 'canceled') {
            session()->flash('error', 'A recorrência já está cancelada.');
            return;
        }

        $subscription->update([
            'status' => 'canceled',
            'canceled_at' => now(),
            'paused_at' => null,
            'next_charge_at' => null,
        ]);

        session()->flash('success', 'Recorrência cancelada com sucesso.');

        if ($this->selectedOrderId) {
            $this->selectOrder($this->selectedOrderId);
        }
    }

    public function pauseRecurring(string $subscriptionId): void
    {
        $subscription = CampaignSubscription::where('id', $subscriptionId)
            ->where('campaign_id', $this->campaign->id)
            ->first();

        if (!$subscription) {
            session()->flash('error', 'Recorrência não encontrada.');
            return;
        }

        if ($subscription->status !== 'active') {
            session()->flash('error', 'A recorrência não está ativa para ser pausada.');
            return;
        }

        $nextChargeAt = $subscription->next_charge_at
            ? Carbon::parse($subscription->next_charge_at)
            : now();

        $subscription->update([
            'status' => 'paused',
            'paused_at' => now(),
            'next_charge_at' => $nextChargeAt->copy()->addMonthNoOverflow(),
        ]);

        session()->flash('success', 'Recorrência pausada. Ela retornará no próximo mês.');

        if ($this->selectedOrderId) {
            $this->selectOrder($this->selectedOrderId);
        }
    }

    public function resumeRecurring(string $subscriptionId): void
    {
        $subscription = CampaignSubscription::where('id', $subscriptionId)
            ->where('campaign_id', $this->campaign->id)
            ->first();

        if (!$subscription) {
            session()->flash('error', 'Recorrência não encontrada.');
            return;
        }

        if ($subscription->status !== 'paused') {
            session()->flash('error', 'A recorrência não está pausada.');
            return;
        }

        $subscription->update([
            'status' => 'active',
            'paused_at' => null,
        ]);

        session()->flash('success', 'Recorrência reativada com sucesso.');

        if ($this->selectedOrderId) {
            $this->selectOrder($this->selectedOrderId);
        }
    }

    public function refreshOrderNotifications()
    {
        if (!$this->selectedOrderId) {
            return;
        }

        $this->selectOrder($this->selectedOrderId);
    }

    public function enviarEmailPorStatus()
    {
        try {
            if (!$this->selectedOrder) {
                session()->flash('error', 'Nenhuma adesão selecionada.');
                return;
            }

            $order = $this->selectedOrder;
            $status = $order->status;

            Log::info('Enviando email por status', [
                'order_id' => $order->id,
                'status' => $status,
            ]);

            $enviado = false;
            $tipoEmail = '';

            // Determina qual email enviar baseado no status
            switch ($status) {
                case 'paid':
                    // Envia notificação de pagamento aprovado E comprovante de participação
                    $enviado1 = \App\Services\CampaignEmailService::enviarNotificacaoPagamentoAprovado($order);
                    $enviado2 = \App\Services\CampaignEmailService::enviarComprovanteParticipacao($order);
                    $enviado = $enviado1 && $enviado2;
                    $tipoEmail = 'pagamento aprovado e comprovante de participação';
                    break;

                case 'pending':
                    // Envia notificação de pagamento pendente
                    $enviado = \App\Services\CampaignEmailService::enviarNotificacaoPagamentoPendente($order);
                    $tipoEmail = 'pagamento pendente';
                    break;

                case 'cancelled':
                case 'refunded':
                default:
                    // Para outros status, envia comprovante de participação
                    $enviado = \App\Services\CampaignEmailService::enviarComprovanteParticipacao($order);
                    $tipoEmail = 'comprovante de participação';
                    break;
            }

            if ($enviado) {
                session()->flash('success', 'Email de ' . $tipoEmail . ' enviado com sucesso para ' . $order->buyer_email);
                Log::info('Email enviado com sucesso', [
                    'order_id' => $order->id,
                    'tipo' => $tipoEmail,
                    'email' => $order->buyer_email,
                ]);
            } else {
                session()->flash('error', 'Erro ao enviar email. Verifique os logs.');
                Log::warning('Falha ao enviar email', [
                    'order_id' => $order->id,
                    'tipo' => $tipoEmail,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Erro ao enviar email por status: ' . $e->getMessage(), [
                'order_id' => $this->selectedOrder->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Erro ao enviar email: ' . $e->getMessage());
        }

        $this->refreshOrderNotifications();
    }

    public function viewOrderFromTransaction($orderId)
    {
        session(['campaign_details_tab' => self::TAB_ADESOES]);

        return redirect()->route('dashboard-campanhas-detalhes-adesoes-item', [
            'campaign_id' => $this->campaign_id,
            'order_id' => $orderId,
        ]);
    }

    // Propriedades para o modal de detalhes do pagamento
    public $selectedPayment = null;
    public $showPaymentModal = false;

    // Propriedades para o modal de QR Code
    public $showQrCodeModal = false;

    public function showPaymentDetails($paymentId)
    {
        $this->selectedPayment = \App\Models\ModCampaign\CampaignPayment::with([
            'attempts' => function ($query) {
                $query->orderBy('attempted_at', 'desc');
            },
            'webhooks' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'gateway'
        ])
            ->where('id', $paymentId)
            ->first();

        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->selectedPayment = null;
    }

    public function openQrCodeModal()
    {
        $this->showQrCodeModal = true;
    }

    public function closeQrCodeModal()
    {
        $this->showQrCodeModal = false;
    }

    public function getSelectedOrderPaymentsProperty()
    {
        if (!$this->selectedOrder) {
            return collect();
        }

        return \App\Models\AppPayment\AppPayment::where('app_ref', 'app_campaign')
            ->where('app_ref_order_id', $this->selectedOrder->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function viewPaymentDetails($paymentId)
    {
        $payment = \App\Models\AppPayment\AppPayment::where('id', $paymentId)
            ->where('app_ref', 'app_campaign')
            ->where('app_ref_order_id', $this->selectedOrder->id)
            ->first();

        if (!$payment) {
            return;
        }

        // Busca dados relacionados
        $order = $this->selectedOrder;
        $gateway = $payment->gateway;

        // Monta array com todos os dados necessários
        $this->selectedTransactionData = [
            'id' => $payment->id,
            'status' => $payment->status,
            'amount' => $payment->value_paid ?? $payment->value_amortization ?? 0,
            'created_at' => $payment->created_at ? $payment->created_at->toDateTimeString() : null,
            'paid_at' => $payment->pay_datetime ? $payment->pay_datetime->toDateTimeString() : null,
            'pay_gateway_label' => $gateway ? $gateway->pay_gateway_label : null,
            'pay_gateway_slug' => $gateway ? $gateway->pay_gateway_slug : null,
            'pay_type' => $payment->pay_type,
            'pay_installments_number' => $payment->pay_installments_number,
            'pay_installment_value' => $payment->pay_installment_value,
            'pay_nsu' => $payment->pay_nsu,
            'external_payment_id' => null, // Payments não têm external_payment_id direto
            'order_control' => $order->order_control,
            'order_status' => $order->status,
            'buyer_name' => $order->buyer_name,
            'buyer_email' => $order->buyer_email,
            'buyer_doc_num' => $order->buyer_doc_num,
            'buyer_contact_ddd' => $order->buyer_contact_ddd,
            'buyer_contact_num' => $order->buyer_contact_num,
            'amount_total' => $order->amount_total,
            'amount_paid' => $order->amount_paid,
            'pay_pix_qr_code' => $payment->pay_pix_qr_code,
            'pay_pix_key' => $payment->pay_pix_key,
            'pay_boleto_barcode' => $payment->pay_boleto_barcode,
            'pay_boleto_url' => $payment->pay_boleto_url,
            'pay_boleto_expiration_date' => $payment->pay_boleto_expiration_date,
            'pay_json_response' => $payment->pay_json_response,
            'pay_card_first' => $payment->pay_card_first,
            'pay_card_last' => $payment->pay_card_last,
            'pay_card_brand' => $payment->pay_card_brand,
            'pay_card_name' => $payment->pay_card_name,
        ];

        $this->showTransactionDetails = true;
    }

    public function reprocessWebhook($webhookId)
    {
        try {
            Log::info('Iniciando reprocessamento de webhook', ['webhook_id' => $webhookId]);

            $webhook = \App\Models\ModCampaign\CampaignPaymentWebhook::find($webhookId);

            if (!$webhook) {
                Log::warning('Webhook não encontrado para reprocessamento', ['webhook_id' => $webhookId]);
                session()->flash('error', 'Webhook não encontrado.');
                return;
            }

            Log::info('Webhook encontrado', [
                'webhook_id' => $webhook->id,
                'status_antes' => $webhook->processing_status,
                'campaign_order_id' => $webhook->campaign_order_id,
                'campaign_payment_id' => $webhook->campaign_payment_id,
            ]);

            // Reseta o status antes de reprocessar
            $webhook->update([
                'processing_status' => 'pending',
                'processing_error' => null,
                'processed_at' => null,
            ]);

            Log::info('Status resetado para pending');

            // Reprocessa o webhook usando a mesma lógica do controller
            $controller = new \App\Http\Controllers\Api\CampaignWebhookController();

            // Cria um mock de Request com o payload do webhook
            $request = new \Illuminate\Http\Request();
            $request->replace($webhook->payload);

            // Chama o método handleSafe2PayWebhook que vai criar e processar internamente
            $controller->handleSafe2PayWebhook(
                $request,
                $webhook->campaign_order_id,
                $webhook->campaign_payment_id,
                $webhook->id
            );

            Log::info('Método handleSafe2PayWebhook executado');

            // Recarrega o webhook do banco para pegar o status atualizado
            $webhook = \App\Models\ModCampaign\CampaignPaymentWebhook::find($webhookId);

            Log::info('Webhook após reprocessamento', [
                'webhook_id' => $webhook->id,
                'processing_status' => $webhook->processing_status,
                'processing_error' => $webhook->processing_error,
                'processed_at' => $webhook->processed_at,
            ]);

            if ($webhook->processing_status === 'processed') {
                session()->flash('success', 'Webhook reprocessado com sucesso!');
                Log::info('Webhook reprocessado com sucesso', ['webhook_id' => $webhookId]);
            } else {
                $errorMsg = $webhook->processing_error ?? 'Erro desconhecido';
                session()->flash('error', 'Erro ao reprocessar webhook: ' . $errorMsg);
                Log::warning('Webhook reprocessado com erro', [
                    'webhook_id' => $webhookId,
                    'error' => $errorMsg
                ]);
            }

            // Recarrega a transação com os webhooks atualizados
            if ($this->selectedTransaction) {
                $transactionId = is_array($this->selectedTransaction)
                    ? $this->selectedTransaction['id']
                    : $this->selectedTransaction->id;
                $this->selectTransaction($transactionId);
                Log::info('Transação recarregada', ['transaction_id' => $transactionId]);
            }

        } catch (\Exception $e) {
            Log::error('Erro ao reprocessar webhook: ' . $e->getMessage(), [
                'webhook_id' => $webhookId,
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Erro ao reprocessar webhook: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.dashboard.campanha-detalhes')
            ->layout('layouts.app-pep-auth');
    }

    public function getSelectedOrderNotificationsProperty()
    {
        if (!$this->selectedOrderId) {
            return collect();
        }

        return NotificacaoLog::where('target_ref', 'campaign_order')
            ->where('target_id', $this->selectedOrderId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function getSelectedOrderSessionKey(): string
    {
        return 'campaign_details_order_' . $this->campaign_id;
    }

    private function resetManualOrderForm(): void
    {
        $this->resetErrorBag();

        $this->manualBuyerName = '';
        $this->manualBuyerEmail = '';
        $this->manualBuyerDocNum = '';
        $this->manualBuyerContactCountry = '55';
        $this->manualBuyerContactDdd = '';
        $this->manualBuyerContactNum = '';
        $this->manualAmountTotal = '';
        $this->manualAmountPaid = '';
        $this->manualStatus = 'paid';
        $this->manualPayType = 'manual';
        $this->manualPaidAt = now()->format('Y-m-d');
        $this->manualDescription = '';
        $this->manualObservation = '';
    }

    private function resetOrderEditForm(): void
    {
        $this->resetErrorBag();

        $this->editOrderId = null;
        $this->editOrderIsManual = false;
        $this->canDeleteSelectedOrder = false;
        $this->confirmDeleteOrder = false;
        $this->editBuyerName = '';
        $this->editBuyerEmail = '';
        $this->editBuyerDocNum = '';
        $this->editBuyerContactCountry = '55';
        $this->editBuyerContactDdd = '';
        $this->editBuyerContactNum = '';
        $this->editOrderAmountTotal = '';
        $this->editOrderAmountPaid = '';
        $this->editOrderStatus = 'pending';
        $this->editOrderPayType = 'manual';
        $this->editOrderPayIntegrationType = 'manual';
        $this->editOrderPaidAt = '';
        $this->editOrderPayDatetime = '';
        $this->editOrderPayTransactionId = '';
        $this->editOrderPayNsu = '';
        $this->editOrderDescription = '';
        $this->editOrderObservation = '';
    }

    private function isManualOrder(\App\Models\ModCampaign\CampaignOrder $order): bool
    {
        if (Str::endsWith((string) $order->order_control, '-M')) {
            return true;
        }

        $hasManualSlip = ($order->paymentSlips ?? collect())->contains(function ($slip) {
            return ($slip->gateway_slug ?? null) === 'manual';
        });

        if ($hasManualSlip) {
            return true;
        }

        return ($order->campaignPayments ?? collect())->contains(function ($payment) {
            return ($payment->gateway_slug ?? null) === 'manual'
                || ($payment->pay_integration_type ?? null) === 'manual';
        });
    }

    private function orderHasPaidRecords(string $orderId): bool
    {
        $paidOrderStatuses = function_exists('listOrderStatusPaid')
            ? listOrderStatusPaid()
            : ['paid'];

        $paidPaymentStatuses = function_exists('listPaymentStatusPaid')
            ? listPaymentStatusPaid()
            : ['paid', 'approved', 'captured', 'autorizado', 'success', 'sucesso'];

        $paidOrderStatusesNormalized = array_values(array_unique(array_map(function ($status) {
            return strtolower((string) $status);
        }, $paidOrderStatuses)));

        $paidPaymentStatusesVariants = array_values(array_unique(array_merge(
            $paidPaymentStatuses,
            array_map(function ($status) {
                return strtolower((string) $status);
            }, $paidPaymentStatuses),
            array_map(function ($status) {
                return strtoupper((string) $status);
            }, $paidPaymentStatuses),
            array_map(function ($status) {
                return ucfirst(strtolower((string) $status));
            }, $paidPaymentStatuses)
        )));

        $order = \App\Models\ModCampaign\CampaignOrder::query()
            ->select(['id', 'status', 'paid_at'])
            ->find($orderId);

        if (!$order) {
            return false;
        }

        if (in_array(strtolower((string) $order->status), $paidOrderStatusesNormalized, true)) {
            return true;
        }

        if (!empty($order->paid_at)) {
            return true;
        }

        $hasPaidSlip = \App\Models\ModCampaign\CampaignPaymentSlip::query()
            ->where('campaign_order_id', $orderId)
            ->where(function ($query) use ($paidPaymentStatusesVariants) {
                $query->whereIn('status', $paidPaymentStatusesVariants)
                    ->orWhereNotNull('paid_at');
            })
            ->exists();

        if ($hasPaidSlip) {
            return true;
        }

        return \App\Models\ModCampaign\CampaignPayment::query()
            ->where('campaign_order_id', $orderId)
            ->where(function ($query) use ($paidPaymentStatusesVariants) {
                $query->whereIn('status', $paidPaymentStatusesVariants)
                    ->orWhereNotNull('paid_at');
            })
            ->exists();
    }

    private function sanitizeContactCountry($country): ?string
    {
        $cleanCountry = preg_replace('/[^0-9]/', '', (string) $country);

        if ($cleanCountry === '' || $cleanCountry === null) {
            return null;
        }

        return substr($cleanCountry, 0, 5);
    }

    private function parseMoneyInputToCents($value, string $field): ?int
    {
        $raw = trim((string) $value);

        if ($raw === '') {
            return 0;
        }

        $normalized = preg_replace('/[^\d,\.\-]/', '', $raw);

        if ($normalized === '' || $normalized === '-' || $normalized === null) {
            $this->addError($field, 'Valor inválido.');
            return null;
        }

        $lastComma = strrpos($normalized, ',');
        $lastDot = strrpos($normalized, '.');

        if ($lastComma !== false && $lastDot !== false) {
            if ($lastComma > $lastDot) {
                $normalized = str_replace('.', '', $normalized);
                $normalized = str_replace(',', '.', $normalized);
            } else {
                $normalized = str_replace(',', '', $normalized);
            }
        } elseif ($lastComma !== false) {
            $normalized = str_replace('.', '', $normalized);
            $normalized = str_replace(',', '.', $normalized);
        } else {
            if (substr_count($normalized, '.') > 1) {
                $lastDotPos = strrpos($normalized, '.');
                $intPart = str_replace('.', '', substr($normalized, 0, $lastDotPos));
                $decimalPart = substr($normalized, $lastDotPos + 1);
                $normalized = $intPart . '.' . $decimalPart;
            }
        }

        if (!is_numeric($normalized)) {
            $this->addError($field, 'Valor inválido.');
            return null;
        }

        return (int) round(((float) $normalized) * 100);
    }

    private function parseFlexibleDateInput($value, string $field): ?Carbon
    {
        $raw = trim((string) $value);

        if ($raw === '') {
            return null;
        }

        $formats = [
            'Y-m-d\TH:i:s',
            'Y-m-d\TH:i',
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'Y-m-d',
            'd/m/Y H:i:s',
            'd/m/Y H:i',
            'd/m/Y',
        ];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $raw);
                if ($date !== false) {
                    return $date;
                }
            } catch (\Throwable $e) {
                // tenta próximo formato
            }
        }

        try {
            return Carbon::parse($raw);
        } catch (\Throwable $e) {
            $this->addError($field, 'Data inválida.');
            return null;
        }
    }

    private function generateManualOrderControl(): string
    {
        do {
            $control = 'CMP' . Str::upper(Str::random(8)) . '-M';
        } while (\App\Models\ModCampaign\CampaignOrder::where('order_control', $control)->exists());

        return $control;
    }

    private function redirectToTab(string $tab)
    {
        return redirect()->route($this->getTabRouteName($tab), ['campaign_id' => $this->campaign_id]);
    }

    private function getTabRouteName(string $tab): string
    {
        return match ($tab) {
            self::TAB_DETALHES => 'dashboard-campanhas-detalhes-detalhes',
            self::TAB_ADESOES => 'dashboard-campanhas-detalhes-adesoes',
            self::TAB_PARTICIPANTES => 'dashboard-campanhas-detalhes-participantes',
            self::TAB_QUESTIONARIOS => 'dashboard-campanhas-detalhes-questionarios',
            self::TAB_TRANSACOES => 'dashboard-campanhas-detalhes-transacoes',
            default => 'dashboard-campanhas-detalhes',
        };
    }

    private function normalizeTab($tab): ?string
    {
        if (!is_string($tab) || trim($tab) === '') {
            return null;
        }

        $tab = strtolower(trim($tab));

        $aliases = [
            'analitico' => self::TAB_ANALITICOS,
            'analiticos' => self::TAB_ANALITICOS,
            'detalhes' => self::TAB_DETALHES,
            'adesoes' => self::TAB_ADESOES,
            'participantes' => self::TAB_PARTICIPANTES,
            'questionarios' => self::TAB_QUESTIONARIOS,
            'transacao' => self::TAB_TRANSACOES,
            'transacoes' => self::TAB_TRANSACOES,
            'transações' => self::TAB_TRANSACOES,
        ];

        return $aliases[$tab] ?? null;
    }

    private function getTabFromRouteName($routeName): ?string
    {
        if (!is_string($routeName) || trim($routeName) === '') {
            return null;
        }

        return match ($routeName) {
            'dashboard-campanhas-detalhes' => self::TAB_ANALITICOS,
            'dashboard-campanhas-detalhes-detalhes' => self::TAB_DETALHES,
            'dashboard-campanhas-detalhes-adesoes',
            'dashboard-campanhas-detalhes-adesoes-item' => self::TAB_ADESOES,
            'dashboard-campanhas-detalhes-participantes' => self::TAB_PARTICIPANTES,
            'dashboard-campanhas-detalhes-questionarios' => self::TAB_QUESTIONARIOS,
            'dashboard-campanhas-detalhes-transacoes',
            'dashboard-campanhas-detalhes-transacoes-item',
            'dashboard-campanhas-detalhes-transacoes-acento',
            'dashboard-campanhas-detalhes-transacoes-item-acento' => self::TAB_TRANSACOES,
            default => null,
        };
    }
}
