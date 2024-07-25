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
        Schema::create('itens_vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id')->constrained();
            $table->foreignId('sku_id')->constrained();
            $table->integer('quantidade');
            $table->decimal('valor_unitario');
            $table->decimal('valor_total');
            $table->decimal('desconto_total');
            $table->decimal('custo_unitario');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens_vendas');
    }
};
