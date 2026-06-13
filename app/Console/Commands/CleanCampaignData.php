<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\ModCampaign\CampaignPaymentWebhook;
use App\Models\ModCampaign\CampaignPaymentAttempt;
use App\Models\ModCampaign\CampaignPayment;
use App\Models\ModCampaign\CampaignPaymentSlip;
use App\Models\ModCampaign\CampaignOrderAnswer;
use App\Models\ModCampaign\CampaignOrder;

class CleanCampaignData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaigns:clean-data 
                            {--force : Força a execução sem confirmação}
                            {--campaign= : ID da campanha específica para limpar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa todos os dados de doações e pagamentos de campanhas (para testes)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $campaignId = $this->option('campaign');
        
        if ($campaignId) {
            $this->warn("⚠️  ATENÇÃO: Vai deletar DOAÇÕES e PAGAMENTOS da campanha: {$campaignId}");
            $this->info("A campanha em si NÃO será deletada, apenas os dados de doações.");
        } else {
            $this->warn("⚠️  ATENÇÃO: Vai deletar TODAS as DOAÇÕES e PAGAMENTOS de TODAS as campanhas!");
            $this->info("As campanhas em si NÃO serão deletadas, apenas os dados de doações.");
        }
        
        // Confirmação
        if (!$this->option('force')) {
            if (!$this->confirm('Deseja continuar e limpar os dados de doações?')) {
                $this->info('Operação cancelada.');
                return 0;
            }
        }

        $this->info('🧹 Iniciando limpeza...');

        try {
            DB::beginTransaction();

            // Contadores
            $counts = [
                'webhooks' => 0,
                'attempts' => 0,
                'payments' => 0,
                'slips' => 0,
                'answers' => 0,
                'orders' => 0,
            ];

            // 1. Webhooks (não tem FK cascata, pode deletar primeiro)
            $this->info('📡 Deletando webhooks...');
            $query = CampaignPaymentWebhook::query();
            if ($campaignId) {
                $query->where('campaign_id', $campaignId);
            }
            $counts['webhooks'] = $query->count();
            $query->delete();
            $this->line("   ✓ {$counts['webhooks']} webhooks deletados");

            // 2. Payment Attempts (referencia payments)
            $this->info('🔄 Deletando tentativas de pagamento...');
            $query = CampaignPaymentAttempt::query();
            if ($campaignId) {
                $query->where('campaign_id', $campaignId);
            }
            $counts['attempts'] = $query->count();
            $query->delete();
            $this->line("   ✓ {$counts['attempts']} tentativas deletadas");

            // 3. Payments (referencia payment_slips)
            $this->info('💳 Deletando payments...');
            $query = CampaignPayment::query();
            if ($campaignId) {
                $query->where('campaign_id', $campaignId);
            }
            $counts['payments'] = $query->count();
            $query->delete();
            $this->line("   ✓ {$counts['payments']} payments deletados");

            // 4. Payment Slips (referencia orders)
            $this->info('📋 Deletando payment slips...');
            $query = CampaignPaymentSlip::query();
            if ($campaignId) {
                $query->where('campaign_id', $campaignId);
            }
            $counts['slips'] = $query->count();
            $query->delete();
            $this->line("   ✓ {$counts['slips']} slips deletados");

            // 5. Order Answers (referencia orders)
            $this->info('📝 Deletando respostas do quiz...');
            $query = CampaignOrderAnswer::query();
            if ($campaignId) {
                $query->whereHas('order', function($q) use ($campaignId) {
                    $q->where('campaign_id', $campaignId);
                });
            }
            $counts['answers'] = $query->count();
            $query->delete();
            $this->line("   ✓ {$counts['answers']} respostas deletadas");

            // 6. Orders (último, é referenciado por tudo)
            $this->info('📦 Deletando pedidos...');
            $query = CampaignOrder::query();
            if ($campaignId) {
                $query->where('campaign_id', $campaignId);
            }
            $counts['orders'] = $query->count();
            $query->delete();
            $this->line("   ✓ {$counts['orders']} pedidos deletados");

            DB::commit();

            // Resumo
            $this->newLine();
            $this->info('✅ Limpeza concluída com sucesso!');
            $this->newLine();
            $this->table(
                ['Tipo', 'Quantidade'],
                [
                    ['Webhooks', $counts['webhooks']],
                    ['Tentativas', $counts['attempts']],
                    ['Payments', $counts['payments']],
                    ['Payment Slips', $counts['slips']],
                    ['Respostas Quiz', $counts['answers']],
                    ['Pedidos', $counts['orders']],
                    ['TOTAL', array_sum($counts)],
                ]
            );

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Erro ao limpar dados: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}
