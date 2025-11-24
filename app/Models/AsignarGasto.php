<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignarGasto extends Model
{
    use HasFactory;

    protected $table = 'asignar_gastos';    
    protected $fillable = [
        'gasto_id',
        'forma_pago_id',
        'fecha',
        'monto',
        'nota',
        'activo',
    ];

    // Relación con el modelo Gasto
    public function gasto()
    {
        return $this->belongsTo(Gasto::class, 'gasto_id');
    }

    // Relación con el modelo FormaPago (si existe)
    public function formaPago()
    {
        return $this->belongsTo(FormaPago::class, 'forma_pago_id');
    }
}
