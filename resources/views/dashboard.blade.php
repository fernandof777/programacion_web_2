@extends('layouts.app')
@section('titulo', 'Dashboard')
@section('encabezado', 'Resumen del taller')
@section('subtitulo', 'Actividad operativa actualizada')
@section('contenido')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h2 class="fw-bold mb-1">¡Bienvenido, {{ explode(' ', Auth::user()->name)[0] }}!</h2><p class="text-secondary mb-0">Controla la operación diaria desde un solo lugar.</p></div>
    <a href="{{ route('ordenes.create') }}" class="btn btn-primary px-4"><i class="bi bi-plus-lg me-2"></i>Nueva orden</a>
</div>
<div class="row g-4 mb-4">
@foreach ([
    ['Servicios activos', $metricas['servicios'], 'bi-tools', 'primary'],
    ['Órdenes abiertas', $metricas['ordenes_pendientes'], 'bi-clipboard2-check', 'warning'],
    ['Clientes activos', $metricas['clientes'], 'bi-people', 'success'],
    ['Alertas de stock', $metricas['stock_bajo'], 'bi-box-seam', 'danger'],
] as [$nombre, $valor, $icono, $color])
<div class="col-12 col-sm-6 col-xl-3"><div class="card app-card h-100"><div class="card-body d-flex justify-content-between p-4"><div><div class="text-secondary small mb-2">{{ $nombre }}</div><div class="fs-3 fw-bold">{{ $valor }}</div></div><div class="rounded-3 bg-{{ $color }} bg-opacity-10 text-{{ $color }} p-3"><i class="bi {{ $icono }} fs-5"></i></div></div></div></div>
@endforeach
</div>
<div class="card app-card">
<div class="card-header bg-white border-0 d-flex justify-content-between align-items-center p-4"><div><h5 class="fw-bold mb-1">Órdenes recientes</h5><small class="text-secondary">Últimos trabajos registrados</small></div><a href="{{ route('ordenes.index') }}" class="btn btn-outline-primary btn-sm">Ver todas</a></div>
<div class="table-responsive"><table class="table table-hover mb-0"><thead class="table-light"><tr><th>Número</th><th>Cliente</th><th>Vehículo</th><th>Estado</th><th>Total</th></tr></thead><tbody>
@forelse ($ordenesRecientes as $orden)
<tr><td class="fw-semibold">{{ $orden->numero }}</td><td>{{ $orden->cliente->nombre }}</td><td>{{ $orden->vehiculo->placa }} · {{ $orden->vehiculo->marca }}</td><td><span class="badge text-bg-primary">{{ $orden->estado }}</span></td><td>Bs {{ number_format($orden->total, 2) }}</td></tr>
@empty
<tr><td colspan="5" class="text-center text-secondary py-5">Todavía no existen órdenes de trabajo.</td></tr>
@endforelse
</tbody></table></div></div>
@endsection
