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
            // Adiciona coluna my_key (birth_date + doc_num) como chave única
            // Formato: 1992-02-14.14225119793
            $table->string('my_key', 100)->nullable()->after('doc_num');
            $table->unique('my_key');
            $table->index('my_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_buyers', function (Blueprint $table) {
            $table->dropUnique(['my_key']);
            $table->dropIndex(['my_key']);
            $table->dropColumn('my_key');
        });
    }
};
