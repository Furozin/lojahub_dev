<x-app-layout>
    @section('title', 'SKUs')
    <div class="py-12" x-data="{}">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <input type="file" id="fileInput" class="hidden">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header class="flex justify-between items-center w-full mb-8">
                        <div>
                            <h2 class="text-lg font-medium text-primary-900">
                                Listagem de SKU
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Encontre aqui os SKUs e seus custos.
                            </p>
                        </div>
                        <div class="flex space-x-2"> <!-- Adicionado espaço entre os elementos para separá-los -->
                            <a href="{{ route('skus.create') }}"
                               class="flex justify-center items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold leading-6 text-primary-900 shadow-sm hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                <i class="bx bx-plus pr-2"></i>Cadastrar
                            </a>
                            <!-- Botão "Importar" que abre o modal -->
                            <button type="button" class="flex justify-center items-center rounded-md bg-primary-900 transition-transform transform hover:scale-105 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white" id="abrirModalImportar">
                                Importar
                            </button>

                            <form id="export-skus-form" action="{{ route('skus.export') }}" method="POST">
                                @csrf
                                <input type="hidden" name="selected_skus" id="selected-skus">
                                <button type="submit" id="export-selected-skus" class="hidden flex justify-center items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold leading-6 text-primary-900 shadow-sm hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                    <i class="bx bx-download pr-2"></i>Exportar Selecionados
                                </button>
                            </form>
                        </div>
                    </header>

                    @if($skus->total() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                <tr class="text-center divide-x divide-gray-200">
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-sm font-semibold text-primary-900 sm:pl-3">
                                        <input type="checkbox" id="select-all-skus" data-checkbox="select-all">
                                    </th>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-sm font-semibold text-primary-900 sm:pl-3">
                                        <i class="bx bx-image text-xl"></i>
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        SKU
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        Título
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        Descrição
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        Status
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        Fornecedor
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        Tempo de Reposição (dias)
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        Ações
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                @foreach($skus as $sku)
                                    <tr class="text-center even:bg-gray-50 divide-x divide-gray-200">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-3">
                                            <input type="checkbox" class="sku-checkbox" value="{{ $sku->id }}">
                                        </td>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-3">
                                            @if($sku->url_imagem)
                                                <img class="inline-block h-8 w-8 rounded-md"
                                                     src="{{$sku->url_imagem}}"
                                                     alt="{{$sku->titulo}}">
                                            @else
                                                <span
                                                    class="inline-block align-middle h-8 w-8 overflow-hidden rounded-md bg-gray-100">
                                                <i class="bx bx-barcode-reader text-lg text-gray-300 mt-1.5"></i>
                                            </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                            {{$sku->sku}}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{str($sku->titulo)->limit(50)}}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{str($sku->descricao)->limit(50)}}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{$sku->status == 1 ? 'Ativo' : 'Inativo'}}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{$sku->fornecedor}}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{$sku->dias_tempo_reposicao}}
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                            <a href="{{route('skus.edit', [$sku])}}"
                                               class="text-primary-900 hover:text-primary-700">
                                                <i class="bx bxs-edit text-lg pr-2"></i>
                                                <span class="sr-only">, {{$sku->titulo}} </span>
                                            </a>
                                            <a href="#" @click.prevent="deleteSku({{$sku->id}})"
                                               class="text-error-900 hover:text-error-700">
                                                <i class="bx bx-trash text-lg pr-2"></i>
                                                <span class="sr-only">, {{$sku->titulo}}</span>
                                            </a>

                                            <form id="sku-destroy-{{$sku->id}}" method="POST"
                                                  action="{{ route('skus.destroy', [$sku]) }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $skus->links() }}
                        </div>
                    @else
                        <div
                            class="text-center relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">Nenhum SKU encontrado!</h3>
                            <p class="mt-1 text-sm text-gray-500">Comece criando um novo SKU.</p>
                            <div class="mt-6">
                                <a href="{{route('skus.create')}}"
                                   class="inline-flex items-center rounded-md bg-primary-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path
                                            d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
                                    </svg>
                                    Cadastrar SKU
                                </a>
                            </div>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteSku = sku => {
                Swal.fire({
                    icon: 'warning',
                    title: 'Deseja realmente excluir esse SKU?',
                    showConfirmButton: true,
                    confirmButtonText: 'Sim, tenho certeza!',
                    confirmButtonColor: 'red',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.querySelector(`#sku-destroy-${sku}`).submit();
                    }
                });
            }

            document.getElementById('abrirModalImportar').addEventListener('click', function() {
                Swal.fire({
                    title: 'Escolha a Origem',
                    html:
                    '<div class="flex flex-col space-y-4">' +
                    '<button class="w-full btn bg-primary-900 text-white px-3 py-1.5 rounded hover:bg-primary-700 transition-all duration-300" id="chooseFileBtn">Escolher Arquivo</button>' +
                    '<input type="file" id="importFile" class="hidden">' +
                    '<div id="fileName" class="mt-3"></div>' +
                    '<div class="mt-3">' +
                        '<input type="checkbox" id="includeCosts" class="mr-2">' +
                        '<label for="includeCosts">Incluir Custos</label>' +
                    '</div>' +
                    '<div id="accountSelector" class="mt-3 hidden">' +
                        '<span class="text-lg font-medium">Selecione uma conta de marketplace para qual deseja realizar a importação completa</span>' +
                        '<select id="accountList" class="w-full form-select">' +
                        '</select>' +
                    '</div>',
                    didOpen: () => {
                        // Lógica para carregar a lista de contas
                        const includeCostsCheckbox = document.getElementById('includeCosts');
                        const accountSelector = document.getElementById('accountSelector');

                        includeCostsCheckbox.addEventListener('change', async () => {
                            if (includeCostsCheckbox.checked) {
                                accountSelector.classList.remove('hidden');
                                const hasAccounts = await loadAccountOptions();
                                if (!hasAccounts) {
                                    Swal.showValidationMessage('Não há contas cadastradas. Cadastre uma conta antes de continuar.');
                                    includeCostsCheckbox.checked = false;
                                    accountSelector.classList.add('hidden');
                                }
                            } else {
                                accountSelector.classList.add('hidden');
                            }
                        });
                    },
                    showCloseButton: true,
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Importar',
                    preConfirm: () => {
                        let file = document.getElementById('importFile').files[0];
                        let includeCosts = document.getElementById('includeCosts').checked;
                        let selectedAccount = null;

                        if (includeCosts) {
                            selectedAccount = document.getElementById('accountList').value;
                            if (!selectedAccount) {
                                Swal.showValidationMessage('Por favor, selecione uma conta antes de continuar.');
                            }
                        }

                        if (!file) {
                            Swal.showValidationMessage('Por favor, selecione um arquivo antes de continuar.');
                        }

                        return file ? {
                            fileName: file.name,
                            includeCosts: includeCosts,
                            selectedAccount: selectedAccount
                        } : null;
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        let formData = new FormData();
                        formData.append('importFile', document.getElementById('importFile').files[0]);
                        if (result.value.includeCosts) {
                            formData.append('accountChannelId', result.value.selectedAccount);
                        }

                        fetch('{{ route('skus.import') }}',
                        {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(data => {
                                    throw data;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Sucesso!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.reload();
                                    }
                                });
                            } else {
                                Swal.fire('Erro!', data.message, 'error');
                            }
                        })
                        .catch(data => {
                            if (data.errors)
                            {
                                let errorMessage = "Erros de validação:\n";
                                for (let field in data.errors) {
                                    errorMessage += data.errors[field].join("\n") + "\n";
                                }
                                Swal.fire('Erro de Validação!', errorMessage, 'error');
                            } else {
                                Swal.fire('Erro!', 'Ocorreu um erro desconhecido: ', + JSON.stringify(data), 'error');
                            }
                    });
                    }
                });

                document.getElementById('chooseFileBtn').addEventListener('click', function() {
                    document.getElementById('importFile').click();
                });

                document.getElementById('importFile').addEventListener('change', function() {
                    document.getElementById('fileName').textContent = this.files[0].name;
                });

            });

            async function loadAccountOptions() {
                try {
                    const response = await fetch('/skus/getAccountsDropdown');
                    const accounts = await response.json();

                    if (accounts.length === 0) {
                        return false;
                    }

                    const accountList = document.getElementById('accountList');
                    accountList.innerHTML = '<option value="" disabled selected>Selecione uma conta</option>';
                    accounts.forEach(account => {
                        const option = document.createElement('option');
                        option.value = account.id;
                        option.textContent = account.nome;
                        accountList.appendChild(option);
                    });

                    return true;
                } catch (error) {
                    console.error('Não foi possível carregar as contas:', error);
                    return false;
                }
            }

            // Selecionar todos os checkboxes quando o "Selecionar Todos" for marcado/desmarcado
            $('#select-all-skus').change(function () {
                let isChecked = $(this).prop('checked');
                $('.sku-checkbox').prop('checked', isChecked);
                updateExportButtonState();
            });

            // Atualizar o estado do botão de exportação quando os checkboxes forem marcados/desmarcados
            $('.sku-checkbox').change(function () {
                updateExportButtonState();
            });

            // Função para atualizar o estado do botão de exportação
            function updateExportButtonState() {
                if ($('.sku-checkbox:checked').length > 0) {
                    $('#export-selected-skus').removeClass('hidden');
                } else {
                    $('#export-selected-skus').addClass('hidden');
                }

                // Atualizar o estado do checkbox "Selecionar Todos"
                if ($('.sku-checkbox:not(:checked)').length === 0) {
                    $('#select-all-skus').prop('checked', true);
                } else {
                    $('#select-all-skus').prop('checked', false);
                }
            }

            // Adicione este bloco para exportar os SKUs selecionados quando o botão for clicado
            $('#export-selected-skus').click(function () {
                let selectedSkus = [];

                // Coletar IDs dos SKUs selecionados
                $('.sku-checkbox:checked').each(function () {
                    selectedSkus.push($(this).val());
                });

                if (selectedSkus.length > 0) {
                    // Defina o valor do campo oculto com os IDs selecionados
                    $('#selected-skus').val(selectedSkus.join(','));

                    // Envie o formulário para a rota de exportação
                    $('#export-skus-form').submit();
                }
            });

            @if ( session('status') === 'sku-deleted')
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
        });
    </script>
</x-app-layout>
