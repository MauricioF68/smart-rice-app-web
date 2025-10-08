<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->rol === 'agricultor' && $this->route('lote')->user_id == $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'nombre_lote' => ['required', 'string', 'max:255'],
        ];
    }
}