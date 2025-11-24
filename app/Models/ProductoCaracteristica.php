<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class ProductoCaracteristica extends Model
{
    use HasFactory;

    protected $table = 'producto_caracteristicas';    
    protected $fillable = [
        'nombre',
        'tipo',
        'imagen',
        'img_thumb',
        'activo',
    ];

    // RELACION PARA MODULO PRECIOS
    public function precios()
    {
        return $this->hasMany(Precio::class, 'producto_caracteristica_id');
    }

    protected function nombre(): Attribute
    {
       /* return new Attribute(
            set: fn ($value) => strtoupper($value)
        );*/
        return Attribute::make(
            set: fn($value) => strtoupper($value),
            get: fn($value) => strtoupper($value)
        );
    }

    // Es un accesores, son mutadores
    /*protected function imagen(): Attribute
    {
        return new Attribute(
            get: function(){
                if($this->imagen){
                    // Verifico si comienza con https:// ó http://
                    if(substr($this->imagen, 0,8) === 'https://'){
                        return $this->imagen;
                    }

                    return Storage::url($this->imagen);
                }else{
                    return '';
                }
            }
        );
    }*/

    /*protected function imagen(): Attribute
    {
        return Attribute::make(
            get: function($value) {
                if ($value) {
                    // Verifico si comienza con https:// ó http://
                    if (substr($value, 0, 8) === 'https://') {
                        return $value;
                    }

                    return Storage::disk('s3')->url($value);
                } else {
                    return '';
                }
            }
        );
    }*/
    
    protected function imagen(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->getImagenUrl($value)
        );
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->getImagenUrl($this->img_thumb)
        );
    }

    private function getImagenUrl($value)
    {
        if ($value) {
            if (substr($value, 0, 8) === 'https://') {
                return $value;
            }
            return Storage::url($value);
            //return route('product.image', $this);
        } else {
            return 'https://pcserviciostc.com.mx/img/no_disponible.png';
        }
    }

    public function atributos()
    {
        return $this->belongsToMany(Atributo::class, 'familia_atributos', 'familia_id', 'atributo_id');
    }


}
