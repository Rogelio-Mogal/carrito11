<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    use HasFactory;

    protected $table = 'kardex';    
    protected $fillable = [
        'sucursal_id',
        'producto_id',
        'movimiento_id',
        'tipo_movimiento',
        'tipo_detalle',
        'fecha',
        'folio',
        'descripcion',
        'debe',
        'haber',
        'saldo',
        'wci',
        'activo',
    ];
}
