<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaCredito extends Model
{
    use HasFactory;

    protected $table = 'venta_creditos';    
    protected $fillable = [
        'venta_id',
        'cliente_id',
        'monto_credito', // Monto inicial a crédito
        'saldo_actual',  // Cuánto falta por pagar
        'liquidado',     // true cuando saldo_actual llega a 0
        'activo',
    ];

    // Relación con Venta
    public function venta()
    {
        //return $this->belongsTo(Venta::class, 'venta_id');
        return $this->belongsTo(Venta::class);
    }

    // Relación indirecta con Cliente (a través de Venta)
    public function cliente()
    {
        return $this->hasOneThrough(
            Cliente::class,  // Modelo destino
            Venta::class,    // Modelo intermedio
            'id',            // Clave en Venta (local key)
            'id',            // Clave en Cliente (local key)
            'venta_id',      // FK en VentaCredito
            'cliente_id'     // FK en Venta
        );
    }

    public function pagos()
    {
        return $this->morphMany(TipoPago::class, 'pagable');
    }
}
