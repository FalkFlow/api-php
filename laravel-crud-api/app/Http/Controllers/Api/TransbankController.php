<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Transbank\Webpay\WebpayPlus\Transaction;
use Transbank\Webpay\Options;
use App\Models\Pago;

class TransbankController extends Controller
{
    public function createTransaction(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
        ]);

        $options = new Options(
            '597055555532',
            'fake',
            Options::ENVIRONMENT_INTEGRATION
        );

        $transaction = new Transaction($options);

        $buyOrder = uniqid('order_');
        $sessionId = uniqid('session_');
        $amount = $request->amount;
        $returnUrl = route('transbank.callback'); // POST callback

        $response = $transaction->create($buyOrder, $sessionId, $amount, $returnUrl);

        return response()->json([
            'url' => $response->getUrl(),
            'token' => $response->getToken(),
        ]);
    }

    public function commitTransaction(Request $request)
    {
        $token = $request->input('token_ws');

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado.'], 400);
        }

        $options = new Options(
            '597055555532',
            'fake',
            Options::ENVIRONMENT_INTEGRATION
        );
        $transaction = new Transaction($options);
        $response = $transaction->commit($token);

        Pago::create([
        'buy_order' => $response->getBuyOrder(),
            'amount' => $response->getAmount(),
            'status' => $response->getStatus(),
            'payment_type' => $response->getPaymentTypeCode(),
        ]);


        return response()->json([
            'status' => $response->getStatus(),
            'buy_order' => $response->getBuyOrder(),
            'amount' => $response->getAmount(),
            'payment_type' => $response->getPaymentTypeCode(),
            'response' => $response,
        ]);
    }
}


