<?php

namespace app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class divisasController extends Controller
{
    public function convertir(Request $request)
    {
        $request->validate([
            'divisa' => 'required|string|size:3', // JPY, USD, EUR, etc.
            'monto' => 'required|numeric',
        ]);

        $divisaDestino = strtoupper($request->divisa);
        $montoCLP = $request->monto;
        $apiKey = "c37d76d3ff2cbf0f414897d0d5626af8"; // desde .env

        $url = "https://api.exchangerate.host/convert";

        $response = Http::get($url, [
            'access_key' => $apiKey,
            'from' => 'CLP',
            'to' => $divisaDestino,
            'amount' => $montoCLP
        ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'No se pudo conectar con el servicio de divisas'], 500);
        }

        $data = $response->json();

        if (!isset($data['result'])) {
            return response()->json(['error' => 'Conversión inválida o access_key incorrecta'], 400);
        }

        return response()->json([
            'de' => 'CLP',
            'a' => $divisaDestino,
            'monto_original' => $montoCLP,
            'monto_convertido' => round($data['result'], 2),
            'tasa' => $data['info']['rate'] ?? null,
        ]);
    }
}
