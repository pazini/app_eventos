<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\ModCampaign\CampaignOrder;

class TestEmailConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email? : Email para teste} {--timeout=30 : Timeout em segundos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa conexão SMTP e diagnostica problemas de email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'teste@example.com';
        $timeout = $this->option('timeout');

        $this->info('🔍 Testando configuração de email...');
        $this->newLine();

        // 1. Verifica configurações
        $this->checkEmailConfiguration();

        // 2. Testa conexão SMTP
        $this->testSmtpConnection($timeout);

        // 3. Testa envio de email
        $this->testEmailSending($email);

        // 4. Mostra estatísticas de jobs de email
        $this->showEmailJobStats();

        $this->newLine();
        $this->info('✅ Teste de email concluído!');
    }

    private function checkEmailConfiguration()
    {
        $this->info('📋 Verificando configurações de email...');

        $config = [
            'MAIL_MAILER' => config('mail.default'),
            'RESEND_API_KEY' => config('services.resend.key') ? '***' . substr(config('services.resend.key'), -4) : 'NÃO DEFINIDO',
            'MAIL_HOST' => config('mail.mailers.smtp.host'),
            'MAIL_PORT' => config('mail.mailers.smtp.port'),
            'MAIL_USERNAME' => config('mail.mailers.smtp.username'),
            'MAIL_ENCRYPTION' => config('mail.mailers.smtp.encryption'),
            'MAIL_TIMEOUT' => config('mail.mailers.smtp.timeout'),
            'MAIL_FROM_ADDRESS' => config('mail.from.address'),
        ];

        foreach ($config as $key => $value) {
            $status = $value && $value !== 'NÃO DEFINIDO' ? '✅' : '❌';
            $displayValue = ($key === 'MAIL_USERNAME' && $value && $value !== 'NÃO DEFINIDO') ? '***@' . explode('@', $value)[1] ?? '***' : ($value ?: 'NÃO DEFINIDO');
            $this->line("  {$status} {$key}: {$displayValue}");
        }

        // Mostra configuração de failover se aplicável
        if (config('mail.default') === 'failover') {
            $mailers = config('mail.mailers.failover.mailers', []);
            $this->line("  ℹ️  FAILOVER ORDER: " . implode(' → ', $mailers));
        }

        $this->newLine();
    }

    private function testSmtpConnection($timeout)
    {
        $this->info('🔌 Testando conexão SMTP...');

        try {
            $transport = Mail::getSwiftMailer()->getTransport();

            if (method_exists($transport, 'start')) {
                $transport->start();
                $this->line('  ✅ Conexão SMTP estabelecida com sucesso');

                if (method_exists($transport, 'stop')) {
                    $transport->stop();
                }
            } else {
                $this->line('  ⚠️  Não foi possível testar conexão direta');
            }

        } catch (\Exception $e) {
            $this->error('  ❌ Falha na conexão SMTP: ' . $e->getMessage());

            if (str_contains($e->getMessage(), 'timeout')) {
                $this->warn('  💡 Dica: Tente aumentar MAIL_TIMEOUT no .env');
            }

            if (str_contains($e->getMessage(), 'authentication')) {
                $this->warn('  💡 Dica: Verifique MAIL_USERNAME e MAIL_PASSWORD');
            }
        }

        $this->newLine();
    }

    private function testEmailSending($email)
    {
        $this->info("📧 Testando envio de email para: {$email}");

        try {
            $startTime = microtime(true);
            $mailerUsed = config('mail.default');

            Mail::raw('Este é um email de teste do sistema ProEventPay com Resend + SMTP fallback.', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Teste de Email - ProEventPay + Resend - ' . now()->format('d/m/Y H:i:s'));
            });

            $duration = round((microtime(true) - $startTime) * 1000, 2);
            $this->line("  ✅ Email enviado com sucesso em {$duration}ms via {$mailerUsed}");

            if ($duration > 5000) { // Mais de 5 segundos (Resend deveria ser mais rápido)
                $this->warn('  ⚠️  Tempo alto - pode estar usando fallback SMTP');
            } elseif ($duration < 1000) {
                $this->line('  ⚡ Velocidade excelente - provavelmente Resend!');
            }

        } catch (\Exception $e) {
            $this->error('  ❌ Falha no envio: ' . $e->getMessage());

            // Dicas específicas baseadas no erro
            if (str_contains($e->getMessage(), 'resend')) {
                $this->warn('  💡 Erro Resend: Verifique RESEND_API_KEY no .env');
            } elseif (str_contains($e->getMessage(), '421')) {
                $this->warn('  💡 Erro 421: Servidor SMTP temporariamente indisponível.');
            } elseif (str_contains($e->getMessage(), 'timeout exceeded')) {
                $this->warn('  💡 Timeout SMTP: Resend deveria ter evitado isso!');
            } elseif (str_contains($e->getMessage(), '550')) {
                $this->warn('  💡 Erro 550: Email rejeitado. Verifique domínio remetente.');
            } elseif (str_contains($e->getMessage(), 'Unauthorized')) {
                $this->warn('  💡 Unauthorized: Verifique API key do Resend.');
            }
        }

        $this->newLine();
    }

    private function showEmailJobStats()
    {
        $this->info('📊 Estatísticas de jobs de email (últimas 24h)...');

        try {
            // Jobs de email que falharam nas últimas 24h
            $failedJobs = \DB::table('failed_jobs')
                ->where('payload', 'LIKE', '%SendCampaignPaymentPendingEmail%')
                ->where('failed_at', '>=', now()->subDay())
                ->count();

            if ($failedJobs > 0) {
                $this->line("  ⚠️  Jobs de email falharam (24h): {$failedJobs}");

                // Últimos erros
                $recentErrors = \DB::table('failed_jobs')
                    ->where('payload', 'LIKE', '%SendCampaignPaymentPendingEmail%')
                    ->where('failed_at', '>=', now()->subHours(6))
                    ->orderBy('failed_at', 'desc')
                    ->limit(3)
                    ->get();

                foreach ($recentErrors as $error) {
                    $exception = json_decode($error->exception, true);
                    $message = $exception['message'] ?? 'Erro desconhecido';
                    $time = \Carbon\Carbon::parse($error->failed_at)->format('H:i');
                    $this->line("    • {$time}: " . substr($message, 0, 80) . '...');
                }
            } else {
                $this->line('  ✅ Nenhum job de email falhou nas últimas 24h');
            }

        } catch (\Exception $e) {
            $this->warn('  ⚠️  Não foi possível verificar estatísticas de jobs');
        }
    }
}
