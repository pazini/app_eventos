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
        Schema::table('app_events_orders', function (Blueprint $table) {
            // Campos de segurança e rastreabilidade
            $table->string('order_ip_address', 45)->nullable()->after('buyer_json_answers');
            $table->text('order_user_agent')->nullable()->after('order_ip_address');
            $table->string('order_device_type', 20)->nullable()->after('order_user_agent'); // mobile, tablet, desktop
            $table->string('order_browser', 50)->nullable()->after('order_device_type');
            $table->string('order_platform', 50)->nullable()->after('order_browser'); // Windows, Mac, Linux, Android, iOS
            $table->string('order_session_id', 100)->nullable()->after('order_platform');
            $table->timestamp('order_tracking_timestamp')->nullable()->after('order_session_id');

            // Índices para melhor performance em consultas de segurança
            $table->index('order_ip_address');
            $table->index('order_tracking_timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_events_orders', function (Blueprint $table) {
            $table->dropIndex(['order_ip_address']);
            $table->dropIndex(['order_tracking_timestamp']);

            $table->dropColumn([
                'order_ip_address',
                'order_user_agent',
                'order_device_type',
                'order_browser',
                'order_platform',
                'order_session_id',
                'order_tracking_timestamp',
            ]);
        });
    }
};
