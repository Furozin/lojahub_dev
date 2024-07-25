<x-app-layout>
    @section('title', 'Empresa')
    <div class="py-12" x-data="{}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header class="flex justify-between items-center w-full mb-8">
                        <div class="">
                            <h2 class="text-lg font-medium text-primary-900">
                                Listagem de empresas
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Empresas cadastradas no seu acesso.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('enterprise.create') }}"
                               class="flex w-full justify-center items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold leading-6 text-primary-900 shadow-sm hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                <i class="bx bx-plus pr-2"></i>Cadastrar
                            </a>
                        </div>
                    </header>
                    @if ($enterpriseCount > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                    <tr class="text-center divide-x divide-gray-200">
                                        <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                            Razão Social
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                            CNPJ
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                            Gerenciamento   
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($empresa as $enterprise)
                                        <tr class="text-center even:bg-gray-50 divide-x divide-gray-200">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                {{ $enterprise->razao_social }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ $enterprise->cnpj }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4">
                                                <div class="">
                                                    @if (session()->has('actualEnterpriseId'))
                                                        @php
                                                            $actualEnterprise = session()->get('actualEnterpriseId');
                                                            $actualEnterpriseId = intval($actualEnterprise);
                                                        @endphp
                                                    @endif
                                                    <button
                                                        type="button"
                                                        data-enterprise-id="{{ $enterprise->getKey() }}"
                                                        {{ $actualEnterpriseId === $enterprise->getKey() ? 'disabled' : '' }}
                                                        class="
                                                        {{ $actualEnterpriseId === $enterprise->getKey() ? 'cursor-not-allowed' : '' }}
                                                        {{ $actualEnterpriseId === $enterprise->getKey() ? 'border-gray-400 bg-gray-300' : 'border-yellow-300 bg-white hover:bg-yellow-400' }}
                                                        text-black
                                                        border-2
                                                        font-bold
                                                        py-1
                                                        px-2
                                                        rounded manage-account">
                                                        Administrar empresa
                                                    </button>
                                                </div>
                                            </td>

                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                                <a href="{{ route('enterprise.edit', ['id' => $enterprise->id]) }}"
                                                    class="text-primary-900 hover:text-primary-700">
                                                    <i class="bx bxs-edit text-lg pr-2"></i>
                                                    <span class="sr-only">, {{$enterprise->nome}} </span>
                                                </a>
                                                <a href="#" @click.prevent="deleteEnterprise({{$enterprise->id}}, {{ $enterpriseCount }}, {{ session('actualEnterpriseId') }})"
                                                    class="text-error-900 hover:text-error-700">
                                                    <i class="bx bx-trash text-lg pr-2"></i>
                                                    <span class="sr-only">, {{$enterprise->nome}}</span>
                                                </a>
    
                                                <form id="enterprise-delete-{{$enterprise->id}}" method="POST"
                                                        action="{{ route('enterprise.delete', [$enterprise]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>

            const deleteEnterprise = (enterprise, count, actualEnterprise) => {
                if(count <= 1) 
                {
                    Swal.fire({ 
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Você não pode excluir a única empresa cadastrada.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                else if(enterprise == actualEnterprise) 
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Você não pode excluir a empresa que está atualmente selecionada.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                else
                {
                    Swal.fire({
                        title: 'Tem certeza que deseja excluir?',
                        text: "Essa ação não pode ser desfeita!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: 'red',
                        confirmButtonText: 'Sim, tenho certeza!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) 
                        {
                            document.querySelector(`#enterprise-delete-${enterprise}`).submit();
                        }
                    });
                }
            }

            function editEnterprise(button) {
                const editUrl = button.getAttribute('data-enterprise-update-url');
                window.location.href = editUrl;
            }

        </script>
        <script type="module">

            @if ( session('status') === 'enterprise-deleted')
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Excluído com sucesso!',
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            @endif

            $('.manage-account').on('click', function() {
                let idEnterprise = $(this).data('enterprise-id');
                enviarDados(idEnterprise);
            });

            function enviarDados(idEnterprise)
            {
                axios.post("{{ route('dashboard') }}", {
                    idEnterprise: idEnterprise,
                    _token: '{{ csrf_token() }}'
                })
                    .then(function(response) {
                        let actualEnterprise = response.data;
                        // Lidar com a resposta do servidor
                        Swal.fire({
                            title: 'Empresa selecionada!',
                            text: 'Razão Social: ' + actualEnterprise.razao_social,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Atualizar o conteúdo do elemento HTML com a razaoSocial
                            $('.razao-social').text(actualEnterprise.razao_social);
                            // Recarregar a página
                            location.reload();
                        });
                    })
                    .catch(function(error) {
                        // Lidar com erros
                        console.log(error);
                    });
            }

        </script>
    @endpush
</x-app-layout>
