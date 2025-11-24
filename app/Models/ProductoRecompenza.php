<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoRecompenza extends Model
{
    use HasFactory;

    protected $table = 'producto_recompenzas';    
    protected $fillable = [
        'producto_id',
        'puntos',
        'piezas',
        'debe',
        'haber',
        'saldo',
        'activo',
    ];
}
