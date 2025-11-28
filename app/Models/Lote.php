<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lote extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'user_id',
        'tipo_arroz_id',
        'nombre_lote',
        'cantidad_total_sacos',
        'cantidad_disponible_sacos',
        'humedad',
        'quebrado',
        'estado',
        'latitud',
        'longitud',
        'referencia_ubicacion',
    ];

    /**
     * Define la relación: Un Lote le pertenece a un Usuario (Agricultor).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tipoArroz(): BelongsTo
    {
        return $this->belongsTo(TipoArroz::class);
    }
}
