<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecompenzaProducto extends Model
{
    use HasFactory;

    protected $table = 'recompenza_productos';    
    protected $fillable = [
        'recompenza_id',
        'producto_id',
        'fecha',
        'puntos_utilizados',
        'activo',
    ];
}
