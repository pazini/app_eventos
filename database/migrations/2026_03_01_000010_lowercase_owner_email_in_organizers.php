<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tb_customers_organizers')
            ->whereNotNull('owner_email')
            ->where('owner_email', '<>', '')
            ->update([
                'owner_email' => DB::raw('LOWER(TRIM(owner_email))'),
            ]);

        DB::table('tbc_campaign_organizer')
            ->whereNotNull('owner_email')
            ->where('owner_email', '<>', '')
            ->update([
                'owner_email' => DB::raw('LOWER(TRIM(owner_email))'),
            ]);

        DB::table('tbs_product_organizer')
            ->whereNotNull('owner_email')
            ->where('owner_email', '<>', '')
            ->update([
                'owner_email' => DB::raw('LOWER(TRIM(owner_email))'),
            ]);
    }

    public function down(): void
    {
        // No-op: conversão para lowercase não possui rollback seguro.
    }
};

