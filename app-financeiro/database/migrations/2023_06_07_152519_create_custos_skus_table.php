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
        Schema::create('custos_skus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sku_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('conta_canal_de_venda_id');
            $table->foreign('conta_canal_de_venda_id')
                ->references('id')
                ->on('contas_canais_de_vendas')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('empresa_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->decimal('custo_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custos_skus');
    }
};
