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
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acesso_id')->constrained();
            $table->foreignId('empresa_id')->constrained();
            $table->unsignedBigInteger('conta_canal_de_venda_id')->comment('ID da conta no canal/mkt');
            $table->foreign('conta_canal_de_venda_id')->references('id')->on('contas_canais_de_vendas');
            $table->string('canal_venda_id', 150)->comment('ID da venda no canal/mkt');
            $table->dateTime('data_criacao');
            $table->dateTime('data_pagamento');
            $table->dateTime('data_cancelamento')->nullable();
            $table->decimal('valor_total');
            $table->decimal('valor_restante_no_canal_de_venda');
            $table->decimal('valor_total_dos_produtos');
            $table->decimal('custo_de_envio');
            $table->string('canal_envio_id', 150);
            $table->decimal('custo_de_comissao');
            $table->decimal('outras_entradas');
            $table->string('aliquota_imposto', 10);
            $table->decimal('valor_imposto_das')->nullable();
            $table->decimal('lucro_bruto');
            $table->decimal('lucro_liquido');
            $table->string('chave_nfe', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendas');
    }
};
