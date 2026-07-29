<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $clientes = Cliente::withCount(['vehiculos', 'ordenes'])
            ->when($request->buscar, fn ($q, $v) => $q->where(fn ($s) => $s->where('nombre', 'like', "%{$v}%")->orWhere('ci_nit', 'like', "%{$v}%")))
            ->latest()->paginate(12)->withQueryString();

        return view('clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        return view('clientes.form', ['cliente' => new Cliente]);
    }

    public function store(Request $request): RedirectResponse
    {
        Cliente::create($this->validated($request));

        return to_route('clientes.index')->with('success', 'Cliente registrado correctamente.');
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.form', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $cliente->update($this->validated($request, $cliente));

        return to_route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        if ($cliente->vehiculos()->exists() || $cliente->ordenes()->exists()) {
            return back()->with('error', 'No se puede eliminar un cliente con vehículos u órdenes asociadas.');
        }
        $cliente->delete();

        return back()->with('success', 'Cliente eliminado.');
    }

    private function validated(Request $request, ?Cliente $cliente = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'ci_nit' => ['required', 'string', 'max:30', Rule::unique('clientes')->ignore($cliente)],
            'telefono' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'ciudad' => ['required', 'string', 'max:80'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'activo' => ['required', 'boolean'],
        ]);
    }
}
