<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCampanaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo el molino dueño de la campaña puede editarla.
        return $this->user()->rol === 'molino' && $this->route('campana')->user_id == $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'nombre_campana' => ['required', 'string', 'max:255'],
            // Añadimos todas las demás reglas del formulario de creación que se pueden editar
            'cantidad_total' => ['required', 'integer', 'min:1'],
            'precio_base' => ['required', 'numeric', 'min:0'],
            'tipo_arroz_id' => ['nullable', 'integer', 'exists:tipos_arroz,id'],
            'cantidad_acordada' => ['nullable', 'integer', 'min:0'],
            'humedad_min' => ['nullable', 'numeric', 'min:0'],
            'humedad_max' => ['nullable', 'numeric', 'gte:humedad_min'],
            'quebrado_min' => ['nullable', 'numeric', 'min:0'],
            'quebrado_max' => ['nullable', 'numeric', 'gte:quebrado_min'],
            'min_sacos_por_agricultor' => ['nullable', 'integer', 'min:1'],
            'max_sacos_por_agricultor' => ['nullable', 'integer', 'gte:min_sacos_por_agricultor'],
        ];
    }
}