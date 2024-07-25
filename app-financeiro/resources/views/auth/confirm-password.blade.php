<x-guest-layout>
    <div class="my-4 text-sm text-justify text-gray-500">
        Esta é uma área segura do aplicativo. Por favor, confirme a sua senha antes de continuar.
    </div>

    <form class="space-y-6" action="{{ route('password.confirm') }}" method="POST">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium leading-6 text-primary-900">Senha</label>
            <div class="mt-2">
                <input id="password" name="password" type="password" autocomplete="new-password" @class(['ring-error-300' => $errors->get('password'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira sua senha...">
            </div>
            @if ($errors->get('password'))
                <ul class="text-sm text-error-600 space-y-1 mt-2">
                    <li>{{ $errors->get('password')[0] }}</li>
                </ul>
            @endif
        </div>

        <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-primary-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-700">Confirmar</button>
        </div>
    </form>
    <p class="mt-10 text-center text-sm text-gray-500">
        Já possui uma conta?
        <a href="{{ route('login') }}" class="font-semibold leading-6 text-secondary-600 hover:text-secondary-500">Autentique-se aqui!</a>
    </p>
</x-guest-layout>
