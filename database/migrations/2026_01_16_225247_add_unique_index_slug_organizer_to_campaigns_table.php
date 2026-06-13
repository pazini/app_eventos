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
        Schema::table('tbc_campaign', function (Blueprint $table) {
            // Remove o índice único simples do slug se existir
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('tbc_campaign');

            // Verifica se existe um índice único apenas para 'slug'
            foreach ($indexesFound as $index) {
                if ($index->isUnique() && count($index->getColumns()) === 1 && in_array('slug', $index->getColumns())) {
                    $table->dropUnique($index->getName());
                }
            }

            // Adiciona índice único composto: slug + organizer_id
            // Garante que não existam campanhas com o mesmo nome do mesmo organizador
            $table->unique(['slug', 'organizer_id'], 'unique_slug_organizer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbc_campaign', function (Blueprint $table) {
            // Remove o índice único composto
            $table->dropUnique('unique_slug_organizer');

            // Restaura o índice único simples no slug
            $table->unique('slug');
        });
    }
};
