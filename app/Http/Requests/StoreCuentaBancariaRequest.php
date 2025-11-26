<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCuentaBancariaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        // true = Cualquiera que esté logueado puede crear una cuenta
        return true; 
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'banco_nombre' => ['required', 'string', 'max:255'],
            'tipo_cuenta' => ['required', 'string', 'max:100'],
            'titular_nombres' => ['required', 'string', 'max:255'],
            'titular_apellidos' => ['required', 'string', 'max:255'],
            'numero_cuenta' => ['required', 'string', 'max:50'],
            'cci' => ['nullable', 'string', 'max:50'], // Opcional
        ];
    }
}