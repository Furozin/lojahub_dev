<x-app-layout>
    @section('title', 'Editar Empresa')

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header class="flex justify-between items-center w-full mb-6">
                        <div class="">
                            <h2 class="text-lg font-medium text-primary-900">
                                Editar Empresa
                            </h2>
                        </div>
                        <div>
                            {{ html()->a(route('enterprise.index'), 'Voltar')
                            ->class(
                                '
                                    flex w-full
                                    justify-center
                                    rounded-md
                                    bg-transparent
                                    px-3
                                    py-1.5
                                    text-sm
                                    font-semibold
                                    leading-6
                                    text-primary-900
                                    shadow-sm
                                    hover:bg-blue-500
                                    hover:border-transparent
                                    hover:text-white
                                    focus-visible:outline
                                    focus-visible:outline-2
                                    focus-visible:outline-offset-2
                                    focus-visible:outline-white
                                '
                                )
                        }}

                        </div>
                    </header>



                    {{ html()->form('PUT', route('enterprise.update', ['id' => $enterprise->id]))
                            ->class('w-full')
                            ->open()
                    }}

                    {{ html()->hidden('id', $enterprise->getKey()) }}

                    <div class="flex flex-wrap -mx-3 mb-2">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            {{ html()->label('Razão social', 'razao-social')->class('block') }}
                            {{ html()->text('razao_social')
                                    ->class('w-full rounded-md border border-gray-300')
                                    ->id('razao-social')
                                    ->placeholder('Razão social')
                                    ->required()
                                    ->value($enterprise->razao_social)
                            }}
                            @error('razao_social')
                                {{ html()->span($message)->class('text-error-600') }}
                            @enderror
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            {{ html()->label('Nome fantasia', 'nome-fantasia')->class('block') }}
                            {{ html()->text('nome_fantasia')
                                    ->class('w-full rounded-md p-2 border border-gray-300')
                                    ->id('nome-fantasia')
                                    ->required()
                                    ->placeholder('Nome fantasia')
                                    ->value($enterprise->nome_fantasia)
                            }}
                            @error('nome_fantasia')
                                {{ html()->span($message)->class('text-error-600') }}
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-2">
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            {{ html()->label('CNPJ', 'cnpj')->class('block') }}
                            {{ html()->text('cnpj')
                                    ->class('w-full rounded-md p-2 border border-gray-300')
                                    ->id('cnpj')
                                    ->placeholder('99.999.999/9999-99')
                                    ->required()
                                    ->value($enterprise->cnpj)
                            }}
                            @error('cnpj')
                                {{ html()->span($message)->class('text-error-600') }}
                            @enderror
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            <div class="relative">
                                {{ html()->label('E-mail', 'email')->class('block') }}
                                {{ html()->email('email')
                                        ->class('w-full rounded-md p-2 border border-gray-300')
                                        ->id('email')
                                        ->required()
                                        ->placeholder('seu@email.com')
                                        ->value($enterprise->email)
                                }}

                                @error('email')
                                    {{ html()->span($message)->class('text-error-600') }}
                                @enderror
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            {{ html()->label('Telefone', 'telefone')->class('block') }}
                            {{ html()->text('telefone')
                                    ->class('w-full rounded-md p-2 border border-gray-300')
                                    ->id('telefone')
                                    ->required()
                                    ->placeholder('(99) 99999-9999')
                                    ->value($enterprise->telefone)

                            }}
                            @error('telefone')
                                {{ html()->span($message)->class('text-error-600') }}
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-1/2 px-3">
                            {{ html()->label('Regime tributário', 'regime-tributario')->class('block') }}
                            <select name="regime_tributario" id="regime-tributario" class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey" required>
                                @foreach($regimeTypes as $value => $label)
                                    <option value="{{ $value }}" {{ $enterprise->regime_tributario == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('regime_tributario')
                                {{ html()->span($message)->class('text-error-600') }}
                            @enderror
                        </div>
                        <div class="w-1/2 px-3">
                            {{ html()->label('Anexo', 'anexo')->class('block') }}
                            <select name="anexo_id" id="anexo" class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey" required>
                                @foreach($taxRates as $value => $label)
                                    <option value="{{ $value }}" {{ $enterprise->anexo_id == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                        </select>
                            @error('anexo')
                                {{ html()->span($message)->class('text-error-600') }}
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-2">
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            {{ html()->label('CEP', 'cep')->class('block') }}
                            {{ html()->text('cep')
                                    ->class('w-full rounded-md p-2 border border-gray-300')
                                    ->id('cep')
                                    ->required()
                                    ->placeholder('00000000')
                                    ->value($enterprise->cep)
                            }}
                            @error('cep')
                                {{ html()->span($message)->class('text-error-600') }}
                            @enderror
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            <div class="relative">
                                {{ html()->label('Rua', 'rua')->class('block') }}
                                {{ html()->text('rua')
                                        ->class('w-full rounded-md p-2 border border-gray-300')
                                        ->id('rua')
                                        ->required()
                                        ->value($enterprise->rua)
                                }}
                                @error('rua')
                                    {{ html()->span($message)->class('text-error-600') }}
                                @enderror
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            {{ html()->label('Número', 'numero')->class('block') }}
                            {{ html()->text('numero')
                                    ->class('w-full rounded-md p-2 border border-gray-300')
                                    ->id('numero')
                                    ->required()
                                    ->value($enterprise->numero)
                            }}
                            @error('numero')
                                {{ html()->span($message)->class('text-error-600') }}
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-2">
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            {{ html()->label('Bairro', 'bairro')->class('block') }}
                            {{ html()->text('bairro')
                                    ->class('w-full rounded-md p-2 border border-gray-300')
                                    ->id('bairro')
                                    ->required()
                                    ->value($enterprise->bairro)
                            }}
                            @error('bairro')
                                {{ html()->span($message)->class('text-error-600') }}
                            @enderror
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">

                            <div class="relative">
                                {{ html()->label('Complemento', 'complemento')->class('block') }}
                                {{ html()->text('complemento')
                                        ->class('w-full rounded-md p-2 border border-gray-300')
                                        ->id('complemento')
                                        ->value($enterprise->complemento)

                                }}
                                @error('complemento')
                                    {{ html()->span($message)->class('text-error-600') }}
                                @enderror
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            {{ html()->label('Município', 'municipio')->class('block') }}
                            {{ html()->text('municipio')
                                    ->class('w-full rounded-md p-2 border border-gray-300')
                                    ->id('municipio')
                                    ->required()
                                    ->value($enterprise->municipio)
                            }}
                            @error('municipio')
                                {{ html()->span($message)->class('text-error-600') }}
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-2">
                        <div class="px-3 mb-6 md:mb-0">
                            {{ html()->label('UF', 'uf')->class('block') }}
                            {{ html()->text('uf')
                                    ->class('rounded-md p-2 border border-gray-300')
                                    ->id('uf')
                                    ->required()
                                    ->value($enterprise->uf)
                            }}
                            @error('uf')
                                {{ html()->span($message)->class('text-error-600') }}
                            @enderror
                        </div>
                        <div class="px-3 mb-6 md:mb-0">
                            {{ html()->label('País', 'pais')->class('block') }}
                            {{ html()->text('pais')
                                    ->class('rounded-md p-2 border border-gray-300')
                                    ->id('pais')
                                    ->required()
                                    ->value($enterprise->pais)
                            }}
                            @error('pais')
                                {{ html()->span($message)->class('text-error-600') }}
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end">
                        {{ html()->submit('Salvar')
                                ->class(
                                    '
                                        bg-transparent
                                        text-primary-900
                                        font-semibold
                                        hover:text-white
                                        py-2
                                        px-4
                                        border
                                        border-blue-400
                                        hover:bg-blue-500
                                        hover:border-transparent
                                        rounded
                                    '
                                )
                        }}
                    </div>
                    {{ html()->form()->close() }}
                </section>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            $('#cep').mask('00000-000', {
                reverse: true
            });

            $('#cnpj').mask('00.000.000/0000-00', {
                reverse: true
            });

            $('#telefone').mask('00 0000-0000', {
                reverse: true
            });

            function limpa_formulario_cep()
            {
                // Limpa valores dos campos de endereço.
                $("#rua").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#uf").val("");
                $("#pais").val("");
            }

            // Quando o campo CEP perde o foco.
            $("#cep").blur(function() {
                // Nova variável "cep" somente com dígitos.
                let cep = $(this).val().replace(/\D/g, '');

                // Verifica se o campo CEP possui valor informado.
                if (cep !== "")
                {
                    // Expressão regular para validar o CEP.
                    let validacep = /^[0-9]{8}$/;

                    // Valida o formato do CEP.
                    if (validacep.test(cep))
                    {
                        // Preenche os campos com "..." enquanto consulta o serviço do ViaCEP.
                        $("#rua").val("").addClass("animate-pulse bg-gray-200");
                        $("#bairro").val("").addClass("animate-pulse bg-gray-200");
                        $("#cidade").val("").addClass("animate-pulse bg-gray-200");
                        $("#uf").val("").addClass("animate-pulse bg-gray-200");
                        $("#pais").val("").addClass("animate-pulse bg-gray-200");

                        // Consulta o webservice do ViaCEP.
                        $.getJSON(`https://viacep.com.br/ws/${cep}/json/?callback=?`, function(dados) {
                            if ("erro" in dados)
                            {
                                limpa_formulario_cep();
                                alert("CEP não encontrado.");

                                return false;
                            }
                            $("#rua").val(dados.logradouro).removeClass("animate-pulse bg-gray-200");
                            $("#bairro").val(dados.bairro).removeClass("animate-pulse bg-gray-200");
                            $("#municipio").val(dados.localidade).removeClass("animate-pulse bg-gray-200");
                            $("#uf").val(dados.uf).removeClass("animate-pulse bg-gray-200");
                            $("#pais").val("Brasil").removeClass("animate-pulse bg-gray-200");

                            return true;
                        });
                    }
                    else
                    {
                        // CEP é inválido.
                        limpa_formulario_cep();
                        alert("Formato de CEP inválido.");

                        return false;
                    }
                }
                else
                {
                    limpa_formulario_cep();
                    return false
                }
            });

            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
            @endif

        </script>
    @endpush
</x-app-layout>


