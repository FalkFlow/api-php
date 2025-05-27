<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{

    use HasFactory;

    protected $table = "material";
    protected $fillable = [
        "codigo_producto",
        "marca",
        "codigo",
        "nombre",
        "precio",
        "stock"
    ];
    public function herramienta()
    {
        return $this->hasMany(Herramienta::class, 'codigo_producto', 'codigo_producto');
    }

    protected static function booted()
    {
        static::created(function ($material){
            if(!Herramienta::where('codigo_producto', $material->codigo_producto)->exists()){
                Herramienta::create([
                    'codigo_producto' => $material->codigo_producto,
                    'recientes' => false,
                    'oferta' => false,
                ]);
            }
        });
    }
}
