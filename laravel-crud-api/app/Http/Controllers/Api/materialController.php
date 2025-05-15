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

    public function show($id)
    {
        $material = Material::findOrFail($id);
        return response()->json($material);
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

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

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material -> delete();

        return response()->json([
            'message'=> 'Material de construcción destruido',
            'status' => 'success'
        ], 200);
    }
}
