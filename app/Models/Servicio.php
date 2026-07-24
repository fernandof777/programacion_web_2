<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Servicio extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'duracion_estimada',
        'estado',
        'user_id',
    ];

    /**
     * Obtener el usuario que registró el servicio.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
