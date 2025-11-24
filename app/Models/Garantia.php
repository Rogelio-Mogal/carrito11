<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garantia extends Model
{
    use HasFactory;
    protected $table = 'garantias';    
    protected $fillable = [
        'folio',
        'fecha',
        'cliente_id',
        'tel1',
        'tel2',
        'producto_id',
        'producto_personalizado',
        'cantidad',
        'precio_producto',
        'importe',
        'venta_id',
        'folio_venta_text',
        'descripcion_fallo',
        'informacion_adicional',
        'solucion',
        'nota_solucion',
        'destino_producto',
        'fecha_destino',
        'estatus',
        'fecha_registro',
        'fecha_cierre',
        'wci'
    ];

    public function notaCreditos()
    {
        return $this->morphMany(NotaCredito::class, 'notable');
    }

    // Relación con el cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    // Relación con la venta (opcional)
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }
}
