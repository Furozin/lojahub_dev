<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SkuImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'importFile' => ['required', 'mimes:csv,txt,xlsx', function ($attribute, $value, $fail) {
                $baseRequiredColumns = ['ID', 'Descrição', 'Situação', 'Fornecedor', 'Preço de custo'];
                $alternativeColumns = ['Código', 'Código (SKU)'];

                if ($value) {
                    // Identificando o delimitador
                    $content = file_get_contents($value->path());
                    $firstLine = strtok($content, "\n");

                    $delimiter = strpos($firstLine, '","') !== false ? ',' : (strpos($firstLine, ';') !== false ? ';' : null);

                    if (!$delimiter) {
                        $fail('Formato de arquivo não suportado.');
                        return;
                    }

                    $handle = fopen($value->path(), 'r');
                    $header = fgetcsv($handle, 0, $delimiter);

                    // Tratamento para remoção de caracteres indesejados
                    $header = array_map(function ($column) {
                        return trim(str_replace(["\xEF\xBB\xBF", "\u{FEFF}"], '', $column), '"');
                    }, $header);

                    fclose($handle);

                    // Lista para guardar colunas ausentes
                    $missingColumns = [];

                    // Verifica se todas as colunas estão presentes e armazena colunas ausentes
                    foreach ($baseRequiredColumns as $column) {
                        if (!in_array($column, $header)) {
                            $missingColumns[] = $column;
                        }
                    }

                    // Verifica se alguma das colunas alternativas está presente
                    $hasAlternative = false;
                    foreach ($alternativeColumns as $altColumn) {
                        if (in_array($altColumn, $header)) {
                            $hasAlternative = true;
                            break;
                        }
                    }

                    // Se não houver coluna alternativa, adiciona essa falha às colunas ausentes
                    if (!$hasAlternative) {
                        $missingColumns[] = 'Código ou Código (SKU)';
                    }

                    // Se houver colunas ausentes, falhe na validação e mostre quais estão faltando
                    if (!empty($missingColumns)) {
                        $fail('O arquivo CSV não possui as colunas necessárias: ' . implode(", ", $missingColumns) . '.');
                    }
                }
            }],
        ];
    }


    public function messages()
    {
        return [
            'importFile.required' => 'Um arquivo é necessário para a importação.',
            'importFile.mimes' => 'O arquivo deve ser um formato CSV ou TXT.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
