<?php

namespace App\Http\Controllers;

use App\Models\CuentaBancaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCuentaBancariaRequest;
use Illuminate\Support\Facades\DB;


class CuentaBancariaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Buscamos todas las cuentas que pertenecen al usuario que ha iniciado sesión
        $cuentas = CuentaBancaria::where('user_id', Auth::id())
            ->latest()
            ->get();

        // 2. Devolvemos la vista y le pasamos la colección de cuentas
        return view('cuentas-bancarias.index', compact('cuentas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Definimos la lista de bancos (tal como lo propusiste)
        $bancos = [
            'BCP - Banco de Crédito del Perú',
            'Interbank',
            'BBVA Continental',
            'Scotiabank',
            'Banco de la Nación',
            'Caja Piura',
            'Caja Arequipa',
            'Caja Trujillo',
            'Caja Huancayo',
            'Otro',
        ];

        $tipos_cuenta = [
            'Ahorros',
            'Corriente',
        ];

        // Devolvemos la vista del formulario y le pasamos la lista de bancos
        return view('cuentas-bancarias.create', compact('bancos', 'tipos_cuenta'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCuentaBancariaRequest $request)
    {
        // 1. Obtiene los datos ya validados por StoreCuentaBancariaRequest
        $data = $request->validated();

        // 2. Añade el ID del usuario que ha iniciado sesión
        $data['user_id'] = Auth::id();

        // 3. Lógica para la cuenta principal:
        // Si esta es la PRIMERA cuenta que registra, la marcamos como principal.
        $existingAccountsCount = CuentaBancaria::where('user_id', Auth::id())->count();
        if ($existingAccountsCount === 0) {
            $data['is_primary'] = true;
        }

        // 4. Crea el registro en la base de datos
        CuentaBancaria::create($data);

        // 5. Redirige al usuario de vuelta al listado con un mensaje de éxito
        return redirect()->route('cuentas-bancarias.index')
            ->with('status', '¡Cuenta bancaria añadida exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CuentaBancaria $cuentaBancaria)
    {
        // No la usaremos, redirigimos al índice
        return redirect()->route('cuentas-bancarias.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, CuentaBancaria $cuenta_bancaria)
    {
        // 1. Verificación de Seguridad: El usuario debe ser el dueño
        if ($cuenta_bancaria->user_id !== $request->user()->id) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Lógica de Negocio: NO permitir borrar la cuenta principal
        if ($cuenta_bancaria->is_primary) {
            return redirect()->route('cuentas-bancarias.index')
                ->with('status-error', 'Error: No puedes eliminar tu cuenta principal. Por favor, marca otra cuenta como principal primero.');
        }

        // 3. Eliminar la cuenta
        $cuenta_bancaria->delete();

        // 4. Redirigir con mensaje de éxito
        return redirect()->route('cuentas-bancarias.index')
            ->with('status', '¡Cuenta bancaria eliminada exitosamente!');
    }

    public function setPrimary(Request $request, CuentaBancaria $cuenta_bancaria)
    {
        // 1. Verificación de Seguridad: Asegurarnos de que el usuario es dueño de esta cuenta
        if ($cuenta_bancaria->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Usamos una transacción para asegurar que ambas operaciones funcionen
        DB::transaction(function () use ($cuenta_bancaria, $request) {

            // 3. Primero, ponemos TODAS las cuentas de este usuario como NO principales
            $request->user()->cuentasBancarias()->update(['is_primary' => false]);

            // 4. Luego, marcamos solo la elegida como SÍ principal
            $cuenta_bancaria->update(['is_primary' => true]);
        });

        // 5. Redirigimos de vuelta con un mensaje de éxito
        return redirect()->route('cuentas-bancarias.index')
            ->with('status', '¡Has cambiado tu cuenta principal exitosamente!');
    }
}
