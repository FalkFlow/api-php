<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;
use App\Models\Empleado;

class MultiAuth
{
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        $token = $request->bearerToken();

        if(!$token){
            return response()->json(['message'=>'Token no proporcionado'], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if(!$accessToken){
            return response() -> json(['message'=>'Token Invalido'],401);
        }

        $tokenable = $accessToken->tokenable;

        if($tokenable instanceof User || $tokenable instanceof Empleado){
            $request -> setUserResolver(fn () => $tokenable);
            return $next($request);
        }

        return response() -> json(['message'=>'Usuario no encontrado'], 403);
    }
}
