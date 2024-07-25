<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustoSku extends Model
{
    use HasFactory;

    protected $table = 'custos_skus';

    protected $fillable = [
        'sku_id',
        'conta_canal_de_venda_id',
        'empresa_id',
        'custo_total'
    ];

    public function sku(): BelongsTo
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }

    public function contaCanalDeVenda(): BelongsTo
    {
        return $this->belongsTo(ContaCanalDeVenda::class, 'conta_canal_de_venda_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

}
