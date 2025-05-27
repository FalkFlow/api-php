<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Herramienta;


class Manual extends Model
{
    use HasFactory;

    protected $table = "manual";

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
        static::created(function ($manual){
            if(!Herramienta::where('codigo_producto', $manual->codigo_producto)->exists()){
                Herramienta::create([
                    'codigo_producto' => $manual->codigo_producto,
                    'recientes' => false,
                    'oferta' => false,
                ]);
            }
        });
    } 
}
