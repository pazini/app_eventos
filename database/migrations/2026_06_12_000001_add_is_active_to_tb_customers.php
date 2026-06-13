<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_customers', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('generate_invoice');
        });
    }

    public function down(): void
    {
        Schema::table('tb_customers', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
