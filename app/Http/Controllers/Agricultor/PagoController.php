<?php

namespace App\Http\Controllers\Agricultor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Preventa;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function index()
    {
        // Traemos las preventas que ya fueron PAGADAS
        $pagos = Preventa::where('user_id', Auth::id())
                    ->where('estado', 'pagada') // Solo las pagadas
                    ->with(['pago', 'analisisCalidad', 'propuestas' => function($q) {
                        $q->where('estado', 'aceptada')->with('user'); // Para saber qué Molino pagó
                    }]) 
                    ->latest()
                    ->get();

        return view('agricultor.pagos.index', compact('pagos'));
    }
}