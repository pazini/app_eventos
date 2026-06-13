<?php

namespace Database\Seeders;

use App\Models\App;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // NOTA: O app padrão "ProEventPay" é criado automaticamente pela migration
        // 2026_01_05_002425_initial_database_app.sql
        // Este seeder está mantido apenas para referência e para casos onde
        // seja necessário recriar o app manualmente.

        // Verifica se já existe um app
        if (App::count() > 0) {
            $this->command->info('Apps já existem no banco de dados (criados pela migration). Pulando seed.');
            return;
        }

        // Cria o app padrão ProEventPay para desenvolvimento local
        // (Este código só será executado se a migration não tiver sido executada)
        $app = App::create([
            'app_name' => 'ProEventPay',
            'app_description' => 'Plataforma de Gestão de Eventos e Campanhas',
            'app_license' => 'development',
            'app_limit_date' => null, // Sem limite para desenvolvimento
            'app_active' => true,

            // Informações do proprietário
            'owner_name' => 'Administrador',
            'owner_email' => 'admin@empresateste.com',
            'owner_phone_country' => '55',
            'owner_phone_ddd' => '11',
            'owner_phone_num' => '999999999',

            // URLs base
            'url_base' => 'http://127.0.0.1:8000',
            'url_image_logo' => null,

            // White Label - Configuração de domínios
            'domain_primary' => '127.0.0.1', // Domínio local
            'domain_aliases' => [
                'localhost',
                'proeventpay.com',
                'painel.proeventpay.com',
                'eventos.proeventpay.com',
                'campanhas.proeventpay.com',
            ],

            // Cores padrão
            'color_primary' => '#3B82F6', // Blue
            'color_secondary' => '#10B981', // Green
            'color_accent' => '#F59E0B', // Amber

            // URLs de imagens (opcionais)
            'url_image_logo_dark' => null,
            'url_image_favicon' => null,

            // Configurações de email
            'email_from_name' => 'ProEventPay',
            'email_from_address' => 'noreply@proeventpay.com.br',
            'email_reply_to' => 'suporte@proeventpay.com',

            // Meta tags SEO
            'meta_title' => 'ProEventPay - Gestão de Eventos e Campanhas',
            'meta_description' => 'Plataforma completa para gestão de eventos e campanhas promocionais',
            'meta_keywords' => 'eventos, campanhas, ingressos, gestão',
            'meta_image' => null,

            // Settings adicionais
            'settings' => [
                'features' => [
                    'events' => true,
                    'campaigns' => true,
                    'subscriptions' => false,
                    'payments' => true,
                    'notifications' => true,
                ],
                'modules' => [
                    'eventos' => true,
                    'campanhas' => true,
                    'assinaturas' => false,
                ],
            ],

            'branding_updated_at' => now(),
        ]);

        $this->command->info("App '{$app->app_name}' (ID: {$app->id}) criado com sucesso!");
        $this->command->info("Domínio principal: {$app->domain_primary}");
        $this->command->info("Aliases: " . implode(', ', $app->domain_aliases));
    }
}
