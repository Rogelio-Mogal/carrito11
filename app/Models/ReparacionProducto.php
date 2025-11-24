<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReparacionProducto extends Model
{
    use HasFactory;

    protected $table = 'reparacion_productos';

    protected $fillable = [
        'reparacion_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'total',
        'activo',
        'wci',
    ];

    /* --------------------
       RELACIONES
    -------------------- */

    public function reparacion()
    {
        return $this->belongsTo(Reparacion::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
