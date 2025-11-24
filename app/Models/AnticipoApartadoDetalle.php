<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnticipoApartadoDetalle extends Model
{
    use HasFactory;

    protected $table = 'anticipo_apartado_detalles';    
    protected $fillable = [
        'anticipo_apartado_id',
        'producto_id',
        'producto_comun',
        'cantidad',
        'precio',
        'total',
        'activo',
        'wci',
    ];

    // 🔗 Relación inversa con el anticipo
    public function anticipoApartado()
    {
        return $this->belongsTo(AnticipoApartado::class, 'anticipo_apartado_id');
    }

    // 🔗 Relación con producto (si tienes tabla productos)
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
