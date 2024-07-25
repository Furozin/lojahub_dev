<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnterpriseInsertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'anexo_id' => ['required', 'integer'],
            'razao_social' => ['required', 'string', 'min:5', 'max:100'],
            'nome_fantasia' => ['required', 'string', 'min:5', 'max:100'],
            'cnpj' => [
                'required',
                'string',
                'max:20',
                'min:18',
                ($this->request->get('_method' === 'PUT')) ? Rule::unique('empresas')->ignore($this->request->get('id')) : ''
            ],
            'regime_tributario' => ['string', 'max:2'],
            'email' => ['email', 'max:255', 'nullable'],
            'telefone' => ['string', 'max:50', 'nullable'],
            'rua' => ['string', 'max:100', 'required'],
            'bairro' => ['string', 'max:50', 'required'],
            'numero' => ['string', 'max:50','required'],
            'cep' => ['string', 'max:20', 'required'],
            'complemento' => ['string', 'max:100', 'nullable'],
            'municipio' => ['string', 'max:100', 'required'],
            'uf' => ['string', 'max:50', 'required'],
            'pais' => ['string', 'max:50', 'required']
        ];
    }
}
