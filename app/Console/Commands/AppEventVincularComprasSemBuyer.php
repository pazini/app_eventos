<?php

namespace App\Console\Commands;

use App\Models\AppBuyers;
use App\Models\AppEvent\AppEventOrder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AppEventVincularComprasSemBuyer extends Command
{
    protected $signature = 'appEvent:vincularComprasSemBuyer
                            {--dry-run : Apenas simula sem persistir no banco}
                            {--report-only : Apenas diagnostica e lista pedidos sem buyer_id}
                            {--limit=0 : Limita a quantidade de pedidos processados}
                            {--show=20 : Quantidade de pedidos exibidos no relatório detalhado}
                            {--cpf= : Filtra pedidos por CPF}
                            {--birth-date= : Filtra pedidos por data de nascimento no formato YYYY-MM-DD}';

    protected $description = 'Vincula pedidos de eventos sem buyer_id ao comprador correto (por CPF + data de nascimento)';

    public function handle()
    {
        $dryRun = (bool) $this->option('dry-run');
        $reportOnly = (bool) $this->option('report-only');
        $limit = (int) $this->option('limit');
        $show = max(0, (int) $this->option('show'));
        $cpfFilter = preg_replace('/\D/', '', (string) $this->option('cpf'));
        $birthDateFilter = trim((string) $this->option('birth-date'));

        $this->line('=============================================');
        $this->info('>> Iniciando rotina de vínculo compra x comprador');
        $this->line('>> Modo: ' . ($reportOnly ? 'RELATORIO' : ($dryRun ? 'SIMULACAO (dry-run)' : 'EXECUCAO')));

        $query = AppEventOrder::query()
            ->whereNull('buyer_id')
            ->orderBy('created_at', 'asc');

        if (!empty($cpfFilter)) {
            $query->whereRaw("regexp_replace(coalesce(buyer_doc_num, ''), '[^0-9]', '', 'g') = ?", [$cpfFilter]);
        }

        if (!empty($birthDateFilter)) {
            $query->whereDate('buyer_birth_date', $birthDateFilter);
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        $orders = $query->get([
            'id',
            'order_control',
            'buyer_id',
            'buyer_doc_type',
            'buyer_doc_num',
            'buyer_birth_date',
            'buyer_name',
            'buyer_email',
            'buyer_contact_country',
            'buyer_contact_ddd',
            'buyer_contact_num',
            'created_at',
        ]);

        if ($orders->isEmpty()) {
            $this->info('>> Nenhum pedido sem buyer_id encontrado.');
            $this->line('=============================================');
            return Command::SUCCESS;
        }

        $stats = [
            'total' => $orders->count(),
            'vinculados' => 0,
            'buyers_criados' => 0,
            'buyers_existentes' => 0,
            'pulados_sem_dados' => 0,
            'pulados_data_invalida' => 0,
            'erros' => 0,
        ];

        $erros = [];
        $detalhes = [];

        foreach ($orders as $order) {
            try {
                $docNumDigits = preg_replace('/\D/', '', (string) ($order->buyer_doc_num ?? ''));

                if (empty($docNumDigits) || empty($order->buyer_birth_date)) {
                    $stats['pulados_sem_dados']++;
                    continue;
                }

                try {
                    $birthDate = Carbon::parse($order->buyer_birth_date)->format('Y-m-d');
                } catch (\Throwable $e) {
                    $stats['pulados_data_invalida']++;
                    continue;
                }

                $normalizedKey = $birthDate . '.' . $docNumDigits;
                $legacyKey = $birthDate . '.' . trim((string) $order->buyer_doc_num);

                $buyer = AppBuyers::where('my_key', $normalizedKey)->first();
                $matchType = 'normalized';

                if (!$buyer && $legacyKey !== $normalizedKey) {
                    $buyer = AppBuyers::where('my_key', $legacyKey)->first();
                    if ($buyer) {
                        $matchType = 'legacy';
                    }
                }

                $buyerCriado = false;
                $buyerExistente = (bool) $buyer;

                if (!$buyer) {
                    if (!$dryRun && !$reportOnly) {
                        $buyer = AppBuyers::firstOrCreate(
                            ['my_key' => $normalizedKey],
                            [
                                'doc_type' => $order->buyer_doc_type ?? 'cpf',
                                'doc_num' => $docNumDigits,
                                'name' => $order->buyer_name,
                                'email' => $order->buyer_email,
                                'birth_date' => $order->buyer_birth_date,
                                'contact_country' => $order->buyer_contact_country ?? 55,
                                'contact_ddd' => (int) preg_replace('/\D/', '', (string) ($order->buyer_contact_ddd ?? '')),
                                'contact_num' => (int) preg_replace('/\D/', '', (string) ($order->buyer_contact_num ?? '')),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );

                        $buyerCriado = $buyer->wasRecentlyCreated;
                        $buyerExistente = !$buyer->wasRecentlyCreated;
                    } else {
                        $matchType = 'nao_encontrado';
                    }
                }

                if (!$dryRun && !$reportOnly) {
                    DB::transaction(function () use ($order, $buyer) {
                        if ($buyer) {
                            $order->buyer_id = $buyer->id;
                            $order->save();
                        }
                    });
                }

                if ($buyer) {
                    $stats['vinculados']++;
                }

                if ($buyerCriado) {
                    $stats['buyers_criados']++;
                }

                if ($buyerExistente && !$buyerCriado) {
                    $stats['buyers_existentes']++;
                }

                $detalhes[] = [
                    'ORDER_CONTROL' => $order->order_control,
                    'ORDER_ID' => $order->id,
                    'CPF' => $docNumDigits,
                    'NASCIMENTO' => $birthDate,
                    'MY_KEY' => $normalizedKey,
                    'BUYER_ENCONTRADO' => $buyer ? 'SIM' : 'NAO',
                    'TIPO_MATCH' => $matchType,
                    'BUYER_ID' => $buyer->id ?? '-',
                    'CRIARIA_BUYER' => (!$buyer && ($dryRun || $reportOnly)) ? 'SIM' : 'NAO',
                ];
            } catch (\Throwable $e) {
                $stats['erros']++;
                $erros[] = [
                    'order_control' => $order->order_control,
                    'order_id' => $order->id,
                    'erro' => $e->getMessage(),
                ];
            }
        }

        $this->line('---------------------------------------------');
        $this->table(
            ['METRICA', 'VALOR'],
            [
                ['Pedidos analisados', $stats['total']],
                ['Pedidos vinculados', $stats['vinculados']],
                ['Buyers existentes encontrados', $stats['buyers_existentes']],
                ['Buyers criados', $stats['buyers_criados']],
                ['Pulados sem CPF/data', $stats['pulados_sem_dados']],
                ['Pulados por data invalida', $stats['pulados_data_invalida']],
                ['Erros', $stats['erros']],
            ]
        );

        if ($show > 0 && !empty($detalhes)) {
            $this->warn('>> Amostra de pedidos sem buyer_id:');
            $this->table(
                ['ORDER_CONTROL', 'CPF', 'NASCIMENTO', 'BUYER_ENCONTRADO', 'TIPO_MATCH', 'BUYER_ID', 'CRIARIA_BUYER'],
                collect($detalhes)->take($show)->map(function ($item) {
                    return [
                        $item['ORDER_CONTROL'],
                        $item['CPF'],
                        $item['NASCIMENTO'],
                        $item['BUYER_ENCONTRADO'],
                        $item['TIPO_MATCH'],
                        $item['BUYER_ID'],
                        $item['CRIARIA_BUYER'],
                    ];
                })->toArray()
            );
        }

        if (!empty($erros)) {
            $this->warn('>> Primeiros erros encontrados:');
            $this->table(
                ['ORDER_CONTROL', 'ORDER_ID', 'ERRO'],
                collect($erros)->take(20)->toArray()
            );
        }

        $this->info('>> Rotina finalizada.');
        $this->line('=============================================');

        return Command::SUCCESS;
    }
}
