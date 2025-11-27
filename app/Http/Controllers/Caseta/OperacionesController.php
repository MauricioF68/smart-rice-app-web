<?php

namespace App\Http\Controllers\Caseta;

use App\Http\Controllers\Controller;
use App\Models\Caseta;
use Illuminate\Http\Request;
use App\Models\Preventa;
use App\Models\User;


class OperacionesController extends Controller
{
    // 1. Mostrar selector
    public function showSelector()
    {
        $casetas = Caseta::where('activa', true)->get();
        return view('caseta.seleccionar', compact('casetas'));
    }

    // 2. Validar Código y Guardar Sesión
    public function setCaseta(Request $request)
    {
        // Validamos que envíe ID y Código
        $request->validate([
            'caseta_id' => 'required|exists:casetas,id',
            'codigo_seguridad' => 'required|string',
        ]);

        $caseta = Caseta::find($request->caseta_id);

        // --- FILTRO DE SEGURIDAD ---
        // Comparamos el código ingresado (en mayúsculas) con el de la base de datos
        if (strtoupper($request->codigo_seguridad) !== $caseta->codigo_unico) {

            // Si falla, regresamos atrás con un error específico
            return back()->withErrors([
                'codigo_seguridad' => 'El código ingresado no coincide con la Caseta seleccionada.'
            ]);
        }

        // Si pasa el filtro, guardamos la caseta en la sesión
        session(['caseta_activa' => $caseta]);

        return redirect()->route('caseta.dashboard');
    }

    // 3. Dashboard
    public function dashboard(Request $request)
    {
        // 1. Seguridad: Verificar que haya elegido caseta
        if (!session()->has('caseta_activa')) {
            return redirect()->route('caseta.seleccion');
        }

        $caseta = session('caseta_activa');

        // Variables vacías por defecto
        $agricultor = null;
        $negociaciones = collect();
        $mensaje = null;

        // 2. Si el operario usó el buscador (hay un DNI en la URL)
        if ($request->has('dni_agricultor')) {

            $dni = $request->dni_agricultor;

            // A. Buscamos al Agricultor por DNI
            $agricultor = \App\Models\User::where('dni', $dni)
                ->where('rol', 'agricultor')
                ->first();

            if ($agricultor) {
                // B. Buscamos sus preventas ACORDADAS (Listas para entregar)
                $negociaciones = \App\Models\Preventa::where('user_id', $agricultor->id)
                    ->where('estado', 'acordada')
                    ->with([
                        'lote', // Traemos info del lote si existe
                        'propuestas' => function ($query) {
                            // Solo traemos la propuesta ganadora
                            $query->where('estado', 'aceptada');
                        },
                        'propuestas.user' // Traemos al Molino de esa propuesta
                    ])
                    ->get();

                if ($negociaciones->isEmpty()) {
                    $mensaje = "El agricultor existe, pero no tiene entregas pendientes (Estado: Acordada).";
                }
            } else {
                $mensaje = "No se encontró ningún agricultor con el DNI: " . $dni;
            }
        }

        // --- 3. CÁLCULO DE MÉTRICAS (ESTO ES LO QUE FALTABA) ---
        // Contamos cuántas preventas están listas para entregar en todo el sistema
        $solicitudesPactadas = \App\Models\Preventa::where('estado', 'acordada')->count(); // <--- NUEVO
        
        $procesadosHoy = 0; // <--- NUEVO (Variable temporal)

        // 4. Retornar vista enviando TODAS las variables
        return view('caseta.dashboard', compact(
            'caseta', 
            'agricultor', 
            'negociaciones', 
            'mensaje',
            'solicitudesPactadas', // <--- AGREGADO AQUÍ
            'procesadosHoy'        // <--- AGREGADO AQUÍ
        ));
    }

    public function recepcion(Request $request)
    {
        if (!session()->has('caseta_activa')) return redirect()->route('caseta.seleccion');
        $caseta = session('caseta_activa');

        $agricultor = null;
        $negociaciones = collect();
        $mensaje = null;

        if ($request->has('dni_agricultor')) {
            $dni = $request->dni_agricultor;
            $agricultor = User::where('dni', $dni)->where('rol', 'agricultor')->first();

            if ($agricultor) {
                $negociaciones = Preventa::where('user_id', $agricultor->id)
                                        ->where('estado', 'acordada') 
                                        ->with(['lote', 'propuestas' => function($q) { $q->where('estado', 'aceptada'); }, 'propuestas.user']) 
                                        ->get();
                if ($negociaciones->isEmpty()) $mensaje = "El agricultor no tiene entregas pendientes.";
            } else {
                $mensaje = "No se encontró agricultor con DNI: " . $dni;
            }
        }

        return view('caseta.recepcion', compact('caseta', 'agricultor', 'negociaciones', 'mensaje'));
    }

    // --- 4. MOSTRAR FORMULARIO DE EVALUACIÓN ---
    public function evaluar($id)
    {
        if (!session()->has('caseta_activa')) return redirect()->route('caseta.seleccion');
        
        // Buscamos la preventa con sus datos
        $preventa = \App\Models\Preventa::with('user', 'lote')->findOrFail($id);

        // Retornamos la vista del formulario
        return view('caseta.evaluar', compact('preventa'));
    }

    // --- 5. GUARDAR DATOS DEL ANÁLISIS ---
    public function guardarAnalisis(Request $request, $id)
    {
        $preventa = \App\Models\Preventa::findOrFail($id);
        /** @var \App\Models\Caseta $caseta */
        $caseta = session('caseta_activa');

        // 1. Validar los datos
        $request->validate([
            'peso_real_sacos' => 'required|numeric|min:0',
            'cantidad_sacos_real' => 'required|integer|min:1',
            'humedad_real' => 'required|numeric|min:0|max:100',
            'quebrado_real' => 'required|numeric|min:0|max:100',
            'impurezas_real' => 'required|numeric|min:0|max:100',
            'observaciones' => 'nullable|string|max:500',
            // NUEVA VALIDACIÓN PARA LA FOTO
            'foto_ticket_balanza' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        // 2. Procesar la imagen (Si se subió una)
        $rutaFoto = null;
        if ($request->hasFile('foto_ticket_balanza')) {
            // Guardamos en la carpeta 'tickets' dentro del disco 'public'
            // El nombre se genera automáticamente
            $rutaFoto = $request->file('foto_ticket_balanza')->store('tickets', 'public');
        }

        // 3. Crear el registro de Calidad
        \App\Models\AnalisisCalidad::create([
            'preventa_id' => $preventa->id,
            'user_caseta_id' => auth()->id(),
            'caseta_id' => $caseta->id,
            
            'peso_real_sacos' => $request->peso_real_sacos,
            'cantidad_sacos_real' => $request->cantidad_sacos_real,
            'humedad_real' => $request->humedad_real,
            'quebrado_real' => $request->quebrado_real,
            'impurezas_real' => $request->impurezas_real,
            'observaciones' => $request->observaciones,
            
            // Guardamos la ruta (o null si no hubo foto)
            'foto_ticket_balanza' => $rutaFoto,
        ]);

        // 4. Actualizar estado de la Preventa
        $preventa->estado = 'entregada';
        $preventa->save();

        // 5. Redirigir
        return redirect()->route('caseta.recepcion')
            ->with('status', '¡Carga recibida y ticket guardado correctamente!');
    }
}
