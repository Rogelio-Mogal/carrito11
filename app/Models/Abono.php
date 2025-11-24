<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    use HasFactory;

    protected $table = 'abonos';    
    protected $fillable = [
        'folio',
        'fecha',
        'abonable_id',
        'abonable_type',
        'cliente_id',
        'monto',                  // monto total del abono
        'saldo_global_antes',     // saldo total antes del abono
        'saldo_global_despues',   // saldo total después del abono
        'referencia',
        'activo',
        'wci',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleAbono::class, 'abono_id');
    }

    public function abonable()
    {
        return $this->morphTo(); // puede ser Venta, Apartado, etc.
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pagos()
    {
        return $this->morphMany(TipoPago::class, 'pagable');
    }
}
