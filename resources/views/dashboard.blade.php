@extends('layouts.app')

@section('titulo', 'Dashboard')
@section('encabezado', 'Resumen del taller')
@section('subtitulo', 'Información actualizada del catálogo y los usuarios')

@section('contenido')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">¡Bienvenido, {{ explode(' ', Auth::user()->name)[0] }}!</h2>
        <p class="text-secondary mb-0">Este es el punto de partida del sistema unificado.</p>
    </div>
    <a href="{{ route('servicios.create') }}" class="btn btn-primary px-4">
        <i class="bi bi-plus-lg me-2"></i>Nuevo servicio
    </a>
</div>

<div class="row g-4 mb-4">
    @php
        $tarjetas = [
            ['Servicios', $metricas['servicios'], 'bi-tools', 'primary'],
            ['Activos', $metricas['servicios_activos'], 'bi-check-circle', 'success'],
            ['Usuarios', $metricas['usuarios'], 'bi-people', 'warning'],
            ['Valor del catálogo', '$'.number_format($metricas['valor_catalogo'], 2), 'bi-cash-stack', 'info'],
        ];
    @endphp
    @foreach ($tarjetas as [$nombre, $valor, $icono, $color])
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card app-card h-100">
                <div class="card-body d-flex justify-content-between align-items-start p-4">
                    <div><div class="text-secondary small mb-2">{{ $nombre }}</div><div class="fs-3 fw-bold">{{ $valor }}</div></div>
                    <div class="rounded-3 bg-{{ $color }} bg-opacity-10 text-{{ $color }} p-3"><i class="bi {{ $icono }} fs-5"></i></div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card app-card">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center p-4">
        <div><h5 class="fw-bold mb-1">Servicios recientes</h5><small class="text-secondary">Últimos registros del catálogo</small></div>
        <a href="{{ route('servicios.index') }}" class="btn btn-outline-primary btn-sm">Ver todos</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Servicio</th><th>Estado</th><th>Precio</th><th>Registrado por</th></tr></thead>
            <tbody>
            @forelse ($serviciosRecientes as $servicio)
                <tr>
                    <td class="fw-semibold">{{ $servicio->nombre }}</td>
                    <td><span class="badge text-bg-{{ $servicio->estado === 'Activo' ? 'success' : ($servicio->estado === 'En espera' ? 'warning' : 'secondary') }}">{{ $servicio->estado }}</span></td>
                    <td>${{ number_format($servicio->precio, 2) }}</td>
                    <td>{{ $servicio->user->name }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-secondary py-5">Todavía no existen servicios registrados.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
