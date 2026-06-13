<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tbc_campaign_order', 'buyer_contact_country')) {
            Schema::table('tbc_campaign_order', function (Blueprint $table) {
                $table->string('buyer_contact_country', 10)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tbc_campaign_order', 'buyer_contact_country')) {
            Schema::table('tbc_campaign_order', function (Blueprint $table) {
                $table->dropColumn('buyer_contact_country');
            });
        }
    }
};

