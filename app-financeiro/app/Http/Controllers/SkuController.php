<?php

namespace App\Http\Controllers;

use App\Http\Requests\SkuRequest;
use App\Http\Requests\SkuImportRequest;
use App\Http\Requests\SkuExportRequest;
use App\Jobs\ImportSkuJob;
use App\Models\Empresa;
use App\Models\ContaCanalDeVenda;
use App\Models\Sku;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SkuController extends Controller
{
    public function index(Request $request)
    {
        // Recupera acesso_id da sessão
        $acessoId = Session::get('acessoId');

        $skus = Sku::query()
            ->meuAcesso($acessoId)
            ->orderByDesc('id')
            ->paginate();

        return view('skus.index', compact('skus'));
    }

    public function create(Request $request)
    {
        // Recupera acesso_id da sessão
        $acessoId = Session::get('acessoId');

        $empresas = Empresa::query()
            ->meuAcesso($acessoId)
            ->get();

        return view('skus.create', compact('empresas'));
    }

    public function store(SkuRequest $request)
    {
        // Recupera acesso_id da sessão
        $acessoId = Session::get('acessoId');

        $sku = new Sku();
        $sku->fill($request->validated());
        $sku->acesso_id = $acessoId;
        $sku->tags = str($request->validated()['tags'])->explode(',');
        if ($request->hasFile('imagem-upload')) {
            $filename = uuid_create() . ".{$request->file('imagem-upload')->getClientOriginalExtension()}";
            $request->file('imagem-upload')->storePubliclyAs("public/skus", $filename);
            $sku->imagem = $filename;
        }
        $sku->save();

        return redirect()->route('skus.custos.index', [$sku])->with('status', 'sku-created');
    }

    public function edit(Sku $sku)
    {
        $sku->loadCount(['custosSkus']);

        return view('skus.edit', compact('sku'));
    }

    public function update(SkuRequest $request, Sku $sku)
    {
        $sku->fill($request->validated());
        $sku->tags = str($request->validated()['tags'])->explode(',');
        if ($request->hasFile('imagem-upload')) {
            if ($sku->imagem && Storage::disk('public')->fileExists("skus/{$sku->imagem}")) {
                Storage::disk('public')->delete("skus/{$sku->imagem}");
            }
            $filename = uuid_create() . ".{$request->file('imagem-upload')->getClientOriginalExtension()}";
            $request->file('imagem-upload')->storePubliclyAs("public/skus", $filename);
            $sku->imagem = $filename;
        }
        $sku->save();

        return redirect()->back()->with('status', 'sku-updated');
    }

    public function destroy(Sku $sku)
    {
        if ($sku->imagem && Storage::disk('public')->fileExists("skus/{$sku->imagem}")) {
            Storage::disk('public')->delete("skus/{$sku->imagem}");
        }

        $sku->delete();
        $sku->custosSkus()->delete();

        return redirect()->back()->with('status', 'sku-deleted');
    }

    public function deleteImage(Sku $sku)
    {
        if ($sku->imagem && Storage::disk('public')->fileExists("skus/{$sku->imagem}")) {
            Storage::disk('public')->delete("skus/{$sku->imagem}");
        }

        $sku->imagem = null;
        $sku->save();

        return redirect()->back()->with('status', 'sku-image-deleted');
    }

    public function exportSku(SkuExportRequest $request)
    {
        // Obtenha os IDs dos SKUs selecionados da solicitação
        $selectedSkus = explode(',', $request->input('selected_skus'));

            $skus = Sku::with(['custosSkus.empresa', 'custosSkus.sku', 'custosSkus.contaCanalDeVenda'])
                            ->whereIn('id', $selectedSkus)
                            ->get();

        // Cria uma planilha com PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Adiciona os novos cabeçalhos
        $headers = [
            'Sku',
            'Título',
            'Descrição',
            'Imagem',
            'Status',
            'Fornecedor',
            'Tempo de Reposição (dias)',
            'Conta Canal de Venda',
            'Empresa',
            'Custo Total'
        ];

        $columnIndex = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($columnIndex, 1, $header);
            $columnIndex++;
        }

        // Preenche a planilha com os dados dos SKUs
        $row = 2;
        foreach ($skus as $sku) {
            $sheet->setCellValueByColumnAndRow(1, $row, $sku->sku);
            $sheet->setCellValueByColumnAndRow(2, $row, $sku->titulo);
            $sheet->setCellValueByColumnAndRow(3, $row, $sku->descricao);
            $sheet->setCellValueByColumnAndRow(4, $row, !empty($sku->imagem) ? $sku->imagem : 'Não informado');
            $sheet->setCellValueByColumnAndRow(5, $row, $sku->status == 1 ? 'Ativo' : 'Inativo');
            $sheet->setCellValueByColumnAndRow(6, $row, $sku->fornecedor);
            $sheet->setCellValueByColumnAndRow(7, $row, $sku->dias_tempo_reposicao);
            $sheet->setCellValueByColumnAndRow(8, $row, $sku->custosSkus->first()?->contaCanalDeVenda->nome ?? 'Não informado');
            $sheet->setCellValueByColumnAndRow(9, $row, $sku->custosSkus->first()?->empresa->razao_social ?? 'Não informado');
            $sheet->setCellValueByColumnAndRow(10, $row, $sku->custosSkus->first()?->custo_total ?? 'Não informado');
            $row++;
        }

        // Salva a planilha em um arquivo temporário
        $tempFile = tempnam(sys_get_temp_dir(), 'skus_export');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFile);

        // Download do arquivo
        return response()->download($tempFile, 'skus_export.xlsx')->deleteFileAfterSend();
    }

    public function importSku(SkuImportRequest $request)
    {
        if (!$request->hasFile('importFile')) {
            return redirect()->back()->withErrors(['error' => 'Por favor, envie um arquivo para importação.']);
        }

        $file = $request->file('importFile')->store('temp'); // Armazenamento temporário

        $acessoId               = session('acessoId');
        $enterpriseId           = session('actualEnterpriseId');
        $contaCanalDeVendaId    = $request->input('accountChannelId') ?? null;

        try
        {
            ImportSkuJob::dispatch(
                $file,
                $contaCanalDeVendaId,
                $enterpriseId,
                $acessoId
            )->onQueue('high');

            return response()->json([
                'success' => true,
                'message' => '
                    Importação iniciada. Recarregue a página daqui alguns instantes para ver o resultado.
                    Caso nenhum resultado seja exibido, entre em contato com o suporte.
                '
            ]);
        }
        catch (Exception $e)
        {
            return redirect()
                ->back()
                ->withErrors([
                    'error' => 'Erro ao iniciar o processo de importação: ' . $e->getMessage()
                ]);
        }
    }

    public function getAccountsDropdown()
    {
        $acesso_id = auth()->user()->acesso_id;
        $contas = ContaCanalDeVenda::meuAcesso($acesso_id)->select(['id', 'nome'])->get();
        return response()->json($contas);
    }

}
