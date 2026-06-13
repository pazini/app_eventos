<?php

namespace App\Console\Commands;

use App\Models\AppBuyers;
use App\Models\ModCampaign\CampaignOrder;
use App\Models\ModCampaign\CampaignPaymentAttempt;
use App\Models\ModCampaign\CampaignSubscription;
use App\Models\ModCampaign\CampaignSubscriptionCycle;
use App\Services\Campaign\CampaignPaymentService;
use App\Services\safe2pay\Safe2PayService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessCampaignRecurring extends Command
{
    protected $signature = 'campaigns:process-recurring';
    protected $description = 'Processa cobranças recorrentes de campanhas (cartão com token Safe2Pay).';

    private array $attemptOffsets = [0, 1, 5, 7, 15, 30];

    public function handle(): int
    {
        $now = now();

        $this->ensureDueCycles($now);
        $this->processDueAttempts($now);

        return Command::SUCCESS;
    }

    private function ensureDueCycles(Carbon $now): void
    {
        $subscriptions = CampaignSubscription::whereIn('status', ['active', 'paused'])
            ->whereNotNull('next_charge_at')
            ->where('next_charge_at', '<=', $now)
            ->get();

        foreach ($subscriptions as $subscription) {
            if ($subscription->status === 'paused') {
                $subscription->update([
                    'status' => 'active',
                    'paused_at' => null,
                ]);
            }

            $billingDate = Carbon::parse($subscription->next_charge_at)->toDateString();
            $existingCycle = CampaignSubscriptionCycle::where('subscription_id', $subscription->id)
                ->where('billing_date', $billingDate)
                ->first();

            if ($existingCycle) {
                continue;
            }

            CampaignSubscriptionCycle::create([
                'subscription_id' => $subscription->id,
                'cycle_number' => ($subscription->current_cycle ?? 0) + 1,
                'billing_date' => $billingDate,
                'status' => 'pending',
                'next_attempt_at' => Carbon::parse($subscription->next_charge_at),
                'attempts_count' => 0,
            ]);
        }
    }

    private function processDueAttempts(Carbon $now): void
    {
        $cycles = CampaignSubscriptionCycle::where('status', 'pending')
            ->whereNotNull('next_attempt_at')
            ->where('next_attempt_at', '<=', $now)
            ->get();

        foreach ($cycles as $cycle) {
            $subscription = $cycle->subscription;
            if (!$subscription || $subscription->status !== 'active') {
                continue;
            }

            if (!$subscription->card_token) {
                $this->disableSubscriptionByError($subscription, $cycle, 'Token do cartão não encontrado.');
                continue;
            }

            $buyer = $subscription->buyer_id ? AppBuyers::find($subscription->buyer_id) : null;
            if (!$buyer) {
                $this->disableSubscriptionByError($subscription, $cycle, 'Comprador da recorrência não encontrado.');
                continue;
            }

            $order = $cycle->order;
            if (!$order) {
                $order = $this->createRecurringOrder($subscription, $cycle, $buyer);
            }

            $paymentService = new CampaignPaymentService();
            $payment = $paymentService->createPayment($order, [
                'value_paid' => $subscription->amount_total,
                'value_fees' => 0,
                'value_liquid' => $subscription->amount_total,
                'fee_percentage_used' => 0,
                'payment_method' => 'credit_card',
                'installments' => 1,
                'installment_value' => $subscription->amount_total,
                'subscription_id' => $subscription->id,
                'subscription_cycle_id' => $cycle->id,
            ]);

            $attemptNumber = $cycle->attempts_count + 1;

            try {
                $paymentResult = $this->executeRecurringPayment($subscription, $buyer, $order, $payment);

                $attempt = CampaignPaymentAttempt::create([
                    'campaign_id' => $order->campaign_id,
                    'campaign_order_id' => $order->id,
                    'campaign_payment_id' => $payment->id,
                    'pay_type' => 'card_credit',
                    'gateway_slug' => $order->campaign->gateway->pay_gateway_slug ?? 'safe2pay',
                    'status' => 'success',
                    'subscription_id' => $subscription->id,
                    'subscription_cycle_id' => $cycle->id,
                    'attempt_number' => $attemptNumber,
                    'scheduled_at' => $cycle->next_attempt_at,
                    'request_data' => [
                        'payment_method' => 'credit_card',
                        'installments' => 1,
                        'amount' => $subscription->amount_total,
                    ],
                    'response_data' => $paymentResult,
                    'attempted_at' => now(),
                ]);

                if (empty($paymentResult) || ($paymentResult['error'] ?? false)) {
                    $errorMsg = $paymentResult['msg'] ?? 'Erro ao processar pagamento';

                    $payment->update([
                        'status' => 'error',
                        'pay_json_response' => $this->sanitizePaymentData($paymentResult),
                    ]);

                    $attempt->update([
                        'status' => 'error',
                        'error_message' => $errorMsg,
                        'response_data' => $paymentResult,
                    ]);

                    $this->updateCycleAfterFailure($subscription, $cycle, $errorMsg);
                    continue;
                }

                $paymentService->updatePaymentFromGateway($payment, $paymentResult);
                $paymentStatus = $paymentResult['status'] ?? 'processing';

                if (in_array($paymentStatus, ['paid', 'pago', 'approved', 'autorizado', 'captured'])) {
                    $paymentService->markAsPaid($payment, $paymentResult);
                    $this->updateCycleAfterSuccess($subscription, $cycle);
                } elseif (in_array($paymentStatus, ['pending', 'processing', 'pendente'])) {
                    $this->updateCycleAfterPending($cycle);
                }
            } catch (\Throwable $e) {
                Log::error('Erro ao processar recorrência', [
                    'subscription_id' => $subscription->id,
                    'cycle_id' => $cycle->id,
                    'error' => $e->getMessage(),
                ]);

                CampaignPaymentAttempt::create([
                    'campaign_id' => $order->campaign_id,
                    'campaign_order_id' => $order->id,
                    'campaign_payment_id' => $payment->id,
                    'pay_type' => 'card_credit',
                    'gateway_slug' => $order->campaign->gateway->pay_gateway_slug ?? 'safe2pay',
                    'status' => 'error',
                    'subscription_id' => $subscription->id,
                    'subscription_cycle_id' => $cycle->id,
                    'attempt_number' => $attemptNumber,
                    'scheduled_at' => $cycle->next_attempt_at,
                    'error_message' => $e->getMessage(),
                    'request_data' => [
                        'payment_method' => 'credit_card',
                        'installments' => 1,
                        'amount' => $subscription->amount_total,
                    ],
                    'attempted_at' => now(),
                ]);

                $payment->update([
                    'status' => 'error',
                    'pay_json_response' => $this->sanitizePaymentData([
                        'error' => true,
                        'message' => $e->getMessage(),
                    ]),
                ]);

                $this->updateCycleAfterFailure($subscription, $cycle, $e->getMessage());
            }
        }
    }

    private function createRecurringOrder(
        CampaignSubscription $subscription,
        CampaignSubscriptionCycle $cycle,
        AppBuyers $buyer
    ): CampaignOrder {
        $order = CampaignOrder::create([
            'campaign_id' => $subscription->campaign_id,
            'buyer_id' => $buyer->id,
            'order_control' => 'REC' . Str::upper(Str::random(8)),
            'buyer_name' => $buyer->name,
            'buyer_email' => $buyer->email,
            'buyer_doc_num' => $buyer->doc_num,
            'buyer_contact_country' => $buyer->contact_country,
            'buyer_contact_ddd' => $buyer->contact_ddd,
            'buyer_contact_num' => $buyer->contact_num,
            'amount_total' => $subscription->amount_total,
            'amount_paid' => 0,
            'status' => 'pending',
            'is_anonymous' => false,
            'is_recurring' => true,
            'subscription_id' => $subscription->id,
            'subscription_cycle_id' => $cycle->id,
            'metadata' => [
                'is_recurring' => true,
                'cycle_number' => $cycle->cycle_number,
            ],
        ]);

        $cycle->update(['campaign_order_id' => $order->id]);

        return $order;
    }

    private function executeRecurringPayment(
        CampaignSubscription $subscription,
        AppBuyers $buyer,
        CampaignOrder $order,
        $payment
    ): array {
        $gateway = $order->campaign->gateway;
        $gatewaySlug = $gateway->pay_gateway_slug ?? '';
        if (!$gateway || $gatewaySlug === '' || !Str::contains($gatewaySlug, 'safe2pay')) {
            throw new \Exception('Gateway inválido para recorrência.');
        }

        $sandbox = $order->campaign->pay_sandbox ?? false;
        $token = $sandbox ? $gateway->token_test : $gateway->token_live;

        $service = new Safe2PayService($token, $sandbox);

        $service->Application = trim(mb_strtoupper(toSlug($gateway->pay_gateway_slug . '-app_campaign-' . ($sandbox ? "SANDBOX" : "LIVE"), '-')));
        $vendor = $order->campaign->organizer->organizer_name_full
            ?? ($order->campaign->organizer->organizer_name ?? null);
        $service->Vendor = trim(mb_strtoupper(str_replace('//', '|', $vendor ?? 'PROEVENTPAY')));
        $service->CallbackUrl = route('api.campaigns.webhook.safe2pay', [
            'orderId' => $order->id,
            'paymentId' => $payment->id,
        ]);
        $service->Reference = $order->order_control;
        $service->setAplication();

        $service->app_ref = 'app_campaign';
        $service->order_id = $order->id;
        $service->payment_id = $payment->id;
        $service->gateway_id = $gateway->id;
        $service->localizador = $order->order_control;
        $service->order_amount = $payment->value_paid;
        $service->order_amount_discount = 0;
        $service->order_amount_pay = $payment->value_paid;
        $service->is_anonymous = false;
        $service->setMeta();

        $service->Name = $buyer->name;
        $service->Identity = preg_replace('/[^0-9]/', '', $buyer->doc_num ?? '');
        $service->Phone = $this->formatBuyerPhone($buyer);
        $service->Email = strtolower(trim($buyer->email ?? ''));
        $service->setCustomer();

        if ($buyer->zip_code) {
            $service->ZipCode = preg_replace('/[^0-9]/', '', $buyer->zip_code);
            $service->Street = $buyer->address ?? 'Não informado';
            $service->Number = $buyer->address_number ?? 'S/N';
            $service->Complement = $buyer->address_complement;
            $service->District = $buyer->city_neighborhood ?? 'Centro';
            $service->CityName = $buyer->city ?? 'São Paulo';
            $service->StateInitials = $buyer->state ?? 'SP';
            $service->setCustomerAddress();
        } elseif ($order->campaign->customer) {
            $customer = $order->campaign->customer;
            if ($customer->zip_code) {
                $service->ZipCode = preg_replace('/[^0-9]/', '', $customer->zip_code);
                $service->Street = $customer->address ?? 'Não informado';
                $service->Number = $customer->address_number ?? 'S/N';
                $service->Complement = $customer->address_complement;
                $service->District = $customer->city_neighborhood ?? 'Centro';
                $service->CityName = $customer->city ?? 'São Paulo';
                $service->StateInitials = $customer->state ?? 'SP';
                $service->setCustomerAddress();
            } else {
                $service->setCustomerAddress(clear: true);
            }
        } else {
            $service->setCustomerAddress(clear: true);
        }

        $service->appendProducts(
            Code: $order->order_control,
            UnitPrice: ($order->campaign->pay_sandbox ?? false) ? 100 : $payment->value_paid,
            Quantity: 1,
            Description: mb_strtoupper(($order->campaign->pay_sandbox ?? false) ? $order->campaign->name . ' - TESTE' : $order->campaign->name, 'UTF-8')
        );
        $service->setProducts();

        $service->InstallmentQuantity = 1;
        $requestPayload = $service->setPaymentCreditToken($service->Identity, $subscription->card_token);

        $transactionResult = $service->executeTransaction();
        $resultArray = json_decode(json_encode($transactionResult), true) ?: [];

        $payment->update([
            'pay_json_request' => $this->sanitizePaymentData($requestPayload),
            'pay_json_response' => $this->sanitizePaymentData($resultArray),
            'status' => $resultArray['status'] ?? 'processing',
        ]);

        return $resultArray;
    }

    private function formatBuyerPhone(AppBuyers $buyer): ?string
    {
        if (!$buyer->contact_country || !$buyer->contact_ddd || !$buyer->contact_num) {
            return null;
        }

        $phone = $buyer->contact_country . $buyer->contact_ddd . $buyer->contact_num;
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($phone, 0, 2) === '55') {
            $phone = substr($phone, 2);
        }

        return substr($phone, 0, 10);
    }

    private function updateCycleAfterFailure(CampaignSubscription $subscription, CampaignSubscriptionCycle $cycle, string $errorMessage): void
    {
        $attemptsCount = $cycle->attempts_count + 1;
        $nextAttemptAt = $this->getNextAttemptAt($cycle->billing_date, $attemptsCount);

        $cycle->update([
            'attempts_count' => $attemptsCount,
            'last_attempt_at' => now(),
            'next_attempt_at' => $nextAttemptAt,
            'error_message' => $errorMessage,
        ]);

        if ($nextAttemptAt) {
            return;
        }

        $cycle->update(['status' => 'failed']);

        $subscription->update([
            'status' => 'error_disabled',
            'error_at' => now(),
            'error_message' => $errorMessage,
        ]);
    }

    private function updateCycleAfterPending(CampaignSubscriptionCycle $cycle): void
    {
        $cycle->update([
            'attempts_count' => $cycle->attempts_count + 1,
            'last_attempt_at' => now(),
            'next_attempt_at' => null,
        ]);
    }

    private function updateCycleAfterSuccess(CampaignSubscription $subscription, CampaignSubscriptionCycle $cycle): void
    {
        $billingDate = Carbon::parse($cycle->billing_date);

        $cycle->update([
            'status' => 'paid',
            'paid_at' => now(),
            'attempts_count' => $cycle->attempts_count + 1,
            'last_attempt_at' => now(),
            'next_attempt_at' => null,
        ]);

        $subscription->update([
            'status' => 'active',
            'current_cycle' => max($subscription->current_cycle ?? 0, $cycle->cycle_number),
            'last_charge_at' => now(),
            'next_charge_at' => $billingDate->copy()->addMonthNoOverflow(),
            'error_at' => null,
            'error_message' => null,
        ]);
    }

    private function getNextAttemptAt(string $billingDate, int $attemptsCount): ?Carbon
    {
        if ($attemptsCount >= count($this->attemptOffsets)) {
            return null;
        }

        return Carbon::parse($billingDate)->startOfDay()->addDays($this->attemptOffsets[$attemptsCount]);
    }

    private function disableSubscriptionByError(
        CampaignSubscription $subscription,
        CampaignSubscriptionCycle $cycle,
        string $errorMessage
    ): void {
        $cycle->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);

        $subscription->update([
            'status' => 'error_disabled',
            'error_at' => now(),
            'error_message' => $errorMessage,
        ]);
    }

    private function sanitizePaymentData($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $sanitize = function (&$arr) use (&$sanitize) {
            foreach ($arr as $key => &$value) {
                if (in_array($key, ['CardNumber', 'SecurityCode', 'Token'], true) && is_string($value)) {
                    $value = '***';
                } elseif (is_array($value)) {
                    $sanitize($value);
                }
            }
        };

        $sanitize($data);
        return $data;
    }
}
