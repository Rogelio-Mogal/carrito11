<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaCredito extends Model
{
    use HasFactory;

    protected $table = 'nota_creditos';    
    protected $fillable = [
        'folio',
        'notable_id',
        'notable_type',
        'cliente_id',
        'monto',
        'motivo',
        'tipo',
        'estado',
        'activo',
    ];

    // GENERA EL FOLIO EN AUTOMÁTICO
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($nota) {
            $year = now()->year;

            // Buscar el último folio con lockForUpdate
            $ultimoFolio = self::whereYear('created_at', $year)
                ->lockForUpdate()
                ->orderByDesc('id')
                ->value('folio');

            $ultimoNumero = 0;
            if ($ultimoFolio && preg_match('/NOTA-CREDITO-(\d+)-' . $year . '/', $ultimoFolio, $matches)) {
                $ultimoNumero = (int) $matches[1];
            }

            $nuevoNumero = $ultimoNumero + 1;

            // Con ceros a la izquierda (7 dígitos)
            $nota->folio = sprintf("NOTA-CREDITO-%05d-%d", $nuevoNumero, $year);
        });
    }

    protected static function boot2()
    {
        parent::boot();

        static::creating(function ($nota) {
            $year = now()->year;

            // Obtener el último consecutivo del año actual
            $ultimoFolio = self::whereYear('created_at', $year)
                ->pluck('folio') // Trae todos los folios del año
                ->map(function($f) use ($year) {
                    // Extraer solo el número central del folio
                    if (preg_match('/NOTA-CREDITO-(\d+)-' . $year . '/', $f, $matches)) {
                        return (int)$matches[1];
                    }
                    return 0;
                })
                ->max(); // Tomar el mayor consecutivo existente

            $consecutivo = $ultimoFolio ? $ultimoFolio + 1 : 1;

            $nota->folio = "NOTA-CREDITO-{$consecutivo}-{$year}";
        });
    }

    /*protected static function boot()
    {
        parent::boot();

        static::creating(function ($nota) {
            $year = now()->year;

            // Obtiene el consecutivo del año actual
            $ultimoFolio = self::whereYear('created_at', $year)->max('folio');

            if ($ultimoFolio) {
                // extrae el número central del formato NOTA-CREDITO-{num}-{año}
                preg_match('/NOTA-CREDITO-(\d+)-' . $year . '/', $ultimoFolio, $matches);
                $consecutivo = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
            } else {
                $consecutivo = 1;
            }

            $nota->folio = "NOTA-CREDITO-{$consecutivo}-{$year}";
        });
    }
    */

    // RELACIONES

    public function notable()
    {
        return $this->morphTo();
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function devoluciones()
    {
        return $this->hasMany(VentaDevoluciones::class, 'nota_credito_id','id');
    }

    public function ventasAplicadas()
    {
        return $this->belongsToMany(
            Venta::class,
            'venta_devoluciones',   // tabla pivote
            'nota_credito_id',      // FK en pivote
            'venta_id'              // FK hacia ventas
        );
    }

    public function ventaAplicadas()
    {
        return $this->hasMany(VentaDevoluciones::class, 'nota_credito_id', 'id')
                    ->with('ventaAplicada'); // 👈 relación hacia la venta
    }

    public function ventaDevoluciones()
    {
        return $this->hasMany(VentaDevoluciones::class, 'nota_credito_id');
    }
}
