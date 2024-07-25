<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnexoSimplesNacional extends Model
{
    use HasFactory;

    protected $table = 'anexos_simples_nacional';

    protected $fillable = [
        'aliquota'
    ];

    protected $casts = [
        'aliquota' => 'array'
    ];

    private array $simplesNacionalTaxRates = [
        [
            'id' => 1,
            'aliquota' => [4, 7.3, 9.5, 10.7, 14.3, 19]
        ],
        [
            'id' => 2,
            'aliquota' => [4.5, 7.8, 10, 11.2, 14.7, 30]
        ]
    ];

    public function getSimplesNacionalTaxRates(): array
    {
        return $this->simplesNacionalTaxRates;
    }

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class, 'anexo_id');
    }

}
