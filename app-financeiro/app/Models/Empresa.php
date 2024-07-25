<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'empresas';

    protected $fillable = [
        'acesso_id',
        'anexo_id',
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'regime_tributario',
        'email',
        'telefone',
        'rua',
        'numero',
        'bairro',
        'cep',
        'complemento',
        'municipio',
        'uf',
        'pais',
        'logo_url',
        'logo_base64'
    ];

    public array $companyTaxRates = [
        1 => 'Anexo I',
        2 => 'Anexo II'
    ];

    public array $companyRegimeTypes = [
        1 => 'Simples Nacional'
    ];

    public function getCompanyTaxRates(): array
    {
        return $this->companyTaxRates;
    }

    public function getCompanyRegimeTypes(): array
    {
        return $this->companyRegimeTypes;
    }

    public function recalculateAndShareEnterpriseData(): array
    {
        // Recalcula a quantidade de empresas no acesso
        $acessId = auth()->user()->acesso_id;
        $existingEnterprise = $this->existsCompanyWithSameAccess();
        $empresa = $this->getEmpresaByAccessId($acessId);

        // Verifica se existem empresas no acesso
        $hasExistingEnterprise = !empty($existingEnterprise);

        return compact('hasExistingEnterprise', 'empresa');
    }

    public function createEnterprise(array $data): Empresa|static
    {
        $enterprise = $this;

        $enterprise->create($data);

        return $enterprise;
    }

    public function getActualEnterprise()
    {
        $actualEnterpriseId = Session::get('actualEnterpriseId');

        if ($actualEnterpriseId === null)
        {
            $actualEnterpriseId = $this->all();
        }

        return $actualEnterpriseId;
    }

    public function updateEmpresa(array $data): bool
    {
        // Atualiza os atributos da empresa com os dados fornecidos
        $this->fill($data);

        // Salva as alterações no banco de dados
        return $this->save();
    }

    public function scopeMeuAcesso(Builder $builder, int $acesso_id): void
    {
        $builder->where('acesso_id', $acesso_id);
    }

    public function existsCompanyWithSameAccess(): bool
    {
        $acessoId = auth()->user()->acesso_id;

        return $this->meuAcesso($acessoId)->exists();
    }

    public function countCompaniesByAccessId($acessoId): int
    {
        return $this->where('empresas.acesso_id', $acessoId)->count();
    }

    public function getEmpresaByAccessId($acessoId)
    {
        return $this->where('empresas.acesso_id', $acessoId)->get();
    }

    public function acesso(): BelongsTo
    {
        return $this->belongsTo(Acesso::class, 'acesso_id');
    }

    public function anexo(): BelongsTo
    {
        return $this->belongsTo(AnexoSimplesNacional::class, 'anexo_id');
    }

    public function contasCanaisDeVendas(): HasMany
    {
        return $this->hasMany(ContaCanalDeVenda::class, 'empresa_id');
    }

    public function custosSkus(): HasMany
    {
        return $this->hasMany(CustoSku::class, 'empresa_id');
    }

    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class, 'empresa_id');
    }
}
