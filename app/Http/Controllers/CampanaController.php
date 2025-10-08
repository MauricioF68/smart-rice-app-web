<?php

namespace App\Http\Controllers;

use App\Models\Campana;
use App\Models\TipoArroz;
use App\Models\Lote;
use App\Http\Requests\AplicarCampanaRequest;
use App\Models\CampanaLote;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateCampanaRequest;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCampanaRequest;


class CampanaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Busca solo las campañas del molino que ha iniciado sesión
        $campanas = Campana::where('user_id', auth()->id())->latest()->get();

        // Devuelve la vista y le pasa los datos
        return view('molino.campanas.index', compact('campanas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Buscamos todas las variedades de arroz para pasarlas al dropdown
        $tiposArroz = TipoArroz::orderBy('nombre')->get();

        return view('molino.campanas.create', compact('tiposArroz'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCampanaRequest $request)
    {
        // 1. Obtiene los datos ya validados
        $datosValidados = $request->validated();

        // 2. Añade el ID del molino que ha iniciado sesión
        $datosValidados['user_id'] = auth()->id();

        // 3. Crea la campaña
        Campana::create($datosValidados);

        // 4. Redirige a la lista de campañas con un mensaje de éxito
        return redirect()->route('campanas.index')->with('status', '¡Campaña creada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Campana $campana)
    {
        //
    }
    public function edit(Campana $campana)
    {
        // Lógica de Negocio: No se puede editar si ya hay sacos acordados.
        if ($campana->cantidad_acordada > 0) {
            return redirect()->route('campanas.index')->with('status', 'Error: No se puede editar una campaña que ya tiene agricultores participantes.');
        }

        $tiposArroz = TipoArroz::orderBy('nombre')->get();
        return view('molino.campanas.edit', compact('campana', 'tiposArroz'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampanaRequest $request, Campana $campana)
    {
        $campana->update($request->validated());

        return redirect()->route('campanas.index')->with('status', '¡Campaña actualizada exitosamente!');
    }
    public function destroy(Campana $campana)
    {
        // Regla de Negocio: No se puede eliminar si ya hay sacos acordados.
        if ($campana->cantidad_acordada > 0) {
            return redirect()->route('campanas.index')->with('status', 'Error: No se puede eliminar una campaña que ya tiene agricultores participantes.');
        }

        $campana->delete();

        return redirect()->route('campanas.index')->with('status', '¡Campaña eliminada exitosamente!');
    }

    public function mercadoParaAgricultores()
    {
        // 1. Obtener los lotes disponibles del agricultor que ha iniciado sesión
        $lotesDisponibles = Lote::where('user_id', auth()->id())
            ->where('estado', 'disponible')
            ->get();

        // 2. Obtener todas las campañas activas de los molinos
        $campanasActivas = Campana::where('estado', 'activa')->with('user')->latest()->get();

        // 3. Lógica de "Matchmaking": Añadir lotes compatibles a cada campaña
        foreach ($campanasActivas as $campana) {
            $campana->lotes_compatibles = collect();
            foreach ($lotesDisponibles as $lote) {
                // Aquí se aplican los filtros de compatibilidad
                if (($campana->tipo_arroz_id && $lote->tipo_arroz_id != $campana->tipo_arroz_id) ||
                    ($campana->humedad_min && $lote->humedad < $campana->humedad_min) ||
                    ($campana->humedad_max && $lote->humedad > $campana->humedad_max) ||
                    ($campana->quebrado_min && $lote->quebrado < $campana->quebrado_min) ||
                    ($campana->quebrado_max && $lote->quebrado > $campana->quebrado_max)
                ) {
                    continue; // Si no cumple, se salta al siguiente lote
                }
                // Si cumple, se añade a la lista de compatibles de la campaña
                $campana->lotes_compatibles->push($lote);
            }
        }

        // 4. Ordenar: las campañas con lotes compatibles se muestran primero
        $campanasOrdenadas = $campanasActivas->sortByDesc(function ($campana) {
            return $campana->lotes_compatibles->isNotEmpty();
        });

        return view('agricultor.campanas.mercado', ['campanas' => $campanasOrdenadas]);
    }

    public function aplicar(AplicarCampanaRequest $request, Campana $campana)
    {
        // Cargar los datos validados del formulario
        $datosValidados = $request->validated();
        $lote = \App\Models\Lote::find($datosValidados['lote_id']);
        $cantidadAplicada = $datosValidados['cantidad_sacos'];

        // --- Validaciones de Negocio Adicionales ---
        // 1. Validar que el lote pertenece al usuario
        if ($lote->user_id !== auth()->id()) {
            return back()->with('status', 'Error: El lote seleccionado no te pertenece.');
        }
        // 2. Validar que la cantidad no exceda el disponible del lote
        if ($cantidadAplicada > $lote->cantidad_disponible_sacos) {
            return back()->with('status', 'Error: No tienes suficientes sacos disponibles en ese lote.');
        }
        // 3. Validar que la cantidad cumpla con las reglas de la campaña
        if (($campana->min_sacos_por_agricultor && $cantidadAplicada < $campana->min_sacos_por_agricultor) ||
            ($campana->max_sacos_por_agricultor && $cantidadAplicada > $campana->max_sacos_por_agricultor)
        ) {
            return back()->with('status', 'Error: La cantidad de sacos no cumple con las reglas de la campaña.');
        }

        // --- Lógica del "Doble Visto Bueno" ---
        // Crear el registro en la tabla pivote con estado 'pendiente'
        \App\Models\CampanaLote::create([
            'campana_id' => $campana->id,
            'lote_id' => $lote->id,
            'user_id' => auth()->id(), // El agricultor que aplica
            'cantidad_comprometida' => $cantidadAplicada,
            'estado' => 'pendiente_aprobacion',
        ]);

        // (Aquí iría la notificación en tiempo real al molino)

        return redirect()->route('campanas.mercado')->with('status', '¡Aplicación enviada! El molino revisará tu propuesta.');
    }

    // ... justo después del método aplicar()

    public function verAplicaciones(Campana $campana)
    {
        // 1. Verificación de seguridad: Asegurarse de que el molino solo vea las aplicaciones de SUS campañas.
        if ($campana->user_id !== auth()->id()) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Cargar las aplicaciones pendientes para esta campaña.
        // Usamos 'with' para cargar también la información del agricultor (user) y del lote.
        // Esto se conoce como "Eager Loading" y es mucho más eficiente.
        $aplicaciones = \App\Models\CampanaLote::where('campana_id', $campana->id)
            ->where('estado', 'pendiente_aprobacion')
            ->with('user', 'lote') // Carga las relaciones para no hacer consultas extra en la vista
            ->get();

        // 3. Devolver la vista con los datos
        return view('molino.campanas.aplicaciones', compact('campana', 'aplicaciones'));
    }

    public function aprobarAplicacion(CampanaLote $aplicacion)
    {
        // Cargamos las relaciones para acceder a sus datos
        $aplicacion->load('campana', 'lote');

        // 1. Verificación de seguridad: El usuario autenticado debe ser el dueño de la campaña.
        if ($aplicacion->campana->user_id !== auth()->id()) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Verificación de negocio: El estado debe ser 'pendiente_aprobacion'.
        if ($aplicacion->estado !== 'pendiente_aprobacion') {
            return back()->with('status', 'Error: Esta aplicación ya ha sido procesada.');
        }

        $campana = $aplicacion->campana;
        $lote = $aplicacion->lote;
        $cantidadAprobada = $aplicacion->cantidad_comprometida;

        // 3. Verificación de capacidad: La campaña debe tener suficiente espacio.
        $espacioDisponible = $campana->cantidad_total - $campana->cantidad_acordada;
        if ($cantidadAprobada > $espacioDisponible) {
            return back()->with('status', 'Error: No hay suficiente espacio en la campaña para aceptar esta cantidad.');
        }

        // 4. Verificación de disponibilidad: El lote del agricultor aún debe tener sacos suficientes.
        if ($cantidadAprobada > $lote->cantidad_disponible_sacos) {
            // Si no hay sacos, rechazamos automáticamente la aplicación.
            $aplicacion->update(['estado' => 'rechazada']);
            return back()->with('status', 'Error: El agricultor ya no tiene suficientes sacos en este lote. La aplicación ha sido rechazada.');
        }

        // --- INICIO DE LA TRANSACCIÓN ---
        // Si algo falla aquí, todo se revierte automáticamente.
        try {
            DB::beginTransaction();

            // A. Actualizar el estado de la aplicación
            $aplicacion->update(['estado' => 'aprobada']);

            // B. Descontar los sacos del lote del agricultor
            $lote->decrement('cantidad_disponible_sacos', $cantidadAprobada);

            // C. Aumentar los sacos acordados en la campaña del molino
            $campana->increment('cantidad_acordada', $cantidadAprobada);

            // Si todo ha ido bien, confirmamos los cambios
            DB::commit();
        } catch (\Exception $e) {
            // Si hubo un error, revertimos todo
            DB::rollBack();
            // Podríamos registrar el error: \Log::error($e->getMessage());
            return back()->with('status', 'Error: Ocurrió un problema al procesar la solicitud. Inténtalo de nuevo.');
        }
        // --- FIN DE LA TRANSACCIÓN ---


        return redirect()->route('campanas.aplicaciones', $campana->id)
            ->with('status', '¡Aplicación aprobada exitosamente! Los inventarios han sido actualizados.');
    }

    // ... después del método aprobarAplicacion()

    public function rechazarAplicacion(CampanaLote $aplicacion)
    {
        // Cargamos la relación con la campaña para la verificación
        $aplicacion->load('campana');

        // 1. Verificación de seguridad: El usuario autenticado debe ser el dueño de la campaña.
        if ($aplicacion->campana->user_id !== auth()->id()) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Verificación de negocio: El estado debe ser 'pendiente_aprobacion'.
        if ($aplicacion->estado !== 'pendiente_aprobacion') {
            return back()->with('status', 'Error: Esta aplicación ya ha sido procesada.');
        }

        // 3. Actualizar el estado de la aplicación a 'rechazada'
        $aplicacion->update(['estado' => 'rechazada']);

        // 4. Redirigir de vuelta con un mensaje de éxito
        return redirect()->route('campanas.aplicaciones', $aplicacion->campana_id)
            ->with('status', 'La aplicación ha sido rechazada.');
    }

    // ... aquí va el cierre de la clase }

    // ... aquí va el cierre de la clase }
}
