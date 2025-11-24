<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recompenza extends Model
{
    use HasFactory;

    protected $table = 'recompenzas';    
    protected $fillable = [
        'cliente_id',
        'venta_id',
        'fecha',
        'debe',
        'haber',
        'saldo',
        'tipo',
        'activo',
    ];
}
