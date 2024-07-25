<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContaCanalDeVenda extends Model
{
    use HasFactory;

    protected $table = 'contas_canais_de_vendas';

    protected $fillable = [
        'acesso_id',
        'empresa_id',
        'origem_id',
        'nome',
        'comissao',
        'referencia_no_canal_de_venda',
    ];

    protected $casts = [
        'comissao' => 'array'
    ];

    public function acesso(): BelongsTo
    {
        return $this->belongsTo(Acesso::class, 'acesso_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function origemVenda(): BelongsTo
    {
        return $this->belongsTo(OrigemVenda::class, 'origem_id');
    }

    public function custosSkus(): HasMany
    {
        return $this->hasMany(CustoSku::class, 'conta_canal_de_venda_id');
    }

    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class, 'conta_canal_de_venda_id');
    }

    public function scopeMeuAcesso(Builder $builder, int $acesso_id): void
    {
        $builder->where('acesso_id', $acesso_id);
    }
}
