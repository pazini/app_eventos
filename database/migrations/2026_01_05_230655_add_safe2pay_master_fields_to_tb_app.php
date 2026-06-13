<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('tb_app', function (Blueprint $table) {
            // Safe2Pay - Tokens da conta MASTER (pai) do APP
            // Estes tokens são usados como fallback/padrão para customers que não tiverem seus próprios tokens
            $table->string('safe2pay_token_live')->nullable()->after('settings');
            $table->string('safe2pay_token_test')->nullable()->after('safe2pay_token_live');
            $table->string('safe2pay_token_live_pass')->nullable()->after('safe2pay_token_test');
            $table->string('safe2pay_token_test_pass')->nullable()->after('safe2pay_token_live_pass');

            // Configurações adicionais do Safe2Pay em nível de APP
            $table->boolean('safe2pay_active')->default(false)->after('safe2pay_token_test_pass');
            $table->boolean('safe2pay_test_mode')->default(true)->after('safe2pay_active');

            // JSON com configurações extras do gateway (taxas, limites, etc)
            $table->json('safe2pay_settings')->nullable()->after('safe2pay_test_mode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_app', function (Blueprint $table) {
            $table->dropColumn([
                'safe2pay_token_live',
                'safe2pay_token_test',
                'safe2pay_token_live_pass',
                'safe2pay_token_test_pass',
                'safe2pay_active',
                'safe2pay_test_mode',
                'safe2pay_settings',
            ]);
        });
    }
};
