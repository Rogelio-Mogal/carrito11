<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoCodigoAlterno extends Model
{
    use HasFactory;

    protected $table = 'producto_codigo_alternos';    
    protected $fillable = [
        'producto_id',
        'codigo_barra',
        'wci',
        'activo'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
