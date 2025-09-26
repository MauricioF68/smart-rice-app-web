<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropuestaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow any authenticated user to make a proposal.
        // We'll add role-specific logic later if needed.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'preventa_id' => ['required', 'integer', 'exists:preventas,id'],
            'cantidad_sacos_propuesta' => ['required', 'integer', 'min:1'],
            'precio_por_saco_propuesta' => ['required', 'numeric', 'min:0'],
        ];
    }
}