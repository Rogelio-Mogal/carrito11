<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamiliaAtributo extends Model
{
    use HasFactory;

    protected $table = 'familia_atributos';    
    protected $fillable = [
        'familia_id',
        'atributo_id',
    ];

    public function familia()
    {
        return $this->belongsTo(ProductoCaracteristica::class, 'familia_id');
    }

    public function atributo()
    {
        return $this->belongsTo(Atributo::class, 'atributo_id');
    }
}
