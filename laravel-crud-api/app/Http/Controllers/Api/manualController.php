<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manual; // Assuming you have a Manual model

class manualController extends Controller
{
    public function index()
    {
        $manuales = Manual::all();
        return response()->json($manuales);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo_producto' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
        ]);

        if (Manual::where('codigo_producto', $validated['codigo_producto'])->exists()) {
            return response()->json([
                'message' => 'Herramienta Manual ya existe',
                'status' => 'error'
            ], 409);
        }

        $manual = Manual::create($validated);

        return response()->json([
            'message' => 'Herramienta Manual creado con exito',
            'manual' => $manual,
            'status' => 'success'
        ], 201);
    }

    public function show($codigo_producto)
    {
        $manual = Manual::where('codigo_producto', $codigo_producto)->firstOrFail();
        return response()->json($manual);
    }

    public function update(Request $request, $codigo_producto)
    {
        $manual = Manual::where('codigo_producto', $codigo_producto)->firstOrFail();

        $validated = $request->validate([
            'marca' => 'sometimes|string|max:255',
            'codigo' => 'sometimes|string|max:255',
            'nombre' => 'sometimes|string|max:255',
            'precio' => 'sometimes|numeric',
        ]);

        $manual->update($validated);

        return response()->json([
            'message' => 'Herramienta Manual actualizada',
            'manual' => $manual,
            'status' => 'success'
        ], 200);
    }

    public function destroy($codigo_producto)
    {
        $manual = Manual::where('codigo_producto', $codigo_producto)->firstOrFail();
        $manual->delete();

        return response()->json([
            'message' => 'Herramienta Manual destruido',
            'status' => 'success'
        ], 200);
    }
    
}
