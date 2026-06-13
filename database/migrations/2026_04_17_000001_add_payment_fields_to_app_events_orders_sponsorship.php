<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_events_orders_sponsorship', function (Blueprint $table) {
            $table->uuid('payment_id')->nullable()->after('status_old');
            $table->uuid('slip_id')->nullable()->after('payment_id');
            $table->string('slip_description')->nullable()->after('slip_id');
        });
    }

    public function down(): void
    {
        Schema::table('app_events_orders_sponsorship', function (Blueprint $table) {
            $table->dropColumn(['payment_id', 'slip_id', 'slip_description']);
        });
    }
};
