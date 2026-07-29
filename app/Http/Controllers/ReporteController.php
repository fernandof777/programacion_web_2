<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\OrdenTrabajo;
use App\Models\Repuesto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReporteController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate(['desde' => ['nullable', 'date'], 'hasta' => ['nullable', 'date', 'after_or_equal:desde']]);
        $base = OrdenTrabajo::query()
            ->when($request->desde, fn ($q, $v) => $q->whereDate('fecha_ingreso', '>=', $v))
            ->when($request->hasta, fn ($q, $v) => $q->whereDate('fecha_ingreso', '<=', $v));

        return view('reportes.index', [
            'metricas' => [
                'ordenes' => (clone $base)->count(),
                'ingresos' => (clone $base)->whereIn('estado', ['Finalizada', 'Entregada'])->sum('total'),
                'clientes' => Cliente::count(),
                'stock_bajo' => Repuesto::whereColumn('stock', '<=', 'stock_minimo')->count(),
            ],
            'porEstado' => (clone $base)->selectRaw('estado, count(*) total')->groupBy('estado')->orderByDesc('total')->get(),
            'ordenes' => (clone $base)->with(['cliente', 'vehiculo'])->latest('fecha_ingreso')->paginate(15)->withQueryString(),
        ]);
    }
}
