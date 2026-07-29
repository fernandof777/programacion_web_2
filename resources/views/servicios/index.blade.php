@extends('layouts.app')

@section('titulo', 'Servicios')
@section('encabezado', 'Servicios')
@section('subtitulo', 'Catálogo de trabajos ofrecidos por el taller')

@section('contenido')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Servicios del taller</h2>
        <p class="text-secondary mb-0">Consulta, filtra y administra el catálogo.</p>
    </div>
    <a href="{{ route('servicios.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Nuevo servicio
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif

<div class="card app-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('servicios.index') }}" class="row g-3 align-items-end">
            <div class="col-md-7">
                <label for="buscar" class="form-label">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="search" id="buscar" name="buscar" class="form-control"
                           value="{{ request('buscar') }}" maxlength="100"
                           placeholder="Nombre o descripción del servicio">
                </div>
            </div>
            <div class="col-md-3">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select">
                    <option value="">Todos</option>
                    @foreach (['Activo', 'Inactivo', 'En espera'] as $estado)
                        <option value="{{ $estado }}" @selected(request('estado') === $estado)>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary flex-fill" type="submit">Filtrar</button>
                @if (request()->hasAny(['buscar', 'estado']))
                    <a class="btn btn-outline-secondary" href="{{ route('servicios.index') }}" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card app-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Servicio</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Duración</th>
                    <th>Estado</th>
                    <th>Registrado por</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($servicios as $servicio)
                <tr>
                    <td class="fw-semibold">{{ $servicio->nombre }}</td>
                    <td class="text-secondary">{{ \Illuminate\Support\Str::limit($servicio->descripcion ?: 'Sin descripción', 55) }}</td>
                    <td>Bs {{ number_format($servicio->precio, 2) }}</td>
                    <td>{{ $servicio->duracion_estimada }} min</td>
                    <td>
                        @php($color = $servicio->estado === 'Activo' ? 'success' : ($servicio->estado === 'En espera' ? 'warning' : 'secondary'))
                        <span class="badge text-bg-{{ $color }}">{{ $servicio->estado }}</span>
                    </td>
                    <td><i class="bi bi-person me-1"></i>{{ $servicio->user->name }}</td>
                    <td class="text-end text-nowrap">
                        @can('update', $servicio)
                            <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        @endcan
                        @can('delete', $servicio)
                            <form method="POST" action="{{ route('servicios.destroy', $servicio) }}" class="d-inline"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este servicio? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-secondary"></i>
                        <h5 class="mt-3">No encontramos servicios</h5>
                        <p class="text-secondary mb-3">Prueba otros filtros o registra el primer servicio.</p>
                        <a href="{{ route('servicios.create') }}" class="btn btn-primary">Registrar servicio</a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($servicios->hasPages())
        <div class="card-footer bg-white border-0 p-3">{{ $servicios->links() }}</div>
    @endif
</div>
@endsection
