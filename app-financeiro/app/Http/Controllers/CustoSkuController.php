<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustoSkuRequest;
use App\Models\ContaCanalDeVenda;
use App\Models\CustoSku;
use App\Models\Empresa;
use App\Models\Sku;
use Illuminate\Http\Request;

class CustoSkuController extends Controller
{
    public function index(Sku $sku)
    {
        $custos = CustoSku::query()
            ->with(['contaCanalDeVenda', 'empresa'])
            ->where('sku_id', $sku->id)
            ->orderByDesc('id')
            ->paginate();

        return view('skus.custos.index', compact('sku', 'custos'));
    }

    public function create(Request $request, Sku $sku)
    {
        $contaCanalDeVendas = ContaCanalDeVenda::query()
            ->meuAcesso($request->user()->acesso_id)
            ->get();

        $empresas = Empresa::query()
            ->meuAcesso($request->user()->acesso_id)
            ->get();

        return view('skus.custos.create', compact('sku', 'empresas', 'contaCanalDeVendas'));
    }

    public function store(CustoSkuRequest $request, Sku $sku)
    {
        $custo = new CustoSku;
        $custo->fill($request->validated());
        $custo->conta_canal_de_venda_id = $request->validated()['conta_canal_de_venda_id'];
        $custo->empresa_id = $request->validated()['empresa_id'];
        $custo->sku_id = $sku->id;
        $custo->save();

        return redirect()->route('skus.custos.index', [$sku])->with('status', 'sku-custo-created');
    }

    public function edit(Request $request, Sku $sku, CustoSku $custo)
    {
        $contaCanalDeVendas = ContaCanalDeVenda::query()
            ->meuAcesso($request->user()->acesso_id)
            ->get();

        $empresas = Empresa::query()
            ->meuAcesso($request->user()->acesso_id)
            ->get();

        return view('skus.custos.edit', compact('sku', 'empresas', 'contaCanalDeVendas', 'custo'));
    }

    public function update(CustoSkuRequest $request, Sku $sku, CustoSku $custo)
    {
        $custo->fill($request->validated());
        $custo->conta_canal_de_venda_id = $request->validated()['conta_canal_de_venda_id'];
        $custo->empresa_id = $request->validated()['empresa_id'];
        $custo->sku_id = $sku->id;
        $custo->save();

        return redirect()->route('skus.custos.edit', [$sku, $custo])->with('status', 'sku-custo-updated');
    }

    public function destroy(Sku $sku, CustoSku $custo)
    {
        $custo->delete();

        return redirect()->back()->with('status', 'sku-custo-deleted');
    }
}
