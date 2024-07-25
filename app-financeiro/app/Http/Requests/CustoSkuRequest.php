<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustoSkuRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'conta_canal_de_venda_id' => ['required', 'exists:contas_canais_de_vendas,id'],
            'empresa_id' => ['required', 'exists:empresas,id'],
            'custo_total' => ['required', 'numeric'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
