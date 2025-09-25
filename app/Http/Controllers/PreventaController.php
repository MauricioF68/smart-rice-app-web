<?php

namespace App\Http\Controllers;

use App\Models\Preventa;
use Illuminate\Http\Request;
use App\Http\Requests\StorePreventaRequest;

class PreventaController extends Controller
{
    /**
     * Muestra una lista de las preventas del usuario.
     */
    public function index()
    {
        // 1. Obtener solo las preventas del usuario que ha iniciado sesión
        $preventas = Preventa::where('user_id', auth()->id())->latest()->get();

        // 2. Devolver la vista y pasarle los datos
        return view('preventas.index', compact('preventas'));
    }

    /**
     * Muestra el formulario para crear una nueva preventa.
     */
    public function create()
    {
        // 3. Simplemente muestra la vista del formulario de creación
        return view('preventas.create');
    }

    /**
     * Guarda una nueva preventa en la base de datos.
     */
    public function store(StorePreventaRequest $request)
    {
        // 1. Obtiene los datos ya validados por StorePreventaRequest
        $datosValidados = $request->validated();

        // 2. Añade el ID del usuario autenticado a los datos
        $datosValidados['user_id'] = auth()->id();

        // 3. Crea la preventa en la base de datos
        Preventa::create($datosValidados);

        // 4. Redirige al listado con un mensaje de éxito
        return redirect()->route('preventas.index')->with('status', '¡Preventa creada exitosamente!');
    }

   
    public function show(Preventa $preventa)
    {
        // Devuelve la vista de detalle y le pasa la preventa encontrada
        return view('preventas.show', compact('preventa'));
    }

    /**
     * Muestra el formulario para editar una preventa.
     */
    public function edit(Preventa $preventa)
    {
        // LÓGICA DE NEGOCIO: Si la preventa tiene propuestas, no se puede editar.
        // if ($preventa->propuestas()->exists()) {
        //     return redirect()->route('preventas.index')->with('status', 'Error: No se puede editar una preventa que ya está en negociación.');
        // }
        return view('preventas.edit', compact('preventa'));
    }

    /**
     * Actualiza una preventa específica en la base de datos.
     */
    public function update(Request $request, Preventa $preventa)
    {
        // 1. Validar los datos del formulario
        $datosValidados = $request->validate([
            'precio_por_saco' => ['required', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string'],
        ]);

        // 2. Actualizar la preventa con los datos validados
        $preventa->update($datosValidados);

        // 3. Redirigir de vuelta a la lista con un mensaje de éxito
        return redirect()->route('preventas.index')->with('status', '¡Preventa actualizada exitosamente!');
    }

    public function destroy(Preventa $preventa)
    {
        // LÓGICA DE NEGOCIO: Si la preventa tiene propuestas, no se puede eliminar.
        // if ($preventa->propuestas()->exists()) {
        //     return redirect()->route('preventas.index')->with('status', 'Error: No se puede eliminar una preventa que ya está en negociación.');
        // }

        $preventa->delete();

        return redirect()->route('preventas.index')->with('status', '¡Preventa eliminada exitosamente!');
    }
}
