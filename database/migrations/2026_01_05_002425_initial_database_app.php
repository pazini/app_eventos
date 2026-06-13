<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Executa o SQL inicial do dump do banco de dados
        $sqlPath = database_path('migrations/2026_01_05_002425_initial_database_app.sql');

        if (!file_exists($sqlPath)) {
            throw new \Exception("Arquivo SQL inicial não encontrado: {$sqlPath}");
        }

        $sql = file_get_contents($sqlPath);

        // Executa o SQL
        DB::unprepared($sql);
    }
};
