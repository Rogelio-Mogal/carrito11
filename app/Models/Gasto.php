<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;
    protected $table = 'gastos';    
    protected $fillable = [
        'tipo_gasto_id',
        'gasto',
        'activo',
    ];

    // Relación con el modelo TipoGasto
    public function tipoGasto()
    {
        return $this->belongsTo(TipoGasto::class, 'tipo_gasto_id');
    }

    // Relación con el modelo AsignarGasto
    public function asignarGastos()
    {
        return $this->hasMany(AsignarGasto::class, 'gasto_id');
    }
}
