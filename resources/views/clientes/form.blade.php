@extends('layouts.app')
@php($edit=$cliente->exists)
@section('titulo',$edit?'Editar cliente':'Nuevo cliente') @section('encabezado',$edit?'Editar cliente':'Nuevo cliente') @section('subtitulo','Datos de contacto y facturación')
@section('contenido')
<div class="card app-card mx-auto" style="max-width:900px"><div class="card-body p-4"><h3 class="fw-bold mb-4">{{ $edit?'Actualizar cliente':'Registrar cliente' }}</h3>
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ $edit?route('clientes.update',$cliente):route('clientes.store') }}">@csrf @if($edit)@method('PUT')@endif
<div class="row g-3"><div class="col-md-8"><label class="form-label">Nombre completo *</label><input class="form-control" name="nombre" value="{{ old('nombre',$cliente->nombre) }}" required></div><div class="col-md-4"><label class="form-label">CI/NIT *</label><input class="form-control" name="ci_nit" value="{{ old('ci_nit',$cliente->ci_nit) }}" required></div>
<div class="col-md-4"><label class="form-label">Teléfono *</label><input class="form-control" name="telefono" value="{{ old('telefono',$cliente->telefono) }}" required></div><div class="col-md-4"><label class="form-label">Correo</label><input type="email" class="form-control" name="email" value="{{ old('email',$cliente->email) }}"></div><div class="col-md-4"><label class="form-label">Ciudad *</label><input class="form-control" name="ciudad" value="{{ old('ciudad',$cliente->ciudad?:'Santa Cruz') }}" required></div>
<div class="col-md-9"><label class="form-label">Dirección</label><input class="form-control" name="direccion" value="{{ old('direccion',$cliente->direccion) }}"></div><div class="col-md-3"><label class="form-label">Estado</label><select class="form-select" name="activo"><option value="1" @selected(old('activo',$cliente->activo??1)==1)>Activo</option><option value="0" @selected(old('activo',$cliente->activo??1)==0)>Inactivo</option></select></div></div>
<div class="d-flex justify-content-end gap-2 mt-4"><a class="btn btn-light" href="{{ route('clientes.index') }}">Cancelar</a><button class="btn btn-primary">Guardar cliente</button></div></form></div></div>
@endsection
