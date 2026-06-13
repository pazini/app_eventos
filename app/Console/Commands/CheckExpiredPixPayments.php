<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModCampaign\CampaignPayment;
use Carbon\Carbon;

class CheckExpiredPixPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check-expired-pix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica e atualiza pagamentos PIX que expiraram';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Verificando pagamentos PIX expirados...');

        // Busca todos os pagamentos PIX que ainda não estão pagos
        $payments = CampaignPayment::whereIn('pay_type', ['pix', 'slip_pix'])
            ->whereNotIn('status', ['paid', 'approved', 'captured', 'pix_expired', 'cancelled', 'error'])
            ->whereNotNull('pay_pix_expires_at')
            ->get();

        $expiredPayments = collect();
        $now = Carbon::now();

        // Filtra pagamentos expirados considerando o modo sandbox
        foreach ($payments as $payment) {
            $isExpired = false;

            if ($payment->gateway_sandbox) {
                // Modo sandbox: expira em 2 minutos após a criação
                $sandboxExpiration = $payment->created_at->addMinutes(2);
                if ($now->greaterThan($sandboxExpiration)) {
                    $isExpired = true;
                }
            } else {
                // Modo produção: usa a data de expiração do gateway
                if ($now->greaterThan($payment->pay_pix_expires_at)) {
                    $isExpired = true;
                }
            }

            if ($isExpired) {
                $expiredPayments->push($payment);
            }
        }

        $count = $expiredPayments->count();

        if ($count === 0) {
            $this->info('Nenhum pagamento PIX expirado encontrado.');
            return Command::SUCCESS;
        }

        $this->info("Encontrados {$count} pagamento(s) PIX expirado(s). Atualizando...");

        $updated = 0;
        foreach ($expiredPayments as $payment) {
            try {
                $payment->update([
                    'status' => 'pix_expired',
                ]);

                $orderControl = $payment->order ? $payment->order->order_control : 'N/A';
                $mode = $payment->gateway_sandbox ? '[SANDBOX]' : '[PRODUÇÃO]';
                $expirationTime = $payment->gateway_sandbox
                    ? $payment->created_at->addMinutes(2)->format('d/m/Y H:i:s')
                    : $payment->pay_pix_expires_at->format('d/m/Y H:i:s');

                $this->line("✓ {$mode} Payment ID: {$payment->id} - Order: {$orderControl} - Expirado em: {$expirationTime}");
                $updated++;
            } catch (\Exception $e) {
                $this->error("✗ Erro ao atualizar payment ID {$payment->id}: {$e->getMessage()}");
            }
        }        $this->info("\n{$updated} de {$count} pagamento(s) atualizado(s) com sucesso!");

        return Command::SUCCESS;
    }
}
