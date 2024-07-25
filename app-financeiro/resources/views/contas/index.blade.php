<x-app-layout>
    @section('title', $origens->find(session('origem_id'))->nome_canal ?? 'Contas')
    <div class="py-12" x-data="{}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header class="flex justify-between items-center w-full mb-8">
                        <div class="">
                            <h2 class="text-lg font-medium text-primary-900">
                                Listagem de Contas
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Encontre aqui as suas contas.
                            </p>
                        </div>
                        <div>
                            <a href="{{route('contas.create')}}"
                               class="flex w-full justify-center items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold leading-6 text-primary-900 shadow-sm hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                <i class="bx bx-plus pr-2"></i>Cadastrar
                            </a>
                        </div>
                    </header>
                    @if($contas->total() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                <tr class="text-center divide-x divide-gray-200">
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        Nome
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        Referência
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        Comissão do Markeplace
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        Ações
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                @foreach($contas as $conta)
                                    <tr class="text-center even:bg-gray-50 divide-x divide-gray-200">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                            {{$conta->nome ?? '-'}}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{str($conta->referencia_no_canal_de_venda)->limit(50) ?? '-'}}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{$conta->comissao['mkt'] ?? '0'}}%
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                            <a href="{{route('contas.edit', [$conta])}}"
                                               class="text-primary-900 hover:text-primary-700">
                                                <i class="bx bxs-edit text-lg pr-2"></i>
                                                <span class="sr-only">, {{$conta->nome}} </span>
                                            </a>
                                            <a href="#" @click.prevent="deleteConta({{$conta->id}})"
                                               class="text-error-900 hover:text-error-700">
                                                <i class="bx bx-trash text-lg pr-2"></i>
                                                <span class="sr-only">, {{$conta->nome}}</span>
                                            </a>

                                            <form id="conta-destroy-{{$conta->id}}" method="POST"
                                                  action="{{ route('contas.destroy', [$conta]) }}">
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
                            {{ $contas->links() }}
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
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">Nenhuma conta encontrado!</h3>
                            <p class="mt-1 text-sm text-gray-500">Comece criando uma conta.</p>
                            <div class="mt-6">
                                <a href="{{route('contas.create')}}"
                                   class="inline-flex items-center rounded-md bg-primary-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path
                                            d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
                                    </svg>
                                    Cadastrar conta
                                </a>
                            </div>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            const deleteConta = conta => {
                Swal.fire({
                    icon: 'warning',
                    title: 'Deseja realmente excluir essa CONTA?',
                    showConfirmButton: true,
                    confirmButtonText: 'Sim, tenho certeza!',
                    confirmButtonColor: 'red',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.querySelector(`#conta-destroy-${conta}`).submit();
                    }
                });
            }
        </script>
        <script type="module">
            @if ( session('status') === 'conta-deleted')
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
        </script>
    @endpush
</x-app-layout>
