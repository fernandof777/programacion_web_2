@extends('layouts.app')

@section('titulo', 'Editar servicio')
@section('encabezado', 'Editar servicio')
@section('subtitulo', 'Actualiza la información del catálogo')

@section('contenido')
    @include('servicios.partials.form', [
        'tituloFormulario' => 'Editar '.$servicio->nombre,
        'textoBoton' => 'Guardar cambios',
        'accion' => route('servicios.update', $servicio),
        'metodo' => 'PUT',
    ])
@endsection
