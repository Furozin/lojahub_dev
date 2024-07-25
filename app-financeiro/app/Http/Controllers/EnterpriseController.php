<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnterpriseInsertRequest;
use App\Models\Empresa;
use Exception;
use Illuminate\View\View;

class EnterpriseController extends Controller
{
    public function __construct(
        protected Empresa $model
    ){}

    public function create(): View
    {
        $companyRegimes = $this->model->getCompanyRegimeTypes();
        $companyTaxRates = $this->model->getCompanyTaxRates();

        return view('enterprise.create')
            ->with('regimeTypes', $companyRegimes)
            ->with('taxRates', $companyTaxRates);
    }

    public function store(EnterpriseInsertRequest $request)
    {
        $data = $request->validated();
        $data['acesso_id'] = auth()->user()->acesso_id;

        $this->model->createEnterprise($data);

        //recalcula a quandidade de empresas no acesso
        $this->model->recalculateAndShareEnterpriseData();

        //recalcula a empresa atual que o colaborador está
        $this->model->getActualEnterprise();

        return back()->with('success', 'Empresa cadastrada com sucesso!');
    }

    public function delete($id)
    {
        $deleted = Empresa::destroy($id);

        if ($deleted)
        {
            // Recalcula a quantidade de empresas no acesso
            $this->model->recalculateAndShareEnterpriseData();

            // Recalcula a empresa atual que o colaborador está
            $this->model->getActualEnterprise();

            return redirect()->back()->with('status', 'enterprise-deleted');        }
        else
        {
            return redirect()->route('enterprise.index')->with('error', 'Erro ao excluir a empresa. Por favor, tente novamente.');
        }
    }

    public function edit(string $id)
    {
        //Carrega as informações da empresa a ser editada
        $enterprise = $this->model->findOrFail($id);

        //Carrega os tipos de Regimes tributários para construção do menu de seleção
        $companyRegimes = $this->model->getCompanyRegimeTypes();

        //Carrega os tipos de Anexos para construção do menu de seleção
        $companyTaxRates = $this->model->getCompanyTaxRates();

        return view('enterprise.edit')
                    ->with('regimeTypes', $companyRegimes)
                    ->with('enterprise', $enterprise)
                    ->with('taxRates', $companyTaxRates);
    }

    public function update(EnterpriseInsertRequest $request, int $companyId)
    {
        try
        {
            $data               = $request->validated();
            $data['acesso_id']  = auth()->user()->acesso_id;

            $empresa = Empresa::findOrFail($companyId);

            $empresa->fill($data);

            $empresa->save();
            return redirect()->back()->with('success', 'Empresa atualizada com sucesso!');
        }
        catch (Exception)
        {
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar a empresa. Por favor, tente novamente.');
        }
    }
}
