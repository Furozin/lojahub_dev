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

        Schema::create('skus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acesso_id')->constrained();
            $table->string('sku', 150);
            $table->string('titulo', 150);
            $table->text('descricao')->nullable();
            $table->string('imagem')->nullable();
            $table->char('status', 2)->default(1);
            $table->string('fornecedor', 150);
            $table->integer('dias_tempo_reposicao')->nullable()->default(0);
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skus');
    }
};
