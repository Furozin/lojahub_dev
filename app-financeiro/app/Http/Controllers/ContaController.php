<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContaCanalDeVendaRequest;
use App\Models\ContaCanalDeVenda;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class ContaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->origem_id) {
            session()->put('origem_id', $request->origem_id);
            return redirect()->route('contas.index');
        }

        // Recupera os dados de sessão da Enterprise escolhida para análise
        $enterpriseId   = collect(session('actualEnterpriseId'))->toArray();

        // Recupera acesso_id da sessão
        $acessoId       = Session::get('acessoId');

        $contas = ContaCanalDeVenda::query()
            ->meuAcesso($acessoId)
            ->where('empresa_id', $enterpriseId)
            ->when(session('origem_id'), fn (Builder $builder) => $builder->where('origem_id', session('origem_id')))
            ->orderByDesc('id')
            ->paginate();

        return view('contas.index', compact('contas'));
    }

    public function create()
    {
        return view('contas.create');
    }

    public function store(ContaCanalDeVendaRequest $request)
    {
        // Recupera os dados de sessão da Enterprise escolhida para análise
        $enterpriseId   = session::get('actualEnterpriseId');

        // Recupera acesso_id da sessão
        $acessoId       = Session::get('acessoId');

        $conta = new ContaCanalDeVenda;
        $conta->fill($request->validated());
        $conta->acesso_id   = $acessoId;
        $conta->empresa_id  = $enterpriseId;
        $conta->comissao    = ['mkt' => $request->comissao];
        $conta->save();

        return redirect()->route('contas.edit', [$conta])->with('status', 'conta-created');
    }

    public function edit(ContaCanalDeVenda $conta)
    {
        return view('contas.edit', compact('conta'));
    }

    public function update(ContaCanalDeVendaRequest $request, ContaCanalDeVenda $conta)
    {
        $conta->fill($request->validated());
        $conta->comissao = ['mkt' => $request->comissao];
        $conta->save();

        return redirect()->route('contas.edit', [$conta])->with('status', 'conta-updated');
    }

    public function destroy(ContaCanalDeVenda $conta)
    {
        $conta->delete();

        return redirect()->back()->with('status', 'conta-deleted');
    }
}
