<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;
    protected $table = 'documentos';    
    protected $fillable = [
        'cliente_id',
        'tipo',
        'tipo_precio',
        'cliente',
        'direccion',
        'fecha',
        'total',
        'nota_general',
        'estado',
        'wci',
        'activo',
    ];

    // Relación con Cliente
    public function clienteDocumento()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Relación con DocumentoDetalles
    public function detallesDocumentos()
    {
        return $this->hasMany(DocumentoDetalle::class, 'documento_id');
    }
}
