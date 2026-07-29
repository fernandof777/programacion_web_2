<?php

namespace App\Http\Requests;

class UpdateServicioRequest extends StoreServicioRequest
{
    public function authorize(): bool
    {
        $servicio = $this->route('servicio');

        return $this->user()?->can('update', $servicio) ?? false;
    }
}
