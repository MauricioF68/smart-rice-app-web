<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePreventaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        // Queremos que cualquier usuario autenticado pueda crear una preventa.
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
            'cantidad_sacos' => ['required', 'integer', 'min:1'],
            'precio_por_saco' => ['required', 'numeric', 'min:0'],
            'humedad' => ['required', 'numeric', 'min:0'],
            'quebrado' => ['required', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string'],
        ];
    }
}