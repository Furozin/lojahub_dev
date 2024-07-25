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

        Schema::create('contas_canais_de_vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acesso_id')->constrained();
            $table->foreignId('empresa_id')->constrained();
            $table->unsignedBigInteger('origem_id');
            $table->foreign('origem_id')->references('id')->on('origens_vendas');
            $table->string('nome', 100);
            $table->json('comissao');
            $table->string('referencia_no_canal_de_venda', 150);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas_canais_de_vendas');
    }
};
