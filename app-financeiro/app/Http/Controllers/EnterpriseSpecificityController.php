<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;

class EnterpriseSpecificityController extends Controller
{
    public function __construct(
        protected Empresa $model
    ){}

    public function index()
    {
        $data = $this->model->recalculateAndShareEnterpriseData();

        //recalcula a empresa atual que o colaborador está
        $this->model->getActualEnterprise();

        return view('dashboard', $data);
    }

    public function update(Request $request)
    {
        // Recebe o Id da empresa que será atuado no momento
        $idEnterprise       = $request->input('idEnterprise');

        // Consulta todos os dados da empresa selecionada
        $razaoSocial = $this->model->where('id', $idEnterprise)->select('razao_social')->first();

        // Salva o objeto actualEnterprise na sessão
        Session::put('actualEnterpriseId', $idEnterprise);  
        // Retorne uma resposta adequada
        return response()->json($razaoSocial, 200); 
    }

    public function search(Request $request)
    {

        $cnpj = $request->query('cnpj');
        // Verificar se o CNPJ já está cadastrado em alguma empresa
        $existingCompany = $this->model->where('cnpj', $cnpj)->first();

        if ($existingCompany) {
            // CNPJ já cadastrado, vincular o acesso_id do usuário logado
            // $existingCompany->acesso_id = Auth::user()->acesso_id;

            // $existingCompany->save();

            // Retornar uma resposta JSON informando que o CNPJ foi vinculado à empresa existente
            return response()->json([
                'status' => true,
                'message' => 'O CNPJ já existe na base de dados.'
            ]);
        } else {
            // CNPJ não encontrado na base de dados, retornar uma resposta JSON solicitando a criação da empresa
            return response()->json([
                'status' => false,
                'message' => 'O CNPJ não existe na base de dados. Deseja cadastrar uma nova empresa?'
            ]);
        }
    }

    public function list(): View
    {
        // Recupera acessoId da sessão
        $acessoId = Session::get('acessoId');    

        $empresa = $this->model->getEmpresaByAccessId($acessoId);
        $enterpriseCount = $this->model->countCompaniesByAccessId($acessoId);

        $this->model->getActualEnterprise();

        return view('enterprise.index')->with([
            'empresa' => $empresa,
            'enterpriseCount' => $enterpriseCount
        ]);
    }
}
