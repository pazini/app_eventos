<?php

namespace App\Services\Campaign;

use App\Models\ModCampaign\CampaignOrder;
use App\Models\ModCampaign\CampaignPayment;
use App\Models\ModCampaign\CampaignPaymentSlip;
use Illuminate\Support\Facades\DB;

class CampaignPaymentService
{
    /**
     * Obtém ou cria PaymentSlip para o pedido
     * Um Order tem apenas 1 PaymentSlip (criado na primeira tentativa)
     *
     * @param CampaignOrder $order
     * @param array $data Dados do pagamento
     * @return CampaignPaymentSlip
     */
    protected function getOrCreatePaymentSlip(CampaignOrder $order, array $data): CampaignPaymentSlip
    {
        // Verifica se já existe um PaymentSlip para este Order
        $paymentSlip = $order->currentPaymentSlip;

        if ($paymentSlip) {
            return $paymentSlip;
        }

        // Cria novo PaymentSlip (primeira tentativa)
        $gatewayPay = $order->campaign->gateway;

        if (!$gatewayPay) {
            throw new \Exception('Gateway de pagamento não configurado para esta campanha.');
        }

        $installmentsTotal = $data['installments'] ?? 1;
        $paymentMethod = $data['payment_method'] ?? 'pix';

        $isRecurring = !empty($data['subscription_id']);
        $slipDescription = $isRecurring ? 'Recorrência mensal' : 'Doação única';

        // Define a data de vencimento (hoje para PIX/Boleto à vista, ou primeira parcela para cartão)
        $dueDate = now();

        // Para cartão parcelado, pode definir data futura se necessário
        if ($paymentMethod === 'credit_card' && $installmentsTotal > 1) {
            // Primeira parcela vence hoje (ou pode adicionar dias se preferir)
            $dueDate = now();
        }

        $paymentSlip = CampaignPaymentSlip::create([
            'campaign_id' => $order->campaign_id,
            'campaign_order_id' => $order->id,
            'slip_group_id' => (string) \Illuminate\Support\Str::uuid(),
            'description' => $slipDescription,
            'status' => 'pending',
            'total_amount' => $data['value_paid'],
            'amount_paid' => 0,
            'amount_fees' => $data['value_fees'],
            'amount_liquid' => $data['value_liquid'],
            'installments_total' => 1, // Sempre 1 (doação única)
            'installments_paid' => 0,
            'installment_control' => 1, // Primeira e única parcela
            'due_date' => $dueDate,
            'customer_pay_gateway_id' => $gatewayPay->id,
            'gateway_slug' => $gatewayPay->pay_gateway_slug,
            'gateway_sandbox' => $order->campaign->pay_sandbox ?? false,
        ]);

        // Verifica se o slip foi criado corretamente
        if (!$paymentSlip || !$paymentSlip->id) {
            throw new \Exception('Erro ao criar PaymentSlip. ID não foi gerado.');
        }

        // Atualiza Order com o slip_id E slip_group_id
        $order->update([
            'current_payment_slip_id' => $paymentSlip->id,
            'slip_group_id' => $paymentSlip->slip_group_id,
        ]);

        return $paymentSlip;
    }

