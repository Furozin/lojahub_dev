<nav x-data="{ open: false }" class="border-b border-gray-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <a href="{{route('dashboard')}}" class="flex flex-shrink-0 items-center">
                    <div class="flex flex-shrink-0 items-center">
                        <img class="block h-8 w-auto lg:hidden" src="https://www.lojahub.com.br/images/logo.svg" alt="LojaHub - Finanças">
                        <img class="hidden h-8 w-auto lg:block" src="https://www.lojahub.com.br/images/logo.svg" alt="LojaHub - Finanças">
                    </div>
                </a>
                <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">

                    <a href="{{ route('dashboard') }}"
                        @class(
                            ['border-secondary-600 text-primary-900'=> request()->routeIs('dashboard'),
                            'border-transparent text-gray-500 hover:border-secondary-400 hover:text-primary-700' => !request()->routeIs('dashboard'),
                            'inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium']
                        )
                        @if(request()->routeIs('dashboard')) aria-current="page" @endif>
                        Dashboard
                    </a>

                    <a href="{{ route('sale.index') }}"
                        @class(['border-secondary-600 text-primary-900'=> request()->routeIs('sale.index'),
                            'border-transparent text-gray-500 hover:border-secondary-400 hover:text-primary-700' => !request()->routeIs('sale.index'),
                            'inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium']
                        )
                        @if(request()->routeIs('sale.index')) aria-current="page" @endif>
                        Vendas
                    </a>

                    <a href="{{ route('enterprise.index') }}"
                        @class(['border-secondary-600 text-primary-900'=> request()->routeIs('enterprise.index'),
                            'border-transparent text-gray-500 hover:border-secondary-400 hover:text-primary-700' => !request()->routeIs('enterprise.index'),
                            'inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium']
                        )
                        @if(request()->routeIs('enterprise.index')) aria-current="page" @endif>
                        Empresas
                    </a>

                    <a href="{{ route('skus.index') }}"
                        @class(
                            ['border-secondary-600 text-primary-900' => request()->routeIs('skus.index'),
                            'border-transparent text-gray-500 hover:border-secondary-400 hover:text-primary-700' => !request()->routeIs('skus.index'),
                            'inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium']
                        )
                        @if(request()->routeIs('skus.index')) aria-current="page" @endif>
                        SKUs
                    </a>

                    <div x-data="{dropdownMenu: false}" x-cloak @click.outside="dropdownMenu = false"
                        @class(
                            ['border-secondary-600 text-primary-900' => request()->routeIs('contas.index'),
                            'border-transparent text-gray-500 hover:border-secondary-400 hover:text-primary-700' => !request()->routeIs('contas.index'),
                            'relative inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium']
                        )>
                        <!-- Dropdown toggle button -->
                        <a href="#" @click.prevent="dropdownMenu = !dropdownMenu"
                            @if(request()->routeIs('contas.index')) aria-current="page" @endif>
                            Marketplaces <i class="bx" :class="{'bx-chevron-down': !dropdownMenu, 'bx-chevron-up': dropdownMenu}"></i>
                        </a>
                        <!-- Dropdown list -->
                        <div x-show="dropdownMenu" style="display: none;'" class="absolute right-0 top-16 py-2 mt-2 bg-white rounded-md shadow-xl w-44 z-50">
{{--                            <a href="{{route('contas.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-900 hover:text-white" tabindex="-1">Todas as contas</a>--}}
                            @foreach($origens as $origem)
                                <a href="{{route('contas.index',['origem_id' => $origem])}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-900 hover:text-white" tabindex="-1">{{$origem->nome_canal}}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <button type="button" class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none">
                    <span class="sr-only">View notifications</span>
                    <i class="rounded-full text-gray-500 text-2xl hover:text-primary-700 bx bx-bell"></i>
                </button>

                <!-- Profile dropdown -->
                <div class="relative ml-3">
                    <div @click="open = !open" @click.outside="open = false">
                        <button type="button" class="flex max-w-xs items-center rounded-full bg-white text-sm" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            <i class="rounded-full text-gray-500 text-2xl hover:text-primary-700 bx bx-user-circle"></i>
                        </button>
                    </div>
                    <div :class="{'block': open, 'hidden': ! open}" class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                        <a href="{{route('profile.edit')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-900 hover:text-white" role="menuitem" tabindex="-1" id="user-menu-item-0">Perfil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-primary-900 hover:text-white" role="menuitem" tabindex="-1" id="user-menu-item-2">Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="-mr-2 flex items-center sm:hidden">
                <!-- Mobile menu button -->
                <button @click="open = !open" type="button" class="inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!-- Menu open: "hidden", Menu closed: "block" -->
                    <svg :class="{'hidden': open, 'block': ! open}" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <!-- Menu open: "block", Menu closed: "hidden" -->
                    <svg :class="{'block': open, 'hidden': ! open}" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div :class="{'block': open, 'hidden': ! open}" class="sm:hidden" id="mobile-menu">
        <div class="space-y-1 pb-3 pt-2">
            <a href="{{ route('dashboard') }}"
                @class(
                    ['border-primary-900 bg-gray-100 text-primary-900'=> request()->routeIs('dashboard'),
                    'border-transparent text-gray-600 hover:border-primary-700 hover:bg-gray-50 hover:text-gray-800' => !request()->routeIs('dashboard'),
                    'block border-l-4 py-2 pl-3 pr-4 text-base font-medium']
                )
                @if(request()->routeIs('dashboard')) aria-current="page" @endif>
                Dashboard
            </a>
            <a href="{{ route('enterprise.index') }}"
               @class(
                   ['border-primary-900 bg-gray-100 text-primary-900' => request()->routeIs('enterprise.index'),
                   'border-transparent text-gray-600 hover:border-primary-700 hover:bg-gray-50 hover:text-gray-800' => !request()->routeIs('enterprise.index'),
                   'block border-l-4 py-2 pl-3 pr-4 text-base font-medium']
               )
               @if(request()->routeIs('enterprise.index')) aria-current="page" @endif>
                Empresas
            </a>
            <a href="{{ route('skus.index') }}"
                @class(
                    ['border-primary-900 bg-gray-100 text-primary-900' => request()->routeIs('skus.index'),
                    'border-transparent text-gray-600 hover:border-primary-700 hover:bg-gray-50 hover:text-gray-800' => !request()->routeIs('skus.index'),
                    'block border-l-4 py-2 pl-3 pr-4 text-base font-medium']
                )
                @if(request()->routeIs('skus.index')) aria-current="page" @endif>
                SKUs
            </a>
        </div>
        <div class="border-t border-gray-200 pb-3 pt-4">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <i class="rounded-full text-gray-500 text-5xl hover:text-primary-700 bx bx-user-circle"></i>
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-gray-800">{{auth()->user()->name}}</div>
                    <div class="text-sm font-medium text-gray-500">{{auth()->user()->email}}</div>
                </div>
                <button type="button" class="ml-auto flex-shrink-0 max-w-xs items-center rounded-full bg-white text-sm focus:outline-none">
                    <span class="sr-only">View notifications</span>
                    <i class="rounded-full text-gray-500 text-2xl hover:text-primary-700 bx bx-bell"></i>
                </button>
            </div>
            <div class="mt-3 space-y-1">
                <a href="{{route('profile.edit')}}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800">Perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800">
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
