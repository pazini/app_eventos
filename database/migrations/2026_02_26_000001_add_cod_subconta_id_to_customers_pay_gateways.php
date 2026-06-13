<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tb_customers_pay_gateways', function (Blueprint $table) {
            $table->string('cod_subconta_id')->nullable()->unique()->after('pay_gateway_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_customers_pay_gateways', function (Blueprint $table) {
            $table->dropColumn('cod_subconta_id');
        });
    }
};
