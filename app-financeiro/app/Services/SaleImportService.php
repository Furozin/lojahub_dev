<?php

namespace App\Services;

use App\Repositories\SaleRepository;
use App\Models\ContaCanalDeVenda;
use Carbon\Carbon;
use App\Services\ItemSaleImportService;
use App\Services\ProcessColumnSaleImportService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class SaleImportService
{
    protected $itemService;
    protected $processColumnSale;

    public function __construct(
        protected SaleRepository $saleRepo, 
        ItemSaleImportService $itemService,
        ProcessColumnSaleImportService $processColumnSale
    ){
        $this->itemService          = $itemService;
        $this->processColumnSale    = $processColumnSale;
    }

    public function processFile($filePath, $contaCanalDeVendaId, $enterpriseId, $acessoId, $fileExtension)
    {
        $data = $this->parseFile($filePath);

        // Busca o canal de venda pelo ID fornecido
        $contaCanalDeVenda  = ContaCanalDeVenda::find($contaCanalDeVendaId);
        $comissaoArray      = $contaCanalDeVenda->comissao;
        $origemVendaId      = $contaCanalDeVenda->origem_id;
        $comissaoMkt        = $comissaoArray['mkt'] ?? null;

        $headers = $data[0];
        $rows = collect($data)->slice(1);

        $processedRows = $rows->map(function ($row) use ($headers, $fileExtension) {
            return $this->processRow($row, $headers, $fileExtension);
        })->filter(); // Remove as linhas que retornaram null

        // Agrega os dados por número de pedido
        $vendasTotais = $processedRows->groupBy('numeroPedido')
        ->map(function ($salesGroup) use ($acessoId, $enterpriseId, $origemVendaId, $comissaoMkt) { // Adicionando as variáveis ao escopo da função anônima
            return $this->aggregateSalesData($salesGroup, $acessoId, $enterpriseId, $origemVendaId, $comissaoMkt);
        });

        $vendasIds = $this->saveSalesData($vendasTotais, $enterpriseId);

        foreach ($processedRows as $processedRow) {
            $vendaId = $vendasIds[$processedRow['numeroPedido']] ?? null;
            if($vendaId)
            {
                $this->itemService->saveItensVendas($processedRow, $vendaId, $acessoId, $enterpriseId);
            }
        }
    }

    private function processRow($row, $headers, $fileExtension)
    {
        //Defini o numero do pedido e o tipo do arquivo(bling ou tiny)
        $numeroPedidoInfo   = $this->processColumnSale->processNumeroPedidoColumn($row, $headers);

        $params = [
            'row'           => $row,
            'headers'       => $headers,
            'fileExtension' => $fileExtension,
            'fileType'      => $numeroPedidoInfo['tipoArquivo']
        ];

        $saleData                           = [];

        $saleData['numeroPedido']           = $numeroPedidoInfo['numeroPedido'];
        $saleData['precoTotal']             = $this->processColumnSale->processPrecoTotalColumn($params);
        $saleData['quantidade']             = $this->processColumnSale->processQuantidadeColumn($params);
        $saleData['frete']                  = $this->processColumnSale->processFreteColumn($params);
        $saleData['dataCriacao']            = $this->processColumnSale->processDataCriacaoColumn($params);
        $saleData['dataPagamento']          = $this->processColumnSale->processDataPagamentoColumn($params);
        $saleData['valorUnitario']          = $this->processColumnSale->processValorUnitarioColumn($params);
        $saleData['chaveNfe']               = $this->processColumnSale->processChaveNfeColumn($params);
        $saleData['canalEnvioId']           = $this->processColumnSale->processCanalEnvioIdColumn($params);
        $saleData['descontoPedido']         = $this->processColumnSale->processdescontoPedidoColumn($params);
        $saleData['descontoItem']           = $this->processColumnSale->processdescontoItemColumn($params);
        $saleData['codigoSku']              = $this->processColumnSale->processCodigoSkuColumn($params);
        $saleData['descricao']              = $this->processColumnSale->processDescricaoColumn($params);
        $saleData['itens']                  = $this->itemService->processItens($params);

        if ($this->isRowValid($saleData)) {
            return $saleData;
        } else {
            Log::warning('Linha inválida ou incompleta', ['row' => $row]);
            return null;
        }
    }

    private function isRowValid($saleData)
    {
        // Verifica se o número do pedido está preenchido
        if (empty($saleData['numeroPedido'])) {
            Log::error('Linha inválida: Número do pedido não informado.', ['row' => $saleData]);
            return false;
        }
    
        // Verifica se a data está preenchida
        if (empty($saleData['dataCriacao'])) {
            Log::error('Linha inválida: Data não informado.', ['row' => $saleData]);
            return false;
        }
    
        // Verifica se o preço total está preenchido
        if (!isset($saleData['precoTotal']) || $saleData['precoTotal'] === '') {
            Log::error('Linha inválida: Preco Total não informado', ['row' => $saleData]);
            return false;
        }
       
         // Verifica se a quantidade é um número inteiro
         if (!isset($saleData['quantidade']) || !$this->isValidQuantity($saleData['quantidade'])) {
            Log::error('Linha inválida: quantidade não é um número inteiro ou conversível', ['row' => $saleData]);
            return false;
        }

        // Verifica se o valor unitário está preenchido
        if (!isset($saleData['valorUnitario']) || $saleData['valorUnitario'] === ''|| $saleData['valorUnitario'] === 0) {
            Log::error('Linha inválida: valor unitário não preenchido', ['row' => $saleData]);
            return false;
        }
    
        return true;
    }    

    private function parseFile($filePath)
    {
         //Carrega o arquivo importado
         $spreadsheet    = IOFactory::load($filePath);
         $worksheet      = $spreadsheet->getActiveSheet();
         $data           = $worksheet->toArray();
        
         return $data;
    }

    private function aggregateSalesData($salesGroup, $acessoId, $enterpriseId, $origemVendaId, $comissaoMkt )
    {
        // Inicializa os valores totais
        $totalPreco         = 0;
        $totalQuantidade    = 0;
        $totalFrete         = 0;
        $totalDesconto      = 0;

        foreach ($salesGroup as $sale) {
            $totalPreco += $sale['precoTotal'];
            $totalQuantidade += $sale['quantidade'];
            $totalFrete += $sale['frete'];
            $totalDesconto += $sale['descontoPedido'];
        }
    
        $valorTotal = ($totalPreco + $totalFrete) - $totalDesconto;
    
        // Retorna os dados agregados
        return [
            'numeroPedido'                      => $salesGroup[0]['numeroPedido'],
            'totalPreco'                        => $totalPreco,
            'totalQuantidade'                   => $totalQuantidade,
            'acesso_id'                         => $acessoId,
            'empresa_id'                        => $enterpriseId,
            'conta_canal_de_venda_id'           => $origemVendaId,
            'canal_venda_id'                    => $salesGroup[0]['numeroPedido'],
            'data_criacao'                      => $salesGroup[0]['dataCriacao'],
            'data_pagamento'                    => $salesGroup[0]['dataPagamento'],
            'valor_total'                       => $valorTotal,
            'valor_restante_no_canal_de_venda'  => $valorTotal,
            'valor_total_dos_produtos'          => $totalPreco,
            'custo_de_envio'                    => $totalFrete,
            'canal_envio_id'                    => 'Não informado',
            'custo_de_comissao'                 => $comissaoMkt,
            'outras_entradas'                   => 0.00,
            'aliquota_imposto'                  => 0.00,
            'lucro_bruto'                       => 0.00,
            'lucro_liquido'                     => 0.00
            // ,'chave_nfe'                      => $chaveNfe,
        ];
    }

    private function isDataValid(array $dadosVenda): bool
    {
        return isset(
            $dadosVenda['numeroPedido'],
            $dadosVenda['totalPreco'],
            $dadosVenda['totalQuantidade'],
            $dadosVenda['acesso_id'],
            $dadosVenda['empresa_id'],
            $dadosVenda['conta_canal_de_venda_id'],
            $dadosVenda['canal_venda_id'],
            $dadosVenda['data_criacao'],
            $dadosVenda['data_pagamento'],
            $dadosVenda['valor_total'],
            $dadosVenda['valor_restante_no_canal_de_venda'],
            $dadosVenda['valor_total_dos_produtos'],
            $dadosVenda['custo_de_envio']
        );
    }

    private function convertDates(array $dadosVenda): array
    {
        if (isset($dadosVenda['data_criacao'])) {
            $dadosVenda['data_criacao'] = Carbon::createFromFormat('d/m/Y', $dadosVenda['data_criacao'])->toDateString();
        }

        if (isset($dadosVenda['data_pagamento'])) {
            $dadosVenda['data_pagamento'] = Carbon::createFromFormat('d/m/Y', $dadosVenda['data_pagamento'])->toDateString();
        }

        return $dadosVenda;
    }

    public function saveSalesData($vendasTotais, $enterpriseId)
    {
        $vendaIds = [];
        foreach ($vendasTotais as $dadosVenda) 
        {
            // Converte as datas
            $dadosVenda = $this->convertDates($dadosVenda);

            // Verifica se os dados são válidos
            if ($this->isDataValid($dadosVenda)) {

                // Atualiza ou cria um novo registro de venda
                $venda = $this->saleRepo->updateOrCreate(
                    [
                        'canal_venda_id'    => $dadosVenda['numeroPedido'],
                        'empresa_id'        => $enterpriseId
                    ],
                    $dadosVenda
                );
                $vendaIds[$dadosVenda['canal_venda_id']] = $venda->id;
            } else {
                Log::warning('Dados de venda inválidos', ['dadosVenda' => $dadosVenda]);
            }
        }
        return $vendaIds;
    }

   private function isValidQuantity($value) {
    // Converte a string para um número, tratando pontos e vírgulas
    $number = floatval(str_replace(',', '.', $value));
    
    // Verifica se o número é um inteiro ou um decimal que pode ser convertido para inteiro sem perda
    return (intval($number) == $number);
}

}