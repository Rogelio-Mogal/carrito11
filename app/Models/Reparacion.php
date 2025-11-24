<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reparacion extends Model
{
    use HasFactory;

    protected $table = 'reparaciones';

    protected $fillable = [
        'folio',
        'cliente_id',
        'fecha_ingreso',
        'fecha_listo',
        'fecha_entregado',
        'equipo',
        'tel1',
        'tel2',
        'fallo',
        'nota_adicional',
        'reparador_id',
        'estatus',
        'solucion',
        'recomendaciones',
        'nota_general',
        'costo_servicio',
        'finalizada',
        'venta_id',
        'activo',
        'wci',
    ];

    protected $casts = [
        'fecha_ingreso'   => 'datetime',
        'fecha_listo'     => 'datetime',
        'fecha_entregado' => 'datetime',
    ];

    /* --------------------
       RELACIONES
    -------------------- */

    // Cliente de la reparación
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Usuario asignado como reparador (puede ser interno o externo)
    public function reparador()
    {
        return $this->belongsTo(User::class, 'reparador_id');
    }

    // Relación con los productos usados en la reparación
    public function productos()
    {
        return $this->hasMany(ReparacionProducto::class);
    }

    // Relación con la venta generada
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    /* --------------------
       HELPERS
    -------------------- */

    // Total de piezas usadas (sumatoria)
    public function getTotalPiezasAttribute()
    {
        return $this->productos->sum('cantidad');
    }

    // Total de costo por piezas
    public function getTotalPiezasMontoAttribute()
    {
        return $this->productos->sum('total');
    }

    // Total general (piezas + servicio externo si aplica)
    public function getTotalGeneralAttribute()
    {
        return $this->productos->sum('total') + $this->costo_servicio;
    }
}
