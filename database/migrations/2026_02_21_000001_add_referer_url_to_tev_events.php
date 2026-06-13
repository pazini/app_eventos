<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tev_events', function (Blueprint $table) {
            $table->string('referer_url', 255)->nullable()->after('event_slug')
                ->comment('URL base do domínio DOMAIN_EVENTOS da instância onde o evento foi criado');
        });
    }

    public function down(): void
    {
        Schema::table('tev_events', function (Blueprint $table) {
            $table->dropColumn('referer_url');
        });
    }
};
