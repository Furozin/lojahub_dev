<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;

class ProcessColumnSaleImportService
{
    public function __construct(){}    

    public function processNumeroPedidoColumn($row, $headers)
    {
        // Procurar índices das possíveis colunas de número do pedido
        $numeroPedidoLojaVirtualIndex   = array_search('N° do Pedido na Loja Virtual', $headers);
        $numeroPedidoComplexoIndex      = array_search('N° do Pedido', $headers);
        $numeroPedidoSimplesIndex       = array_search('Número do pedido', $headers);        
    
        // Definir a lógica para selecionar qual número de pedido usar
        if ($numeroPedidoLojaVirtualIndex !== false && !empty(trim($row[$numeroPedidoLojaVirtualIndex]))) {
            return [
                'numeroPedido' => trim($row[$numeroPedidoLojaVirtualIndex]),
                'tipoArquivo' => 'bling'
            ];
        } elseif ($numeroPedidoComplexoIndex !== false && !empty(trim($row[$numeroPedidoComplexoIndex]))) {
            return [
                'numeroPedido' => trim($row[$numeroPedidoComplexoIndex]),
                'tipoArquivo' => 'bling'
            ];
        } elseif ($numeroPedidoSimplesIndex !== false) {
            return [
                'numeroPedido' => trim($row[$numeroPedidoSimplesIndex]),
                'tipoArquivo' => 'tiny'
            ];
        } else {
            Log::warning('Número do pedido não encontrado na linha', ['row' => $row]);
            return null;
        }
    }

    public function processCodigoSkuColumn($params)
    {
        if( $params['fileType'] === 'tiny')
        {
            $codigoSkuIndex   = array_search('Código (SKU)', $params['headers']);
        }
        else
        {
            $codigoSkuIndex   = array_search('Código do produto', $params['headers']);
        }
        $codigoSku        = $params['row'][$codigoSkuIndex];

        return $codigoSku;
    }

    public function processPrecoTotalColumn($params)
    {
        $precoTotalIndex = array_search('Preço Total', $params['headers']) ?: array_search('Valor unitário', $params['headers']);

        $precoTotal = $params['row'][$precoTotalIndex];

        if ($params['fileType'] === 'tiny') {
            return $this->convertToFloatTiny($precoTotal);
        } else {
            return $this->convertToFloatBling($precoTotal, $params['fileExtension']);
        }
    }

    public function processQuantidadeColumn($params)
    {
        $quantidadeIndex    = array_search('Quantidade', $params['headers']);
        $quantidade         = $params['row'][$quantidadeIndex];

        // Agora, a lógica de conversão difere com base no tipo de arquivo
        if ($params['fileType'] === 'tiny') {
            return $this->convertToFloatTiny($quantidade);
        } else {
            return $this->convertToFloatBling($quantidade, $params['fileExtension']);
        }
    }

    public function processFreteColumn($params)
    {
        $fretelIndex    = array_search('Frete', $params['headers']) ?: array_search('Frete pedido', $params['headers']);
        $frete          = $params['row'][$fretelIndex];

         // Agora, a lógica de conversão difere com base no tipo de arquivo
         if ($params['fileType'] === 'tiny') {
            return $this->convertToFloatTiny($frete);
        } else {
            return $this->convertToFloatBling($frete, $params['fileExtension']);
        }
        
        return $frete;
    }

    public function processDataCriacaoColumn($params)
    {
        $dataCriacaoIndex   = array_search('Data', $params['headers']);
        $dataCriacao        = $params['row'][$dataCriacaoIndex];

        return $dataCriacao;
    }

    public function processDataPagamentoColumn($params)
    {
        $dataPagamentoIndex   = array_search('Data', $params['headers']);
        $dataPagamento        = $params['row'][$dataPagamentoIndex];

        return $dataPagamento;
    }

    public function processValorUnitarioColumn($params)
    {
        $valorUnitarioIndex = array_search('Valor unitário', $params['headers']);
        $valorUnitario      = $params['row'][$valorUnitarioIndex];

        // Agora, a lógica de conversão difere com base no tipo de arquivo
        if ($params['fileType'] === 'tiny') {
            return $this->convertToFloatTiny($valorUnitario);
        } else {
            return $this->convertToFloatBling($valorUnitario, $params['fileExtension']);
        }
    }

    public function processChaveNfeColumn($params)
    {
        $chaveNfeIndex  = array_search('Nº da NFe', $params['headers']);
        $chaveNfe       = $params['row'][$chaveNfeIndex];

        return $chaveNfe;
    }

    public function processCanalEnvioIdColumn($params)
    {
        if($params['fileType'] === 'tiny')
        {
            $canalEnvioIdIndex  = array_search('Código de rastreamento', $params['headers']);
            $canalEnvioId       = $params['row'][$canalEnvioIdIndex];
        }
        else
        {
            $canalEnvioId = null;
        }

        return $canalEnvioId;
    }
   
    public function processDescontoPedidoColumn($params)
    {
        $descontoPedidoIndex  = array_search('Desconto pedido', $params['headers']) ?: array_search('Desconto do pedido (% ou valor)', $params['headers']);    
        $descontoPedido       = $params['row'][$descontoPedidoIndex];

        // Agora, a lógica de conversão difere com base no tipo de arquivo
        if ($params['fileType'] === 'tiny') {
            return $this->convertToFloatTiny($descontoPedido);
        } else {
            return $this->convertToFloatBling($descontoPedido, $params['fileExtension']);
        }
    }

    public function processDescontoItemColumn($params)
    {
        $descontoItemIndex  = array_search('Desconto item', $params['headers']);  
        $descontoItem       = $params['row'][$descontoItemIndex];

        // Agora, a lógica de conversão difere com base no tipo de arquivo
        if ($params['fileType'] === 'tiny') {
            return $this->convertToFloatTiny($descontoItem);
        } else {
            return $this->convertToFloatBling($descontoItem, $params['fileExtension']);
        }
    }

    public function processDescricaoColumn($params)
    {
        $descricaoItemIndex  = array_search('Descrição', $params['headers']);  
        $descricaoItem       = $params['row'][$descricaoItemIndex];

        return $descricaoItem;
    }

    public function convertToFloatTiny($str): float
    {
        $str = str_replace(',', '.', $str);
        $str = preg_replace('/[^0-9.]/', '', $str);
    
        return floatval($str);
    }

    public function convertToFloatBling($str, $fileExtension): float
    {
        if ($fileExtension === 'csv' || $fileExtension === 'txt') 
        {
            $str = str_replace('.', '', $str);
            $str = str_replace(',', '.', $str);
            return floatval($str);
        } else
        {
            return floatval($str);
        }       
    }

}