<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campana extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'user_id',
        'nombre_campana',
        'tipo_arroz_id',
        'cantidad_total',
        'cantidad_acordada',
        'precio_base',
        'estado',
        'humedad_min',
        'humedad_max',
        'quebrado_min',
        'quebrado_max',
        'min_sacos_por_agricultor',
        'max_sacos_por_agricultor',
    ];

    /**
     * Define la relación: Una Campaña le pertenece a un Usuario (Molino).
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