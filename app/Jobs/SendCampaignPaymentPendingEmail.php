<?php

namespace App\Jobs;

use App\Models\ModCampaign\CampaignOrder;
use App\Models\NotificacaoLog;
use App\Jobs\Middleware\RateLimitEmails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendCampaignPaymentPendingEmail implements ShouldQueue
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
    public $timeout = 120;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array
     */
    public $backoff = [10, 30, 120]; // Retry mais rápido com Resend

    /**
     * Determine if the job should fail on timeout.
     *
     * @var bool
     */
    public $failOnTimeout = false;

    /**
     * The campaign order instance.
     *
     * @var CampaignOrder
     */
    protected $order;

    /**
     * Determine if the job should be retried based on the exception.
     *
     * @param \Throwable $exception
     * @return bool
     */
    public function retryUntil()
    {
        return now()->addHours(2); // Retry por até 2 horas
    }

    /**
     * Determine if the job should be retried based on the exception.
     *
     * @param \Throwable $exception
     * @return bool
     */
    public function shouldRetry($exception)
    {
        // Retry para timeouts SMTP, conexão perdida, etc.
        if (str_contains($exception->getMessage(), 'timeout exceeded') ||
            str_contains($exception->getMessage(), 'Connection lost') ||
            str_contains($exception->getMessage(), '421') ||
            str_contains($exception->getMessage(), '450') ||
            str_contains($exception->getMessage(), '451') ||
            str_contains($exception->getMessage(), '452')) {
            return true;
        }

        return false;
    }

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
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new RateLimitEmails()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Garante que o relacionamento campaign está carregado
            if (!$this->order->relationLoaded('campaign')) {
                $this->order->load('campaign');
            }

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

            // Gera URL para retornar e concluir o pagamento
            $paymentUrl = campanhaUrl($campaign->customer_organization_slug, $campaign->slug, $this->order->id);

            // Busca informações do payment pendente mais recente (pode ter múltiplas tentativas)
            $payment = $this->order->payments()
                ->whereIn('status', ['pending', 'processing', 'autorizado'])
                ->latest()
                ->first();

            // Log para debug
            if ($payment) {
                Log::info('[QUEUE] Payment encontrado para email pendente', [
                    'order_id' => $this->order->id,
                    'payment_id' => $payment->id,
                    'payment_type' => $payment->pay_type,
                    'payment_status' => $payment->status,
                    'has_boleto_url' => !empty($payment->pay_boleto_url),
                    'has_boleto_barcode' => !empty($payment->pay_boleto_barcode),
                ]);
            }

            $data = [
                'buyer_name' => $this->order->buyer_name,
                'buyer_email' => $this->order->buyer_email,
                'order_control' => $this->order->order_control,
                'campaign_name' => $campaign->name,
                'company_name' => $companyName,
                'amount_total' => number_format($this->order->amount_total / 100, 2, ',', '.'),
                'payment_url' => $paymentUrl,
                'payment_type' => $payment ? $payment->pay_type : null,
                'boleto_url' => $payment ? $payment->pay_boleto_url : null,
                'boleto_barcode' => $payment ? $payment->pay_boleto_barcode : null,
                'boleto_expiration' => $payment ? $payment->pay_boleto_expiration_date : null,
            ];

            $subject = $this->buildSubject($campaign->name ?? null);

            try {
                // Tenta enviar com o mailer padrão (failover: Resend → SMTP → Log)
                Mail::send('emails.campanha.pagamento-pendente', $data, function ($message) use ($subject) {
                    $message->to($this->order->buyer_email, $this->order->buyer_name)
                        ->subject($subject);
                });

                Log::info('[QUEUE] Email de pagamento pendente enviado', [
                    'order_id' => $this->order->id,
                    'buyer_email' => $this->order->buyer_email,
                    'attempt' => $this->attempts(),
                    'mailer' => config('mail.default'),
                ]);

                $this->logNotification('sent', $subject, null, [
                    'attempt' => $this->attempts(),
                    'mailer' => config('mail.default'),
                ]);

            } catch (\Exception $e) {
                // Log do erro específico
                Log::error('[QUEUE] Erro no envio de email de pagamento pendente', [
                    'order_id' => $this->order->id,
                    'error' => $e->getMessage(),
                    'attempt' => $this->attempts(),
                    'max_attempts' => $this->tries,
                    'mailer' => config('mail.default'),
                ]);

                // Se for a última tentativa, tenta forçar envio via SMTP como último recurso
                if ($this->attempts() >= $this->tries) {
                    $this->tryDirectSmtpFallback($data, $campaign, $e);
                } else {
                    // Re-throw para retry automático
                    throw $e;
                }
            }

        } catch (\Exception $e) {
            Log::error('[QUEUE] Erro ao enviar email de pagamento pendente: ' . $e->getMessage(), [
                'order_id' => $this->order->id ?? null,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Se for timeout e não for a última tentativa, faz retry
            if ($this->shouldRetry($e) && $this->attempts() < $this->tries) {
                throw $e;
            }

            // Na última tentativa, tenta fallback
            if ($this->attempts() >= $this->tries) {
                $this->tryFallbackEmail($data ?? [], $campaign ?? null, $e);
            } else {
                throw $e;
            }
        }
    }

    /**
     * Tenta envio direto via SMTP quando failover automático falha.
     *
     * @param array $data
     * @param mixed $campaign
     * @param \Throwable $originalException
     * @return void
     */
    private function tryDirectSmtpFallback(array $data, $campaign, \Throwable $originalException)
    {
        try {
            Log::warning('[QUEUE] Tentando fallback direto via SMTP', [
                'order_id' => $this->order->id,
                'original_error' => $originalException->getMessage(),
            ]);

            $subject = $this->buildSubject($campaign->name ?? null);

            // Tenta envio direto via SMTP (bypassa failover)
            \Mail::mailer('smtp')->send('emails.campanha.pagamento-pendente', $data, function ($message) use ($subject) {
                $message->to($this->order->buyer_email, $this->order->buyer_name)
                    ->subject($subject);
            });

            Log::info('[QUEUE] Email enviado via fallback SMTP direto', [
                'order_id' => $this->order->id,
                'buyer_email' => $this->order->buyer_email,
                'original_error' => $originalException->getMessage(),
            ]);

            $this->logNotification('sent', $subject, null, [
                'attempt' => $this->attempts(),
                'mailer' => 'smtp',
                'fallback' => 'smtp',
                'original_error' => $originalException->getMessage(),
            ]);

        } catch (\Exception $smtpError) {
            // Se SMTP também falhar, tenta log como último recurso
            $this->tryLogFallback($data, $campaign, $originalException, $smtpError);
        }
    }

    /**
     * Último recurso: salva no log quando todos os métodos falham.
     *
     * @param array $data
     * @param mixed $campaign
     * @param \Throwable $originalException
     * @param \Throwable $smtpException
     * @return void
     */
    private function tryLogFallback(array $data, $campaign, \Throwable $originalException, \Throwable $smtpException)
    {
        try {
            $subject = $this->buildSubject($campaign->name ?? null);

            // Salva no log como último recurso
            \Mail::mailer('log')->send('emails.campanha.pagamento-pendente', $data, function ($message) use ($subject) {
                $message->to($this->order->buyer_email, $this->order->buyer_name)
                    ->subject($subject);
            });

            Log::critical('[QUEUE] Email salvo no log após falhas múltiplas', [
                'order_id' => $this->order->id,
                'buyer_email' => $this->order->buyer_email,
                'original_error' => $originalException->getMessage(),
                'smtp_error' => $smtpException->getMessage(),
                'action_required' => 'Email não foi enviado - verificar configurações',
            ]);

            $this->logNotification('logged', $subject, $originalException->getMessage(), [
                'attempt' => $this->attempts(),
                'mailer' => 'log',
                'fallback' => 'log',
                'smtp_error' => $smtpException->getMessage(),
            ]);

            // Agenda nova tentativa em 2 horas (quando possível problema temporário)
            if (str_contains($originalException->getMessage(), 'timeout') ||
                str_contains($originalException->getMessage(), '421') ||
                str_contains($originalException->getMessage(), '450')) {

                self::dispatch($this->order)->delay(now()->addHours(2));

                Log::info('[QUEUE] Nova tentativa de email agendada em 2 horas', [
                    'order_id' => $this->order->id,
                    'retry_at' => now()->addHours(2)->toDateTimeString(),
                ]);
            }

        } catch (\Exception $logError) {
            Log::critical('[QUEUE] FALHA CRÍTICA: Não foi possível enviar email por nenhum método', [
                'order_id' => $this->order->id,
                'buyer_email' => $this->order->buyer_email,
                'original_error' => $originalException->getMessage(),
                'smtp_error' => $smtpException->getMessage(),
                'log_error' => $logError->getMessage(),
            ]);
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

        Log::error('[QUEUE] Job de email de pagamento pendente falhou após todas as tentativas', [
            'order_id' => $this->order->id ?? null,
            'buyer_email' => $this->order->buyer_email ?? null,
            'error' => $exception->getMessage(),
            'total_attempts' => $this->attempts(),
            'is_timeout_error' => str_contains($exception->getMessage(), 'timeout exceeded'),
        ]);

        // Se for erro de timeout, agenda uma nova tentativa em 30 minutos
        if (str_contains($exception->getMessage(), 'timeout exceeded') ||
            str_contains($exception->getMessage(), '421')) {

            Log::info('[QUEUE] Agendando nova tentativa de email devido a timeout', [
                'order_id' => $this->order->id,
                'retry_at' => now()->addMinutes(30)->toDateTimeString(),
            ]);

            // Nova tentativa em 30 minutos
            self::dispatch($this->order)->delay(now()->addMinutes(30));
        }
    }

    private function buildSubject(?string $campaignName): string
    {
        $name = $campaignName ?? 'Campanha';

        return mb_strtoupper('Aguardando Pagamento - ' . $name . ' - ' . $this->order->order_control);
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
                'notification_type' => 'payment_pending',
                'status' => $status,
                'recipient_email' => $this->order->buyer_email ?? '',
                'recipient_name' => $this->order->buyer_name,
                'subject' => $subject,
                'error_message' => $errorMessage,
                'meta' => $meta ?: null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('[QUEUE] Falha ao registrar notificacao de pagamento pendente', [
                'order_id' => $this->order->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
