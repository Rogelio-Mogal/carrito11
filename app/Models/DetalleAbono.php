<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleAbono extends Model
{
    use HasFactory;

    protected $table = 'detalle_abonos';    
    protected $fillable = [
        'abono_id',
        'venta_credito_id',
        'abonado_a_id',
        'abonado_a_type',    
        'monto_antes',     // Cuánto debía antes del abono
        'abonado',         // Cuánto abonó en esta operación
        'saldo_despues',   // Cuánto debe después del abono
        'activo',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function abono()  
    {
        //return $this->belongsTo(Abono::class, 'abono_id');
        return $this->belongsTo(Abono::class);
    }

    public function abonado_a()
    {
        return $this->morphTo(); // Venta, Apartado, Anticipo
    }

    public function ventaCredito()
    {
        //return $this->belongsTo(VentaCredito::class, 'venta_credito_id');
        return $this->belongsTo(VentaCredito::class);
    }

    public function pagos()
    {
        return $this->morphMany(TipoPago::class, 'pagable');
    }
}
