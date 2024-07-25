<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrigemVenda extends Model
{
    use HasFactory;

    protected $table = 'origens_vendas';

    protected $fillable = [
        'nome_canal'
    ];

    public array $providers = [
        [
            'id' => 1,
            'nome_canal' => 'Mercado Livre'
        ],
        [
            'id' => 2,
            'nome_canal' => 'Shopee'
        ],
        [
            'id' => 3,
            'nome_canal' => 'Amazon'
        ],
        [
            'id' => 4,
            'nome_canal' => 'Magalu'
        ],
        [
            'id' => 5,
            'nome_canal' => 'Americanas'
        ],
        [
            'id' => 6,
            'nome_canal' => 'Simplo7'
        ],
        [
            'id' => 7,
            'nome_canal' => 'Via Varejo'
        ],
        [
            'id' => 8,
            'nome_canal' => 'Shein'
        ],
        [
            'id' => 9,
            'nome_canal' => 'Desconhecido'
        ],
    ];

    public function getProviders(): array
    {
        return $this->providers;
    }

    public function contasCanaisDeVendas(): HasMany
    {
        return $this->hasMany(ContaCanalDeVenda::class, 'origem_id');
    }
}
