<x-guest-layout>

    <form class="space-y-6" action="{{ route('login') }}" method="POST">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium leading-6 text-primary-900">Email</label>
            <div class="mt-2">
                <input id="email" name="email" type="email" autocomplete="email" value="{{old('email')}}" autofocus @class(['ring-error-300' => $errors->get('email'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira seu email...">
            </div>
            @if ($errors->get('email'))
                <ul class="text-sm text-error-600 space-y-1 mt-2">
                    <li>{{ $errors->get('email')[0] }}</li>
                </ul>
            @endif
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium leading-6 text-primary-900">Senha</label>
                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-semibold text-secondary-600 hover:text-secondary-500">Esqueceu sua senha?</a>
                </div>
            </div>
            <div class="mt-2">
                <input id="password" name="password" type="password" autocomplete="current-password" @class(['ring-error-300' => $errors->get('password'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira sua senha...">
            </div>
            @if ($errors->get('password'))
                <ul class="text-sm text-error-600 space-y-1 mt-2">
                    <li>{{ $errors->get('password')[0] }}</li>
                </ul>
            @endif
        </div>
        <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-primary-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-700">Entrar</button>
        </div>
    </form>
    <p class="mt-10 text-center text-sm text-gray-500">
        Não é cadastrado?
        <a href="{{ route('register') }}" class="font-semibold leading-6 text-secondary-600 hover:text-secondary-500">Registre-se aqui!</a>
    </p>
</x-guest-layout>
