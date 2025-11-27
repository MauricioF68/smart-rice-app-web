<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'preventa_id',
        'user_id',
        'monto_total',
        'banco_origen',
        'numero_operacion',
        'fecha_pago',
        'foto_voucher',
    ];

    // Relación: Un pago pertenece a una Preventa
    public function preventa()
    {
        return $this->belongsTo(Preventa::class);
    }

    // Relación: Un pago lo hace un Usuario (Molino)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}