<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CasetaUsuarioController extends Controller
{
    // Listar solo usuarios con rol 'caseta'
    public function index()
    {
        $operarios = User::where('rol', 'caseta')->get();
        return view('admin.usuarios-caseta.index', compact('operarios'));
    }

    // Mostrar formulario de registro
    public function create()
    {
        return view('admin.usuarios-caseta.create');
    }

    // Guardar nuevo operario
    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|size:8|unique:users,dni',
            'primer_nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'rol' => 'caseta', // ROL FIJO
            'dni' => $request->dni,
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'telefono' => $request->telefono,
            
            // Campos obligatorios de Laravel
            'name' => $request->primer_nombre . ' ' . $request->apellido_paterno,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'estado' => 'verificado', // Nacen activos
        ]);

        return redirect()->route('admin.usuarios-caseta.index')
            ->with('status', 'Operario registrado correctamente.');
    }

    public function destroy($id)
    {
        $operario = \App\Models\User::findOrFail($id);
        
        // Evitar que un admin se borre a sí mismo (seguridad básica)
        if ($operario->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta.']);
        }

        $operario->delete();

        return redirect()->route('admin.usuarios-caseta.index')
            ->with('status', 'Operario eliminado correctamente.');
    }
}