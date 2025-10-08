<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AplicarCampanaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->rol === 'agricultor';
    }

    public function rules(): array
    {
        return [
            'lote_id' => ['required', 'integer', 'exists:lotes,id'],
            'cantidad_sacos' => ['required', 'integer', 'min:1'],
        ];
    }
}