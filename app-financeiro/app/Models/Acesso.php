<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Acesso extends Model
{
    use HasFactory;

    protected $table = 'acessos';

    protected $fillable = [
        'nome',
        'telefone'
    ];


    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'acesso_id');
    }

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class, 'acesso_id');
    }

    public function skus(): HasMany
    {
        return $this->hasMany(Sku::class, 'acesso_id');
    }

    public function contasCanaisDeVendas(): HasMany
    {
        return $this->hasMany(ContaCanalDeVenda::class, 'acesso_id');
    }

    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class, 'acesso_id');
    }
}
