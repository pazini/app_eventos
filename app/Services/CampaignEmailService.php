<?php

namespace App\Services;

use App\Jobs\SendCampaignPaymentPendingEmail;
use App\Jobs\SendCampaignPaymentApprovedEmail;
use App\Jobs\SendCampaignParticipationProofEmail;
use App\Models\ModCampaign\CampaignOrder;
use Illuminate\Support\Facades\Log;

class CampaignEmailService
{
    /**
     * Envia comprovante de pagamento para o comprador via fila
     */
    public static function enviarComprovanteParticipacao(CampaignOrder $order)
    {
        try {
            $campaign = $order->campaign;

            if (! $campaign) {
                Log::warning('Campanha não encontrada para o pedido: ' . $order->id);
                return false;
            }

            // Dispara job SÍNCRONO (sem fila) - processa imediatamente
            SendCampaignParticipationProofEmail::dispatchSync($order);

            Log::info('Email de comprovante de participação processado imediatamente', [
                'order_id' => $order->id,
                'buyer_email' => $order->buyer_email,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erro ao processar comprovante de participação: ' . $e->getMessage(), [
                'order_id' => $order->id ?? null,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Envia notificação de pagamento aprovado via fila
     */
    public static function enviarNotificacaoPagamentoAprovado(CampaignOrder $order)
    {
        try {
            $campaign = $order->campaign;

            if (! $campaign) {
                Log::warning('Campanha não encontrada para o pedido: ' . $order->id);
                return false;
            }

            // Dispara job SÍNCRONO (sem fila) - processa imediatamente
            SendCampaignPaymentApprovedEmail::dispatchSync($order);

            Log::info('Email de pagamento aprovado processado imediatamente', [
                'order_id' => $order->id,
                'buyer_email' => $order->buyer_email,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erro ao processar notificação de pagamento aprovado: ' . $e->getMessage(), [
                'order_id' => $order->id ?? null,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Envia notificação de pagamento pendente via fila
     */
    public static function enviarNotificacaoPagamentoPendente(CampaignOrder $order)
    {
        try {
            // Garante que o relacionamento campaign está carregado
            if (!$order->relationLoaded('campaign')) {
                $order->load('campaign');
            }

            $campaign = $order->campaign;

            if (! $campaign) {
                Log::warning('Campanha não encontrada para o pedido: ' . $order->id);
                return false;
            }

            // Enfileira o job para processamento assíncrono
            SendCampaignPaymentPendingEmail::dispatch($order)->afterCommit();

            Log::info('Email de pagamento pendente enfileirado', [
                'order_id' => $order->id,
                'buyer_email' => $order->buyer_email,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erro ao processar email de pagamento pendente: ' . $e->getMessage(), [
                'order_id' => $order->id ?? null,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
