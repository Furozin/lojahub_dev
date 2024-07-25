<section>
    <header>
        <h2 class="text-lg font-medium text-primary-900">
            Deletar conta
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Depois que sua conta for excluída, todos os seus recursos e dados serão excluídos permanentemente. Antes de excluir sua conta, faça o download de todos os dados ou informações que deseja reter.
        </p>
    </header>
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                Tem certeza de que deseja excluir sua conta?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Depois que sua conta for excluída, todos os seus recursos e dados serão excluídos permanentemente. Digite sua senha para confirmar que deseja excluir permanentemente sua conta.
            </p>

            <div class="mt-6">
                <label for="password" class="block text-sm font-medium leading-6 text-primary-900">Senha</label>
                <div class="mt-2">
                    <input id="del-password" name="password" type="password" autocomplete="new-password" @class(['ring-error-300' => $errors->get('password'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira sua nova senha...">
                </div>
                @if ($errors->get('password'))
                    <ul class="text-sm text-error-600 space-y-1 mt-2">
                        <li>{{ $errors->get('password')[0] }}</li>
                    </ul>
                @endif
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" x-on:click="$dispatch('close')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150"><i class="bx bx-x pr-2"></i> Cancelar</button>

                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-error-300 rounded-md font-semibold text-xs text-error-700 uppercase tracking-widest shadow-sm hover:bg-error-700 hover:text-white hover:border-white focus:outline-none disabled:opacity-25 transition ease-in-out duration-150 ml-2"><i class="bx bx-trash pr-2"></i> Deletar conta</button>
            </div>
        </form>
    </x-modal>

    <div class="flex justify-end items-center gap-4 col-span-full">
        <div class="w-full lg:w-fit">
            <button type="button" x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="flex w-full justify-center items-center rounded-md bg-error-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-error-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-error-700"><i class="bx bx-trash pr-2"></i> Deletar conta</button>
        </div>
    </div>
</section>
