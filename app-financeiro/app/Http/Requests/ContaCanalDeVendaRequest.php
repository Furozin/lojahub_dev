<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContaCanalDeVendaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'origem_id' => ['required', 'exists:origens_vendas,id'],
            'nome' => ['required'],
            'comissao' => ['required'],
            'referencia_no_canal_de_venda' => ['required', 'max:150'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
