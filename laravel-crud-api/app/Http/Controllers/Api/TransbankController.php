<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Transbank\Webpay\WebpayPlus\Transaction;
use Transbank\Webpay\WebpayPlus;
use App\Models\Pago;

class TransbankController extends Controller
{
    protected $transaction;

    public function __construct()
    {
        if(config('app.debug')){
            WebpayPlus::configureForTesting();
        } else {
            $cc = "";
            $apiKey = "";
            WebpayPlus::configureForProduction($cc, $apiKey);
        }

        $this->transaction = new Transaction();
    }
    public function createTransaction(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50',
        ]);

        try {
            $buyOrder = uniqid('order_');
            $sessionId = uniqid('session_');
            $amount = $request->amount;
            $returnUrl = 'https://quick-pens-shake.loca.lt/api/transbank/callback';

            $response = $this->transaction->create($buyOrder, $sessionId, $amount, $returnUrl);

            return response()->json([
                'url' => $response->getUrl(),
                'token' => $response->getToken(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Transacción fallida',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function commitTransaction(Request $request)
    {
        $token = $request->get('token_ws');

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado.'], 400);
        }

        $response = $this->transaction->commit($token);

        return response()->json([
            'status' => $response->getStatus(),
            'buy_order' => $response->getBuyOrder(),
            'amount' => $response->getAmount(),
            'payment_type' => $response->getPaymentTypeCode(),
            'response' => $response,
        ]);
    }

    public function callback(Request $request)
    {
        $token = $request->input('token_ws');

        if (!$token) {
            return response('Token no proporcionado', 400);
        }

        $response = $this->transaction->commit($token);

        if ($response->getStatus() !== 'AUTHORIZED') {
            return response()->json([
                'error' => 'Transacción no autorizada',
                'status' => $response->getStatus(),
            ], 400);
        }else{
            $pago = new Pago();
            $pago->buy_order = $response->getBuyOrder();
            $pago->amount = $response->getAmount();
            $pago->status = $response->getStatus();
            $pago->payment_type = $response->getPaymentTypeCode();
            $pago->card_number = $response->getCardNumber();
            $pago->authorization_code = $response->getAuthorizationCode();
            $pago->response_code = $response->getResponseCode();
            $pago->transaction_date = $response->getTransactionDate();
            $pago->session_id = $response->getSessionId();
            $pago->save();
        }

        return response()->json([
            'message' => 'Transacción exitosa',
            'status' => $response->getStatus(),
            'buy_order' => $response->getBuyOrder(),
            'amount' => $response->getAmount(),
            'payment_type' => $response->getPaymentTypeCode(),
            'card_number' => $response->getCardNumber(),
            'authorization_code' => $response->getAuthorizationCode(),
            'response_code' => $response->getResponseCode(),
            'transaction_date' => $response->getTransactionDate(),
            'session_id' => $response->getSessionId(),
        ], 200);
    }
}






