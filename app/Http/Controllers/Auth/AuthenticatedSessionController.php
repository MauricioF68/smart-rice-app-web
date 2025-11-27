<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // AQUÍ ESTÁ EL CAMBIO:
        // En lugar de "return redirect()->intended(RouteServiceProvider::HOME);"
        // Vamos a preguntar qué rol tiene el usuario.

        $rol = auth()->user()->rol;

        if ($rol === 'admin' || $rol === 'administrador') {
            return redirect()->intended(route('dashboard')); // O admin.dashboard si tienes uno específico
        }

        if ($rol === 'caseta') {
            // ¡Al operario lo mandamos a seleccionar su caseta!
            return redirect()->route('caseta.seleccion');
        }

        // Agricultores y Molinos van al dashboard normal
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
