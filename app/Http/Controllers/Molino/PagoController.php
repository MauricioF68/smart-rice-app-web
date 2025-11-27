<?php

namespace App\Http\Controllers\Molino;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Preventa;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    // Listar las cargas que ya llegaron y están pendientes de pago
    public function index()
    {
        $molinoId = Auth::id();

        // Buscamos preventas que:
        // 1. Tengan estado 'entregada' (Ya pasaron por caseta)
        // 2. Tengan una propuesta ganadora ('aceptada') que pertenezca a ESTE molino
        $porPagar = Preventa::where('estado', 'entregada')
                    ->whereHas('propuestas', function($query) use ($molinoId) {
                        $query->where('user_id', $molinoId)
                              ->where('estado', 'aceptada');
                    })
                    ->with(['user', 'analisisCalidad']) // Traemos datos del agricultor y el pesaje real
                    ->get();

        return view('molino.pagos.index', compact('porPagar'));
    }

    // --- 2. MOSTRAR FORMULARIO DE PAGO ---
    public function create($id)
    {
        // Buscamos la preventa con los datos del agricultor y el pesaje real
        $preventa = \App\Models\Preventa::with('user', 'analisisCalidad')->findOrFail($id);

        return view('molino.pagos.create', compact('preventa'));
    }

    // --- 3. GUARDAR EL PAGO Y EL VOUCHER ---
    public function store(Request $request, $id)
    {
        $preventa = \App\Models\Preventa::with('analisisCalidad')->findOrFail($id);

        // 1. Validar los datos del formulario
        $request->validate([
            'banco_origen' => 'required|string|max:100',
            'numero_operacion' => 'required|string|max:50',
            'fecha_pago' => 'required|date',
            'foto_voucher' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048', // Obligatorio
        ]);

        // 2. Calcular el monto total automáticamente para asegurar consistencia
        // (Sacos Reales * Precio Pactado)
        $montoTotal = $preventa->analisisCalidad->cantidad_sacos_real * $preventa->precio_por_saco;

        // 3. Subir la foto del voucher
        $rutaVoucher = $request->file('foto_voucher')->store('vouchers', 'public');

        // 4. Crear el registro en la tabla 'pagos'
        \App\Models\Pago::create([
            'preventa_id' => $preventa->id,
            'user_id' => auth()->id(), // El Molino que paga
            'monto_total' => $montoTotal,
            'banco_origen' => $request->banco_origen,
            'numero_operacion' => $request->numero_operacion,
            'fecha_pago' => $request->fecha_pago,
            'foto_voucher' => $rutaVoucher,
        ]);

        // 5. Actualizar estado de la Preventa a "pagada"
        // Esto hará que desaparezca de la lista de "Por Pagar"
        $preventa->estado = 'pagada';
        $preventa->save();

        return redirect()->route('molino.pagos.index')
            ->with('status', '¡Pago registrado correctamente! El agricultor será notificado.');
    }
}