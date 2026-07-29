@extends('layouts.app')

@section('titulo', 'Nuevo servicio')
@section('encabezado', 'Nuevo servicio')
@section('subtitulo', 'Agrega un trabajo al catálogo del taller')

@section('contenido')
    @include('servicios.partials.form', [
        'tituloFormulario' => 'Registrar nuevo servicio',
        'textoBoton' => 'Registrar servicio',
        'accion' => route('servicios.store'),
        'metodo' => 'POST',
    ])
@endsection
