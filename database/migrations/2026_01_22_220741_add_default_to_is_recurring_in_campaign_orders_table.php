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
        Schema::table('tbc_campaign_order', function (Blueprint $table) {
            // Altera a coluna is_recurring para nullable
            $table->boolean('is_recurring')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbc_campaign_order', function (Blueprint $table) {
            // Remove nullable
            $table->boolean('is_recurring')->nullable(false)->change();
        });
    }
};
