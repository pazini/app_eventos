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
            if (! Schema::hasColumn('tbs_product_plan', 'pay_gateway_id')) {
                $table->uuid('pay_gateway_id')->nullable();
            }
            if (! Schema::hasColumn('tbs_product_plan', 'pay_sandbox')) {
                $table->boolean('pay_sandbox')->default(false);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'pay_pix')) {
                $table->boolean('pay_pix')->default(false);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'pay_boleto')) {
                $table->boolean('pay_boleto')->default(false);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'pay_card_credit')) {
                $table->boolean('pay_card_credit')->default(false);
            }
            if (! Schema::hasColumn('tbs_product_plan', 'pay_card_credit_installment_max')) {
                $table->smallInteger('pay_card_credit_installment_max')->nullable();
            }
            if (! Schema::hasColumn('tbs_product_plan', 'pay_card_credit_installment_fee_payer')) {
                $table->string('pay_card_credit_installment_fee_payer', 20)->default('customer');
            }
            if (! Schema::hasColumn('tbs_product_plan', 'pay_card_credit_installment_amount_min')) {
                $table->integer('pay_card_credit_installment_amount_min')->nullable();
            }
        });

        if (Schema::hasTable('tbs_product')) {
            DB::statement(
                "UPDATE tbs_product_plan AS plan
                SET pay_gateway_id = product.pay_gateway_id,
                    pay_sandbox = product.pay_sandbox,
                    pay_pix = product.pay_pix,
                    pay_boleto = product.pay_boleto,
                    pay_card_credit = product.pay_card_credit,
                    pay_card_credit_installment_max = product.pay_card_credit_installment_max,
                    pay_card_credit_installment_fee_payer = product.pay_card_credit_installment_fee_payer,
                    pay_card_credit_installment_amount_min = product.pay_card_credit_installment_amount_min
                FROM tbs_product AS product
                WHERE plan.product_id = product.id"
            );
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('tbs_product_plan')) {
            return;
        }

        Schema::table('tbs_product_plan', function (Blueprint $table) {
            if (Schema::hasColumn('tbs_product_plan', 'pay_gateway_id')) {
                $table->dropColumn('pay_gateway_id');
            }
            if (Schema::hasColumn('tbs_product_plan', 'pay_sandbox')) {
                $table->dropColumn('pay_sandbox');
            }
            if (Schema::hasColumn('tbs_product_plan', 'pay_pix')) {
                $table->dropColumn('pay_pix');
            }
            if (Schema::hasColumn('tbs_product_plan', 'pay_boleto')) {
                $table->dropColumn('pay_boleto');
            }
            if (Schema::hasColumn('tbs_product_plan', 'pay_card_credit')) {
                $table->dropColumn('pay_card_credit');
            }
            if (Schema::hasColumn('tbs_product_plan', 'pay_card_credit_installment_max')) {
                $table->dropColumn('pay_card_credit_installment_max');
            }
            if (Schema::hasColumn('tbs_product_plan', 'pay_card_credit_installment_fee_payer')) {
                $table->dropColumn('pay_card_credit_installment_fee_payer');
            }
            if (Schema::hasColumn('tbs_product_plan', 'pay_card_credit_installment_amount_min')) {
                $table->dropColumn('pay_card_credit_installment_amount_min');
            }
        });
    }
};
