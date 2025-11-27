<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalisisCalidad extends Model
{
    use HasFactory;

    protected $table = 'analisis_calidad';

    protected $fillable = [
        'preventa_id',
        'user_caseta_id',
        'caseta_id',
        'peso_real_sacos',
        'cantidad_sacos_real',
        'humedad_real',
        'quebrado_real',
        'impurezas_real',
        'observaciones',
        'foto_ticket_balanza'
    ];

    // Relación con la Preventa (El trato original)
    public function preventa()
    {
        return $this->belongsTo(Preventa::class);
    }
    
    // Relación con la Caseta
    public function caseta()
    {
        return $this->belongsTo(Caseta::class);
    }
}