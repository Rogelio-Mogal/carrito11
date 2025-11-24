<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';    
    protected $fillable = [
        'sucursal_id',
        'proveedor_id',
        'tipo',
        'num_factura',
        'fecha_compra',
        'fecha_captura',
        'total',
        'wci',
        'activo',
    ];

    // RELACIONES

    // Relación con el modelo Proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    // Relación con el modelo ComprasDetalles
    public function compraDetalles()
    {
        return $this->hasMany(ComprasDetalle::class, 'compra_id');
    }
}
