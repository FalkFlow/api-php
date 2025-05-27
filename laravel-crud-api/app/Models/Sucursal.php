<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';

    protected $fillable = [
        'nombre',
        'codigo_producto',
        'cantidad'
    ];

    public function herramienta()
    {
        return $this->belongsTo(Herramienta::class, 'codigo_producto', 'codigo_producto');
    }
}


