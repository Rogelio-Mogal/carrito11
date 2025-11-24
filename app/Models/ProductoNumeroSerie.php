<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoNumeroSerie extends Model
{
    use HasFactory;

    protected $table = 'producto_numero_series';    
    protected $fillable = [
        'producto_id',
        'proveedor_id',
        'compra_id',
        'venta_id',
        'nota_credito_id',
        'garantia_id',
        'numero_serie',
        'disponible'
    ];
}
