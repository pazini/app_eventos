<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_app', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_app', 'app_slug')) {
                $table->string('app_slug', 255)->nullable()->after('app_name');
            }
        });

        $apps = DB::table('tb_app')->select('id', 'app_name', 'app_slug')->get();
        $usedSlugs = [];

        foreach ($apps as $app) {
            if ($app->app_slug) {
                $usedSlugs[] = $app->app_slug;
                continue;
            }

            $baseSlug = Str::slug($app->app_name ?? '');
            if ($baseSlug === '') {
                continue;
            }

            $slug = $baseSlug;
            $suffix = 2;

            while (in_array($slug, $usedSlugs, true)
                || DB::table('tb_app')->where('app_slug', $slug)->where('id', '!=', $app->id)->exists()
            ) {
                $slug = $baseSlug . '-' . $suffix;
                $suffix++;
            }

            $usedSlugs[] = $slug;
            DB::table('tb_app')->where('id', $app->id)->update(['app_slug' => $slug]);
        }

        Schema::table('tb_app', function (Blueprint $table) {
            $table->unique('app_slug', 'tb_app_app_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('tb_app', function (Blueprint $table) {
            if (Schema::hasColumn('tb_app', 'app_slug')) {
                $table->dropUnique('tb_app_app_slug_unique');
                $table->dropColumn('app_slug');
            }
        });
    }
};
