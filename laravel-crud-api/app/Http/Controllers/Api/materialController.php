<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;

class materialController extends Controller
{
    
    public function index()
    {
        $material = Material::all();
        return response()->json($material);
    }

    public function store (Request $request)
    {
        $validated = $request->validate([
            'codigo_producto' => 'required|string|max:225',
            'marca' => 'required|string|max:225',
            'codigo' => 'required|string|max:225',
            'nombre' => 'required|string|max:225',
            'precio' => 'required|numeric',
        ]);

        if (Material::where('codigo_producto', $validated['codigo_producto'])->exists()){
            return response()->json([
                'message' => 'Material de construcción ya existe',
                'status' => 'error'
            ],409);
        }

        $material = Material::create($validated);

        return response()->json([
            'message'=> 'Material de construcción creado con exito',
            'material' => $material,
            'status'=> 'success'
        ], 201);
    }

    public function show($codigo_producto)
    {
        $material = Material::where('codigo_producto', $codigo_producto)->firstOrFail();
        return response()->json($material);
    }

    public function update(Request $request, $codigo_producto)
    {
        $material = Material::where('codigo_producto', $codigo_producto)->firstOrFail();

        $validated = $request->validate([
            'marca'=> 'sometimes|string|max:225',
            'codigo'=> 'sometimes|string|max:225',
            'nombre'=> 'sometimes|string|max:225',
            'precio'=> 'sometimes|numeric',
        ]);

        $material -> update($validated);

        return response()->json([
            'message'=> 'Material de construcción actualizado',
            'material' => $material,
            'status'=> 'success'
        ], 200);
    }


    public function destroy($codigo_producto)
    {
        $material = Material::where('codigo_producto', $codigo_producto)->firstOrFail();
        $material -> delete();

        return response()->json([
            'message'=> 'Material de construcción destruido',
            'status' => 'success'
        ], 200);
    }
}
