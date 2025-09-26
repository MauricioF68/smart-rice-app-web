<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        
            // Reemplaza tu bloque de validación con este
            $request->validate([
                'rol' => ['required', 'string', Rule::in(['agricultor', 'molino'])],

                // --- CAMPOS DE AGRICULTOR ---
                // Son obligatorios SOLO SI el rol es 'agricultor'. Si no, pueden estar vacíos.
                'primer_nombre' => ['required_if:rol,agricultor', 'nullable', 'string', 'max:255'],
                'segundo_nombre' => ['nullable', 'string', 'max:255'],
                'apellido_paterno' => ['required_if:rol,agricultor', 'nullable', 'string', 'max:255'],
                'apellido_materno' => ['required_if:rol,agricultor', 'nullable', 'string', 'max:255'],
                'dni' => ['required_if:rol,agricultor', 'nullable', 'string', 'digits:8', 'unique:' . User::class],
                'codigo_de_agricultor' => ['required_if:rol,agricultor', 'nullable', 'string', 'max:255', 'unique:' . User::class],

                // --- CAMPOS DE MOLINO ---
                // Son obligatorios SOLO SI el rol es 'molino'. Si no, pueden estar vacíos.
                'razon_social' => ['required_if:rol,molino', 'nullable', 'string', 'max:255'],
                'nombre_comercial' => ['nullable', 'string', 'max:255'],
                'ruc' => ['required_if:rol,molino', 'nullable', 'string', 'digits:11', 'unique:' . User::class],

                // --- CAMPOS COMUNES ---
                // Siempre son obligatorios.
                'telefono' => ['required', 'string', 'digits:9', 'regex:/^9\d{8}$/'],
                'direccion' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
        

        // El código solo llegará aquí si la validación es exitosa
        $user = User::create([
            'name' => $request->rol === 'agricultor' ? $request->primer_nombre . ' ' . $request->apellido_paterno : $request->razon_social,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'dni' => $request->dni,
            'codigo_de_agricultor' => $request->codigo_de_agricultor,
            'razon_social' => $request->razon_social,
            'nombre_comercial' => $request->nombre_comercial,
            'ruc' => $request->ruc,
            
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false))->with('status', '¡Te has registrado exitosamente! Bienvenido.');
    }
}
