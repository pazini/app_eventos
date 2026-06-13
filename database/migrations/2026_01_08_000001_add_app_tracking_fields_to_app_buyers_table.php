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
        Schema::table('app_buyers', function (Blueprint $table) {
            $table->string('app_source')->nullable()->after('zip_code')->comment('Referer URL que originou o acesso com appUserUuid');
            $table->string('app_user_uuid')->nullable()->after('app_source')->index()->comment('UUID do usuário do app para tracking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_buyers', function (Blueprint $table) {
            $table->dropIndex(['app_user_uuid']);
            $table->dropColumn(['app_source', 'app_user_uuid']);
        });
    }
};
