<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuentaBancaria extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'cuentas_bancarias'; // Le decimos explícitamente el nombre de la tabla

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'banco_nombre',
        'tipo_cuenta',
        'titular_nombres',
        'titular_apellidos',
        'numero_cuenta',
        'cci',
        'is_primary',
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_primary' => 'boolean', // Para que 'is_primary' sea siempre verdadero/falso
    ];

    /**
     * Define la relación: Una Cuenta Bancaria le pertenece a un Usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}