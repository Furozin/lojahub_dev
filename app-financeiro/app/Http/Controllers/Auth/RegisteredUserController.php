<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Models\Acesso;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $acesso = new Acesso;
        $acesso->nome = $request->validated()['name'];
        $acesso->telefone = $request->validated()['phone'];
        $acesso->save();

        $user = new User;
        $user->fill($request->validated());
        $user->acesso_id = $acesso->id;
        $user->save();

        // Criar Repo

        event(new Registered($user));

        Auth::login($user);
        Session::forget('actualEnterpriseId'); // Cada registra limpa o sessin

        return redirect(RouteServiceProvider::HOME);
    }
}
