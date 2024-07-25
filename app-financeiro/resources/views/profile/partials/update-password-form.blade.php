<section>
    <header>
        <h2 class="text-lg font-medium text-primary-900">
            Atualizar senha
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Certifique-se de que sua conta esteja usando uma senha longa e aleatória para se manter segura.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-4 w-full max-w-full mt-6">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <label for="current_password" class="block text-sm font-medium leading-6 text-primary-900">Senha</label>
            <div class="mt-2">
                <input id="current_password" name="current_password" type="password" autocomplete="current-password" @class(['ring-error-300' => $errors->get('current_password'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira sua senha atual...">
            </div>
            @if ($errors->get('current_password'))
                <ul class="text-sm text-error-600 space-y-1 mt-2">
                    <li>{{ $errors->get('current_password')[0] }}</li>
                </ul>
            @endif
        </div>

        <!-- New Password -->
        <div>
            <label for="password" class="block text-sm font-medium leading-6 text-primary-900">Senha</label>
            <div class="mt-2">
                <input id="password" name="password" type="password" autocomplete="new-password" @class(['ring-error-300' => $errors->get('password'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira sua nova senha...">
            </div>
            @if ($errors->get('password'))
                <ul class="text-sm text-error-600 space-y-1 mt-2">
                    <li>{{ $errors->get('password')[0] }}</li>
                </ul>
            @endif
        </div>

        <!-- Password Confirmation -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium leading-6 text-primary-900">Confirme a Senha</label>
            <div class="mt-2">
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" @class(['ring-error-300' => $errors->get('password'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Confirme sua nova senha...">
            </div>
            @if ($errors->get('password'))
                <ul class="text-sm text-error-600 space-y-1 mt-2">
                    <li>{{ $errors->get('password')[0] }}</li>
                </ul>
            @endif
        </div>

        <div class="flex justify-end items-center gap-4 col-span-full">
            <div class="w-full lg:w-fit">
                <button type="submit" class="flex w-full justify-center items-center rounded-md bg-primary-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-700"><i class="bx bx-save pr-2"></i> Salvar</button>
            </div>
        </div>
    </form>
</section>
