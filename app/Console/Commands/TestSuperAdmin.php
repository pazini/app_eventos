<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:super-admin {email=superadmin@empresateste.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa se o super admin pode ser acessado sem erro SQL';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info("🔍 Procurando usuário: {$email}");

        try {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->error("❌ Usuário não encontrado!");
                return Command::FAILURE;
            }

            $this->info("✅ Usuário encontrado: {$user->name} (ID: {$user->id})");

            // Verificar dados na tabela pivot users_app
            $this->info("🔗 Verificando dados na tabela users_app...");
            $pivotData = \DB::table('users_app')->where('user_id', $user->id)->get();

            if ($pivotData->isEmpty()) {
                $this->error("❌ Usuário não tem apps associados na tabela users_app!");
            } else {
                foreach ($pivotData as $data) {
                    $this->info("📊 App: {$data->app_id} | Role: {$data->user_role} | Active: {$data->user_active}");
                }
            }

            // Teste do relacionamento app()
            $this->info("🔗 Testando relacionamento app()...");
            $hasApp = $user->app()->wherePivot('user_role', 'super-admin')->exists();
            $this->info($hasApp ? "✅ É Super Admin!" : "❌ Não é Super Admin");

            // Verificar se existe role super-admin
            $superAdminApps = $user->app()->wherePivot('user_role', 'super-admin')->get();
            $this->info("📈 Total de apps com role super-admin: " . $superAdminApps->count());

            // Teste do relacionamento customers()
            $this->info("🔗 Testando relacionamento customers()...");
            $customersCount = $user->customers()->count();
            $this->info("📊 Total de customers: {$customersCount}");

            // Teste do relacionamento customerOrganization()
            $this->info("🔗 Testando relacionamento customerOrganization()...");
            $orgsCount = $user->customerOrganization()->count();
            $this->info("🏢 Total de organizações: {$orgsCount}");

            $this->info("🎉 Todos os testes passaram sem erro SQL!");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Erro: " . $e->getMessage());
            $this->error("📍 Arquivo: " . $e->getFile() . ":" . $e->getLine());
            return Command::FAILURE;
        }
    }
}
