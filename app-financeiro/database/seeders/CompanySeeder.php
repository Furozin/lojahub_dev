<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Empresa::query()->create([
            'acesso_id' => 1,
            'anexo_id' => 1,
            'razao_social' => 'THEOBALD SISTEMAS LTDA',
            'nome_fantasia' => 'LOJA HUB',
            'cnpj' => '39.795.614/0001-42',
            'regime_tributario' => 1,
            'email' => 'gilmar@lojahub.com.br',
            'telefone' => '(11) 4139-7980',
            'rua' => 'RUA CABO ANTONIO PINTON',
            'numero' => 31,
            'bairro' => 'PARQUE NOVO MUNDO',
            'cep' => '02186-000',
            'municipio' => 'São Paulo',
            'uf' => 'SP',
            'pais' => 'BRASIL'
        ]);
    }
}
