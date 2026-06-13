<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tb_app_modules')) {
            return;
        }

        $exists = DB::table('tb_app_modules')
            ->where('slug', 'assinaturas')
            ->exists();

        if ($exists) {
            return;
        }

        DB::table('tb_app_modules')->insert([
            'id' => (string) Str::uuid(),
            'slug' => 'assinaturas',
            'module_name' => 'Assinaturas',
            'module_description' => 'Módulo de gestão de assinaturas',
            'module_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('tb_app_modules')) {
            return;
        }

        DB::table('tb_app_modules')
            ->where('slug', 'assinaturas')
            ->delete();
    }
};
