<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tbs_product')) {
            Schema::create('tbs_product', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->timestamps();

                $table->uuid('customer_id');
                $table->uuid('organizer_id')->nullable();
                $table->string('slug');
                $table->string('name');
                $table->string('name_short')->nullable();
                $table->text('description')->nullable();
                $table->text('about')->nullable();
                $table->string('status')->default('draft');
                $table->boolean('visibility_public')->default(false);
                $table->timestamp('datetime_start')->nullable();
                $table->timestamp('datetime_finish')->nullable();
                $table->bigInteger('amount_min')->nullable();
                $table->string('color_primary', 20)->nullable();
                $table->string('color_secondary', 20)->nullable();
                $table->string('url_image_logo', 255)->nullable();
                $table->string('url_image_bg', 255)->nullable();
                $table->string('url_image_banner', 255)->nullable();
                $table->string('url_image_thumb', 255)->nullable();
                $table->uuid('pay_gateway_id')->nullable();
                $table->boolean('pay_sandbox')->default(false);
                $table->boolean('pay_pix')->default(false);
                $table->boolean('pay_boleto')->default(false);
                $table->boolean('pay_card_credit')->default(false);
                $table->smallInteger('pay_card_credit_installment_max')->nullable();
                $table->string('pay_card_credit_installment_fee_payer', 20)->default('customer');
                $table->integer('pay_card_credit_installment_amount_min')->nullable();
                $table->json('metadata')->nullable();

                $table->unique(['customer_id', 'slug']);
                $table->index(['customer_id', 'status']);
            });
        }

        if (! Schema::hasTable('tbs_product_plan')) {
            Schema::create('tbs_product_plan', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->timestamps();

                $table->uuid('product_id');
                $table->string('plan_name');
                $table->string('plan_code')->nullable();
                $table->text('description')->nullable();
                $table->string('status')->default('active');
                $table->bigInteger('amount');
                $table->string('interval_unit', 20)->default('month');
                $table->unsignedInteger('interval_count')->default(1);
                $table->unsignedInteger('trial_days')->default(0);
                $table->bigInteger('setup_fee_amount')->default(0);
                $table->boolean('is_default')->default(false);
                $table->unsignedInteger('sort_order')->default(0);
                $table->json('metadata')->nullable();

                $table->index(['product_id', 'status']);
                $table->unique(['product_id', 'plan_code']);
            });
        }

        if (! Schema::hasTable('tbs_subscription')) {
            Schema::create('tbs_subscription', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->timestamps();

                $table->uuid('product_id');
                $table->uuid('product_plan_id');
                $table->uuid('customer_id')->nullable();
                $table->uuid('buyer_id')->nullable();
                $table->bigInteger('amount_total');

                $table->string('status')->default('active');
                $table->unsignedInteger('current_cycle')->default(0);
                $table->timestamp('next_charge_at')->nullable();
                $table->timestamp('last_charge_at')->nullable();

                $table->string('card_token')->nullable();
                $table->string('card_description')->nullable();
                $table->string('card_validate_mm')->nullable();
                $table->string('card_validate_aaaa')->nullable();

                $table->timestamp('canceled_at')->nullable();
                $table->timestamp('paused_at')->nullable();
                $table->timestamp('error_at')->nullable();
                $table->text('error_message')->nullable();
                $table->json('metadata')->nullable();

                $table->index(['product_id', 'status']);
                $table->index(['buyer_id']);
                $table->index(['next_charge_at']);
            });
        }

        if (! Schema::hasTable('tbs_subscription_cycle')) {
            Schema::create('tbs_subscription_cycle', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->timestamps();

                $table->uuid('subscription_id');
                $table->unsignedInteger('cycle_number');
                $table->date('billing_date');
                $table->string('status')->default('pending');
                $table->uuid('subscription_order_id')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamp('last_attempt_at')->nullable();
                $table->timestamp('next_attempt_at')->nullable();
                $table->unsignedInteger('attempts_count')->default(0);
                $table->text('error_message')->nullable();

                $table->index(['subscription_id', 'cycle_number']);
                $table->index(['billing_date', 'status']);
                $table->index(['next_attempt_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tbs_subscription_cycle');
        Schema::dropIfExists('tbs_subscription');
        Schema::dropIfExists('tbs_product_plan');
        Schema::dropIfExists('tbs_product');
    }
};
