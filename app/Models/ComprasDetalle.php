<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprasDetalle extends Model
{
    use HasFactory;

    protected $table = 'compras_detalles';    
    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'precio',
        'importe',
        'nota_compra',
        'tipo_movimiento',
        'wci',
        'activo',
    ];

    // RELACIONES 

    // Relación con el modelo Compras
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    // Relación con el modelo Producto - compraDetalle
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
