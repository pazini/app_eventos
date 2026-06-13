<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModCampaign\Campaign;
use App\Models\ModCampaign\CampaignOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampaignApiController extends Controller
{
    /**
     * Lista campanhas (admin)
     */
    public function index(Request $request)
    {
        $customer = sessionCustomer();

        if (! $customer) {
            return response()->json(['error' => 'Cliente não identificado na sessão.'], 400);
        }

        $campaigns = Campaign::where('customer_id', $customer->id)
            ->with(['customer', 'organization'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($campaigns);
    }

    /**
     * Mostra uma campanha pública (por slug hierárquico)
     */
    public function show($customer_organization_slug, $campaign_slug)
    {
        $campaign = $this->findCampaignByPublicUrl($customer_organization_slug, $campaign_slug, ['active']);

        if (!$campaign) {
            abort(404);
        }

        return response()->json($campaign);
    }

    /**
     * Resumo público da campanha (detalhes + financeiro sumarizado)
     */
    public function summary(Request $request, $customer_organization_slug, $campaign_slug)
    {
        $campaign = $this->findCampaignByPublicUrl($customer_organization_slug, $campaign_slug, ['active']);

        if (! $campaign) {
            abort(404);
        }

        $paidOrderStatuses = function_exists('listOrderStatusPaid')
            ? listOrderStatusPaid()
            : ['paid'];

        $pendingOrderStatuses = function_exists('listOrderStatusPendente')
            ? listOrderStatusPendente()
            : ['pending'];

        $paidPaymentStatuses = function_exists('listPaymentStatusPaid')
            ? listPaymentStatusPaid()
            : ['paid'];

        $ordersQuery = CampaignOrder::query()->where('campaign_id', $campaign->id);

        $ordersTotal = (clone $ordersQuery)->count();
        $ordersPaid = (clone $ordersQuery)->whereIn('status', $paidOrderStatuses)->count();
        $ordersPending = (clone $ordersQuery)->whereIn('status', $pendingOrderStatuses)->count();
        $ordersByStatus = (clone $ordersQuery)
            ->selectRaw("COALESCE(NULLIF(status, ''), 'undefined') as status_group")
            ->selectRaw('COUNT(*) as qty')
            ->groupByRaw("COALESCE(NULLIF(status, ''), 'undefined')")
            ->orderBy('status_group')
            ->get()
            ->map(function ($row) {
                return [
                    'status' => $row->status_group,
                    'qty' => (int) $row->qty,
                ];
            })
            ->values();
        $ordersOther = max(0, $ordersTotal - $ordersPaid - $ordersPending);
        $paidOrdersAmountTotal = (int) ((clone $ordersQuery)->whereIn('status', $paidOrderStatuses)->sum('amount_paid') ?? 0);
        $averageTicket = $ordersPaid > 0
            ? (int) round($paidOrdersAmountTotal / $ordersPaid)
            : 0;

        $paidOrders = CampaignOrder::query()
            ->where('campaign_id', $campaign->id)
            ->whereIn('status', $paidOrderStatuses)
            ->with(['campaignPayments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->get();

        $paidOrdersByPaymentType = $paidOrders
            ->groupBy(function (CampaignOrder $order) use ($paidPaymentStatuses) {
                return $this->resolvePaidOrderPaymentType($order, $paidPaymentStatuses);
            })
            ->map(function ($orders, $paymentType) {
                return [
                    'payment_type' => $paymentType,
                    'qty' => $orders->count(),
                    'total_amount' => $this->formatMoneyNoSymbol((int) $orders->sum('amount_paid')),
                ];
            })
            ->sortBy('payment_type')
            ->values();

        $ordersTimeline = (clone $ordersQuery)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function (CampaignOrder $order) use ($paidOrderStatuses) {
                $isPaid = in_array($order->status, $paidOrderStatuses, true);

                return [
                    'datetime' => optional($order->created_at)->format('Y-m-d H:i:s'),
                    'locator' => $order->order_control,
                    'amount' => $this->formatMoneyNoSymbol((int) $order->amount_total),
                    'status' => $order->status,
                    'paid_amount' => $isPaid
                        ? $this->formatMoneyNoSymbol((int) $order->amount_paid)
                        : null,
                ];
            })
            ->values();

        $publicUrl = function_exists('campanhaUrl')
            ? campanhaUrl($campaign->customer_organization_slug, $campaign->slug)
            : url('/' . $campaign->customer_organization_slug . '/' . $campaign->slug);

        return response()->json([
            'campaign_summary' => [
                'name' => $campaign->name,
                'slug' => $campaign->slug,
                'status' => $campaign->status,
                'public_url' => $publicUrl,
                'organizer' => [
                    'name' => optional($campaign->organizer)->organizer_name,
                    'name_full' => optional($campaign->organizer)->organizer_name_full,
                ],
                'details' => [
                    'description' => (string) ($campaign->description ?? ''),
                    'about' => (string) ($campaign->about ?? ''),
                    'datetime_start' => optional($campaign->datetime_start)->format('Y-m-d H:i:s'),
                    'datetime_finish' => optional($campaign->datetime_finish)->format('Y-m-d H:i:s'),
                    'created_at' => optional($campaign->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => optional($campaign->updated_at)->format('Y-m-d H:i:s'),
                ],
                'goals' => [
                    'goal_amount' => $this->formatMoneyNoSymbol((int) ($campaign->goal_amount ?? 0)),
                    'goal_leads' => (int) ($campaign->goal_leads ?? 0),
                    'goal_conversions' => (int) ($campaign->goal_conversions ?? 0),
                    'amount_min' => $this->formatMoneyNoSymbol((int) ($campaign->amount_min ?? 0)),
                ],
                'settings' => [
                    'show_goal_amount' => (bool) $campaign->show_goal_amount,
                    'show_goal_leads' => (bool) $campaign->show_goal_leads,
                    'show_goal_conversions' => (bool) $campaign->show_goal_conversions,
                    'show_progress' => (bool) $campaign->show_progress,
                    'enable_questions' => (bool) $campaign->enable_questions,
                    'require_doc' => (bool) $campaign->require_doc,
                    'allow_anonymous' => (bool) $campaign->allow_anonymous,
                    'allow_recurring' => (bool) $campaign->allow_recurring,
                ],
            ],
            'financial_summary' => [
                'orders_summary' => [
                    'total_qty' => $ordersTotal,
                    'paid_qty' => $ordersPaid,
                    'pending_qty' => $ordersPending,
                    'other_qty' => $ordersOther,
                    'by_status' => $ordersByStatus,
                ],
                'paid_donations_summary' => [
                    'qty' => $ordersPaid,
                    'total_amount' => $this->formatMoneyNoSymbol($paidOrdersAmountTotal),
                    'average_ticket' => $this->formatMoneyNoSymbol($averageTicket),
                    'by_payment_type' => $paidOrdersByPaymentType,
                ],
                'orders_timeline' => $ordersTimeline,
            ],
        ]);
    }

    /**
     * Cria uma campanha (admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tbc_campaign,slug',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,paused,finished,cancelled',
        ]);

        $customer = sessionCustomer();

        if (! $customer) {
            return response()->json(['error' => 'Cliente não identificado.'], 400);
        }

        $validated['customer_id'] = $customer->id;
        $campaign = Campaign::create($validated);

        return response()->json($campaign, 201);
    }

    /**
     * Atualiza uma campanha (admin)
     */
    public function update(Request $request, $campaign_id)
    {
        $customer = sessionCustomer();

        if (! $customer) {
            return response()->json(['error' => 'Cliente não identificado.'], 400);
        }

        $campaign = Campaign::where('customer_id', $customer->id)
            ->findOrFail($campaign_id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:tbc_campaign,slug,' . $campaign_id,
            'description' => 'nullable|string',
            'status' => 'sometimes|in:draft,active,paused,finished,cancelled',
        ]);

        $campaign->update($validated);

        return response()->json($campaign);
    }

    /**
     * Deleta/arquiva uma campanha (admin)
     */
    public function destroy($campaign_id)
    {
        $customer = sessionCustomer();

        if (! $customer) {
            return response()->json(['error' => 'Cliente não identificado.'], 400);
        }

        $campaign = Campaign::where('customer_id', $customer->id)
            ->findOrFail($campaign_id);

        $campaign->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Campanha arquivada com sucesso.']);
    }

    /**
     * Retorna métricas de uma campanha (admin)
     */
    public function metrics($campaign_id)
    {
        $customer = sessionCustomer();

        if (! $customer) {
            return response()->json(['error' => 'Cliente não identificado.'], 400);
        }

        $campaign = Campaign::where('customer_id', $customer->id)
            ->with('metrics')
            ->findOrFail($campaign_id);

        return response()->json($campaign->metrics);
    }

    /**
     * Retorna transações de uma campanha (admin)
     */
    public function transactions($campaign_id)
    {
        $customer = sessionCustomer();

        if (! $customer) {
            return response()->json(['error' => 'Cliente não identificado.'], 400);
        }

        $campaign = Campaign::where('customer_id', $customer->id)
            ->with('transactions')
            ->findOrFail($campaign_id);

        return response()->json($campaign->transactions);
    }

    /**
     * Retorna pedidos de uma campanha (admin)
     */
    public function orders($campaign_id)
    {
        $customer = sessionCustomer();

        if (! $customer) {
            return response()->json(['error' => 'Cliente não identificado.'], 400);
        }

        $campaign = Campaign::where('customer_id', $customer->id)
            ->with('orders')
            ->findOrFail($campaign_id);

        return response()->json($campaign->orders);
    }

    /**
     * Cria um pedido para uma campanha (público)
     */
    public function createOrder(Request $request, $customer_organization_slug, $campaign_slug)
    {
        $campaign = $this->findCampaignByPublicUrl($customer_organization_slug, $campaign_slug, ['active']);

        if (!$campaign) {
            abort(404);
        }

        $validated = $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email|max:255',
            'buyer_doc_num' => 'nullable|string|max:20',
            'buyer_contact_country' => 'nullable|string|max:5',
            'buyer_contact_ddd' => 'nullable|string|max:3',
            'buyer_contact_num' => 'nullable|string|max:15',
            'amount_total' => 'required|numeric|min:0',
            'metadata' => 'nullable|array',
        ]);

        $validated['campaign_id'] = $campaign->id;
        $validated['order_control'] = Str::upper(Str::random(8));
        $validated['status'] = 'pending';
        $sanitizedContactCountry = !empty($validated['buyer_contact_country'])
            ? preg_replace('/[^0-9]/', '', (string) $validated['buyer_contact_country'])
            : '';
        $validated['buyer_contact_country'] = $sanitizedContactCountry !== ''
            ? substr($sanitizedContactCountry, 0, 5)
            : '55';

        $order = CampaignOrder::create($validated);

        return response()->json($order, 201);
    }

    private function findCampaignByPublicUrl(string $customerOrganizationSlug, string $campaignSlug, array $allowedStatuses): ?Campaign
    {
        $campaign = Campaign::where('customer_organization_slug', $customerOrganizationSlug)
            ->where('slug', $campaignSlug)
            ->where('visibility_public', true)
            ->whereIn('status', $allowedStatuses)
            ->with(['customer', 'organization', 'organizer'])
            ->first();

        if ($campaign) {
            return $campaign;
        }

        $legacyCandidates = Campaign::where('slug', $campaignSlug)
            ->where('visibility_public', true)
            ->whereIn('status', $allowedStatuses)
            ->with(['customer', 'organization', 'organizer'])
            ->get();

        return $legacyCandidates->first(function ($item) use ($customerOrganizationSlug) {
            $legacySlugs = collect([
                $item->customer_organization_slug,
                $item->organizer?->organizer_slug,
                $item->organizer?->organizer_name,
                $item->organization?->organization_slug,
                $item->organization?->organization_name,
            ])
                ->filter()
                ->map(fn ($value) => Str::slug($value))
                ->unique();

            return $legacySlugs->contains(Str::slug($customerOrganizationSlug));
        });
    }

    /**
     * Consulta um pedido pelo order_control (público)
     */
    public function getOrder($order_control)
    {
        $order = CampaignOrder::where('order_control', $order_control)
            ->with(['campaign', 'transactions'])
            ->firstOrFail();

        return response()->json($order);
    }

    private function formatMoneyNoSymbol(int $amount): string
    {
        if (function_exists('toMoney')) {
            return toMoney($amount);
        }

        return number_format($amount / 100, 2, ',', '.');
    }

    private function resolvePaidOrderPaymentType(CampaignOrder $order, array $paidPaymentStatuses): string
    {
        $payments = $order->campaignPayments->sortByDesc('created_at');

        $paidPayment = $payments->first(function ($payment) use ($paidPaymentStatuses) {
            return in_array($payment->status, $paidPaymentStatuses, true);
        });

        $payment = $paidPayment ?: $payments->first();

        return strtolower($payment->pay_type ?? 'undefined');
    }
}
