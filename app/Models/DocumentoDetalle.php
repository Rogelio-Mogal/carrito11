<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoDetalle extends Model
{
    use HasFactory;
    protected $table = 'documento_detalles';    
    protected $fillable = [
        'documento_id',
        'producto_id',
        'producto_comun',
        'cantidad',
        'precio',
        'precio_publico',
        'precio_medio_mayoreo',
        'precio_mayoreo',
        'importe',
        'nota',
        'activo',
    ];

    // Relación con Documento
    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }

    // Relación con Producto
    public function productoDocumento()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
