<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VehiculoController extends Controller
{
    public function index(Request $request): View
    {
        $vehiculos = Vehiculo::with('cliente')
            ->when($request->buscar, fn ($q, $v) => $q->where(fn ($s) => $s->where('placa', 'like', "%{$v}%")->orWhere('marca', 'like', "%{$v}%")->orWhere('modelo', 'like', "%{$v}%")))
            ->latest()->paginate(12)->withQueryString();

        return view('vehiculos.index', compact('vehiculos'));
    }

    public function create(): View
    {
        return view('vehiculos.form', ['vehiculo' => new Vehiculo, 'clientes' => Cliente::where('activo', true)->orderBy('nombre')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Vehiculo::create($this->validated($request));

        return to_route('vehiculos.index')->with('success', 'Vehículo registrado correctamente.');
    }

    public function edit(Vehiculo $vehiculo): View
    {
        return view('vehiculos.form', ['vehiculo' => $vehiculo, 'clientes' => Cliente::orderBy('nombre')->get()]);
    }

    public function update(Request $request, Vehiculo $vehiculo): RedirectResponse
    {
        $vehiculo->update($this->validated($request, $vehiculo));

        return to_route('vehiculos.index')->with('success', 'Vehículo actualizado correctamente.');
    }

    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        if ($vehiculo->ordenes()->exists()) {
            return back()->with('error', 'No se puede eliminar un vehículo con órdenes asociadas.');
        }
        $vehiculo->delete();

        return back()->with('success', 'Vehículo eliminado.');
    }

    private function validated(Request $request, ?Vehiculo $vehiculo = null): array
    {
        return $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'placa' => ['required', 'string', 'max:15', Rule::unique('vehiculos')->ignore($vehiculo)],
            'marca' => ['required', 'string', 'max:60'],
            'modelo' => ['required', 'string', 'max:80'],
            'anio' => ['required', 'integer', 'min:1950', 'max:'.(now()->year + 1)],
            'color' => ['nullable', 'string', 'max:40'],
            'kilometraje' => ['required', 'integer', 'min:0', 'max:2000000'],
        ]);
    }
}
