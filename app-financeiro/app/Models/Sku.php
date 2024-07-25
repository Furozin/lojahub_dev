<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Sku extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'skus';

    protected $fillable = [
        'acesso_id',
        'sku',
        'titulo',
        'descricao',
        'imagem',
        'status',
        'fornecedor',
        'dias_tempo_reposicao',
        'tags'
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    public function acesso(): BelongsTo
    {
        return $this->belongsTo(Acesso::class, 'acesso_id');
    }

    public function custosSkus(): HasMany
    {
        return $this->hasMany(CustoSku::class, 'sku_id');
    }

    public function itensVendas(): HasMany
    {
        return $this->hasMany(ItemVenda::class, 'venda_id');
    }

    public function scopeMeuAcesso(Builder $builder, int $acesso_id): void
    {
        $builder->where('acesso_id', $acesso_id);
    }

    public function urlImagem(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if ($attributes['imagem'] && Storage::disk('public')->exists("skus/{$attributes['imagem']}")) {
                    return Storage::disk('public')->url("skus/{$attributes['imagem']}");
                }
                return null;
            }
        );
    }
}
