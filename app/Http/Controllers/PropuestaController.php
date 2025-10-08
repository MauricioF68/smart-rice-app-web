<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropuestaRequest;
use App\Models\Preventa;
use App\Models\Propuesta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PropuestaController extends Controller
{
    /**
     * Guarda una nueva propuesta en la base de datos.
     */
    public function store(StorePropuestaRequest $request): RedirectResponse
    {
        // 1. Los datos ya vienen validados.
        $datosValidados = $request->validated();

        // 2. Añadimos el ID del usuario (el Molino).
        $datosValidados['user_id'] = auth()->id();

        // 3. Asignamos el estado 'pendiente'.
        $datosValidados['estado'] = 'pendiente';

        // 4. Creamos la propuesta.
        $propuesta = Propuesta::create($datosValidados);

        // 5. Actualizamos el estado de la preventa a 'en_negociacion'.
        if ($propuesta) {
            $preventa = Preventa::find($propuesta->preventa_id);
            if ($preventa) {
                $preventa->update(['estado' => 'en_negociacion']);
            }
        }

        // 6. Redirigimos.
        return back()->with('status', '¡Propuesta enviada exitosamente!');
    }

    // app/Http/Controllers/PropuestaController.php

    // ... (después del método store())

    /**
     * Acepta una propuesta y cierra el trato para la preventa.
     */
    public function accept(Propuesta $propuesta)
    {
        // 1. Cargamos las relaciones para acceder a los datos necesarios
        $propuesta->load('preventa.lote');
        $preventa = $propuesta->preventa;
        $lote = $preventa->lote;

         if (!$lote) {
            // Si no se encuentra un lote, detenemos la ejecución con un error claro.
            return back()->with('status', 'Error: La preventa original no está asociada a un lote y no puede ser procesada.');
        }

        // 2. VERIFICACIÓN DE SEGURIDAD: El usuario debe ser el dueño de la preventa
        if ($preventa->user_id !== auth()->id()) {
            abort(403, 'Acción no autorizada.');
        }

        // 3. VALIDACIÓN DE NEGOCIO: El lote debe tener suficientes sacos disponibles
        if ($propuesta->cantidad_sacos_propuesta > $lote->cantidad_disponible_sacos) {
            return back()->with('status', 'Error: No hay suficientes sacos disponibles en el lote para completar este trato.');
        }

       

        // --- INICIO DE LA TRANSACCIÓN SEGURA ---
        try {
            DB::beginTransaction();

            // A. Actualizar el estado de la preventa a 'acordada'
            $preventa->update(['estado' => 'acordada']);

            // B. Actualizar el estado de esta propuesta a 'aceptada'
            $propuesta->update(['estado' => 'aceptada']);

            // C. Rechazar todas las demás propuestas para esta preventa
            $preventa->propuestas()->where('id', '!=', $propuesta->id)->update(['estado' => 'rechazada']);

            // D. DESCONTAR SACOS DEL LOTE
            $lote->decrement('cantidad_disponible_sacos', $propuesta->cantidad_sacos_propuesta);

            // E. ACTUALIZAR ESTADO DEL LOTE SI SE AGOTÓ
            if ($lote->cantidad_disponible_sacos <= 0) {
                $lote->update(['estado' => 'vendido']);
            }

            // Si todo sale bien, confirmamos los cambios en la base de datos
            DB::commit();
        } catch (\Exception $e) {
            // Si algo falla, revertimos todos los cambios
            DB::rollBack();
            // Opcional: registrar el error para depuración
            // \Log::error('Error al aceptar propuesta: ' . $e->getMessage());
            return back()->with('status', 'Error: Ocurrió un problema al procesar el trato. Inténtalo de nuevo.');
        }
        // --- FIN DE LA TRANSACCIÓN ---

        // 5. Redirigir de vuelta con un mensaje de éxito
        return redirect()->route('preventas.negociaciones')->with('status', '¡Trato cerrado exitosamente! Tu inventario ha sido actualizado.');
    }

    /**
     * Rechaza una propuesta específica.
     */
    public function reject(Propuesta $propuesta)
    {
        $propuesta->estado = 'rechazada';
        $propuesta->save();

        return back()->with('status', 'La propuesta ha sido rechazada.');
    }
}
