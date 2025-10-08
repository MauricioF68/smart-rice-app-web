<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampanaLote extends Model
{
    use HasFactory;

    protected $table = 'campana_lote';

    protected $fillable = [
        'campana_id',
        'lote_id',
        'user_id', // ID del agricultor
        'cantidad_comprometida',
        'estado',
    ];

    // --- AÑADIR ESTAS DOS FUNCIONES ---

    /**
     * Define la relación: Una aplicación pertenece a un Usuario (Agricultor).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define la relación: Una aplicación pertenece a un Lote.
     */
    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }
    /**
     * Define la relación: Una aplicación pertenece a una Campaña.
     */
    public function campana()
    {
        return $this->belongsTo(Campana::class);
    }
}
