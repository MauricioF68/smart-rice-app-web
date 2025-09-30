<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoArroz;
use Illuminate\Http\Request;

class TipoArrozController extends Controller
{
    public function index()
    {
        $tiposArroz = TipoArroz::latest()->paginate(10);
        return view('admin.tipos_arroz.index', compact('tiposArroz'));
    }

    public function create()
    {
        return view('admin.tipos_arroz.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:tipos_arroz,nombre'],
        ]);

        TipoArroz::create($request->all());

        return redirect()->route('admin.tipos-arroz.index')->with('status', 'Variedad de arroz creada exitosamente.');
    }

    public function edit(TipoArroz $tiposArroz)
    {
        return view('admin.tipos_arroz.edit', compact('tiposArroz'));
    }

    public function update(Request $request, TipoArroz $tiposArroz)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:tipos_arroz,nombre,' . $tiposArroz->id],
        ]);

        $tiposArroz->update($request->all());

        return redirect()->route('admin.tipos-arroz.index')->with('status', 'Variedad de arroz actualizada exitosamente.');
    }

    public function destroy(TipoArroz $tiposArroz)
    {
        $tiposArroz->delete();
        return redirect()->route('admin.tipos-arroz.index')->with('status', 'Variedad de arroz eliminada exitosamente.');
    }
}