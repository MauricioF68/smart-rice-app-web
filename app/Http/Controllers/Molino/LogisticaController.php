<?php

namespace App\Http\Controllers\Molino;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Preventa;
use Illuminate\Support\Facades\Auth;

class LogisticaController extends Controller
{
    // 1. LISTADO DE RECOJOS (Con validación de ubicación)
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // --- EL PORTERO ---
        if (!$user->latitud || !$user->longitud) {
            return redirect()->route('molino.logistica.configurar')
                ->with('warning', 'Antes de programar recojos, necesitamos saber la ubicación de tu planta.');
        }

        $molinoId = $user->id;

        $porRecoger = \App\Models\Preventa::where('estado', 'acordada')
            ->whereHas('propuestas', function($query) use ($molinoId) {
                $query->where('user_id', $molinoId)
                      ->where('estado', 'aceptada');
            })
            // AQUÍ ESTÁ EL CAMBIO CLAVE: Agregamos 'recojo' a la lista
            ->with(['user', 'lote', 'recojo']) 
            ->get();

        return view('molino.logistica.index', compact('porRecoger'));
    }

    // 2. VISTA PARA CONFIGURAR UBICACIÓN (El Mapa)
    public function configurar()
    {
        return view('molino.logistica.configurar');
    }

    // 3. GUARDAR LA UBICACIÓN DEL MOLINO
    public function guardarUbicacion(Request $request)
    {
        $request->validate([
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'direccion_referencia' => 'nullable|string|max:255'
        ]);

        $user = Auth::user();
        /** @var \App\Models\User $user */ // Pista para el editor
        
        $user->latitud = $request->latitud;
        $user->longitud = $request->longitud;
        // Opcional: Si quieres guardar la dirección escrita también, asegúrate que 'direccion' esté en fillable
        if ($request->direccion_referencia) {
            $user->direccion = $request->direccion_referencia;
        }
        
        $user->save();

        return redirect()->route('molino.logistica.index')
            ->with('status', '¡Ubicación configurada! Ahora puedes gestionar tus rutas.');
    }

    // 4. PROGRAMAR (Lógica futura para el mapa de ruta)
    public function programar($id)
    {
        // Traemos la preventa con el agricultor y su lote
        $preventa = \App\Models\Preventa::with(['user', 'lote'])->findOrFail($id);

        // Validamos que el agricultor tenga su lote geolocalizado
        if (!$preventa->lote || !$preventa->lote->latitud) {
            return back()->with('error', 'El agricultor no ha registrado la ubicación GPS de este lote.');
        }

        // Validamos que el Molino (Tú) tengas ubicación configurada
        $molino = \Illuminate\Support\Facades\Auth::user();
        if (!$molino->latitud) {
            return redirect()->route('molino.logistica.configurar')
                ->with('warning', 'Primero configura tu ubicación para trazar la ruta.');
        }

        return view('molino.logistica.programar', compact('preventa'));
    }
    // 5. GUARDAR LA PROGRAMACIÓN
    public function store(Request $request, $id)
    {
        $request->validate([
            'fecha_programada' => 'required|date|after_or_equal:today',
            'placa_camion' => 'required|string|max:20',
            'nombre_chofer' => 'required|string|max:100',
        ]);

        \App\Models\Recojo::create([
            'preventa_id' => $id,
            'fecha_programada' => $request->fecha_programada,
            'placa_camion' => $request->placa_camion,
            'nombre_chofer' => $request->nombre_chofer,
            
            // Guardamos los datos que vinieron del mapa
            'distancia_km' => $request->distancia_km,
            'tiempo_estimado' => $request->tiempo_estimado,
            
            'estado' => 'programado'
        ]);

        return redirect()->route('molino.logistica.index')
            ->with('status', '¡Recojo programado! Se ha generado la orden de transporte.');
    }
    // 6. VER DETALLE (SOLO LECTURA)
    public function detalle($idRecojo)
    {
        // Buscamos el recojo y cargamos la preventa asociada
        $recojo = \App\Models\Recojo::with('preventa.lote', 'preventa.user')->findOrFail($idRecojo);
        
        // Reutilizamos la misma vista del mapa, pero le pasamos una variable extra
        // para decirle que sea "solo lectura"
        return view('molino.logistica.detalle', compact('recojo'));
    }
}