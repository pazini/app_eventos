<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tb_customers_pay_gateways', function (Blueprint $table) {
            $table->string('conta_cod', 50)->nullable()->after('cod_subconta_id');
            $table->string('conta_banco', 255)->nullable()->after('conta_cod');
            $table->string('conta_banco_descricao', 255)->nullable()->after('conta_banco');
            $table->string('conta_tipo', 20)->nullable()->after('conta_banco_descricao');
            $table->string('conta_agencia', 20)->nullable()->after('conta_tipo');
            $table->string('conta_agencia_dv', 5)->nullable()->after('conta_agencia');
            $table->string('conta_numero', 30)->nullable()->after('conta_agencia_dv');
            $table->string('conta_numero_dv', 5)->nullable()->after('conta_numero');

            $table->unique('conta_cod');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_customers_pay_gateways', function (Blueprint $table) {
            $table->dropUnique(['conta_cod']);
            $table->dropColumn([
                'conta_cod',
                'conta_banco',
                'conta_banco_descricao',
                'conta_tipo',
                'conta_agencia',
                'conta_agencia_dv',
                'conta_numero',
                'conta_numero_dv',
            ]);
        });
    }
};
