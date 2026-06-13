<?php

namespace App\Jobs;

use App\Models\ModCampaign\CampaignOrder;
use App\Models\NotificacaoLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendCampaignParticipationProofEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array
     */
    public $backoff = [60, 300]; // Retry após 1min, depois 5min

    /**
     * The campaign order instance.
     *
     * @var CampaignOrder
     */
    protected $order;

    /**
     * Create a new job instance.
     *
     * @param CampaignOrder $order
     * @return void
     */
    public function __construct(CampaignOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $campaign = $this->order->campaign;

            if (! $campaign) {
                Log::warning('Campanha não encontrada para o pedido: ' . $this->order->id);
                return;
            }

            $campaign->loadMissing(['organization', 'customer']);
            $companyName = $campaign->organization->organization_name
                ?? $campaign->customer->name_fantasy
                ?? $campaign->customer->name_corporate
                ?? null;

            // Gera URL da campanha
            $paymentUrl = campanhaUrl($campaign->customer_organization_slug, $campaign->slug, $this->order->id);

            $data = [
                'buyer_name' => $this->order->buyer_name,
                'buyer_email' => $this->order->buyer_email,
                'order_control' => $this->order->order_control,
                'campaign_name' => $campaign->name,
                'company_name' => $companyName,
                'amount_total' => number_format($this->order->amount_total / 100, 2, ',', '.'),
                'paid_at' => $this->order->paid_at ? $this->order->paid_at->format('d/m/Y H:i') : '-',
                'status' => $this->getStatusLabel($this->order->status),
                'payment_url' => $paymentUrl,
            ];

            $subject = $this->buildSubject($campaign->name ?? null);

            Mail::send('emails.campanha.comprovante-participacao', $data, function ($message) use ($subject) {
                $message->to($this->order->buyer_email, $this->order->buyer_name)
                    ->subject($subject);
            });

            Log::info('[QUEUE] Email de comprovante de participação enviado', [
                'order_id' => $this->order->id,
                'buyer_email' => $this->order->buyer_email,
            ]);

            $this->logNotification('sent', $subject, null, [
                'attempt' => $this->attempts(),
                'mailer' => config('mail.default'),
            ]);

        } catch (\Exception $e) {
            Log::error('[QUEUE] Erro ao enviar comprovante de participação: ' . $e->getMessage(), [
                'order_id' => $this->order->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw para que o Laravel registre a falha e tente novamente
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        $subject = null;

        try {
            $this->order->loadMissing('campaign');
            $subject = $this->buildSubject($this->order->campaign->name ?? null);
        } catch (\Throwable $error) {
        }

        $this->logNotification('failed', $subject, $exception->getMessage(), [
            'attempt' => $this->attempts(),
            'final' => true,
        ]);

        Log::error('[QUEUE] Job de comprovante de participação falhou após todas as tentativas', [
            'order_id' => $this->order->id ?? null,
            'error' => $exception->getMessage(),
        ]);
    }

    /**
     * Retorna label do status
     */
    protected function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Pendente',
            'paid' => 'Pago',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado',
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    private function buildSubject(?string $campaignName): string
    {
        $name = $campaignName ?? 'Campanha';

        return mb_strtoupper('Comprovante de Participação - ' . $name . ' - ' . $this->order->order_control);
    }

    private function logNotification(string $status, ?string $subject, ?string $errorMessage = null, array $meta = []): void
    {
        try {
            NotificacaoLog::create([
                'target_ref' => 'campaign_order',
                'target_id' => $this->order->id,
                'campaign_id' => $this->order->campaign_id ?? null,
                'customer_id' => optional($this->order->campaign)->customer_id,
                'channel' => 'email',
                'notification_type' => 'participation_proof',
                'status' => $status,
                'recipient_email' => $this->order->buyer_email ?? '',
                'recipient_name' => $this->order->buyer_name,
                'subject' => $subject,
                'error_message' => $errorMessage,
                'meta' => $meta ?: null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('[QUEUE] Falha ao registrar notificacao de comprovante', [
                'order_id' => $this->order->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
