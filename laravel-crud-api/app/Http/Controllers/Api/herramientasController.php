<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Herramienta; 

class herramientasController extends Controller
{
    public function index()
    {
        $herramientas = Herramienta::all();
        return response()->json($herramientas);
    }

    public function store(Request $request)
    {
        return response()->json([
            'message' => 'Nose puede crear una herramienta de manera directa'
        ], 405);
    }

    public function update(Request $request, $id)
    {
        $herramienta = Herramienta::findOrFail($id);

        $validated = $request->validate([
            'recientes' => 'boolean',
            'oferta' => 'boolean',
        ]);

        $herramienta->update($validated);

        return response()->json([
            'message' => 'Herramienta updated successfully',
            'herramienta' => $herramienta,
            'status' => 'success'
        ], 200);
    }

    public function destroy($id)
    {
        $herramienta = Herramienta::findOrFail($id);
        $herramienta->delete();

        return response()->json([
            'message' => 'Herramienta deleted successfully',
            'status' => 'success'
        ], 200);
    }

    public function show($id)
    {
        $herramienta = Herramienta::with(['manual', 'material', 'seguridad'])->findOrFail($id);
        return response()->json($herramienta);
    }
}

