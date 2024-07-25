<x-guest-layout>
    <div class="my-4 text-sm text-justify text-gray-500">
        Esqueceu sua senha? Sem problemas. Basta nos informar o seu endereço de e-mail e enviaremos um e-mail com um link de redefinição de senha que permitirá que você escolha um novo.
    </div>

    <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
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

        <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-primary-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-700">Enviar link para redefinir sua senha</button>
        </div>
    </form>
    <p class="mt-10 text-center text-sm text-gray-500">
        Já possui uma conta?
        <a href="{{ route('login') }}" class="font-semibold leading-6 text-secondary-600 hover:text-secondary-500">Autentique-se aqui!</a>
    </p>
</x-guest-layout>
