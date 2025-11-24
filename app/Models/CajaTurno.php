<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajaTurno extends Model
{
    use HasFactory;

    protected $table = 'caja_turnos';    
    protected $fillable = [
        'usuario_id',
        'turno',
        'efectivo_inicial',
        'efectivo_calculado',
        'efectivo_real',
        'diferencia',
        'fecha_apertura',
        'fecha_cierre',
        'estado',
        'detalle_calculo',
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre'   => 'datetime',
        'detalle_calculo' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
