<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_notificacoes_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();

            $table->string('target_ref');
            $table->uuid('target_id');
            $table->uuid('campaign_id')->nullable();
            $table->uuid('customer_id')->nullable();

            $table->string('channel')->default('email');
            $table->string('notification_type');
            $table->string('status');

            $table->string('recipient_email');
            $table->string('recipient_name')->nullable();
            $table->string('subject')->nullable();
            $table->text('error_message')->nullable();
            $table->json('meta')->nullable();

            $table->index(['target_ref', 'target_id']);
            $table->index('campaign_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_notificacoes_logs');
    }
};
