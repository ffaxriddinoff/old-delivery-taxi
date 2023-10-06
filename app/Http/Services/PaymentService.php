<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Payment;


class PaymentService {
    public static function payByCard($token, $amount, $type, $id) {
        $response = Http::withHeaders([
            'Auth' => Payment::authHeader(),
            'Accept' => 'application/json'
        ])->post(env('PAYMENT_MERCHANT_URL'), [
            'service_id' => env('SERVICE_ID'),
            "card_token" => $token,
            "amount" => $amount,
            "transaction_parameter" => $type . "_" . $id
        ]);

        $data = $response->json();

        if ($data['error_code'] == -21){
            return false;
        }
        return $data['payment_status'] >= 0;
    }
}
