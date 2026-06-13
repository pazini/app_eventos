<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tbs_product_plan')) {
            return;
        }

        Schema::table('tbs_product_plan', function (Blueprint $table) {
            if (! Schema::hasColumn('tbs_product_plan', 'url_image_header')) {
                $table->string('url_image_header', 255)->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('tbs_product_plan')) {
            return;
        }

        Schema::table('tbs_product_plan', function (Blueprint $table) {
            if (Schema::hasColumn('tbs_product_plan', 'url_image_header')) {
                $table->dropColumn('url_image_header');
            }
        });
    }
};
