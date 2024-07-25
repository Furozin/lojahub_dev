<?php

namespace App\Services;
use App\Models\Sku;
use App\Models\ItemVenda;
use App\Services\ProcessColumnSaleImportService;

use Illuminate\Support\Facades\Log;

class ItemSaleImportService
{
    protected $processColumnSale;

    public function __construct(ProcessColumnSaleImportService $processColumnSale)
    {   
        $this->processColumnSale    = $processColumnSale;
    }    

    public function saveItensVendas($itens, $vendaId, $acessoId, $enterpriseId) 
    {
        // Verifica se o SKU existe
        $sku = Sku::firstOrCreate(
            ['sku' => $itens['codigoSku']],
            [
                'acesso_id'             => $acessoId,
                'sku'                   => $itens['codigoSku'],
                'titulo'                => $itens['descricao'],
                'descricao'             => $itens['descricao'],
                'fornecedor'            => 'Não informado',
                'dias_tempo_reposicao'  => '0',
                'enterprise_id'         => $enterpriseId
            ]
        );

        $valor_total = $itens['quantidade'] * $itens['valorUnitario'];

        ItemVenda::UpdateOrcreate(
            [
                'venda_id'  => $vendaId,
                'sku_id'    => $sku->id
            ],
            [
                'venda_id'          => $vendaId,
                'sku_id'            => $sku->id,
                'quantidade'        => $itens['quantidade'],
                'valor_unitario'    => $itens['valorUnitario'],
                'valor_total'       => $valor_total,
                'desconto_total'    => $itens['descontoItem'],
                'custo_unitario'    => $itens['valorUnitario']
            ]
        );
    }

    public function processItens($params) {
        // Identificar os índices das colunas necessárias no cabeçalho
        $quantidade         = $this->processColumnSale->processQuantidadeColumn($params);
       
        $valorUnitario      = $this->processColumnSale->processValorUnitarioColumn($params);

        $descontoItem       = $this->processColumnSale->processdescontoItemColumn($params);
    
        // Extrair os dados do item da linha atual
        $item = [
            'quantidade'        => isset($quantidade) ? $quantidade : 0,
            'precoUnitario'     => isset($valorUnitario) ? $valorUnitario : 0,
            'desconto'          => isset($descontoItem) ? $descontoItem : 0,
        ];
    
        return $item;
    }

}