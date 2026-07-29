<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServicioRequest;
use App\Http\Requests\UpdateServicioRequest;
use App\Models\Servicio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ServicioController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'buscar' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'in:Activo,Inactivo,En espera'],
        ]);

        $servicios = Servicio::query()
            ->with('user')
            ->when($filtros['buscar'] ?? null, function ($query, string $buscar): void {
                $query->where(function ($subquery) use ($buscar): void {
                    $subquery
                        ->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->when($filtros['estado'] ?? null, fn ($query, string $estado) => $query->where('estado', $estado))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('servicios.index', compact('servicios'));
    }

    public function create(): View
    {
        return view('servicios.create');
    }

    public function store(StoreServicioRequest $request): RedirectResponse
    {
        $request->user()->servicios()->create($request->validated());

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio registrado exitosamente.');
    }

    public function edit(Servicio $servicio): View
    {
        Gate::authorize('update', $servicio);

        return view('servicios.edit', compact('servicio'));
    }

    public function update(UpdateServicioRequest $request, Servicio $servicio): RedirectResponse
    {
        $servicio->update($request->validated());

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    public function destroy(Servicio $servicio): RedirectResponse
    {
        Gate::authorize('delete', $servicio);
        $servicio->delete();

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio eliminado correctamente.');
    }
}
