<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->rol === 'agricultor';
    }

    public function rules(): array
    {
        return [
            'nombre_lote' => ['required', 'string', 'max:255'],
            'tipo_arroz_id' => ['required', 'integer', 'exists:tipos_arroz,id'],
            'cantidad_total_sacos' => ['required', 'integer', 'min:1'],
            'humedad' => ['required', 'numeric', 'min:0'],
            'quebrado' => ['required', 'numeric', 'min:0'],
        ];
    }
}