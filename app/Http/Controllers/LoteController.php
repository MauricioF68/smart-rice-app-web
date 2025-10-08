<?php

namespace App\Http\Controllers;

use App\Models\TipoArroz;
use App\Http\Requests\StoreLoteRequest;
use App\Http\Requests\UpdateLoteRequest;
use App\Models\Lote;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Busca solo los lotes del agricultor que ha iniciado sesión
        // Ejemplo de cómo debe quedar tu código CORREGIDO
        $lotes = Lote::with('tipoArroz')->where('user_id', auth()->id())->latest()->get();

        return view('lotes.index', compact('lotes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Necesitaremos los tipos de arroz para el dropdown
        $tiposArroz = TipoArroz::orderBy('nombre')->get();

        return view('lotes.create', compact('tiposArroz'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoteRequest $request)
    {
        $datosValidados = $request->validated();
        $datosValidados['user_id'] = auth()->id();

        // La cantidad disponible es igual a la total al crear
        $datosValidados['cantidad_disponible_sacos'] = $datosValidados['cantidad_total_sacos'];

        Lote::create($datosValidados);

        return redirect()->route('lotes.index')->with('status', '¡Lote registrado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lote $lote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lote $lote)
    {
        //
    }

    public function update(UpdateLoteRequest $request, Lote $lote)
    {
        // Regla de Negocio: No se puede editar si ya está comprometido.
        if ($lote->cantidad_disponible_sacos < $lote->cantidad_total_sacos) {
            return back()->with('status', 'Error: No se puede editar un lote que ya tiene sacos comprometidos.');
        }

        $lote->update($request->validated());

        return redirect()->route('lotes.index')->with('status', '¡Lote actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lote $lote)
    {
        //
    }
}
