<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atributo extends Model
{
    use HasFactory;

    protected $table = 'atributos';    
    protected $fillable = [
        'nombre',
        'tipo_campo',
        'opciones',
        'activo',
    ];

    protected $casts = [
        'opciones' => 'array', // Laravel convierte automáticamente JSON <-> array
    ];

    public function familias()
    {
        return $this->belongsToMany(ProductoCaracteristica::class, 'familia_atributos', 'atributo_id', 'familia_id');
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_atributo_valores')
                    ->withPivot('valor')
                    ->withTimestamps();
    }
}
