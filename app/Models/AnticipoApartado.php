<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnticipoApartado extends Model
{
    use HasFactory;

    protected $table = 'anticipo_apartados';    
    protected $fillable = [
        'fecha',
        'cliente_id',
        'tipo',
        'folio',
        'total',
        'debia',
        'abona',
        'debe',
        'estatus',
        'venta_id',
        'activo',
        'wci',
    ];

     // 🔗 Relación con detalles
    public function detalles()
    {
        return $this->hasMany(AnticipoApartadoDetalle::class, 'anticipo_apartado_id');
    }

    // 🔗 Relación con cliente (si existe la tabla clientes)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function abonos()
    {
        return $this->morphMany(Abono::class, 'abonable');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function pagos()
    {
        return $this->morphMany(TipoPago::class, 'pagable');
    }
    
}
