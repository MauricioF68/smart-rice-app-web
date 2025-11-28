<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recojo extends Model
{
    use HasFactory;

    protected $fillable = [
        'preventa_id',
        'fecha_programada',
        'placa_camion',
        'nombre_chofer',
        'distancia_km',    // <--- Nuevo
        'tiempo_estimado',
        'estado'
    ];

    public function preventa()
    {
        return $this->belongsTo(Preventa::class);
    }
}