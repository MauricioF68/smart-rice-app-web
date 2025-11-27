<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caseta;
use Illuminate\Http\Request;

class CasetaController extends Controller
{
    // Mostrar lista de casetas
    public function index()
    {
        $casetas = Caseta::all();
        return view('admin.casetas.index', compact('casetas'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('admin.casetas.create');
    }

    // Guardar la nueva caseta en BD
    public function store(Request $request)
    {
        // 1. Validar datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_unico' => 'required|string|unique:casetas,codigo_unico|max:50',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        // 2. Crear Caseta
        Caseta::create([
            'nombre' => $request->nombre,
            'codigo_unico' => strtoupper($request->codigo_unico), // Guardar en mayúsculas
            'ubicacion' => $request->ubicacion,
            'activa' => true
        ]);

        // 3. Redireccionar con mensaje de éxito
        return redirect()->route('admin.casetas.index')
            ->with('status', 'Caseta creada correctamente.');
    }

    public function edit($id)
    {
        $caseta = Caseta::findOrFail($id);
        return view('admin.casetas.edit', compact('caseta'));
    }

    // 2. Actualizar datos en BD
    public function update(Request $request, $id)
    {
        $caseta = Caseta::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            // Importante: unique ignora el ID actual para que no de error si no cambias el código
            'codigo_unico' => 'required|string|max:50|unique:casetas,codigo_unico,' . $caseta->id,
            'ubicacion' => 'nullable|string|max:255',
        ]);

        $caseta->update([
            'nombre' => $request->nombre,
            'codigo_unico' => strtoupper($request->codigo_unico),
            'ubicacion' => $request->ubicacion,
            // Si quisieras desactivarla, podrías enviar un campo 'activa'
        ]);

        return redirect()->route('admin.casetas.index')
            ->with('status', 'Caseta actualizada correctamente.');
    }

    // 3. Eliminar caseta
    public function destroy($id)
    {
        $caseta = Caseta::findOrFail($id);
        $caseta->delete();

        return redirect()->route('admin.casetas.index')
            ->with('status', 'Caseta eliminada correctamente.');
    }
}