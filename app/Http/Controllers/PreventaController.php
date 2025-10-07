<?php

namespace App\Http\Controllers;

use App\Models\Preventa;
use App\Models\Lote;
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

    public function mercado()
    {
        // Modificamos la consulta para incluir las relaciones con 'user', 'lote' y 'lote.tipoArroz'
        $preventas = \App\Models\Preventa::with(['user', 'lote.tipoArroz'])
            ->where('estado', 'activa')
            ->latest()
            ->get();

        // Devolvemos la vista que creamos
        return view('molino.preventas.index', compact('preventas'));
    }

    /**
     * Muestra el formulario para crear una nueva preventa.
     */
    public function create()
    {
        // 1. Buscamos los lotes disponibles del agricultor que ha iniciado sesión
        $lotesDisponibles = Lote::where('user_id', auth()->id())
            ->where('estado', 'disponible')
            ->where('cantidad_disponible_sacos', '>', 0)
            ->get();

        // 2. Pasamos los lotes a la vista
        return view('preventas.create', compact('lotesDisponibles'));
    }

    /**
     * Guarda una nueva preventa en la base de datos.
     */
    public function store(StorePreventaRequest $request)
    {
        // 1. Obtiene los datos ya validados (necesitaremos añadir 'lote_id')
        $datosValidados = $request->validated();

        // 2. Buscamos el lote seleccionado para obtener sus datos
        $loteSeleccionado = Lote::where('id', $datosValidados['lote_id'])
            ->where('user_id', auth()->id()) // Seguridad: asegurar que el lote es del usuario
            ->firstOrFail(); // Falla si no lo encuentra

        // 3. Preparamos los datos para crear la preventa
        $datosPreventa = [
            'user_id' => auth()->id(),
            'lote_id' => $loteSeleccionado->id,
            'precio_por_saco' => $datosValidados['precio_por_saco'],
            'notas' => $datosValidados['notas'] ?? null,
            // --- Datos tomados directamente del lote ---
            'cantidad_sacos' => $loteSeleccionado->cantidad_disponible_sacos,
            'humedad' => $loteSeleccionado->humedad,
            'quebrado' => $loteSeleccionado->quebrado,
        ];

        // 4. Crea la preventa en la base de datos
        Preventa::create($datosPreventa);

        // 5. Redirige al listado con un mensaje de éxito
        return redirect()->route('preventas.index')->with('status', '¡Preventa creada exitosamente usando los datos de tu lote!');
    }



    public function show(Preventa $preventa)
    {
        // Cargar la preventa junto con sus propuestas
        // y para cada propuesta, cargar la información del usuario (molino) que la hizo.
        $preventa->load('propuestas.user');

        return view('preventas.show', compact('preventa'));
    }

    /**
     * Muestra el formulario para editar una preventa .
     */
    public function edit(Preventa $preventa)
    {
        // LÓGICA DE NEGOCIO: Si la preventa tiene propuestas, no se puede editar.
        if ($preventa->propuestas()->exists()) {
            return redirect()->route('preventas.index')->with('status', 'Error: No se puede editar una preventa que ya está en negociación.');
        }
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
        if ($preventa->propuestas()->exists()) {
            return redirect()->route('preventas.index')->with('status', 'Error: No se puede eliminar una preventa que ya está en negociación.');
        }

        $preventa->delete();

        return redirect()->route('preventas.index')->with('status', '¡Preventa eliminada exitosamente!');
    }

    public function accept(Preventa $preventa)
    {
        // Lógica de negocio: Asegurarse de que la preventa todavía esté activa
        if ($preventa->estado !== 'activa') {
            return back()->with('status', 'Error: Esta oferta ya no está disponible.');
        }

        // 1. Crear una propuesta con los mismos términos para formalizar
        $preventa->propuestas()->create([
            'user_id' => auth()->id(), // El ID del molino que acepta
            'cantidad_sacos_propuesta' => $preventa->cantidad_sacos,
            'precio_por_saco_propuesta' => $preventa->precio_por_saco,
            'estado' => 'aceptada',
        ]);

        // 2. Actualizar el estado de la preventa a 'acordada'
        $preventa->estado = 'acordada';
        $preventa->save();

        // 3. Redirigir con un mensaje de éxito
        return redirect()->route('mercado.index')->with('status', '¡Oferta aceptada exitosamente!');
    }

    public function negociaciones()
    {
        // 1. Buscamos todas las preventas del agricultor que tengan al menos una propuesta.
        // Usamos 'with' para cargar eficientemente todas las relaciones que necesitaremos en la vista.
        $preventasConPropuestas = Preventa::where('user_id', auth()->id())
            ->has('propuestas') // Solo trae preventas que tienen propuestas
            ->with(['propuestas' => function ($query) {
                // Carga las propuestas y, para cada una, el usuario (molino) que la hizo.
                $query->with('user')->orderBy('created_at', 'desc');
            }, 'lote.tipoArroz']) // Carga también el lote y su tipo de arroz
            ->latest()
            ->get();

        // 2. Separamos las propuestas en dos colecciones: pendientes y acordadas.
        $propuestasPendientes = collect();
        $propuestasAcordadas = collect();

        foreach ($preventasConPropuestas as $preventa) {
            foreach ($preventa->propuestas as $propuesta) {
                if ($propuesta->estado === 'pendiente') {
                    $propuestasPendientes->push($propuesta);
                } elseif ($propuesta->estado === 'aceptada') {
                    $propuestasAcordadas->push($propuesta);
                }
            }
        }

        // 3. Devolvemos la vista con las dos colecciones de datos.
        return view('preventas.negociaciones', compact('propuestasPendientes', 'propuestasAcordadas'));
    }
}
