<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';    
    protected $fillable = [
        'name',
        'last_name',
        'full_name',
        'telefono',
        'direccion',
        'email',
        'tipo_cliente',
        'comentario',
        'ejecutivo_id',
        'dias_credito',
        'limite_credito',
        'wci',
        'activo',
    ];

    // Relación con Documentos
    public function documentos()
    {
        return $this->hasMany(Documento::class, 'cliente_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }

    public function garantias()
    {
        return $this->hasMany(Garantia::class, 'cliente_id');
    }

    public function ventaCreditos()
    {
        return $this->hasMany(VentaCredito::class, 'cliente_id', 'id');
    }

    public function anticiposApartados()
    {
        return $this->hasMany(AnticipoApartado::class, 'cliente_id', 'id');
    }
}
