<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajaMovimiento extends Model
{
    use HasFactory;
    protected $table = 'caja_movimientos';    
    protected $fillable = [
        'monto',
        'tipo',
        'motivo',
        'fecha',
        'origen_id',   // ✅ debes incluir estos
        'origen_type', // ✅
        'usuario_id',
        'activo',
    ];

    protected $casts = [
        'fecha'   => 'datetime',
    ];

    // Relación polimórfica
    public function origen()
    {
        return $this->morphTo();
    }

}
