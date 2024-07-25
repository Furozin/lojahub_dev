<?php

namespace App\View\Composers;

use Illuminate\Support\Facades;
use App\Models\OrigemVenda;
use Illuminate\View\View;

class MultiComposer
{
    /**
     * Create a new multi composer.
     */
    public function __construct(
        protected OrigemVenda $channels
    ) {}

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with('origens', $this->channels::query()->where('origens_vendas.id', '<>', 9)->get());
    }

}
