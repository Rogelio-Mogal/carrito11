<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Venta extends Model
{
    use HasFactory;
    protected $table = 'ventas';    
    protected $fillable = [
        'user_id',
        'cliente_id',
        'folio',
        'fecha',
        'total',
        'monto_credito',
        'monto_recibido',
        'cambio',
        'tipo_venta',
        'activo',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // RELACIONES
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class, 'venta_id');
    }

    public function pagos()
    {
        return $this->morphMany(TipoPago::class, 'pagable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function credito() {
        return $this->hasOne(VentaCredito::class, 'venta_id');
    }

    public function devoluciones()
    {
        return $this->hasMany(VentaDevoluciones::class, 'venta_id');
    }

    public function notaCreditos()
    {
        //return $this->hasMany(NotaCredito::class, 'venta_id');
        return $this->morphMany(NotaCredito::class, 'notable');
    }

    public function abonos()
    {
        return $this->morphMany(Abono::class, 'abonable');
    }

    public function garantias()
    {
        return $this->hasMany(Garantia::class, 'venta_id');
    }

    public function notasCreditoAplicadas()
    {
        return $this->hasMany(VentaDevoluciones::class, 'venta_aplicada_id');
    }

    public function notaCreditoAsociada()
    {
        $ventaId = $this->id;

        return NotaCredito::where(function ($q) use ($ventaId) {
            // Caso 1: esta venta generó la nota
            $q->where('notable_type', self::class)
            ->where('notable_id', $ventaId);
        })->orWhereHas('ventaDevoluciones', function ($q) use ($ventaId) {
            // Caso 2: esta venta aplicó la nota
            $q->where('venta_aplicada_id', $ventaId);
        })->first();
    }

    public function anticipos()
    {
        return $this->hasMany(AnticipoApartado::class);
    }

}
