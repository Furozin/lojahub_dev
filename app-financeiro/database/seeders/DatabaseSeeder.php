<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{
    Acesso,
    User
};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AnexoSimplesNacionalSeeder::class);
        $this->call(OrigemVendaSeeder::class);

        if (app()->isLocal())
        {
            Acesso::factory(1)->has(User::factory([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]))->create();

            Acesso::factory(49)->has(User::factory(1))->create();
            $this->call(CompanySeeder::class);
            $this->call(MarketplaceAccountSeeder::class);
        }
    }
}
