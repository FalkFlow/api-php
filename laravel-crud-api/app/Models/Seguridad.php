<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seguridad extends Model
{

    use HasFactory;

    protected $table = "seguridad";

    protected $fillable = [
        "codigo_producto",
        "marca",
        "codigo",
        "nombre",
        "precio"
    ];
    public function herramienta()
    {
        return $this->belongsTo(Herramienta::class, 'codigo_producto', 'codigo_producto');
    }

    protected static function booted()
    {
        static::created(function ($seguridad){
            if(!Herramienta::where('codigo_producto', $seguridad->codigo_producto)->exists()){
                Herramienta::create([
                    'codigo_producto' => $seguridad->codigo_producto,
                    'recientes' => false,
                    'oferta' => false,
                ]);
            }
        });
    }
}
