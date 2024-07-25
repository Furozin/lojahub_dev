<?php

namespace Database\Seeders;

use App\Models\ContaCanalDeVenda;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarketplaceAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContaCanalDeVenda::query()->create([
            'acesso_id' => 1,
            'empresa_id' => 1,
            'origem_id' => 1,
            'nome' => 'Lojahub ML',
            'comissao' => [
                'mkt' => 2.00
            ],
            'referencia_no_canal_de_venda' => 'LHML0001'
        ]);
    }
}
