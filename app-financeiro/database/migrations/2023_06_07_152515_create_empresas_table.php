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

        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acesso_id')->constrained();
            $table->unsignedBigInteger('anexo_id');
            $table->foreign('anexo_id')->references('id')->on('anexos_simples_nacional');
            $table->string('razao_social', 100);
            $table->string('nome_fantasia', 100);
            $table->string('cnpj', 50);
            $table->char('regime_tributario', 2);
            $table->string('email', 100)->nullable();
            $table->string('telefone', 50)->nullable();
            $table->string('rua', 100);
            $table->string('numero', 50);
            $table->string('bairro', 50);
            $table->string('cep', 20);
            $table->string('complemento', 100)->nullable();
            $table->string('municipio', 100);
            $table->string('uf', 50);
            $table->string('pais', 50);
            $table->char('status', 2)->default(1);
            $table->string('logo_url')->nullable();
            $table->text('logo_base64')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
