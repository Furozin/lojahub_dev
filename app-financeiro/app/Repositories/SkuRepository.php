<?php

namespace App\Repositories;

use App\Models\Sku;

class SkuRepository
{
    protected $model;

    public function __construct(Sku $sku)
    {
        $this->model = $sku;
    }

    public function updateOrCreate($attributes, $values)
    {
        return $this->model->updateOrCreate($attributes, $values);
    }
}