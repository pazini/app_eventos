<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModCampaign\Campaign;
use App\Models\ModCampaign\CampaignOrder;
use App\Models\ModCampaign\CampaignPaymentWebhook;
use App\Models\ModCampaign\CampaignPayment;
use App\Models\ModCampaign\CampaignSubscription;
use App\Models\ModCampaign\CampaignSubscriptionCycle;
use App\Services\CampaignEmailService;
use App\Services\Campaign\CampaignPaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CampaignWebhookController extends Controller
{
    /**
     * Recebe e processa webhook do Safe2Pay para campanhas
     *
     * @param Request $request
     * @param string|null $orderId ID do pedido (CampaignOrder)
     * @param string|null $paymentId ID do pagamento (CampaignPayment)
     */
    public function handleSafe2PayWebhook(Request $request, $orderId, $paymentId,$webhookId=false)
    {
        try
        {
            $payload = $request->all();

            Log::info('Safe2Pay Webhook recebido [Campanhas]', [
                'payload' => $payload,
                'orderId' => $orderId,
                'paymentId' => $paymentId,
            ]);

            // Prepara dados do webhook
            $data = [
                'gateway_slug'            => 'safe2pay',
                'webhook_type'            => 'payment',
                'external_transaction_id' => $payload['IdTransaction'] ?? null,
                'reference'               => $payload['Reference'] ?? null,
                'status'                  => $this->normalizeSafe2PayStatus($payload['TransactionStatus']['Id'] ?? null),
                'amount'                  => convertDecimalInt($payload['Amount'] ?? 0),
                'payload'                 => array_merge($payload, [
                    'orderId'   => $orderId,
                    'paymentId' => $paymentId,
                ]),
                'processing_status'   => 'pending',
                'campaign_order_id'   => $orderId ?: null,
                'campaign_payment_id' => $paymentId ?: null,
            ];

            if($webhookId ?? false)
            {
                // Salva webhook raw
                $webhook = CampaignPaymentWebhook::find($webhookId);
                $webhook->update($data);
                $webhookMsg = 'Webhook atualizado com ID existente: ' . $webhookId;
            }
            else
            {
                // Salva webhook raw
                $webhook = CampaignPaymentWebhook::create($data);
                $webhookMsg = 'Webhook criado com novo ID: ' . $webhook->id;
            }

            // Processa o webhook
            $this->processWebhook($webhook, $orderId, $paymentId);

            return response()->json(['message' => $webhookMsg], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao processar Safe2Pay Webhook [Campanhas]: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao processar webhook.'], 500);
        }
    }

    /**
     * Processa o webhook e atualiza orders/payments
     *
     * @param CampaignPaymentWebhook $webhook
     * @param string|null $orderId ID do pedido da URL
     * @param string|null $paymentId ID do pagamento da URL
     */
    protected function processWebhook(CampaignPaymentWebhook $webhook, $orderId = null, $paymentId = null)
    {
        try {
            $payload = $webhook->payload;

            // Prioriza orderId da URL, senão busca pela referência (order_control)
            if ($orderId) {
                $order = CampaignOrder::find($orderId);
            } else {
                $order = CampaignOrder::where('order_control', $webhook->reference)->first();
            }

            if (! $order) {
                $webhook->update([
                    'processing_status' => 'error',
                    'processing_error' => 'Pedido não encontrado. OrderId: ' . ($orderId ?? 'null') . ' | Referência: ' . ($webhook->reference ?? 'null'),
                ]);
                return;
            }

            // Atualiza webhook com IDs encontrados
            $webhook->update([
                'campaign_id' => $order->campaign_id,
                'campaign_order_id' => $order->id,
            ]);

            // Buscar payment
            $campaignPayment = null;

            // 1. Tenta buscar por paymentId (se fornecido na URL)
            if ($paymentId) {
                $campaignPayment = CampaignPayment::find($paymentId);
            }
            elseif ($webhook->campaign_payment_id) {
                $campaignPayment = CampaignPayment::find($webhook->campaign_payment_id);
            }

            // 2. Se não encontrou, busca por NSU ou transaction_id no pedido
            if (!$campaignPayment && $webhook->external_transaction_id) {
                $campaignPayment = CampaignPayment::where('campaign_order_id', $order->id)
                    ->where(function($query) use ($webhook) {
                        $query->where('pay_nsu', $webhook->external_transaction_id)
                              ->orWhere('pay_transaction_id', $webhook->external_transaction_id);
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();
            }

            // 3. Se ainda não encontrou, pega o payment mais recente do pedido
            if (!$campaignPayment) {
                $campaignPayment = $order->campaignPayments()->orderBy('created_at', 'desc')->first();
            }

            if (!$campaignPayment) {
                $webhook->update([
                    'processing_status' => 'error',
                    'processing_error' => 'Nenhum pagamento encontrado para este pedido. OrderId: ' . $order->id,
                ]);

                Log::warning('Webhook recebido mas CampaignPayment não encontrado', [
                    'webhook_id' => $webhook->id,
                    'order_id' => $order->id,
                    'external_transaction_id' => $webhook->external_transaction_id,
                    'payment_id_url' => $paymentId,
                ]);

                return;
            }

            // Atualiza webhook com payment_id encontrado
            $webhook->update([
                'campaign_payment_id' => $campaignPayment->id,
            ]);

            // Atualiza status do payment baseado no webhook
            $paymentStatus = $this->mapWebhookStatusToPaymentStatus($webhook->status);

            $campaignPayment->update([
                'status' => $paymentStatus,
                'status_old' => $campaignPayment->status,
                'pay_datetime' => ($webhook->status === 'paid') ? now() : null,
                'pay_json_response' => array_merge(
                    $campaignPayment->pay_json_response ?? [],
                    ['webhook' => $webhook->payload]
                ),
            ]);

            // Se foi pago, marca como pago usando o service
            if ($webhook->status === 'paid') {
                $paymentService = new CampaignPaymentService();
                $paymentService->markAsPaid($campaignPayment, [
                    'datahora' => now(),
                    'pagamento_valor' => $webhook->amount, // Já em centavos
                ]);

                $this->syncRecurringPaid($campaignPayment);

                // Envia e-mails de confirmação
                CampaignEmailService::enviarNotificacaoPagamentoAprovado($order);
                CampaignEmailService::enviarComprovanteParticipacao($order);

            } elseif (in_array($webhook->status, ['refused', 'cancelled'])) {
                $paymentService = new CampaignPaymentService();
                $paymentService->markAsFailed($campaignPayment, $webhook->status, strtoupper($webhook->status));
                $this->syncRecurringFailed($campaignPayment, strtoupper($webhook->status));

            } elseif ($webhook->status === 'refunded') {
                $campaignPayment->update([
                    'status' => 'refunded',
                    'status_old' => $campaignPayment->status,
                ]);

                // Atualiza order
                $order->update([
                    'status' => 'refunded',
                ]);
            }

            $webhook->update([
                'processed_at' => now(),
                'processing_status' => 'processed',
            ]);

        } catch (\Exception $e) {
            $webhook->update([
                'processing_status' => 'error',
                'processing_error' => $e->getMessage(),
            ]);

            Log::error('Erro ao processar webhook [Campanhas] ID: ' . $webhook->id . ' - ' . $e->getMessage(), [
                'webhook_id' => $webhook->id,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    private function syncRecurringPaid(CampaignPayment $payment): void
    {
        if (!$payment->subscription_cycle_id) {
            return;
        }

        $cycle = CampaignSubscriptionCycle::find($payment->subscription_cycle_id);
        if (!$cycle) {
            return;
        }

        $subscription = $cycle->subscription;
        if (!$subscription) {
            return;
        }

        if ($cycle->status !== 'paid') {
            $cycle->update([
                'status' => 'paid',
                'paid_at' => now(),
                'next_attempt_at' => null,
            ]);
        }

        $billingDate = Carbon::parse($cycle->billing_date);
        $subscription->update([
            'status' => 'active',
            'current_cycle' => max($subscription->current_cycle ?? 0, $cycle->cycle_number),
            'last_charge_at' => now(),
            'next_charge_at' => $billingDate->copy()->addMonthNoOverflow(),
            'error_at' => null,
            'error_message' => null,
        ]);
    }

    private function syncRecurringFailed(CampaignPayment $payment, string $errorMessage): void
    {
        if (!$payment->subscription_cycle_id) {
            return;
        }

        $cycle = CampaignSubscriptionCycle::find($payment->subscription_cycle_id);
        if (!$cycle || $cycle->status === 'paid') {
            return;
        }

        $attemptsCount = $cycle->attempts_count ?? 0;
        $nextAttemptAt = $this->getNextAttemptAt($cycle->billing_date, $attemptsCount);

        $cycle->update([
            'next_attempt_at' => $nextAttemptAt,
            'error_message' => $errorMessage,
        ]);

        if ($nextAttemptAt) {
            return;
        }

        $cycle->update(['status' => 'failed']);

        $subscription = $cycle->subscription;
        if ($subscription) {
            $subscription->update([
                'status' => 'error_disabled',
                'error_at' => now(),
                'error_message' => $errorMessage,
            ]);
        }
    }

    private function getNextAttemptAt(string $billingDate, int $attemptsCount): ?Carbon
    {
        $offsets = [0, 1, 5, 7, 15, 30];
        if ($attemptsCount >= count($offsets)) {
            return null;
        }

        return Carbon::parse($billingDate)->startOfDay()->addDays($offsets[$attemptsCount]);
    }

    /**
     * Normaliza status do Safe2Pay para nosso padrão
     */
    protected function normalizeSafe2PayStatus($status)
    {
        $statusMap = [
            1 => 'pending',      // Aguardando pagamento
            2 => 'processing',   // Em processamento
            3 => 'paid',         // Pago
            4 => 'paid',         // Disponível (pago e liberado)
            5 => 'dispute',      // Em disputa
            6 => 'refunded',     // Devolvido
            7 => 'cancelled',    // Cancelado
            8 => 'refused',      // Recusado
            9 => 'chargeback',   // Chargeback
        ];

        return $statusMap[$status] ?? 'pending-default';
    }

    /**
     * Mapeia status do webhook para status do CampaignPayment
     */
    protected function mapWebhookStatusToPaymentStatus($webhookStatus)
    {
        $statusMap = [
            'paid' => 'paid',
            'pending' => 'processing',
            'processing' => 'processing',
            'refused' => 'failed',
            'cancelled' => 'cancelled',
            'refunded' => 'refunded',
            'dispute' => 'dispute',
            'chargeback' => 'chargeback',
        ];

        return $statusMap[$webhookStatus] ?? 'processing';
    }
}
