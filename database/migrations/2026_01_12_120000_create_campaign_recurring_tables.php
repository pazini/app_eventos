<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbc_campaign_subscription', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();

            $table->uuid('campaign_id');
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

            $table->index(['campaign_id', 'status']);
            $table->index(['buyer_id']);
            $table->index(['next_charge_at']);
        });

        Schema::create('tbc_campaign_subscription_cycle', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();

            $table->uuid('subscription_id');
            $table->unsignedInteger('cycle_number');
            $table->date('billing_date');
            $table->string('status')->default('pending');
            $table->uuid('campaign_order_id')->nullable();
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

    public function down(): void
    {
        Schema::dropIfExists('tbc_campaign_subscription_cycle');
        Schema::dropIfExists('tbc_campaign_subscription');
    }
};
