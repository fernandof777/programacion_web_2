<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'precio' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'duracion_estimada' => ['required', 'integer', 'min:1', 'max:10080'],
            'estado' => ['required', Rule::in(['Activo', 'Inactivo', 'En espera'])],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'descripcion.max' => 'La descripción no puede superar los 1000 caracteres.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'precio.min' => 'El precio no puede ser negativo.',
            'duracion_estimada.required' => 'La duración estimada es obligatoria.',
            'duracion_estimada.integer' => 'La duración debe expresarse en minutos enteros.',
            'duracion_estimada.min' => 'La duración debe ser de al menos un minuto.',
            'estado.in' => 'Selecciona un estado permitido.',
        ];
    }
}
