<?php

namespace Database\Factories;

use App\Models\Acesso;
use App\Models\Sku;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SkuFactory extends Factory
{
    protected $model = Sku::class;

    public function definition(): array
    {
        return [
            'acesso_id' => Acesso::query()
                ->inRandomOrder()
                ->limit(1)
                ->first()->id,
            'sku' => $this->faker->word(),
            'titulo' => $this->faker->word(),
            'descricao' => $this->faker->word(),
            'imagem' => $this->faker->word(),
            'status' => $this->faker->randomElement([1, 2]),
            'fornecedor' => $this->faker->word(),
            'dias_tempo_reposicao' => $this->faker->randomNumber(),
            'tags' => $this->faker->words(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => Carbon::now(),
        ];
    }
}
