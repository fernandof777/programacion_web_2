<?php

namespace App\Policies;

use App\Models\Servicio;
use App\Models\User;

class ServicioPolicy
{
    public function update(User $user, Servicio $servicio): bool
    {
        return $user->id === $servicio->user_id;
    }

    public function delete(User $user, Servicio $servicio): bool
    {
        return $user->id === $servicio->user_id;
    }
}
