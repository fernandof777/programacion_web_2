@extends('layouts.app')

@section('titulo', 'Nuevo Servicio')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-plus-circle"></i> Registrar Nuevo Servicio</h2>
            <a href="{{ route('servicios.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong><i class="bi bi-exclamation-triangle"></i> Errores de validación:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('servicios.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Servicio <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre" name="nombre" value="{{ old('nombre') }}"
                               required maxlength="100" placeholder="Ej: Cambio de aceite">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                  id="descripcion" name="descripcion" rows="3"
                                  placeholder="Descripción del servicio (opcional)">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="precio" class="form-label">Precio ($) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('precio') is-invalid @enderror"
                                   id="precio" name="precio" value="{{ old('precio') }}"
                                   required step="0.01" min="0" placeholder="0.00">
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="duracion_estimada" class="form-label">Duración (minutos) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('duracion_estimada') is-invalid @enderror"
                                   id="duracion_estimada" name="duracion_estimada" value="{{ old('duracion_estimada') }}"
                                   required min="1" placeholder="Ej: 60">
                            @error('duracion_estimada')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select @error('estado') is-invalid @enderror"
                                    id="estado" name="estado" required>
                                <option value="">Seleccionar...</option>
                                <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ old('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="En espera" {{ old('estado') == 'En espera' ? 'selected' : '' }}>En espera</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Registrar Servicio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
