<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\OrdenTrabajo;
use App\Models\Repuesto;
use App\Models\Servicio;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'metricas' => [
                'servicios' => Servicio::where('estado', 'Activo')->count(),
                'ordenes_pendientes' => OrdenTrabajo::whereNotIn('estado', ['Entregada', 'Cancelada'])->count(),
                'clientes' => Cliente::where('activo', true)->count(),
                'stock_bajo' => Repuesto::whereColumn('stock', '<=', 'stock_minimo')->count(),
            ],
            'ordenesRecientes' => OrdenTrabajo::with(['cliente', 'vehiculo'])->latest()->take(5)->get(),
        ]);
    }
}
