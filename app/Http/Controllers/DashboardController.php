<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'metricas' => [
                'servicios' => Servicio::count(),
                'servicios_activos' => Servicio::where('estado', 'Activo')->count(),
                'usuarios' => User::count(),
                'valor_catalogo' => Servicio::sum('precio'),
            ],
            'serviciosRecientes' => Servicio::with('user')->latest()->take(5)->get(),
        ]);
    }
}
