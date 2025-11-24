<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPago extends Model
{
    use HasFactory;

    protected $table = 'tipo_pagos';    
    protected $fillable = [
        'pagable_id',
        'pagable_type',
        'metodo',
        'monto',
        'referencia',
        'wci',
        'activo',
    ];

    public function pagable()
    {
        return $this->morphTo();
    }
}
