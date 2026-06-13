<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tbs_product_plan')) {
            return;
        }

        Schema::table('tbs_product_plan', function (Blueprint $table) {
            if (! Schema::hasColumn('tbs_product_plan', 'monthly_active')) {
                $table->boolean('monthly_active')->default(true);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'monthly_amount')) {
                $table->bigInteger('monthly_amount')->nullable();
            }
            if (! Schema::hasColumn('tbs_product_plan', 'monthly_pay_gateway_id')) {
                $table->uuid('monthly_pay_gateway_id')->nullable();
            }
            if (! Schema::hasColumn('tbs_product_plan', 'monthly_pay_sandbox')) {
                $table->boolean('monthly_pay_sandbox')->default(false);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'monthly_pay_pix')) {
                $table->boolean('monthly_pay_pix')->default(false);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'monthly_pay_boleto')) {
                $table->boolean('monthly_pay_boleto')->default(false);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'monthly_pay_card_credit')) {
                $table->boolean('monthly_pay_card_credit')->default(false);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'monthly_pay_card_credit_installment_max')) {
                $table->smallInteger('monthly_pay_card_credit_installment_max')->nullable();
            }
            if (! Schema::hasColumn('tbs_product_plan', 'monthly_pay_card_credit_installment_fee_payer')) {
                $table->string('monthly_pay_card_credit_installment_fee_payer', 20)->default('customer');
            }
            if (! Schema::hasColumn('tbs_product_plan', 'monthly_pay_card_credit_installment_amount_min')) {
                $table->integer('monthly_pay_card_credit_installment_amount_min')->nullable();
            }

            if (! Schema::hasColumn('tbs_product_plan', 'annual_active')) {
                $table->boolean('annual_active')->default(false);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'annual_amount')) {
                $table->bigInteger('annual_amount')->nullable();
            }
            if (! Schema::hasColumn('tbs_product_plan', 'annual_pay_gateway_id')) {
                $table->uuid('annual_pay_gateway_id')->nullable();
            }
            if (! Schema::hasColumn('tbs_product_plan', 'annual_pay_sandbox')) {
                $table->boolean('annual_pay_sandbox')->default(false);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'annual_pay_pix')) {
                $table->boolean('annual_pay_pix')->default(false);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'annual_pay_boleto')) {
                $table->boolean('annual_pay_boleto')->default(false);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'annual_pay_card_credit')) {
                $table->boolean('annual_pay_card_credit')->default(false);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'annual_pay_card_credit_installment_max')) {
                $table->smallInteger('annual_pay_card_credit_installment_max')->nullable();
            }
            if (! Schema::hasColumn('tbs_product_plan', 'annual_pay_card_credit_installment_fee_payer')) {
                $table->string('annual_pay_card_credit_installment_fee_payer', 20)->default('customer');
            }
            if (! Schema::hasColumn('tbs_product_plan', 'annual_pay_card_credit_installment_amount_min')) {
                $table->integer('annual_pay_card_credit_installment_amount_min')->nullable();
            }
        });

        DB::statement(
            "UPDATE tbs_product_plan
            SET monthly_active = true,
                monthly_amount = amount,
                monthly_pay_gateway_id = pay_gateway_id,
                monthly_pay_sandbox = pay_sandbox,
                monthly_pay_pix = pay_pix,
                monthly_pay_boleto = pay_boleto,
                monthly_pay_card_credit = pay_card_credit,
                monthly_pay_card_credit_installment_max = pay_card_credit_installment_max,
                monthly_pay_card_credit_installment_fee_payer = pay_card_credit_installment_fee_payer,
                monthly_pay_card_credit_installment_amount_min = pay_card_credit_installment_amount_min
            WHERE amount IS NOT NULL"
        );
    }

    public function down(): void
    {
        if (! Schema::hasTable('tbs_product_plan')) {
            return;
        }

        Schema::table('tbs_product_plan', function (Blueprint $table) {
            if (Schema::hasColumn('tbs_product_plan', 'monthly_active')) {
                $table->dropColumn('monthly_active');
            }
            if (Schema::hasColumn('tbs_product_plan', 'monthly_amount')) {
                $table->dropColumn('monthly_amount');
            }
            if (Schema::hasColumn('tbs_product_plan', 'monthly_pay_gateway_id')) {
                $table->dropColumn('monthly_pay_gateway_id');
            }
            if (Schema::hasColumn('tbs_product_plan', 'monthly_pay_sandbox')) {
                $table->dropColumn('monthly_pay_sandbox');
            }
            if (Schema::hasColumn('tbs_product_plan', 'monthly_pay_pix')) {
                $table->dropColumn('monthly_pay_pix');
            }
            if (Schema::hasColumn('tbs_product_plan', 'monthly_pay_boleto')) {
                $table->dropColumn('monthly_pay_boleto');
            }
            if (Schema::hasColumn('tbs_product_plan', 'monthly_pay_card_credit')) {
                $table->dropColumn('monthly_pay_card_credit');
            }
            if (Schema::hasColumn('tbs_product_plan', 'monthly_pay_card_credit_installment_max')) {
                $table->dropColumn('monthly_pay_card_credit_installment_max');
            }
            if (Schema::hasColumn('tbs_product_plan', 'monthly_pay_card_credit_installment_fee_payer')) {
                $table->dropColumn('monthly_pay_card_credit_installment_fee_payer');
            }
            if (Schema::hasColumn('tbs_product_plan', 'monthly_pay_card_credit_installment_amount_min')) {
                $table->dropColumn('monthly_pay_card_credit_installment_amount_min');
            }

            if (Schema::hasColumn('tbs_product_plan', 'annual_active')) {
                $table->dropColumn('annual_active');
            }
            if (Schema::hasColumn('tbs_product_plan', 'annual_amount')) {
                $table->dropColumn('annual_amount');
            }
            if (Schema::hasColumn('tbs_product_plan', 'annual_pay_gateway_id')) {
                $table->dropColumn('annual_pay_gateway_id');
            }
            if (Schema::hasColumn('tbs_product_plan', 'annual_pay_sandbox')) {
                $table->dropColumn('annual_pay_sandbox');
            }
            if (Schema::hasColumn('tbs_product_plan', 'annual_pay_pix')) {
                $table->dropColumn('annual_pay_pix');
            }
            if (Schema::hasColumn('tbs_product_plan', 'annual_pay_boleto')) {
                $table->dropColumn('annual_pay_boleto');
            }
            if (Schema::hasColumn('tbs_product_plan', 'annual_pay_card_credit')) {
                $table->dropColumn('annual_pay_card_credit');
            }
            if (Schema::hasColumn('tbs_product_plan', 'annual_pay_card_credit_installment_max')) {
                $table->dropColumn('annual_pay_card_credit_installment_max');
            }
            if (Schema::hasColumn('tbs_product_plan', 'annual_pay_card_credit_installment_fee_payer')) {
                $table->dropColumn('annual_pay_card_credit_installment_fee_payer');
            }
            if (Schema::hasColumn('tbs_product_plan', 'annual_pay_card_credit_installment_amount_min')) {
                $table->dropColumn('annual_pay_card_credit_installment_amount_min');
            }
        });
    }
};
