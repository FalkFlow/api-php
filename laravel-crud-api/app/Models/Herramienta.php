<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Herramienta extends Model
{
    use HasFactory;

    protected $table = "herramienta";

    protected $fillable = [
        "codigo_producto",
        "recientes",
        "oferta"
        ];


    public function manual()
    {
        return $this->belongsTo(Manual::class, 'codigo_producto', 'codigo_producto');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'codigo_producto', 'codigo_producto');
    }

    public function seguridad()
    {
        return $this->belongsTo(Seguridad::class, 'codigo_producto', 'codigo_producto');
    }

    public function sucursales()
    {
        return $this->hasMany(Sucursal::class, 'codigo_producto', 'codigo_producto');
    }
}
