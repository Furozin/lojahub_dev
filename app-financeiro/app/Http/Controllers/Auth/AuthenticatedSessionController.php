<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        Session::forget('actualEnterpriseId'); // Cada login limpa a sessão
        
        // Retorna os dados do user logado
        $userDados  = $request->user();

        // Retorna id do user logado
        $userId   = $userDados->id;

        // Salva o id do user logado na sessão
        Session::put('userId', $userId);

        // Retorna o acesso_id do user logado
        $acessoId   = $userDados->acesso_id;

        // Salva o acesso_id na sessão
        Session::put('acessoId', $acessoId);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        Session::forget('actualEnterpriseId'); // Cada logout  limpa o sessin
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
