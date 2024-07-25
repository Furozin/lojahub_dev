<?php

namespace App\Repositories;

use App\Models\CustoSku;

class CostSkuRepository 
{
    protected $model;

    public function __construct(CustoSku $custoSku)
    {
        $this->model = $custoSku;
    }

    public function updateOrCreate($attributes, $values)
    {
        return $this->model->updateOrCreate($attributes, $values);
    }
}
