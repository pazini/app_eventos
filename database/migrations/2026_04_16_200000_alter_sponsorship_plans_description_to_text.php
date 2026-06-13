<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE tev_events_sponsorship_plans ALTER COLUMN description TYPE text');
    }

    public function down()
    {
        DB::statement('ALTER TABLE tev_events_sponsorship_plans ALTER COLUMN description TYPE character varying(255)');
    }
};
