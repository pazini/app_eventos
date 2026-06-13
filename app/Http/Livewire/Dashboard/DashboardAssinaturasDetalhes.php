<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Customer;
use App\Models\CustomerPayGateway;
use App\Models\ModSubscription\Product;
use App\Models\ModSubscription\ProductPlan;
use App\Models\ModSubscription\Subscription;
use App\Models\ModSubscription\SubscriptionCycle;
use App\Services\ModuleAccessService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Exceptions\HttpResponseException;
use Livewire\Component;

class DashboardAssinaturasDetalhes extends Component
{
    public $productId;
    public $product;
    public $gateway;
    public $gatewayMap = [];
    public $plans;
    public $subscriptions;
    public $stats = [];
    public $activeTab = 'analiticos';
    public $metricsLast30Days = [];
    public $periodComparison = [];
    public $chartData = [];
    public $filterTransactionStatus = '';
    public $filterTransactionDateFrom = '';
    public $filterTransactionDateTo = '';
    public $filterTransactionSearch = '';
    public $selectedTransactionId = null;
    public $selectedTransaction = null;

    public function mount(string $product_id)
    {
        $this->productId = $product_id;
        $this->loadProduct();

        $savedTab = session('subscription_details_tab');
        if (is_string($savedTab) && $savedTab !== '') {
            $this->activeTab = $savedTab;
        }

        if (request()->get('export') === 'transacoes') {
            throw new HttpResponseException($this->exportTransacoes());
        }
    }

    protected function loadProduct(): void
    {
        $this->product = Product::with(['customer', 'organizer'])->findOrFail($this->productId);
        $this->gateway = null;

        if (auth()->check()) {
            $user = auth()->user();

            if (! ModuleAccessService::userIsAppAdmin($user)) {
                $customer = $this->product->customer ?: Customer::find($this->product->customer_id);
                if ($customer && ! ModuleAccessService::userCanAccessSubscriptions($user, $customer)) {
                    abort(403, 'Você não tem permissão para acessar esta assinatura.');
                }
            }
        }

        if ($this->product->customer_id) {
            sessionCustomer($this->product->customer_id);
        }

        if ($this->product->pay_gateway_id) {
            $this->gateway = CustomerPayGateway::where('id', $this->product->pay_gateway_id)
                ->where('customer_id', $this->product->customer_id)
                ->first();
        }

        $gateways = CustomerPayGateway::where('customer_id', $this->product->customer_id)->get();
        $this->gatewayMap = $gateways->mapWithKeys(function ($gateway) {
            return [
                $gateway->id => [
                    'label' => $gateway->pay_gateway_label,
                    'description' => $gateway->pay_gateway_description,
                ],
            ];
        })->toArray();

        $this->plans = ProductPlan::where('product_id', $this->product->id)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();

        $this->subscriptions = Subscription::where('product_id', $this->product->id)
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $this->stats = [
            'plans' => $this->plans->count(),
            'subscriptions' => Subscription::where('product_id', $this->product->id)->count(),
            'subscriptions_active' => Subscription::where('product_id', $this->product->id)->where('status', 'active')->count(),
            'mrr' => (int) Subscription::where('product_id', $this->product->id)->where('status', 'active')->sum('amount_total'),
        ];

        $this->loadAnalytics();
    }

    public function render()
    {
        return view('livewire.dashboard.assinaturas-detalhes')
            ->layout('layouts.app-pep-auth');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        session(['subscription_details_tab' => $tab]);

        if ($tab !== 'transacoes') {
            $this->selectedTransactionId = null;
            $this->selectedTransaction = null;
        }
    }

    public function getFilteredTransactions()
    {
        $query = SubscriptionCycle::query()
            ->whereHas('subscription', function ($subQuery) {
                $subQuery->where('product_id', $this->product->id);
            })
            ->with(['subscription.plan']);

        if ($this->filterTransactionStatus) {
            $query->where('status', $this->filterTransactionStatus);
        }

        if ($this->filterTransactionDateFrom) {
            $query->whereDate('billing_date', '>=', $this->filterTransactionDateFrom);
        }

        if ($this->filterTransactionDateTo) {
            $query->whereDate('billing_date', '<=', $this->filterTransactionDateTo);
        }

        if ($this->filterTransactionSearch) {
            $search = $this->filterTransactionSearch;
            $query->where(function ($inner) use ($search) {
                $inner->where('subscription_id', 'ilike', '%' . $search . '%')
                    ->orWhereHas('subscription', function ($subQuery) use ($search) {
                        $subQuery->where('buyer_id', 'ilike', '%' . $search . '%')
                            ->orWhere('id', 'ilike', '%' . $search . '%')
                            ->orWhereHas('plan', function ($planQuery) use ($search) {
                                $planQuery->where('plan_name', 'ilike', '%' . $search . '%');
                            });
                    });
            });
        }

        return $query->orderBy('billing_date', 'desc')->orderBy('created_at', 'desc')->get();
    }

    public function selectTransaction(string $transactionId): void
    {
        $this->selectedTransactionId = $transactionId;
        $this->selectedTransaction = SubscriptionCycle::with(['subscription.plan'])
            ->where('id', $transactionId)
            ->whereHas('subscription', function ($subQuery) {
                $subQuery->where('product_id', $this->product->id);
            })
            ->first();
    }

