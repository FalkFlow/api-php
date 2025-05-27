<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function index()
    {
        $sucursales = Sucursal::with('herramienta')->get();
        return response()->json($sucursales);
    }

    // Mostrar sucursales por código de producto
    public function showByCodigo($codigo_producto)
    {
        $sucursales = Sucursal::where('codigo_producto', $codigo_producto)->with('herramienta')->get();

        if ($sucursales->isEmpty()) {
            return response()->json(['message' => 'No hay sucursales para este producto'], 404);
        }

        return response()->json($sucursales);
    }
}

