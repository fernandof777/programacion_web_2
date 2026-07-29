<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
            <h2 class="fw-bold mb-0"><i class="bi bi-tools me-2"></i>{{ $tituloFormulario }}</h2>
            <a href="{{ route('servicios.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>

        <div class="card app-card">
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <strong><i class="bi bi-exclamation-triangle me-1"></i>Revisa los datos ingresados:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ $accion }}" novalidate>
                    @csrf
                    @if ($metodo !== 'POST')
                        @method($metodo)
                    @endif

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold">Nombre del servicio <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre" name="nombre" value="{{ old('nombre', $servicio->nombre ?? '') }}"
                               required maxlength="100" placeholder="Ej.: Cambio de aceite">
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                  id="descripcion" name="descripcion" rows="4" maxlength="1000"
                                  placeholder="Describe brevemente el trabajo">{{ old('descripcion', $servicio->descripcion ?? '') }}</textarea>
                        @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Máximo 1000 caracteres.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="precio" class="form-label fw-semibold">Precio (Bs) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('precio') is-invalid @enderror"
                                   id="precio" name="precio" value="{{ old('precio', $servicio->precio ?? '') }}"
                                   required step="0.01" min="0" max="99999999.99" placeholder="0.00">
                            @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="duracion_estimada" class="form-label fw-semibold">Duración (min) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('duracion_estimada') is-invalid @enderror"
                                   id="duracion_estimada" name="duracion_estimada"
                                   value="{{ old('duracion_estimada', $servicio->duracion_estimada ?? '') }}"
                                   required min="1" max="10080" placeholder="60">
                            @error('duracion_estimada')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                            <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                <option value="">Seleccionar...</option>
                                @foreach (['Activo', 'Inactivo', 'En espera'] as $estado)
                                    <option value="{{ $estado }}" @selected(old('estado', $servicio->estado ?? '') === $estado)>{{ $estado }}</option>
                                @endforeach
                            </select>
                            @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <a href="{{ route('servicios.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i>{{ $textoBoton }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
