<?php

namespace App\Services;

use App\Repositories\SkuRepository;
use App\Repositories\CostSkuRepository;

class SkuService
{
    public function __construct(
        protected SkuRepository $skuRepo,
        protected CostSkuRepository $custoSkuRepo
    ){}

    public function processRows(
        array $data,
        int|null $contaCanalDeVendaId,
        int $enterpriseId,
        int $acessoId
    ): void
    {
        $headers = $data[0];

        // Definição campo da imagem
        $imageColumn = null;
        if (array_search('URL imagem 1', $headers) !== false)
        {
            $imageColumn = $data[0][array_search('URL imagem 1', $headers)];
        }
        elseif (array_search('URL Imagens Externas', $headers) !== false)
        {
            $imageColumn = $data[0][array_search('URL Imagens Externas', $headers)];
        }

        // Definição do campo SKU
        $skuIndex           = array_search('Código', $headers);
        if ($skuIndex === false)
        {
            $skuIndex = array_search('Código (SKU)', $headers);
        }

        // Definição dos outros campos
        $descricaoIndex     = array_search('Descrição', $headers);
        $imageColumnIndex   = array_search($imageColumn, $headers);
        $statusIndex        = array_search('Situação', $headers);
        $fornecedorIndex    = array_search('Fornecedor', $headers);
        $precoCustoIndex    = array_search('Preço de custo', $headers);

        // Remove o cabeçalho para processar apenas os dados
        unset($data[0]);

        foreach ($data as $row)
        {
            $skuValue           = trim($row[$skuIndex]);
            $tituloValue        = trim($row[$descricaoIndex]);
            $descricaoValue     = $tituloValue;
            $imagemValue        = $imageColumnIndex !== false ? trim($row[$imageColumnIndex]) : '';
            $statusValue        = trim($row[$statusIndex]);
            $fornecedorValue    = trim($row[$fornecedorIndex]);
            $precoValue         = str_replace(',', '.', str_replace('.', '', trim($row[$precoCustoIndex])));

            if (empty($precoValue) || $precoValue <= 0)
            {
                $precoValue = str_replace(',', '.', str_replace('.', '', $row[array_search('Preço', $headers)]));
            }

            //Caso alguma desses valores estejam vazios o banco não irá aceitar, então iremos pular a inserção dessa linha
            if (empty($skuValue) || empty($tituloValue))
            {
                continue;
            }

            $sku = $this->skuRepo->updateOrCreate(
                ['acesso_id' => $acessoId, 'sku' => $skuValue],
                [
                    'titulo'        => $tituloValue,
                    'descricao'     => empty($descricaoValue) ? null : $descricaoValue,
                    'imagem'        => empty($imagemValue) ? null : $imagemValue,
                    'status'        => $statusValue == 'Ativo' ? '1' : '0',
                    'fornecedor'    => empty($fornecedorValue) ? 'Não informado' : $fornecedorValue
                ]
            );

            if (!is_null($contaCanalDeVendaId))
            {

                $this->custoSkuRepo->updateOrCreate(
                    [
                        'sku_id'                    => $sku->id,
                        'conta_canal_de_venda_id'   => $contaCanalDeVendaId,
                        'empresa_id'                => $enterpriseId
                    ],
                    [
                        'custo_total'               => empty($precoValue) ? '0' : $precoValue
                    ]
                );
            }
        }
    }
}
