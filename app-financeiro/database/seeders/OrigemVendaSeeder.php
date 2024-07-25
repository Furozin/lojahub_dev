<?php

namespace Database\Seeders;

use App\Models\OrigemVenda;
use Illuminate\Database\Seeder;

class OrigemVendaSeeder extends Seeder
{
    public function run(): void
    {
        $providers = collect( (new OrigemVenda())->getProviders() );
        $providers->each(fn($provider) => OrigemVenda::query()
            ->updateOrCreate(
                [
                    'id' => $provider['id']
                ],
                [
                    'nome_canal' => $provider['nome_canal']
                ]
            )
        );
    }
}
