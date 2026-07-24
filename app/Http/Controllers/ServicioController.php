<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    /**
     * Mostrar el listado de servicios con su usuario propietario.
     */
    public function index()
    {
        $servicios = Servicio::with('user')->latest()->get();

        return view('servicios.index', compact('servicios'));
    }

    /**
     * Mostrar el formulario para crear un nuevo servicio.
     */
    public function create()
    {
        return view('servicios.create');
    }

    /**
     * Almacenar un nuevo servicio en la base de datos.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'max:100'],
            'descripcion' => ['nullable'],
            'precio' => ['required', 'numeric', 'min:0'],
            'duracion_estimada' => ['required', 'integer', 'min:1'],
            'estado' => ['required', 'max:30'],
        ]);

        $datos['user_id'] = auth()->id();

        Servicio::create($datos);

        return redirect()->route('servicios.index')->with('success', 'Servicio registrado exitosamente.');
    }
}
