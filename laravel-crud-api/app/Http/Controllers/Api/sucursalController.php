<?php

namespace app\Http\Controllers\Api;

use App\Models\Sucursal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Manual;
use App\Models\Seguridad;
use App\Models\Sucursal as SucursalModel;

class sucursalController extends Controller
{

    // Mostrar sucursales por código de producto
    public function descontarStock(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:material,manual,seguridad',
            'codigo_producto' => 'required',
            'cantidad' => 'required|integer|min:1',
            'sucursal' => 'required|string'
        ]);

        $tipo = $request->input('tipo');
        $codigo = $request->input('codigo_producto');
        $cantidad = $request->input('cantidad');
        $sucursalNombre = ucwords(strtolower($request->input('sucursal')));

        // Determinar el modelo del producto
        $model = match ($tipo) {
            'material' => Material::class,
            'manual' => Manual::class,
            'seguridad' => Seguridad::class,
        };

        $producto = $model::where('codigo_producto', $codigo)->first();

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        if ($producto->stock < $cantidad) {
            return response()->json(['message' => 'Stock insuficiente en inventario general'], 400);
        }

        // Restar stock al producto
        $producto->stock -= $cantidad;
        $producto->save();

        // Buscar o crear el registro en sucursales
        $sucursal = SucursalModel::firstOrNew([
            'codigo_producto' => $codigo,
            'nombre' => $sucursalNombre
        ]);

        // Si es nuevo, inicializamos la cantidad
        if (is_null($sucursal->cantidad)) {
            $sucursal->cantidad = 0;
        }

        // Sumar el stock a la sucursal
        $sucursal->cantidad += $cantidad;
        $sucursal->save();

        return response()->json([
            'message' => 'Stock transferido correctamente',
            'stock_general' => $producto->stock,
            'stock_sucursal' => $sucursal->cantidad,
            'sucursal' => $sucursalNombre
        ]);
    } 

    public function verStockSucursal($nombre)
    {
        $productos = Sucursal::where('nombre', $nombre)->get();

        if ($productos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron productos en esta sucursal'], 404);
        }

        $respuesta = [
            'sucursal' => $nombre,
            'productos' => $productos->map(function ($item) {
                return [
                    'codigo_producto' => $item->codigo_producto,
                    'stock' => $item->cantidad
                ];
            })
        ];

        return response()->json($respuesta);
    }

}