<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tbc_campaign as c')
            ->join('tbc_campaign_organizer as o', 'o.id', '=', 'c.organizer_id')
            ->whereNotNull('o.organizer_slug')
            ->select('c.id', 'c.customer_organization_slug', 'o.organizer_slug')
            ->orderBy('c.id')
            ->chunk(500, function ($rows) {
                foreach ($rows as $row) {
                    if (($row->customer_organization_slug ?? null) === $row->organizer_slug) {
                        continue;
                    }

                    DB::table('tbc_campaign')
                        ->where('id', $row->id)
                        ->update([
                            'customer_organization_slug' => $row->organizer_slug,
                            'updated_at' => now(),
                        ]);
                }
            });
    }

    public function down(): void
    {
        // Migração de correção de dados sem rollback automático.
    }
};
