@extends('layouts.app')

@section('titulo', 'Servicios')

@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-gear"></i> Servicios del Taller</h2>
    <a href="{{ route('servicios.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nuevo Servicio
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Servicio</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Duración (min)</th>
                    <th>Estado</th>
                    <th>Registrado por</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($servicios as $servicio)
                    <tr>
                        <td>{{ $servicio->id }}</td>
                        <td>{{ $servicio->nombre }}</td>
                        <td>{{ $servicio->descripcion ?? '—' }}</td>
                        <td>${{ number_format($servicio->precio, 2) }}</td>
                        <td>{{ $servicio->duracion_estimada }} min</td>
                        <td>
                            <span class="badge bg-{{ $servicio->estado === 'Activo' ? 'success' : 'secondary' }}">
                                {{ $servicio->estado }}
                            </span>
                        </td>
                        <td>
                            <i class="bi bi-person"></i> {{ $servicio->user->name }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">No hay servicios registrados aún.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
