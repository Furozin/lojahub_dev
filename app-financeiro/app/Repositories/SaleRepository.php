<?php

namespace App\Repositories;
use App\Models\Venda;

class SaleRepository
{
    public function __construct(protected Venda $model){}

    public function updateOrCreate($attributes, $values): Venda
    {
        return $this->model->updateOrCreate($attributes, $values);
    }
}
