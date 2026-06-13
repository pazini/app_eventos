<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tbs_product_organizer')) {
            return;
        }

        Schema::create('tbs_product_organizer', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();

            $table->uuid('customer_id');
            $table->string('organizer_slug', 255);
            $table->string('organizer_name', 255);
            $table->string('organizer_name_full', 255);
            $table->string('owner_name', 255)->nullable();
            $table->string('owner_email', 255)->nullable();
            $table->string('owner_phone_country', 255)->nullable();
            $table->string('owner_phone_ddd', 255)->nullable();
            $table->string('owner_phone_num', 255)->nullable();
            $table->string('url_image_logo', 255)->nullable();
            $table->string('url_image_thumbnail', 255)->nullable();
            $table->string('url_image', 255)->nullable();
            $table->string('url_image_bg', 255)->nullable();
            $table->string('url_site', 255)->nullable();
            $table->string('url_instagram', 255)->nullable();
            $table->string('url_facebook', 255)->nullable();
            $table->uuid('customer_pay_gateway_id')->nullable();
            $table->string('customer_pay_gateway_seller_recipient_id', 255)->nullable();

            $table->unique('organizer_slug');
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbs_product_organizer');
    }
};
