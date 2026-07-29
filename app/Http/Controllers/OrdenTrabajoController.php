<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\OrdenTrabajo;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrdenTrabajoController extends Controller
{
    public function index(Request $request): View
    {
        $ordenes = OrdenTrabajo::with(['cliente', 'vehiculo', 'usuario'])
            ->when($request->buscar, fn ($q, $v) => $q->where(fn ($s) => $s->where('numero', 'like', "%{$v}%")->orWhereHas('cliente', fn ($c) => $c->where('nombre', 'like', "%{$v}%"))))
            ->when($request->estado, fn ($q, $v) => $q->where('estado', $v))
            ->latest('fecha_ingreso')->paginate(12)->withQueryString();

        return view('ordenes.index', compact('ordenes'));
    }

    public function create(): View
    {
        return $this->form(new OrdenTrabajo);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['numero'] = 'OT-'.now()->format('ymd').'-'.Str::upper(Str::random(5));
        $request->user()->ordenesTrabajo()->create($data);

        return to_route('ordenes.index')->with('success', 'Orden de trabajo creada correctamente.');
    }

    public function edit(OrdenTrabajo $orden): View
    {
        return $this->form($orden);
    }

    public function update(Request $request, OrdenTrabajo $orden): RedirectResponse
    {
        $orden->update($this->validated($request));

        return to_route('ordenes.index')->with('success', 'Orden actualizada correctamente.');
    }

    public function destroy(OrdenTrabajo $orden): RedirectResponse
    {
        $orden->delete();

        return back()->with('success', 'Orden eliminada.');
    }

    private function form(OrdenTrabajo $orden): View
    {
        return view('ordenes.form', [
            'orden' => $orden,
            'clientes' => Cliente::where('activo', true)->orderBy('nombre')->get(),
            'vehiculos' => Vehiculo::with('cliente')->orderBy('placa')->get(),
        ]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'vehiculo_id' => ['required', Rule::exists('vehiculos', 'id')->where('cliente_id', $request->cliente_id)],
            'problema' => ['required', 'string', 'max:2000'],
            'diagnostico' => ['nullable', 'string', 'max:3000'],
            'estado' => ['required', Rule::in(['Pendiente', 'En diagnóstico', 'En reparación', 'Finalizada', 'Entregada', 'Cancelada'])],
            'fecha_ingreso' => ['required', 'date'],
            'fecha_entrega_estimada' => ['nullable', 'date', 'after_or_equal:fecha_ingreso'],
            'fecha_entrega' => ['nullable', 'date', 'after_or_equal:fecha_ingreso'],
            'total' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
        ], ['vehiculo_id.exists' => 'El vehículo seleccionado no pertenece al cliente.']);
    }
}
