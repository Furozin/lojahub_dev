<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleImportRequest;
use App\Jobs\ImportSaleJob;
use App\Models\ContaCanalDeVenda;
use Exception;

class SaleController extends Controller
{
    public function index()
    {
        return view('sales.index');
    }

    public function importSale(SaleImportRequest $request)
    {
        if (!$request->hasFile('importFile')) {
            return redirect()->back()->withErrors(['error' => 'Por favor, envie um arquivo para importação.']);
        }

        $file                   = $request->file('importFile')->store('temp');
        $acessoId               = session('acessoId');
        $enterpriseId           = session('actualEnterpriseId');
        $contaCanalDeVendaId    = $request->input('selectedAccount') ?? null;
        try
        {
            ImportSaleJob::dispatch(
                $file,
                $contaCanalDeVendaId,
                $enterpriseId,
                $acessoId
            )->onQueue('high');

            return response()->json([
                'success' => true,
                'message' => 'Importação iniciada. O processo será concluido em alguns instantes.'
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
