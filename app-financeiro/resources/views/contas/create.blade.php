<x-app-layout>
    @section('title', $origens->find(session('origem_id'))->nome_canal ?? 'Contas')
    <div class="py-12" x-data="{}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header class="flex justify-between items-center w-full">
                        <div class="">
                            <h2 class="text-lg font-medium text-primary-900">
                                Cadastro de Contas
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Cadastre as Contas.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('contas.index') }}"
                               class="flex w-full justify-center items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold leading-6 text-primary-900 shadow-sm hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"><i
                                    class="bx bx-arrow-back pr-2"></i> Voltar</a>
                        </div>
                    </header>

                    <form method="post" action="{{ route('contas.store') }}"
                          class="grid grid-cols-1 lg:grid-cols-3 gap-4 w-full max-w-full mt-6"
                          enctype="multipart/form-data">
                        @csrf

                        <!-- Nome -->
                        <div>
                            <label for="nome" class="block text-sm font-medium leading-6 text-primary-900">Nome</label>
                            <div class="mt-2">
                                <input id="nome" name="nome" type="text" autocomplete="nome" value="{{old('nome')}}"
                                       autofocus
                                       @class(['ring-error-300' => $errors->get('nome'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira o nome da conta...">
                            </div>
                            @error ('nome')
                            <ul class="text-sm text-error-600 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>

                        <!-- Referência -->
                        <div>
                            <label for="referencia_no_canal_de_venda"
                                   class="block text-sm font-medium leading-6 text-primary-900">Referência</label>
                            <div class="mt-2">
                                <input id="referencia_no_canal_de_venda" name="referencia_no_canal_de_venda" type="text"
                                       autocomplete="referencia_no_canal_de_venda"
                                       value="{{old('referencia_no_canal_de_venda')}}"
                                       @class(['ring-error-300' => $errors->get('referencia_no_canal_de_venda'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira a referência no canal de venda...">
                            </div>
                            @error ('referencia_no_canal_de_venda')
                            <ul class="text-sm text-error-600 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>

                        <!-- Comissão do Marketplace -->
                        <div>
                            <label for="comissao"
                                   class="block text-sm font-medium leading-6 text-primary-900">Comissão do Marketplace
                                (%)</label>
                            <div class="mt-2">
                                <input id="comissao" name="comissao" type="text"
                                       autocomplete="comissao"
                                       value="{{old('comissao')}}"
                                       @class(['ring-error-300' => $errors->get('comissao'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira a porcentagem da comissão do marketplace...">
                            </div>
                            @error ('comissao')
                            <ul class="text-sm text-error-600 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>

                        {{-- Origem ID vindo da session--}}
                        <input id="origem_id" name="origem_id" type="hidden"
                               value="{{old('origem_id', session('origem_id'))}}" />

                        <div class="flex justify-end items-center gap-4 col-span-full">
                            <div class="w-full lg:w-fit">
                                <button type="submit"
                                        class="flex w-full justify-center rounded-md bg-primary-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-700">
                                    Salvar
                                </button>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
    <script>
        @push('scripts')
        $('#comissao').mask('00', {
            reverse: true
        });
        @endpush
    </script>
</x-app-layout>