    /**
     * Cria um novo payment (tentativa de pagamento)
     *
     * @param CampaignOrder $order
     * @param array $data Dados do pagamento
     * @return CampaignPayment
     */
    public function createPayment(CampaignOrder $order, array $data): CampaignPayment
    {
        // Obtém ou cria PaymentSlip (1 por Order)
        $paymentSlip = $this->getOrCreatePaymentSlip($order, $data);

        $installmentsTotal = $data['installments'] ?? 1;
        $paymentMethod = $data['payment_method'] ?? 'pix';

        // Descrição do payment baseada no método
        if ($installmentsTotal > 1 && $paymentMethod === 'credit_card') {
            $paymentDescription = "Pagamento parcelado ({$installmentsTotal}x no cartão)";
        } elseif ($paymentMethod === 'pix') {
            $paymentDescription = "Pagamento via PIX";
        } elseif ($paymentMethod === 'boleto') {
            $paymentDescription = "Pagamento via Boleto";
        } else {
            $paymentDescription = "Pagamento via Cartão";
        }

        // PIX e Boleto NUNCA devem ter mais de 1 parcela (segurança extra)
        $finalInstallments = ($paymentMethod === 'credit_card') ? $installmentsTotal : 1;
        $finalInstallmentValue = ($paymentMethod === 'credit_card')
            ? $data['installment_value']
            : $data['value_paid'];

        // Cria Payment (nova tentativa)
        $payment = CampaignPayment::create([
            'campaign_id' => $order->campaign_id,
            'campaign_order_id' => $order->id,
            'campaign_payment_slip_id' => $paymentSlip->id,
            'slip_group_id' => $paymentSlip->slip_group_id,
            'installment_number' => null, // Sempre null (não é carnê)
            'description' => $paymentDescription,
            'customer_pay_gateway_id' => $paymentSlip->customer_pay_gateway_id,
            'status' => 'pending',
            'value_paid' => $data['value_paid'],
            'value_fees' => $data['value_fees'],
            'value_liquid' => $data['value_liquid'],
            'fee_percentage_used' => $data['fee_percentage_used'] ?? 0,
            'paid_label' => toMoney($data['value_paid'], 'R$ '),
            'paid_description' => strtoupper('AGUARDANDO PAGAMENTO'),
            'pay_integration_type' => ($order->campaign->pay_sandbox ?? false) ? 'sandbox' : 'live',
            'pay_type' => $paymentMethod === 'credit_card' ? 'card_credit' : $paymentMethod,
            'gateway_slug' => $paymentSlip->gateway_slug,
            'gateway_sandbox' => $paymentSlip->gateway_sandbox,
            'pay_installments_number' => $finalInstallments,
            'pay_installment_value' => $finalInstallmentValue,
            'subscription_id' => $data['subscription_id'] ?? null,
            'subscription_cycle_id' => $data['subscription_cycle_id'] ?? null,
        ]);

        return $payment;
    }

