<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TenantMigrateFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate-files
                            {--app= : Migrar apenas arquivos de um app específico (ID)}
                            {--dry-run : Simular migração sem mover arquivos}
                            {--backup : Criar backup antes de migrar}
                            {--force : Forçar migração sem confirmação}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra arquivos do storage compartilhado para storage isolado por tenant';

    protected $stats = [
        'campaigns_processed' => 0,
        'campaigns_files' => 0,
        'events_processed' => 0,
        'events_files' => 0,
        'customers_processed' => 0,
        'customers_files' => 0,
        'total_files' => 0,
        'total_size' => 0,
        'errors' => 0,
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Migração de Arquivos para Storage Isolado por Tenant');
        $this->newLine();

        // Verificar se é dry-run
        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('⚠️  MODO DRY-RUN: Nenhum arquivo será movido ou atualizado');
            $this->newLine();
        }

        // Criar backup se solicitado
        if ($this->option('backup') && !$dryRun) {
            $this->createBackup();
        }

        // Confirmar antes de prosseguir
        if (!$this->option('force') && !$dryRun) {
            if (!$this->confirm('Deseja continuar com a migração de arquivos?')) {
                $this->warn('Migração cancelada pelo usuário.');
                return 0;
            }
        }

        // Determinar quais apps migrar
        $appId = $this->option('app');
        $apps = $appId ? [$appId] : $this->getActiveApps();

        $this->info("📦 Apps a processar: " . count($apps));
        $this->newLine();

        // Processar cada app
        $progressBar = $this->output->createProgressBar(count($apps));
        $progressBar->start();

        foreach ($apps as $appIdToProcess) {
            $this->migrateAppFiles($appIdToProcess, $dryRun);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Exibir estatísticas
        $this->displayStats($dryRun);

        return 0;
    }

    /**
     * Obter lista de apps ativos
     */
    protected function getActiveApps()
    {
        return DB::table('tb_app')
            ->where('app_active', true)
            ->pluck('id')
            ->map(function($id) {
                return (string) $id; // Converter UUID para string
            })
            ->toArray();
    }

    /**
     * Migrar arquivos de um app específico
     */
    protected function migrateAppFiles($appId, $dryRun = false)
    {
        $this->newLine();
        $this->info("📁 Processando App ID: {$appId}");

        // Migrar campanhas
        try {
            $this->migrateCampaigns($appId, $dryRun);
        } catch (\Exception $e) {
            $this->warn("  ⚠️  Erro ao migrar campanhas: " . $e->getMessage());
        }

        // Migrar eventos
        try {
            $this->migrateEvents($appId, $dryRun);
        } catch (\Exception $e) {
            $this->warn("  ⚠️  Erro ao migrar eventos: " . $e->getMessage());
        }

        // Migrar customers
        try {
            $this->migrateCustomers($appId, $dryRun);
        } catch (\Exception $e) {
            $this->warn("  ⚠️  Erro ao migrar customers: " . $e->getMessage());
        }
    }

    /**
     * Migrar arquivos de campanhas
     */
    protected function migrateCampaigns($appId, $dryRun = false)
    {
        $campaigns = DB::table('tbc_campaign')
            ->join('tb_customers', 'tbc_campaign.customer_id', '=', 'tb_customers.id')
            ->whereRaw('tb_customers.app_id::text = ?', [(string) $appId])
            ->select('tbc_campaign.id', 'tbc_campaign.url_image_banner', 'tbc_campaign.url_image_thumb')
            ->get();

        foreach ($campaigns as $campaign) {
            $this->stats['campaigns_processed']++;

            // Migrar banner
            if ($campaign->url_image_banner && $this->isOldPath($campaign->url_image_banner)) {
                $newPath = $this->migrateFile(
                    $campaign->url_image_banner,
                    "campaigns/{$campaign->id}/banner",
                    $appId,
                    $dryRun
                );

                if ($newPath && !$dryRun) {
                    DB::table('tbc_campaign')
                        ->where('id', $campaign->id)
                        ->update(['url_image_banner' => $newPath]);
                }

                $this->stats['campaigns_files']++;
                $this->stats['total_files']++;
            }

            // Migrar thumb
            if ($campaign->url_image_thumb && $this->isOldPath($campaign->url_image_thumb)) {
                $newPath = $this->migrateFile(
                    $campaign->url_image_thumb,
                    "campaigns/{$campaign->id}/thumb",
                    $appId,
                    $dryRun
                );

                if ($newPath && !$dryRun) {
                    DB::table('tbc_campaign')
                        ->where('id', $campaign->id)
                        ->update(['url_image_thumb' => $newPath]);
                }

                $this->stats['campaigns_files']++;
                $this->stats['total_files']++;
            }
        }
    }

    /**
     * Migrar arquivos de eventos
     */
    protected function migrateEvents($appId, $dryRun = false)
    {
        // Buscar eventos via json_event que contém customer_id
        $events = DB::table('app_events')
            ->whereRaw("json_event::jsonb->>'customer_id' = ?", [(string) $appId])
            ->select(
                'id',
                'event_slug',
                'json_event'
            )
            ->get();

        foreach ($events as $event) {
            $this->stats['events_processed']++;

            // Decodificar json_event
            $jsonEvent = json_decode($event->json_event, true);
            if (!$jsonEvent) continue;

            $fields = [
                'url_image' => "events/{$event->event_slug}/layouts",
                'url_image_logo' => "events/{$event->event_slug}/layouts",
                'url_image_thumbnail' => "events/{$event->event_slug}/layouts",
                'url_image_bg' => "events/{$event->event_slug}/layouts",
            ];

            $updated = false;
            foreach ($fields as $field => $targetPath) {
                $value = $jsonEvent[$field] ?? null;

                if ($value && $this->isOldPath($value)) {
                    $newPath = $this->migrateFile($value, $targetPath, $appId, $dryRun);

                    if ($newPath && !$dryRun) {
                        $jsonEvent[$field] = $newPath;
                        $updated = true;
                    }

                    $this->stats['events_files']++;
                    $this->stats['total_files']++;
                }
            }

            // Atualizar json_event se houve mudanças
            if ($updated && !$dryRun) {
                DB::table('app_events')
                    ->where('id', $event->id)
                    ->update(['json_event' => json_encode($jsonEvent)]);
            }
        }

        // Migrar logos de patrocinadores (tb_sponsorship)
        $sponsorships = DB::table('tb_sponsorship')
            ->whereRaw('customer_id::text = ?', [(string) $appId])
            ->whereNotNull('url_logo')
            ->select('id', 'url_logo')
            ->get();

        foreach ($sponsorships as $sponsorship) {
            if ($this->isOldPath($sponsorship->url_logo)) {
                $newPath = $this->migrateFile(
                    $sponsorship->url_logo,
                    "events/sponsors",
                    $appId,
                    $dryRun
                );

                if ($newPath && !$dryRun) {
                    DB::table('tb_sponsorship')
                        ->where('id', $sponsorship->id)
                        ->update(['url_logo' => $newPath]);
                }

                $this->stats['events_files']++;
                $this->stats['total_files']++;
            }
        }
    }

    /**
     * Migrar arquivos de customers
     */
    protected function migrateCustomers($appId, $dryRun = false)
    {
        $customers = DB::table('tb_customers')
            ->whereRaw('app_id::text = ?', [(string) $appId])
            ->select('id', 'url_image_logo')
            ->get();

        foreach ($customers as $customer) {
            $this->stats['customers_processed']++;

            if ($customer->url_image_logo && $this->isOldPath($customer->url_image_logo)) {
                $newPath = $this->migrateFile(
                    $customer->url_image_logo,
                    "customers/{$customer->id}",
                    $appId,
                    $dryRun
                );

                if ($newPath && !$dryRun) {
                    DB::table('tb_customers')
                        ->where('id', $customer->id)
                        ->update(['url_image_logo' => $newPath]);
                }

                $this->stats['customers_files']++;
                $this->stats['total_files']++;
            }
        }
    }

    /**
     * Verificar se o path é do formato antigo (compartilhado)
     */
    protected function isOldPath($path)
    {
        // Paths antigos começam com: campanhas/, images_eventos/, images_patrocinadores/, images_customers_logo/, storage/
        $oldPrefixes = [
            'campanhas/',
            'images_eventos/',
            'images_patrocinadores/',
            'images_customers_logo/',
            'storage/',
            '/storage/',
        ];

        foreach ($oldPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        // Se já tem formato campaigns/, events/, customers/ provavelmente já foi migrado
        $newPrefixes = ['campaigns/', 'events/', 'customers/'];
        foreach ($newPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return false;
            }
        }

        return false;
    }

    /**
     * Migrar um arquivo específico
     */
    protected function migrateFile($oldPath, $targetPath, $appId, $dryRun = false)
    {
        try {
            // Limpar path
            $oldPath = str_replace(['/storage/', 'storage/'], '', $oldPath);

            // Determinar paths completos
            $sourceFile = storage_path('app/public/' . $oldPath);

            // Se não existe em public, tentar em app direto
            if (!file_exists($sourceFile)) {
                $sourceFile = storage_path('app/' . $oldPath);
            }

            if (!file_exists($sourceFile)) {
                $this->warn("  ⚠️  Arquivo não encontrado: {$oldPath}");
                $this->stats['errors']++;
                return null;
            }

            // Obter nome do arquivo
            $filename = basename($sourceFile);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            // Gerar nome único se necessário (evitar conflitos)
            $newFilename = pathinfo($filename, PATHINFO_FILENAME) . '_' . substr(md5($oldPath), 0, 8) . '.' . $extension;

            // Path de destino
            $destPath = storage_path("app/{$appId}/{$targetPath}");
            $destFile = $destPath . '/' . $newFilename;

            if ($dryRun) {
                $size = filesize($sourceFile);
                $this->stats['total_size'] += $size;
                $this->line("  → {$oldPath} → {$appId}/{$targetPath}/{$newFilename} (" . $this->formatBytes($size) . ")");
                return "{$targetPath}/{$newFilename}";
            }

            // Criar diretório se não existe
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }

            // Copiar arquivo (não mover, para manter backup)
            copy($sourceFile, $destFile);

            $size = filesize($destFile);
            $this->stats['total_size'] += $size;

            $this->line("  ✓ {$oldPath} → {$appId}/{$targetPath}/{$newFilename}");

            // Retornar path relativo para salvar no banco
            return "{$targetPath}/{$newFilename}";

        } catch (\Exception $e) {
            $this->error("  ✗ Erro ao migrar {$oldPath}: " . $e->getMessage());
            $this->stats['errors']++;
            return null;
        }
    }

    /**
     * Criar backup do storage atual
     */
    protected function createBackup()
    {
        $this->info('📦 Criando backup do storage...');

        $backupPath = storage_path('backups/storage_' . date('Y-m-d_His'));

        try {
            if (!file_exists(dirname($backupPath))) {
                mkdir(dirname($backupPath), 0755, true);
            }

            // Copiar storage/app/public para backup
            $this->recursiveCopy(storage_path('app/public'), $backupPath);

            $this->info("✓ Backup criado em: {$backupPath}");
            $this->newLine();
        } catch (\Exception $e) {
            $this->error("✗ Erro ao criar backup: " . $e->getMessage());
            $this->warn("Continuando sem backup...");
            $this->newLine();
        }
    }

    /**
     * Copiar diretório recursivamente
     */
    protected function recursiveCopy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst, 0755, true);

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recursiveCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }

        closedir($dir);
    }

    /**
     * Exibir estatísticas da migração
     */
    protected function displayStats($dryRun = false)
    {
        $this->info('📊 Estatísticas da Migração:');
        $this->newLine();

        $this->table(
            ['Categoria', 'Valor'],
            [
                ['Campanhas Processadas', $this->stats['campaigns_processed']],
                ['Arquivos de Campanhas', $this->stats['campaigns_files']],
                ['Eventos Processados', $this->stats['events_processed']],
                ['Arquivos de Eventos', $this->stats['events_files']],
                ['Customers Processados', $this->stats['customers_processed']],
                ['Arquivos de Customers', $this->stats['customers_files']],
                ['Total de Arquivos', $this->stats['total_files']],
                ['Tamanho Total', $this->formatBytes($this->stats['total_size'])],
                ['Erros', $this->stats['errors']],
            ]
        );

        $this->newLine();

        if ($dryRun) {
            $this->warn('⚠️  SIMULAÇÃO CONCLUÍDA - Nenhum arquivo foi movido');
        } else {
            $this->info('✅ MIGRAÇÃO CONCLUÍDA COM SUCESSO!');
        }

        $this->newLine();
    }

    /**
     * Formatar bytes em formato legível
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
