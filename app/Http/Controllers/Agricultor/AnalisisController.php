<?php

namespace App\Http\Controllers\Agricultor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnalisisCalidad;
use Illuminate\Support\Facades\Auth;

class AnalisisController extends Controller
{
    public function index()
    {
        // Buscamos los análisis donde la preventa original pertenezca al agricultor logueado
        $analisis = AnalisisCalidad::whereHas('preventa', function($query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->with(['preventa', 'caseta']) // Traemos datos de la preventa y la caseta
                    ->latest() // Los más recientes primero
                    ->get();

        return view('agricultor.analisis.index', compact('analisis'));
    }
}