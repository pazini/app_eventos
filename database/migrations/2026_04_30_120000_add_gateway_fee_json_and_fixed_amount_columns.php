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
            $table->text('pay_boleto_fees_json')->nullable()->after('pay_gateway_description');
            $table->text('pay_pix_fees_json')->nullable()->after('pay_boleto_fees_json');

            // Valores fixos por transacao em centavos.
            $table->integer('fee_boleto_fixed_amount')->nullable()->after('pay_pix_fees_json');
            $table->integer('fee_pix_fixed_amount')->nullable()->after('fee_boleto_fixed_amount');
            $table->integer('fee_slip_pix_fixed_amount')->nullable()->after('fee_pix_fixed_amount');
            $table->integer('fee_credit_fixed_amount')->nullable()->after('fee_slip_pix_fixed_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_customers_pay_gateways', function (Blueprint $table) {
            $table->dropColumn([
                'pay_boleto_fees_json',
                'pay_pix_fees_json',
                'fee_boleto_fixed_amount',
                'fee_pix_fixed_amount',
                'fee_slip_pix_fixed_amount',
                'fee_credit_fixed_amount',
            ]);
        });
    }
};