    public function closeTransactionDetails(): void
    {
        $this->selectedTransactionId = null;
        $this->selectedTransaction = null;
    }

    public function refreshTransacoes(): void
    {
        $this->loadProduct();
    }

    public function exportTransacoes()
    {
        $transactions = $this->getFilteredTransactions();
        $filename = 'transacoes_assinatura_' . $this->product->slug . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'ID',
                'Assinante',
                'Plano',
                'Ciclo',
                'Status',
                'Valor',
                'Data Cobrança',
                'Pago em',
                'Tentativas',
            ], ';');

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    substr($transaction->id, 0, 8),
                    optional($transaction->subscription)->buyer_id ?? '',
                    optional(optional($transaction->subscription)->plan)->plan_name ?? '',
                    $transaction->cycle_number ?? '',
                    $transaction->status ?? '',
                    number_format(((int) (optional($transaction->subscription)->amount_total ?? 0)) / 100, 2, ',', '.'),
                    $transaction->billing_date ? $transaction->billing_date->format('d/m/Y') : '',
                    $transaction->paid_at ? $transaction->paid_at->format('d/m/Y H:i:s') : '',
                    $transaction->attempts_count ?? 0,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function loadAnalytics(): void
    {
        $end = Carbon::now()->endOfDay();
        $start = Carbon::now()->subDays(29)->startOfDay();
        $previousStart = (clone $start)->subDays(30)->startOfDay();
        $previousEnd = (clone $start)->subDay()->endOfDay();

        $baseQuery = Subscription::where('product_id', $this->product->id);

        $currentRevenue = (int) (clone $baseQuery)->whereBetween('created_at', [$start, $end])->sum('amount_total');
        $previousRevenue = (int) (clone $baseQuery)->whereBetween('created_at', [$previousStart, $previousEnd])->sum('amount_total');

        $currentNew = (clone $baseQuery)->whereBetween('created_at', [$start, $end])->count();
        $previousNew = (clone $baseQuery)->whereBetween('created_at', [$previousStart, $previousEnd])->count();

        $currentCanceled = (clone $baseQuery)->whereBetween('canceled_at', [$start, $end])->count();
        $previousCanceled = (clone $baseQuery)->whereBetween('canceled_at', [$previousStart, $previousEnd])->count();

        $currentChanges = $this->countPlanChanges((clone $baseQuery), $start, $end);
        $previousChanges = $this->countPlanChanges((clone $baseQuery), $previousStart, $previousEnd);

        $this->metricsLast30Days = [
            'revenue' => $currentRevenue,
            'new' => $currentNew,
            'canceled' => $currentCanceled,
            'changed' => $currentChanges,
        ];

        $this->periodComparison = [
            'revenue' => $this->buildComparison($currentRevenue, $previousRevenue),
            'new' => $this->buildComparison($currentNew, $previousNew),
            'canceled' => $this->buildComparison($currentCanceled, $previousCanceled),
            'changed' => $this->buildComparison($currentChanges, $previousChanges),
        ];

        $this->chartData = $this->buildChartData($start, $end);
    }

    protected function countPlanChanges($query, Carbon $start, Carbon $end): int
    {
        $candidates = $query
            ->whereBetween('updated_at', [$start, $end])
            ->whereNotNull('metadata')
            ->get(['metadata']);

        return $candidates->filter(function ($subscription) {
            $metadata = $subscription->metadata ?? [];
            return data_get($metadata, 'previous_plan_id')
                || data_get($metadata, 'plan_changed_at')
                || data_get($metadata, 'plan_change');
        })->count();
    }

    protected function buildComparison(int $current, int $previous): array
    {
        $percent = 0;
        if ($previous > 0) {
            $percent = round((($current - $previous) / $previous) * 100, 1);
        } elseif ($current > 0) {
            $percent = 100;
        }

        return [
            'previous' => $previous,
            'percent' => $percent,
        ];
    }

    protected function buildChartData(Carbon $start, Carbon $end): array
    {
        $labels = [];
        $dailyRevenue = [];
        $dailyNew = [];
        $dailyCanceled = [];

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $key = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');
            $dailyRevenue[$key] = 0;
            $dailyNew[$key] = 0;
            $dailyCanceled[$key] = 0;
        }

        $subscriptionsRecent = Subscription::where('product_id', $this->product->id)
            ->whereBetween('created_at', [$start, $end])
            ->get(['created_at', 'amount_total']);

        foreach ($subscriptionsRecent as $subscription) {
            $key = $subscription->created_at->format('Y-m-d');
            if (array_key_exists($key, $dailyRevenue)) {
                $dailyRevenue[$key] += (int) $subscription->amount_total;
                $dailyNew[$key] += 1;
            }
        }

        $canceledRecent = Subscription::where('product_id', $this->product->id)
            ->whereBetween('canceled_at', [$start, $end])
            ->get(['canceled_at']);

        foreach ($canceledRecent as $subscription) {
            $key = $subscription->canceled_at->format('Y-m-d');
            if (array_key_exists($key, $dailyCanceled)) {
                $dailyCanceled[$key] += 1;
            }
        }

        return [
            'labels' => $labels,
            'revenue' => array_values($dailyRevenue),
            'new' => array_values($dailyNew),
            'canceled' => array_values($dailyCanceled),
        ];
    }
}
