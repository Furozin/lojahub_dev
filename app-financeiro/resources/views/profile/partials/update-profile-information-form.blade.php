<section>
    <header>
        <h2 class="text-lg font-medium text-primary-900">
            Informação do Perfil
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Atualize as informações de perfil e o endereço de email da sua conta.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}"
          class="grid grid-cols-1 lg:grid-cols-3 gap-4 w-full max-w-full mt-6">
        @csrf
        @method('patch')

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium leading-6 text-primary-900">Nome</label>
            <div class="mt-2">
                <input id="name" name="name" type="text" autocomplete="name" value="{{old('name', $user->name)}}"
                       autofocus
                       @class(['ring-error-300' => $errors->get('name'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira seu nome...">
            </div>
            @if ($errors->get('name'))
                <ul class="text-sm text-error-600 space-y-1 mt-2">
                    <li>{{ $errors->get('name')[0] }}</li>
                </ul>
            @endif
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-sm font-medium leading-6 text-primary-900">Telefone</label>
            <div class="mt-2">
                <input id="phone" name="phone" type="text" autocomplete="phone"
                       value="{{old('phone', $user->acesso->telefone)}}"
                       @class(['ring-error-300' => $errors->get('phone'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira seu telefone...">
            </div>
            @if ($errors->get('phone'))
                <ul class="text-sm text-error-600 space-y-1 mt-2">
                    <li>{{ $errors->get('phone')[0] }}</li>
                </ul>
            @endif
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium leading-6 text-primary-900">Email</label>
            <div class="mt-2">
                <input id="email" name="email" type="email" autocomplete="email" value="{{old('email', $user->email)}}"
                       @class(['ring-error-300' => $errors->get('email'), 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-700 sm:text-sm sm:leading-6']) placeholder="Insira seu email...">
            </div>
            @if ($errors->get('email'))
                <ul class="text-sm text-error-600 space-y-1 mt-2">
                    <li>{{ $errors->get('email')[0] }}</li>
                </ul>
            @endif
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex justify-end items-center gap-4 col-span-full">
            <div class="w-full lg:w-fit">
                <button type="submit"
                        class="flex w-full justify-center items-center rounded-md bg-primary-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-700">
                    <i class="bx bx-save pr-2"></i> Salvar
                </button>
            </div>
        </div>
    </form>
    @push('scripts')
        <script type="module">
            @if (session('status') === 'profile-updated')
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Alterações salvo com sucesso!',
                timer: 1500,
                showConfirmButton: false,
                timerProgressBar: true,
            });
            @endif

            @if (session('status') === 'password-updated')
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Senha alterada com sucesso!',
                timer: 1500,
                showConfirmButton: false,
                timerProgressBar: true,
            });
            @endif
        </script>
    @endpush
</section>
