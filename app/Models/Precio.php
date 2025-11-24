<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    use HasFactory;

    protected $table = 'precios';    
    protected $fillable = [
        'producto_caracteristica_id',
        'desde',
        'hasta',
        'porcentaje_publico',
        'porcentaje_medio',
        'porcentaje_mayoreo',
        'especifico_publico',
        'especifico_medio',
        'especifico_mayoreo',
        'precio',
        'tipo_precio',
        'activo',
    ];

    // RELACION PARA MODULO PRECIOS
    public function productoCaracteristica()
    {
        return $this->belongsTo(ProductoCaracteristica::class, 'producto_caracteristica_id');
    }
}
