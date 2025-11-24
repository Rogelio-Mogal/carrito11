<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDevoluciones extends Model
{
    use HasFactory;

    protected $table = 'venta_devoluciones';    
    protected $fillable = [
        'venta_id',
        'venta_detalle_id',
        'nota_credito_id',
        'venta_aplicada_id',
        'cantidad',
        'monto',
        'motivo',
    ];

    public function detalle()
    {
        return $this->belongsTo(VentaDetalle::class, 'venta_detalle_id');
    }

    public function notaCredito()
    {
        return $this->belongsTo(NotaCredito::class, 'nota_credito_id','id');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }  
    
    public function ventaAplicada()
    {
        return $this->belongsTo(Venta::class, 'venta_aplicada_id');
    }

    public function ventaAplicadas()
    {
        return $this->hasMany(VentaDevoluciones::class, 'nota_credito_id', 'id')
                    ->with('ventaAplicada'); // cada devolución ya carga su venta aplicada
    }

}
