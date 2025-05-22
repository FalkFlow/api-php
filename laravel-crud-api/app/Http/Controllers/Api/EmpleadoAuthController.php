<?php

namespace App\Http\Controllers\Api;

use App\Models\Empleado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmpleadoAuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:empleado,email',
            'password' => 'required|string|confirmed',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
            'cargo' => 'required|string',
            'sueldo' => 'required|numeric',
            'estado' => 'required|string'
        ]);

        $empleado = Empleado::create([
            ...$fields,
            'password' => bcrypt($fields['password']),
        ]);

        $token = $empleado->createToken('empleado-token')->plainTextToken;

        return response([
            'empleado' => $empleado,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $empleado = Empleado::where('email', $fields['email'])->first();

        if (! $empleado || ! Hash::check($fields['password'], $empleado->password)) {
            return response([
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }

        $token = $empleado->createToken('empleado-token')->plainTextToken;

        return response([
            'empleado' => $empleado,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Sesión cerrada'
        ]);
    }
}

