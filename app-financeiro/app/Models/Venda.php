<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venda extends Model
{
    use HasFactory;

    protected $table = 'vendas';

    protected $fillable = [
        'acesso_id',
        'empresa_id',
        'conta_canal_de_venda_id',
        'canal_venda_id',
        'data_criacao',
        'data_pagamento',
        'data_cancelamento',
        'valor_total',
        'valor_restante_no_canal_de_venda',
        'valor_total_dos_produtos',
        'custo_de_envio',
        'canal_envio_id',
        'custo_de_comissao',
        'outras_entradas',
        'aliquota_imposto',
        'valor_imposto_das',
        'lucro_bruto',
        'lucro_liquido',
        'chave_nfe'
    ];

    public function acesso(): BelongsTo
    {
        return $this->belongsTo(Acesso::class, 'acesso_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function contaCanalDeVenda(): BelongsTo
    {
        return $this->belongsTo(ContaCanalDeVenda::class, 'conta_canal_de_venda_id');
    }

    public function itensVendas(): HasMany
    {
        return $this->hasMany(ItemVenda::class, 'venda_id');
    }

}
