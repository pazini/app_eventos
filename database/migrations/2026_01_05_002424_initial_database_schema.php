<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        echo "Verificando e criando tabelas do schema inicial...\n";

        // Lista de todas as tabelas que devem ser criadas
        $tables = [
            'app_buyers', 'app_callback', 'app_config', 'app_events',
            'app_events_notifications', 'app_events_orders', 'app_events_orders_items',
            'app_events_orders_sponsorship', 'app_events_orders_tickets', 'app_events_organizers',
            'app_notifica', 'app_payments', 'app_payments_callbacks', 'app_payments_slip',
            'app_sponsorship_orders', 'personal_access_tokens',
            'ref_app_states', 'ref_app_event_category', 'ref_app_event_type',
            'ref_app_notification_type', 'ref_event_category', 'ref_event_status',
            'ref_event_type', 'tb_app', 'tb_app_faturamento', 'tb_app_faturamento_pagamentos',
            'tb_app_modules', 'tb_app_pay_gateways', 'tb_customers', 'tb_customers_app_modules',
            'tb_customers_organizations', 'tb_customers_organizations_places',
            'tb_customers_organizations_subs', 'tb_customers_organizers',
            'tb_customers_pay_gateways', 'tb_customers_pay_gateways_fees',
            'tb_notificacoes', 'tb_notificacoes_envios', 'tb_providers', 'tb_sponsorship',
            'tbc_campaign', 'tbc_campaign_metric', 'tbc_campaign_order', 'tbc_campaign_order_answer',
            'tbc_campaign_organizer', 'tbc_campaign_payment', 'tbc_campaign_payment_attempt',
            'tbc_campaign_payment_slip', 'tbc_campaign_payment_webhook', 'tbc_campaign_question',
            'tev_events', 'tev_events_budgets', 'tev_events_budgets_items', 'tev_events_page',
            'tev_events_publishs', 'tev_events_sponsorship', 'tev_events_sponsorship_plans',
            'tev_events_tickets_codes_promo', 'tev_events_tickets_sponsorships',
            'tev_events_tickets_types', 'users', 'users_app', 'users_campaign_organizer',
            'users_customer', 'users_customer_organizer', 'users_customer_organization',
            'users_customer_organization_sub', 'sessions', 'migrations'
        ];

        // Verifica quais tabelas já existem
        $existing = [];
        $missing = [];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $existing[] = $table;
            } else {
                $missing[] = $table;
            }
        }

        if (empty($missing)) {
            echo "✅ Todas as tabelas já existem! Schema já inicializado.\n";
            return;
        }

        // Se ALGUMA tabela existir, apenas informa quais faltam e sai
        if (!empty($existing)) {
            echo "⚠️  Algumas tabelas já existem no banco!\n";
            echo "📊 Existem: " . count($existing) . " tabelas\n";
            echo "📊 Faltam: " . count($missing) . " tabelas\n";
            echo "\n🔍 Tabelas que não existem:\n";
            foreach ($missing as $table) {
                echo "   ❌ {$table}\n";
            }
            echo "\n⚠️  Migration não executada. Execute apenas em ambiente completamente limpo.\n";
            return;
        }

        // Se chegou aqui, NENHUMA tabela existe - ambiente completamente limpo
        echo "✅ Ambiente completamente limpo detectado (0 tabelas existentes).\n";
        echo "📊 Serão criadas " . count($missing) . " tabelas.\n";

        // Executa o SQL completo (mais simples que parse individual)
        $sqlPath = database_path('migrations/2026_01_05_002424_initial_database_schema.sql');

        if (!file_exists($sqlPath)) {
            throw new \Exception("Arquivo SQL inicial não encontrado: {$sqlPath}");
        }

        echo "📄 Executando setup inicial completo do banco de dados...\n";

        $sql = file_get_contents($sqlPath);

        try {
            DB::unprepared($sql);
            echo "✅ Schema inicial criado com sucesso! Todas as tabelas foram criadas.\n";
        } catch (\Exception $e) {
            echo "❌ Erro durante criação do schema: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        echo "ATENÇÃO: Esta migration não pode ser revertida automaticamente.\n";
        echo "O rollback desta migration removeria todas as tabelas do sistema.\n";
        echo "Se necessário, faça backup e restore manual do banco de dados.\n";

        return;
    }
};
