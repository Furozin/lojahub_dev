<x-app-layout>
    @section('title', 'Dashboard')
    <div class="py-12">
        <div class="container">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-primary-900">Dash</div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                @if (!$hasExistingEnterprise)
                    Swal.fire({
                        title: 'Encontrar CNPJ da empresa',
                        html: `
                                    <p>É necessário estar vinculado a uma empresa para utilizar a plataforma. </p>
                                        <form id="enterpriseForm">
                                            <input type="text" id="cnpj" name="cnpj" placeholder="00.000.000/0000-00" required>
                                        </form>
                                    `,
                        icon: 'info',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Pesquisar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        preConfirm: () => {
                            return new Promise((resolve) => {
                                const form = document.getElementById('enterpriseForm');
                                const cnpj = form.elements['cnpj'].value;

                                if (!cnpj)
                                {
                                    Swal.showValidationMessage('Por favor, preencha o campo CNPJ');
                                    resolve(false);
                                }
                                else
                                {
                                    Swal.showLoading();

                                    axios.get('{{ route("enterprise.search") }}', {
                                        params: {
                                            cnpj: cnpj
                                        }
                                    })
                                    .then(response => {
                                        if (response.status === 200) {
                                            const data = response.data;
                                            if (data.status)
                                            {
                                                Swal.update({
                                                    title: 'Empresa encontrada',
                                                    html: `
                                                                    <p>${data.message}</p>
                                                                    <p>O uso da plataforma tem relação direta ao vínculo com alguma empresa.</p>
                                                                    <button id="refazerConsultaBtn" class="swal2-confirm swal2-styled" type="button">Refazer consulta</button>
                                                                `,
                                                    showCloseButton: false,
                                                    showConfirmButton: false,
                                                    showCancelButton: false,
                                                    preConfirm: () => {
                                                        resolve(true);
                                                    }
                                                });

                                                const refazerConsultaBtn = document.getElementById('refazerConsultaBtn');
                                                refazerConsultaBtn.addEventListener('click', function() {
                                                    window.location.href = '{{ route("dashboard") }}';
                                                    resolve(true);
                                                });
                                                resolve(true);
                                            }
                                            else if (!data.status)
                                            {
                                                Swal.update({
                                                    title: 'Cadastrar Nova Empresa',
                                                    html: `
                                                        <p>${data.message}</p>
                                                        <button id="cadastrarEmpresaBtn" class="swal2-confirm swal2-styled" type="button">
                                                            Cadastrar Empresa
                                                        </button>
                                                    `,
                                                    showCloseButton: false,
                                                    showConfirmButton: false,
                                                    showCancelButton: false,
                                                    preConfirm: () => {
                                                        // Ação para cadastrar uma nova empresa
                                                        // ...
                                                    }
                                                });

                                                const cadastrarEmpresaBtn = document.getElementById('cadastrarEmpresaBtn');
                                                cadastrarEmpresaBtn.addEventListener('click', function() {
                                                    Swal.close();
                                                    window.location.href = '{{ route("enterprise.create") }}';
                                                    resolve(true);
                                                });
                                            }
                                        }
                                        else
                                        {
                                            throw new Error('Erro na solicitação AJAX');
                                        }
                                    })
                                    .catch(error => {
                                        // Exiba uma mensagem de erro ou tome as ações apropriadas em caso de erro
                                        console.error(error);
                                        resolve(false);
                                    });
                                }
                            });
                        }
                    });
                @elseif (session('actualEnterpriseId') === null && $hasExistingEnterprise)
                    @php
                        $swalContent = '';
                    @endphp

                    @foreach($empresa as $enterprise)
                        @php
                            $swalContent .= 
                                '<div role="button" id="' . $enterprise->id . '" class="flex flex-col items-center intem-card m-2 p-4 border rounded-lg w-60 min-h-[230px]">';
                                    if (!empty($enterprise->logo_base64)) 
                                    {
                                        $swalContent .= '<img src="' . asset('storage/' . $enterprise->logo_base64) . '" class="w-24 h-24 rounded-lg mb-2" alt="' . $enterprise->razao_social . '">';
                                    } 
                                    else {
                                        $swalContent .= '<img src="' . asset('imagens/enterprise_icon.png') . '" class="w-24 h-20 rounded-lg mb-2" alt="' . $enterprise->razao_social . '">';
                                    }

                                    $swalContent .= 
                                    '<div class="text-center w-full">
                                        <strong class="text-lg mb-2 break-words">' . $enterprise->razao_social . '</strong>
                                        <p class="text-sm text-gray-500 truncate">' . $enterprise->cnpj . '</p>

                                    </div>
                                </div>';
                        @endphp
                    @endforeach

                    @php
                        $swalContent = '<div class="flex flex-wrap justify-start items-start">' . $swalContent . '</div>';
                    @endphp

                    let swalContent = `{!! $swalContent !!}`;
                    Swal.fire({
                        title: 'Selecione a empresa',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        html: swalContent,
                        width: '850px',
                        showConfirmButton: false
                    });
                @endif

                function enviarDados(idEnterprise)
                {
                    axios.post("{{ route('dashboard.update') }}", 
                    {
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
                        });
                    })
                    .catch(function(error) {
                        // Lidar com erros
                        console.log(error);
                    });
                }

                $('.intem-card').on('click', function() {

                    // Remover a classe de outros elementos .card
                    $('.intem-card').not(this).removeClass('border-4 border-indigo-500');
                    $(this).toggleClass('border-4 border-indigo-500');

                    let idEnterprise = $(this).attr('id');
                    enviarDados(idEnterprise);
                });

                $(document).on('click', '.intem-card', function() {
                    Swal.close();
                });

                $('#cnpj').mask('00.000.000/0000-00', {
                    reverse: true
                });
            });
        </script>
    @endpush
</x-app-layout>

