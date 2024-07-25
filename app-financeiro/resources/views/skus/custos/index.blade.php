<x-app-layout>
    @section('title', 'Custos do SKU')
    <div class="py-12" x-data="{tab: 'custos'}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header class="flex justify-between items-center w-full">
                        <div class="">
                            <h2 class="text-lg font-medium text-primary-900">
                                Listagem de Custos
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Encontre aqui os custos de seu SKU.
                            </p>
                        </div>

                        <div class="flex justify-around space-x-4">
                            <a href="{{route('skus.custos.create', [$sku])}}"
                               class="flex w-full justify-center items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold leading-6 text-primary-900 shadow-sm hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"><i
                                    class="bx bx-plus pr-2"></i> Cadastrar</a>
                            <a href="{{route('skus.index')}}"
                               class="flex w-full justify-center items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold leading-6 text-primary-900 shadow-sm hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"><i
                                    class="bx bx-arrow-back pr-2"></i> Voltar</a>
                        </div>
                    </header>


                    <div class="py-4">
                        <div class="sm:hidden">
                            <label for="tabs" class="sr-only">Selecie uma tab</label>
                            <select x-model="tab" id="tabs" name="tabs"
                                    class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-secondary-500 focus:outline-none focus:ring-primary-500 sm:text-sm">
                                <option :value="'sku'">SKU</option>
                                <option :value="'custos'">Custos</option>
                            </select>
                        </div>
                        <div class="hidden sm:block">
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                    <a href="{{route('skus.edit', [$sku])}}"
                                       :class="[tab === 'sku' ? 'border-secondary-600 text-primary-900' : 'border-transparent text-gray-500 hover:border-gray-200 hover:text-gray-700' , 'flex whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium']"
                                       aria-current="page">
                                        SKU
                                    </a>
                                    <a href="{{route('skus.custos.index', [$sku])}}"
                                       :class="[tab === 'custos' ? 'border-secondary-600 text-primary-900' : 'border-transparent text-gray-500 hover:border-gray-200 hover:text-gray-700' , 'flex whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium']"
                                       aria-current="page">
                                        Custos
                                        <span
                                            :class="[tab === 'custos' ?  'bg-primary-100 text-primary-600' : 'bg-gray-100 text-gray-900','ml-3 hidden rounded-full py-0.5 px-2.5 text-xs font-medium md:inline-block']">{{$custos->total()}}</span>
                                    </a>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 w-fullmt-6">
                        @if($custos->total() > 0)
                            <table class="min-w-full w-full divide-y divide-gray-300">
                                <thead>
                                <tr class="text-center divide-x divide-gray-200">
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        Conta Canal de Venda
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        Empresa
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-primary-900">
                                        Custo
                                    </th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-3">
                                        <span class="sr-only">Opções</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                @foreach($custos as $custo)
                                    <tr class="text-center even:bg-gray-50 divide-x divide-gray-200">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-3">
                                            {{$custo->contaCanalDeVenda->nome}}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{$custo->empresa->nome_fantasia ?? '-'}}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{$custo->custo_total}}
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                            <a href="{{route('skus.custos.edit', [$sku, $custo])}}"
                                               class="text-primary-900 hover:text-primary-700">
                                                <i class="bx bxs-edit text-lg pr-2"></i>
                                                <span class="sr-only">, {{$custo->custo_total}}</span>
                                            </a>
                                            <a href="#" @click.prevent="deleteSkuCusto({{$custo->id}})"
                                               class="text-error-900 hover:text-error-700">
                                                <i class="bx bx-trash text-lg pr-2"></i>
                                                <span class="sr-only">, {{$custo->custo_total}}</span>
                                            </a>

                                            <form id="sku-custo-destroy-{{$custo->id}}" method="POST"
                                                  action="{{ route('skus.custos.destroy', [$sku, $custo]) }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $custos->links() }}
                            </div>
                        @else
                            <div
                                class="text-center relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" aria-hidden="true">
                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round"
                                          stroke-linejoin="round" stroke-width="2"
                                          d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900">Nenhum custo encontrado!</h3>
                                <p class="mt-1 text-sm text-gray-500">Comece criando um novo custo.</p>
                                <div class="mt-6">
                                    <a href="{{route('skus.custos.create', [$sku])}}"
                                       class="inline-flex items-center rounded-md bg-primary-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                             aria-hidden="true">
                                            <path
                                                d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
                                        </svg>
                                        Cadastrar custo
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                </section>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            const deleteSkuCusto = skuCusto => {
                Swal.fire({
                    icon: 'warning',
                    title: 'Deseja realmente excluir esse custo?',
                    showConfirmButton: true,
                    confirmButtonText: 'Sim, tenho certeza!',
                    confirmButtonColor: 'red',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.querySelector(`#sku-custo-destroy-${skuCusto}`).submit();
                    }
                });
            }
        </script>
        <script type="module">
            @if (session('status') == 'sku-custo-deleted')
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Excluído com sucesso!',
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
            @endif

            @if (session('status') == 'sku-custo-created')
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Custo salvo com sucesso!',
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
            @endif
        </script>
    @endpush
</x-app-layout>