    /**
     * Atualiza payment com dados do gateway (PIX, Boleto, Cartão)
     *
     * @param CampaignPayment $payment
     * @param array $gatewayData Dados retornados do gateway
     */
    public function updatePaymentFromGateway(CampaignPayment $payment, array $gatewayData): void
    {
        $updateData = [
            'status' => $gatewayData['status'] ?? 'processing',
            'pay_transaction_id' => $gatewayData['pay_transaction_id'] ?? null,
            'pay_nsu' => $gatewayData['nsu'] ?? $gatewayData['pay_nsu'] ?? null,
            'pay_json_response' => $gatewayData,
        ];

        // Atualiza dados específicos do tipo de pagamento
        if ($payment->pay_type === 'pix' || $payment->pay_type === 'slip_pix') {
            // Mapeia campos do gateway (Safe2Pay usa datahoraExpiracao)
            $expiresAt = $gatewayData['pay_pix_expires_at'] ?? $gatewayData['datahoraExpiracao'] ?? null;

            $updateData = array_merge($updateData, [
                'pay_pix_qr_code' => $gatewayData['pay_pix_qr_code'] ?? null,
                'pay_pix_qr_code_url' => $gatewayData['pay_pix_qr_code_url'] ?? null,
                'pay_pix_key' => $gatewayData['pay_pix_key'] ?? null,
                'pay_pix_expires_at' => $expiresAt ? \Carbon\Carbon::parse($expiresAt) : null,
            ]);

            // Atualiza expires_at do PaymentSlip se houver
            if ($payment->slip && $expiresAt) {
                $payment->slip->update([
                    'expires_at' => \Carbon\Carbon::parse($expiresAt),
                ]);
            }
        } elseif ($payment->pay_type === 'boleto' || $payment->pay_type === 'slip') {
            // Converte data de vencimento do formato brasileiro (dd/mm/yyyy) para Carbon
            $expirationDate = null;
            if (isset($gatewayData['pay_boleto_expiration_date'])) {
                $dateString = $gatewayData['pay_boleto_expiration_date'];
                try {
                    // Se está no formato dd/mm/yyyy, converte para Carbon
                    if (str_contains($dateString, '/')) {
                        $expirationDate = \Carbon\Carbon::createFromFormat('d/m/Y', $dateString);
                    } else {
                        // Formato ISO ou outro formato suportado
                        $expirationDate = \Carbon\Carbon::parse($dateString);
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Erro ao converter data de vencimento do boleto', [
                        'date' => $dateString,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $updateData = array_merge($updateData, [
                'pay_boleto_barcode' => $gatewayData['pay_boleto_barcode'] ?? null,
                'pay_boleto_url' => $gatewayData['pay_boleto_url'] ?? null,
                'pay_boleto_expiration_date' => $expirationDate,
                // PIX do Boleto (Safe2Pay permite pagamento via PIX também)
                'pay_pix_key' => $gatewayData['pay_boleto_pix_key'] ?? null,
                'pay_pix_qr_code' => $gatewayData['pay_boleto_pix_key'] ?? null,
                'pay_pix_qr_code_url' => $gatewayData['pay_boleto_pix_qrcode_url'] ?? null,
            ]);

            // Atualiza expires_at do PaymentSlip se houver
            if ($payment->slip && $expirationDate) {
                $payment->slip->update([
                    'expires_at' => $expirationDate,
                ]);
            }
        } elseif ($payment->pay_type === 'card_credit') {
            $updateData = array_merge($updateData, [
                'pay_card_first' => $gatewayData['pay_card_first'] ?? null,
                'pay_card_last' => $gatewayData['pay_card_last'] ?? null,
                'pay_card_brand' => $gatewayData['pay_card_brand'] ?? null,
                'pay_card_name' => $gatewayData['pay_card_name'] ?? null,
            ]);
        }

        $payment->update($updateData);
    }

    /**
     * Marca pagamento como pago e atualiza order/slip
     *
     * @param CampaignPayment $payment
     * @param array $gatewayData Dados do gateway
     */
    public function markAsPaid(CampaignPayment $payment, array $gatewayData = []): void
    {
        DB::beginTransaction();
        try {
            // Atualiza Payment
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'pay_datetime' => $gatewayData['datahora'] ?? now(),
                'value_paid' => $gatewayData['pagamento_valor'] ?? $payment->value_paid,   // Já em centavos
                'value_fees' => $gatewayData['pagamento_taxa'] ?? $payment->value_fees,    // Já em centavos
                'value_liquid' => $gatewayData['pagamento_liquido'] ?? $payment->value_liquid, // Já em centavos
                'paid_description' => 'PAGAMENTO CONFIRMADO',
            ]);

            // Atualiza PaymentSlip
            if ($payment->slip) {
                $slip = $payment->slip;
                $installmentsPaid = $slip->installments_paid + 1;
                $amountPaid = $slip->amount_paid + $payment->value_paid;

                // Verifica se todas as parcelas foram pagas
                $allPaid = ($installmentsPaid >= $slip->installments_total);

                $slip->update([
                    'status' => $allPaid ? 'paid' : 'partial',
                    'amount_paid' => $amountPaid,
                    'installments_paid' => $installmentsPaid,
                    'paid_at' => $allPaid ? now() : null,
                ]);
            }

            // Atualiza Order (só marca como paid se o slip estiver completamente pago)
            if ($payment->slip && $payment->slip->status === 'paid') {
                $payment->order->update([
                    'status' => 'paid',
                    'amount_paid' => $payment->order->amount_total,
                    'paid_at' => now(),
                    'current_payment_slip_id' => $payment->slip->id,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Marca pagamento como falho/cancelado
     *
     * @param CampaignPayment $payment
     * @param string $status
     * @param string|null $errorMessage
     */
    public function markAsFailed(CampaignPayment $payment, string $status = 'failed', ?string $errorMessage = null): void
    {
        $payment->update([
            'status' => $status,
            'status_old' => $payment->status,
            'paid_description' => $errorMessage ?? 'PAGAMENTO NÃO APROVADO',
        ]);

        // Atualiza PaymentSlip se for o último payment ativo
        if ($payment->slip) {
            $activePendingPayments = $payment->slip->payments()
                ->whereIn('status', ['pending', 'processing'])
                ->where('id', '!=', $payment->id)
                ->count();

            if ($activePendingPayments === 0) {
                $payment->slip->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);
            }
        }
    }
}
