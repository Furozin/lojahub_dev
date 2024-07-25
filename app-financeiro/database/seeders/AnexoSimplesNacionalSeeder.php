<?php

namespace Database\Seeders;

use App\Models\AnexoSimplesNacional;
use Illuminate\Database\Seeder;

class AnexoSimplesNacionalSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $taxRates = collect( (new AnexoSimplesNacional())->getSimplesNacionalTaxRates() );
        $taxRates->each( fn($rate) => AnexoSimplesNacional::query()
            ->updateOrCreate(
                ['id' => $rate['id']],
                ['aliquota' => $rate['aliquota']]
            )
        );
    }
}
