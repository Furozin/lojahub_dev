<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SkuRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sku' => ['required'],
            'titulo' => ['required'],
            'descricao' => ['nullable'],
            'status' => ['required'],
            'fornecedor' => ['required'],
            'dias_tempo_reposicao' => ['required', 'integer'],
            'tags' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
