<?php

namespace App\Http\Controllers;

use App\Models\Campana;
use App\Models\TipoArroz;
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
}
