<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;

    protected $table = 'venta_detalles';    
    protected $fillable = [
        'venta_id',
        'tipo_item',
        'producto_id',
        'producto_comun',
        'cantidad',
        'precio',
        'total',
        'activo',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function devoluciones()
    {
        return $this->hasMany(VentaDevoluciones::class, 'venta_detalle_id');
    }
}
