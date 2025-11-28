<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Importar SoftDeletes

class Preventa extends Model
{
    use HasFactory, SoftDeletes; // 2. Usar SoftDeletes

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [ // 3. Definir los campos rellenables
        'user_id',
        'lote_id',
        'cantidad_sacos',
        'precio_por_saco',
        'humedad',
        'quebrado',
        'notas',
        'estado',
    ];

    /**
     * Define la relación: Una Preventa le pertenece a un Usuario (Agricultor).
     */
    public function user(): BelongsTo // 4. Definir la relación
    {
        return $this->belongsTo(User::class);
    }

    public function lote()
    {
        return $this->belongsTo(\App\Models\Lote::class);
    }
    
    public function propuestas()
    {
        return $this->hasMany(\App\Models\Propuesta::class);
    }
    public function analisisCalidad()
    {
        // Usamos hasOne porque cada preventa tiene un solo análisis final
        return $this->hasOne(\App\Models\AnalisisCalidad::class);
    }
    public function pago()
    {
        return $this->hasOne(\App\Models\Pago::class);
    }
    public function recojo()
    {
        return $this->hasOne(\App\Models\Recojo::class);
    }
}
