<x-app-layout>
    @section('title', 'Custos do SKU')
    <div class="py-12" x-data="{}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header class="flex justify-between items-center w-full">
                        <div class="">
                            <h2 class="text-lg font-medium text-primary-900">
                                Edição de Custo do SKU
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Edite os custos do SKU {{$sku->sku}} - {{$sku->titulo}}.
                            </p>
                        </div>
                        <div>
                            <a href="{{route('skus.custos.index', [$sku])}}"
                               class="flex w-full justify-center items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold leading-6 text-primary-900 shadow-sm hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"><i
                                    class="bx bx-arrow-back pr-2"></i> Voltar</a>
                        </div>
                    </header>

                    <form method="post" action="{{ route('skus.custos.update', [$sku, $custo]) }}"
                          class="grid grid-cols-1 lg:grid-cols-3 gap-4 w-full max-w-full mt-6">
                        @csrf
                        @method('PUT')

                        <!-- Conta Canal Venda -->
                        <div>
                            <label for="conta_canal_de_venda_id"
                                   class="block text-sm font-medium leading-6 text-primary-900">Conta Canal
                                Venda</label>
                            <div class="mt-2">
                                <select id="conta_canal_de_venda_id" name="conta_canal_de_venda_id" type="text"
                                        autocomplete="conta_canal_de_venda_id" @class(['ring-error-300' => $errors->get('conta_canal_de_venda_id'), 'block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-primary-600 sm:text-sm sm:leading-6'])>
                                    <option value="">Selecione uma conta canal venda</option>
                                    @foreach($contaCanalDeVendas as $contaCanalDeVenda)
                                        <option
                                            value="{{$contaCanalDeVenda->id}}" @selected(old('conta_canal_de_venda_id', $custo->conta_canal_de_venda_id) == $contaCanalDeVenda->id)>{{$contaCanalDeVenda->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->get('conta_canal_de_venda_id'))
                                <ul class="text-sm text-error-600 space-y-1 mt-2">
                                    <li>{{ $errors->get('conta_canal_de_venda_id')[0] }}</li>
                                </ul>
                            @endif
                        </div>

                        <!-- Empresa -->
                        <div>
                            <label for="empresa_id"
                                   class="block text-sm font-medium leading-6 text-primary-900">Empresa</label>
                            <div class="mt-2">
                                <select id="empresa_id" name="empresa_id" type="text"
                                        autocomplete="empresa_id" @class(['ring-error-300' => $errors->get('empresa_id'), 'block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-primary-600 sm:text-sm sm:leading-6'])>
                                    <option value="">Selecione uma empresa</option>
                                    @foreach($empresas as $empresa)
                                        <option
                                            value="{{$empresa->id}}" @selected(old('empresa_id', $custo->empresa_id) == $empresa->id)>{{$empresa->nome_fantasia}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->get('empresa_id'))
                                <ul class="text-sm text-error-600 space-y-1 mt-2">
                                    <li>{{ $errors->get('empresa_id')[0] }}</li>
                                </ul>
                            @endif
                        </div>

                        <!-- Custo Total -->
                        <div>
                            <label for="custo_total" class="block text-sm font-medium leading-6 text-primary-900">Custo
                                Total</label>
                            <div class="mt-2">
                                <input id="custo_total" name="custo_total" type="text" autocomplete="custo_total"
                                       value="{{old('custo_total', $custo->custo_total)}}"
                                       @class(['ring-error-300' => $errors->get('custo_total'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira o custo total...">
                            </div>
                            @if ($errors->get('custo_total'))
                                <ul class="text-sm text-error-600 space-y-1 mt-2">
                                    <li>{{ $errors->get('custo_total')[0] }}</li>
                                </ul>
                            @endif
                        </div>

                        <div class="flex justify-end items-center gap-4 col-span-full">
                            <div class="w-full lg:w-fit">
                                <button type="submit"
                                        class="flex w-full justify-center items-center rounded-md bg-primary-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-700">
                                    <i class="bx bx-save pr-2"></i>Salvar
                                </button>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="module">
            @if (session('status') == 'sku-custo-updated')
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Custos salvos com sucesso!',
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
            @endif
        </script>
    @endpush
</x-app-layout>
