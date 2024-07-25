<x-app-layout>
    @section('title', 'SKUs')
    <div class="py-12"
         x-data="{tag: '', tags: @js(old('tags') ? explode(',', old('tags')) : ($sku->tags ?? [])), tab: 'sku', imgsrc: null}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header class="flex justify-between items-center w-full">
                        <div class="">
                            <h2 class="text-lg font-medium text-primary-900">
                                Edição de SKU
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Atualize os SKUs e seus custos.
                            </p>
                        </div>
                        <div>
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
                                            :class="[tab === 'custos' ?  'bg-primary-100 text-primary-600' : 'bg-gray-100 text-gray-900','ml-3 hidden rounded-full py-0.5 px-2.5 text-xs font-medium md:inline-block']">{{$sku->custos_count}}</span>
                                    </a>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <form id="form-sku-remove-image" method="post" action="{{ route('skus.image.destroy', [$sku]) }}"
                          class="hidden" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="image-delete" id="image-delete" value="delete">
                    </form>

                    <form id="form-sku-edit" method="post" action="{{ route('skus.update', [$sku]) }}"
                          class="grid grid-cols-1 lg:grid-cols-3 gap-4 w-full max-w-full mt-6"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Imagem -->
                        <div class="flex items-center gap-x-8 cursor-pointer"
                             @click="document.querySelector('#imagem-upload').click()">
                            @if($sku->url_imagem)
                                <img loading="lazy" x-show="imgsrc" :src="imgsrc" alt="imagem"
                                     id="imagem-preview" @class(['hidden h-16 w-16 flex-none rounded-lg bg-gray-800 object-cover'])>
                                <img loading="lazy" x-show="!imgsrc" src="{{$sku->url_imagem}}" alt="{{$sku->titulo}}"
                                     class="h-16 w-16 flex-none rounded-lg bg-gray-800 object-cover">
                            @else
                                <img loading="lazy" x-show="imgsrc" :src="imgsrc" alt="imagem"
                                     id="imagem-preview" @class(['hidden h-16 w-16 flex-none rounded-lg bg-gray-800 object-cover'])>
                                <span x-show="!imgsrc"
                                      class="flex items-center justify-center inline-block h-16 w-16 overflow-hidden rounded-md bg-gray-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-2/3 w-2/3 text-gray-100"
                                             fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 4C14.7614 4 17 6.23858 17 9V10C19.2091 10 21 11.7909 21 14C21 15.4806 20.1956 16.8084 19 17.5M7 10V9C7 7.87439 7.37194 6.83566 7.99963 6M7 10C4.79086 10 3 11.7909 3 14C3 15.4806 3.8044 16.8084 5 17.5M7 10C7.43285 10 7.84965 10.0688 8.24006 10.1959M12 12V21M12 12L15 15M12 12L9 15"
                                                stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                            @endif
                            <div>
                                @if($sku->url_imagem)
                                    <button x-show="!imgsrc" @click.stop="removeImage" type="button"
                                            class="flex w-full justify-center items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold leading-6 text-primary-900 shadow-sm hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                        <i class="bx bx-trash pr-2"></i> Remover imagem
                                    </button>
                                    <button x-show="imgsrc" @click.stop="removeFile" type="button" id="btn-change-image"
                                            class="flex w-full hidden justify-center items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold leading-6 text-primary-900 shadow-sm hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                        <i class="bx bx-trash pr-2"></i> Remover seleção
                                    </button>
                                @else
                                    <button x-show="!imgsrc" type="button"
                                            class="flex w-full justify-center items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold leading-6 text-primary-900 shadow-sm hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                        <i class="bx bx-image pr-2"></i> Selecione uma imagem
                                    </button>
                                    <button x-show="imgsrc" @click.stop="removeFile" type="button" id="btn-change-image"
                                            class="flex w-full hidden justify-center items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold leading-6 text-primary-900 shadow-sm hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                        <i class="bx bx-trash pr-2"></i> Remover seleção
                                    </button>
                                @endif
                                <p class="mt-2 text-xs leading-5 text-gray-400">JPG ou PNG.</p>
                            </div>
                            <input x-ref="myFile" @change="previewFile" type="file" name="imagem-upload"
                                   id="imagem-upload" @change="document.querySelector('#form-sku-edit').submit()"
                                   accept="image/jpeg, image/png" class="hidden">
                        </div>

                        <!-- SKU -->
                        <div>
                            <label for="sku" class="block text-sm font-medium leading-6 text-primary-900">SKU</label>
                            <div class="mt-2">
                                <input id="sku" name="sku" type="text" autocomplete="sku"
                                       value="{{old('sku', $sku->sku)}}" autofocus
                                       @class(['ring-error-300' => $errors->get('sku'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira seu SKU...">
                            </div>
                            @error('sku')
                            <ul class="text-sm text-error-600 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>

                        <!-- Titulo -->
                        <div>
                            <label for="titulo"
                                   class="block text-sm font-medium leading-6 text-primary-900">Título</label>
                            <div class="mt-2">
                                <input id="titulo" name="titulo" type="text" autocomplete="titulo"
                                       value="{{old('titulo', $sku->titulo)}}"
                                       @class(['ring-error-300' => $errors->get('titulo'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira seu título...">
                            </div>
                            @error('titulo')
                            <ul class="text-sm text-error-600 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>


                        <!-- Status -->
                        <div>
                            <label for="status"
                                   class="block text-sm font-medium leading-6 text-primary-900">Status</label>
                            <div class="mt-2">
                                <select id="status" name="status" type="text"
                                        autocomplete="status" @class(['ring-error-300' => $errors->get('status'), 'block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-primary-600 sm:text-sm sm:leading-6'])>
                                    <option value="">Selecione um status</option>
                                    <option value="1" @selected(old('status', $sku->status) == 1)>Ativo</option>
                                    <option value="2" @selected(old('status', $sku->status) == 2)>Inativo</option>
                                </select>
                            </div>
                            @error('status')
                            <ul class="text-sm text-error-600 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>

                        <!-- Fornecedor -->
                        <div>
                            <label for="fornecedor" class="block text-sm font-medium leading-6 text-primary-900">Fornecedor</label>
                            <div class="mt-2">
                                <input id="fornecedor" name="fornecedor" type="text" autocomplete="fornecedor"
                                       value="{{old('fornecedor', $sku->fornecedor)}}"
                                       @class(['ring-error-300' => $errors->get('fornecedor'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira seu fornecedor...">
                            </div>
                            @error('fornecedor')
                            <ul class="text-sm text-error-600 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>

                        <!-- Tempo de Reposição (dias) -->
                        <div>
                            <label for="dias_tempo_reposicao"
                                   class="block text-sm font-medium leading-6 text-primary-900">Tempo de Reposição
                                (dias)</label>
                            <div class="mt-2">
                                <input id="dias_tempo_reposicao" name="dias_tempo_reposicao" type="text"
                                       autocomplete="dias_tempo_reposicao"
                                       value="{{old('dias_tempo_reposicao', $sku->dias_tempo_reposicao)}}"
                                       @class(['ring-error-300' => $errors->get('dias_tempo_reposicao'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira o tempo de reposição em quantidade de dias...">
                            </div>
                            @error('dias_tempo_reposicao')
                            <ul class="text-sm text-error-600 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>

                        <!-- Descrição -->
                        <div class="col-span-full">
                            <label for="descricao" class="block text-sm font-medium leading-6 text-primary-900">Descrição</label>
                            <div class="mt-2">
                                <textarea rows="5" name="descricao" id="descricao"
                                          @class(['ring-error-300' => $errors->get('descricao'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira a descrição....">{{old('descricao', $sku->descricao)}}</textarea>
                            </div>
                            @error('descricao')
                            <ul class="text-sm text-error-600 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>


                        <!-- Tags -->
                        <div class="col-span-full divide-x divide-gray-200 mt-4">
                            <div class="w-1/3">
                                <label for="tag" class="block text-sm font-medium leading-6 text-primary-900">
                                    Tags <span
                                        class="text-xs text-gray-500 font-thin">(Tecle Enter para adicionar)</span>
                                </label>
                                <div class="mt-2">
                                    <input id="tag" name="tag" type="text" autocomplete="tag"
                                           value="{{old('tag', $sku->tag)}}"
                                           @class(['ring-error-300' => $errors->get('tags'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira seu tag..."
                                           x-model="tag" @keydown.enter.prevent="tags.push(tag); tag='';">
                                    <input id="tags" name="tags" type="hidden" autocomplete="tags"
                                           value="{{old('tags', implode(',', $sku->tags))}}" x-model="tags"/>
                                </div>
                                @error('tags')
                                <ul class="text-sm text-error-600 space-y-1 mt-2">
                                    <li>{{ $message }}</li>
                                </ul>
                                @enderror
                            </div>
                        </div>

                        <!-- Listagem das Tags -->
                        <div class="col-span-full">
                            <template x-for="(tag, index) in tags">
                                    <span
                                        class="mr-4 inline-flex items-center gap-x-0.5 rounded-md bg-secondary-50 px-3 py-1 text-sm font-medium text-secondary-700 ring-1 ring-inset ring-secondary-700/10">
                                        <span x-text="tag"></span>
                                          <button type="button"
                                                  class="group relative -mr-1 h-3.5 w-3.5 rounded-sm hover:bg-secondary-600/20"
                                                  @click="tags.splice(index, 1)">
                                            <span class="sr-only">Remove</span>
                                                <svg viewBox="0 0 14 14"
                                                     class="h-3.5 w-3.5 stroke-secondary-700/50 group-hover:stroke-secondary-700/75">
                                                    <path d="M4 4l6 6m0-6l-6 6"/>
                                                </svg>
                                            <span class="absolute -inset-1"></span>
                                          </button>
                                    </span>
                            </template>
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
        <script>
            function previewFile() {
                let file = this.$refs.myFile.files[0];
                if (!file || file.type.indexOf('image/') === -1) return;
                this.imgsrc = null;
                let reader = new FileReader();

                reader.onload = e => {
                    this.imgsrc = e.target.result;
                }

                reader.readAsDataURL(file);
            }

            function removeFile() {
                this.imgsrc = null;
                this.$refs.myFile.files = null;
                document.querySelector('#imagem-upload').value = "";
            }

            function removeImage() {
                document.querySelector('#form-sku-remove-image').submit();
            }

            // tip para não ficar piscando ao recarregar tela
            setTimeout(() => {
                document.querySelector('#imagem-preview')?.classList.remove('hidden');
                document.querySelector('#btn-change-image')?.classList.remove('hidden');
            }, 500)
        </script>
        <script type="module">
            @if (session('status') == 'sku-updated')
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Sku atualizado com sucesso!',
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
            @endif

            @if (session('status') == 'sku-image-deleted')
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
        </script>
    @endpush
</x-app-layout>
