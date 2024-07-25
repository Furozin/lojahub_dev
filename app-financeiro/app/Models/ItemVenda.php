<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemVenda extends Model
{
    use HasFactory;

    protected $table = 'itens_vendas';

    protected $fillable = [
        'venda_id',
        'sku_id',
        'quantidade',
        'valor_unitario',
        'valor_total',
        'desconto_total',
        'custo_unitario'
    ];


    public function venda(): BelongsTo
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function sku(): BelongsTo
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }
}
