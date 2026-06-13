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
        Schema::table('tb_app', function (Blueprint $table) {
            $table->string('url_image_default_thumb', 500)->nullable()->after('url_image_favicon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_app', function (Blueprint $table) {
            $table->dropColumn('url_image_default_thumb');
        });
    }
};
