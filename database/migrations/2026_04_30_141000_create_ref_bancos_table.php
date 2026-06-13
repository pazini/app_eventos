<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ref_bancos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ref_cod', 20)->unique();
            $table->string('ref_banco', 255);
            $table->string('ref_banco_descricao', 255)->nullable();
            $table->boolean('to_view')->default(true);
            $table->timestamps();
        });

        DB::table('ref_bancos')->insert([
            [
                'id' => (string) Str::uuid(),
                'ref_cod' => '206',
                'ref_banco' => 'PagBank Instituicao de pagamento',
                'ref_banco_descricao' => 'PagSeguro',
                'to_view' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'ref_cod' => '001',
                'ref_banco' => 'Banco do Brasil',
                'ref_banco_descricao' => 'BB',
                'to_view' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'ref_cod' => '237',
                'ref_banco' => 'Bradesco',
                'ref_banco_descricao' => 'Bradesco',
                'to_view' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_bancos');
    }
};
