<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Propuesta extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'preventa_id',
        'user_id',
        'cantidad_sacos_propuesta',
        'precio_por_saco_propuesta',
        'estado',
    ];

    /**
     * Define la relación: Una Propuesta pertenece a una Preventa.
     */
    public function preventa(): BelongsTo
    {
        return $this->belongsTo(Preventa::class);
    }

    /**
     * Define la relación: Una Propuesta pertenece a un Usuario (el Molino).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}