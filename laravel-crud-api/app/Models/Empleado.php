<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empleado extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'empleado';

    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'direccion',
        'cargo',
        'sueldo',
        'estado'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
