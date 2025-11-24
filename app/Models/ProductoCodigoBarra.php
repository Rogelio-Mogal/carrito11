<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoCodigoBarra extends Model
{
    use HasFactory;

    protected $table = 'producto_codigo_barras';    
    protected $fillable = [
        'producto_id',
        'proveedor_id',
        'codigo_barra',
        'activo',
    ];
}
