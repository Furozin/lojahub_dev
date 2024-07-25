<x-app-layout>
    @section('title', 'Vendas')
    <div class="py-12">
        <div class="container">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-primary-900">Importar Vendas</div>
                

                    <!-- Botão "Importar" que abre o modal -->
                    <button type="button" class="flex justify-center items-center rounded-md bg-primary-900 transition-transform transform hover:scale-105 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white" id="abrirModalImportar">
                        Importar
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>

    document.addEventListener('DOMContentLoaded', function() {

        
        document.getElementById('abrirModalImportar').addEventListener('click', function() {

            Swal.fire({
                title: 'Atenção!',
                text: 'Certifique-se de que os campos: Data, Valor unitário, Quantidade, "N° do Pedido" e Frete estejam preenchidos. Linhas faltando esses dados não serão inseridas no sistema.',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then(() => {
                // Após o fechamento do alerta, abrir o modal de importação
                abrirModalImportacao();
            });
        });

        function abrirModalImportacao(){

            Swal.fire({
                title: 'Escolha a Origem',
                html:
                '<div class="flex flex-col space-y-4">' +
                '<button class="w-full btn bg-primary-900 text-white px-3 py-1.5 rounded hover:bg-primary-700 transition-all duration-300" id="chooseFileBtn">Escolher Arquivo</button>' +
                '<input type="file" id="importFile" class="hidden">' +
                '<div id="fileName" class="mt-3"></div>' +
                '<div id="accountSelector" class="mt-3">' +
                    '<span class="text-lg font-medium">Selecione uma conta de marketplace para qual deseja realizar a importação completa</span>' +
                    '<select id="accountList" class="w-full form-select">' +
                    '</select>' +
                '</div>',
                didOpen: () => {
                    loadAccountOptions();
                },
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Importar',
                preConfirm: () => {
                    let file = document.getElementById('importFile').files[0];
                    let selectedAccount = document.getElementById('accountList').value;
                    if (!selectedAccount) {
                        Swal.showValidationMessage('Por favor, selecione uma conta antes de continuar.');
                        return false;
                    }
                    if (!file) {
                        Swal.showValidationMessage('Por favor, selecione um arquivo antes de continuar.');
                        return false;
                    }
                    return {
                        fileName: file.name,
                        selectedAccount: selectedAccount
                    };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    let formData = new FormData();
                    formData.append('importFile', document.getElementById('importFile').files[0]);
                    formData.append('selectedAccount', result.value.selectedAccount);
                    if (result.value.includeCosts) {
                        formData.append('accountChannelId', result.value.selectedAccount);
                    }
                    fetch('{{ route('sale.import') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                console.error('Erro no envio do arquivo:', data.error);
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
                        if (data.errors) {
                            let errorMessage = "Erros de validação:\n";
                            for (let field in data.errors) {
                                errorMessage += data.errors[field].join("\n") + "\n";
                            }
                            Swal.fire('Erro de Validação!', errorMessage, 'error');
                        } else {
                            console.error('Erro desconhecido:', JSON.stringify(data));
                            Swal.fire('Erro!', 'Ocorreu um erro desconhecido. Consulte o log para mais informações.', 'error');
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
        }

        async function loadAccountOptions() {
            try {
                const response = await fetch('/sale/getAccountsDropdown');
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
    
    });
</script>