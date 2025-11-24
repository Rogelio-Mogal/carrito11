<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoGasto extends Model
{
    use HasFactory;
    protected $table = 'tipo_gastos';    
    protected $fillable = [
        'tipo_gasto',
        'activo',
    ];

    // Relación con el modelo Gasto
    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'tipo_gasto_id');
    }
}
