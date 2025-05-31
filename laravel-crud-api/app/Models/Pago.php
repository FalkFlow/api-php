<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'buy_order',
        'amount',
        'status',
        'payment_type',
        'card_number',
        'authorization_code',
        'response_code',
        'transaction_date',
        'session_id',
    ];
    
}

