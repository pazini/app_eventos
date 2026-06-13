<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbc_campaign', function (Blueprint $table) {
            $table->boolean('allow_recurring')->default(false);
        });

        Schema::table('tbc_campaign_order', function (Blueprint $table) {
            $table->uuid('subscription_id')->nullable();
            $table->uuid('subscription_cycle_id')->nullable();
            $table->boolean('is_recurring')->default(false);

            $table->index(['subscription_id']);
            $table->index(['subscription_cycle_id']);
        });

        Schema::table('tbc_campaign_payment', function (Blueprint $table) {
            $table->uuid('subscription_id')->nullable();
            $table->uuid('subscription_cycle_id')->nullable();

            $table->index(['subscription_id']);
            $table->index(['subscription_cycle_id']);
        });

        Schema::table('tbc_campaign_payment_attempt', function (Blueprint $table) {
            $table->uuid('subscription_id')->nullable();
            $table->uuid('subscription_cycle_id')->nullable();
            $table->unsignedInteger('attempt_number')->nullable();
            $table->timestamp('scheduled_at')->nullable();

            $table->index(['subscription_id']);
            $table->index(['subscription_cycle_id']);
        });
    }

    public function down(): void
    {
        Schema::table('tbc_campaign_payment_attempt', function (Blueprint $table) {
            $table->dropIndex(['subscription_id']);
            $table->dropIndex(['subscription_cycle_id']);
            $table->dropColumn(['subscription_id', 'subscription_cycle_id', 'attempt_number', 'scheduled_at']);
        });

        Schema::table('tbc_campaign_payment', function (Blueprint $table) {
            $table->dropIndex(['subscription_id']);
            $table->dropIndex(['subscription_cycle_id']);
            $table->dropColumn(['subscription_id', 'subscription_cycle_id']);
        });

        Schema::table('tbc_campaign_order', function (Blueprint $table) {
            $table->dropIndex(['subscription_id']);
            $table->dropIndex(['subscription_cycle_id']);
            $table->dropColumn(['subscription_id', 'subscription_cycle_id', 'is_recurring']);
        });

        Schema::table('tbc_campaign', function (Blueprint $table) {
            $table->dropColumn('allow_recurring');
        });
    }
};
