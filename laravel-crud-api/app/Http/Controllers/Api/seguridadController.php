<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seguridad;

class seguridadController extends Controller
{
    
    public function index()
    {
        $material = Seguridad::all();
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

        if (Seguridad::where('codigo_producto', $validated['codigo_producto'])->exists()){
            return response()->json([
                'message' => 'Implemento de seguridad ya existe',
                'status' => 'error'
            ],409);
        }

        $seguridad = Seguridad::create($validated);

        return response()->json([
            'message'=> 'Implemento de seguridad creado con exito',
            'material' => $seguridad,
            'status'=> 'success'
        ], 201);
    }

    public function show($id)
    {
        $seguridad = Seguridad::findOrFail($id);
        return response()->json($seguridad);
    }

    public function update(Request $request, $id)
    {
        $seguridad = Seguridad::findOrFail($id);

        $validated = $request->validate([
            'marca'=> 'sometimes|string|max:225',
            'codigo'=> 'sometimes|string|max:225',
            'nombre'=> 'sometimes|string|max:225',
            'precio'=> 'sometimes|numeric',
        ]);

        $seguridad -> update($validated);

        return response()->json([
            'message'=> 'Herramienta de seguridad actualizado',
            'material' => $seguridad,
            'status'=> 'success'
        ], 200);
    }

    public function destroy($id)
    {
        $seguridad = Seguridad::findOrFail($id);
        $seguridad -> delete();

        return response()->json([
            'message'=> 'Herramienta de seguridad destruida',
            'status' => 'success'
        ], 200);
    }
}
