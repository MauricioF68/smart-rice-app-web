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
        
        $tieneParticipantes = \App\Models\CampanaLote::where('campana_id', $campana->id)->exists();

        if ($tieneParticipantes) {
         
            return redirect()->route('campanas.index')->with('status', 'Error: No se puede editar. Ya hay agricultores participando en esta campaña.');
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
        $tieneParticipantes = \App\Models\CampanaLote::where('campana_id', $campana->id)->exists();
        
        if ($tieneParticipantes) {
            return redirect()->route('campanas.index')
                ->with('status', 'Error: No se puede eliminar esta campaña porque ya tiene agricultores participantes.');
        }        
        $campana->delete();

        return redirect()->route('campanas.index')
            ->with('status', '¡Campaña eliminada exitosamente!');
        }

    public function mercadoParaAgricultores()
    {
       
        $lotesDisponibles = Lote::where('user_id', auth()->id())
            ->where('estado', 'disponible')
            ->get();

       
        $campanasActivas = Campana::where('estado', 'activa')->with('user')->latest()->get();

        
        foreach ($campanasActivas as $campana) {
            $campana->lotes_compatibles = collect();
            foreach ($lotesDisponibles as $lote) {
               
                if (($campana->tipo_arroz_id && $lote->tipo_arroz_id != $campana->tipo_arroz_id) ||
                    ($campana->humedad_min && $lote->humedad < $campana->humedad_min) ||
                    ($campana->humedad_max && $lote->humedad > $campana->humedad_max) ||
                    ($campana->quebrado_min && $lote->quebrado < $campana->quebrado_min) ||
                    ($campana->quebrado_max && $lote->quebrado > $campana->quebrado_max)
                ) {
                    continue; 
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

        // 1. Verificación de seguridad
        if ($aplicacion->campana->user_id !== auth()->id()) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Verificación de negocio
        if ($aplicacion->estado !== 'pendiente_aprobacion') {
            return back()->with('status', 'Error: Esta aplicación ya ha sido procesada.');
        }

        $campana = $aplicacion->campana;
        $lote = $aplicacion->lote;
        $cantidadAprobada = $aplicacion->cantidad_comprometida;

        // 3. Verificación de capacidad
        $espacioDisponible = $campana->cantidad_total - $campana->cantidad_acordada;
        if ($cantidadAprobada > $espacioDisponible) {
            return back()->with('status', 'Error: No hay suficiente espacio en la campaña.');
        }

        // 4. Verificación de disponibilidad del agricultor
        if ($cantidadAprobada > $lote->cantidad_disponible_sacos) {
            $aplicacion->update(['estado' => 'rechazada']);
            return back()->with('status', 'Error: El agricultor ya no tiene suficientes sacos.');
        }

        // --- INICIO DE LA TRANSACCIÓN ---
        try {
            DB::beginTransaction();

            // A. Actualizar estado en campana_lote
            $aplicacion->update(['estado' => 'aprobada']);

            // B. Descontar sacos del lote
            $lote->decrement('cantidad_disponible_sacos', $cantidadAprobada);

            // C. Aumentar sacos acordados en la campaña
            $campana->increment('cantidad_acordada', $cantidadAprobada);

            // -------------------------------------------------------
            // D. [NUEVO] CREAR EL PUENTE (PREVENTA AUTOMÁTICA)
            // -------------------------------------------------------
            // Creamos una Preventa "Espejo" ya en estado 'acordada'
            $preventa = \App\Models\Preventa::create([
                'user_id' => $lote->user_id, // El Agricultor
                'lote_id' => $lote->id,      // Su lote
                'cantidad_sacos' => $cantidadAprobada,
                'precio_por_saco' => $campana->precio_base, // Precio de la campaña
                'humedad' => $lote->humedad,
                'quebrado' => $lote->quebrado,
                'notas' => 'Acuerdo automático desde Campaña #' . $campana->id,
                'estado' => 'acordada', // ¡Esto la hace visible en Logística!
            ]);

            // E. [NUEVO] CREAR LA PROPUESTA GANADORA
            // Para que el sistema sepa qué Molino ganó (Tú)
            \App\Models\Propuesta::create([
                'preventa_id' => $preventa->id,
                'user_id' => auth()->id(), // Tú (El Molino)
                'cantidad_sacos_propuesta' => $cantidadAprobada,
                'precio_por_saco_propuesta' => $campana->precio_base,
                'estado' => 'aceptada', // ¡Esto confirma que tú eres el comprador!
            ]);
            // -------------------------------------------------------

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // \Log::error($e->getMessage()); // Útil para debug si falla
            return back()->with('status', 'Error al procesar: ' . $e->getMessage());
        }
        // --- FIN DE LA TRANSACCIÓN ---

        return redirect()->route('campanas.aplicaciones', $campana->id)
            ->with('status', '¡Aplicación aprobada! Se ha generado la orden logística automáticamente.');
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
