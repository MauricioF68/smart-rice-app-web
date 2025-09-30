<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampanaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo los usuarios con rol 'molino' pueden crear campañas.
        return $this->user()->rol === 'molino';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre_campana' => ['required', 'string', 'max:255'],
            'tipo_arroz_id' => ['required', 'integer', 'exists:tipos_arroz,id'],
            'cantidad_total' => ['required', 'integer', 'min:1'],
            'precio_base' => ['required', 'numeric', 'min:0'],
            
            // Cantidad inicial "ficticia"
            'cantidad_acordada' => ['nullable', 'integer', 'min:0'],

            // Rangos de calidad (opcionales)
            'humedad_min' => ['nullable', 'numeric', 'min:0'],
            'humedad_max' => ['nullable', 'numeric', 'gte:humedad_min'],
            'quebrado_min' => ['nullable', 'numeric', 'min:0'],
            'quebrado_max' => ['nullable', 'numeric', 'gte:quebrado_min'],

            // Reglas por agricultor (opcionales)
            'min_sacos_por_agricultor' => ['nullable', 'integer', 'min:1'],
            'max_sacos_por_agricultor' => ['nullable', 'integer', 'gte:min_sacos_por_agricultor'],
        ];
    }
}