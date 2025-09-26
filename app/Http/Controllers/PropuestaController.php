<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropuestaRequest;
use App\Models\Propuesta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PropuestaController extends Controller
{
    /**
     * Guarda una nueva propuesta en la base de datos.
     */
    public function store(StorePropuestaRequest $request): RedirectResponse
    {
        // 1. Los datos ya vienen validados por StorePropuestaRequest.
        $datosValidados = $request->validated();

        // 2. Añadimos el ID del usuario (el Molino) que hace la propuesta.
        $datosValidados['user_id'] = auth()->id();

        // 3. Creamos la propuesta en la base de datos.
        Propuesta::create($datosValidados);

        // 4. Redirigimos de vuelta a la página anterior con un mensaje de éxito.
        return back()->with('status', '¡Propuesta enviada exitosamente!');
    }

    // app/Http/Controllers/PropuestaController.php

    // ... (después del método store())

    /**
     * Acepta una propuesta y cierra el trato para la preventa.
     */
    public function accept(Propuesta $propuesta)
    {
        // 1. Obtener la preventa asociada
        $preventa = $propuesta->preventa;

        // 2. Cambiar el estado de la preventa a 'acordada'
        $preventa->estado = 'acordada';
        $preventa->save();

        // 3. Cambiar el estado de esta propuesta a 'aceptada'
        $propuesta->estado = 'aceptada';
        $propuesta->save();

        // 4. (Opcional pero recomendado) Rechazar todas las demás propuestas para esta preventa
        $preventa->propuestas()->where('id', '!=', $propuesta->id)->update(['estado' => 'rechazada']);

        // 5. Redirigir de vuelta con un mensaje de éxito
        return back()->with('status', '¡Trato cerrado exitosamente! La preventa ha sido retirada del mercado.');
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