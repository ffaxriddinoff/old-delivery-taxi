<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;


class MessageService {

    public function refreshToken() {
        $response = Http::patch("notify.eskiz.uz/api/auth/refresh")->json();
        return $response['data']['token'];
    }

    public function sendMessage($phone, $message) {
        $token = Cache::get('token');
        if (!$token) {
            $token = $this->getToken();
            Cache::put('token', $token);
        }

        $res = Http::withToken($token)->post("notify.eskiz.uz/api/message/sms/send", [
            'mobile_phone' => "998$phone",
            'message' => $message,
            'from' => '4546',
            'callback_url' => route('receive_status')
        ]);

        if ($res->status() >= 400) {
            $token = $this->getToken();
            Cache::put('token', $token);
        }
    }


    public function getToken() {
        $response = Http::post("notify.eskiz.uz/api/auth/login", [
            'email' => env('SMS_SERVICE_EMAIL'),
            'password' => env('SMS_SERVICE_PASSWORD'),
        ])->json();

        return $response['data']['token'];
    }

    public function receive(Request $request) {// EXPIRED
        if ($request->get('status') != "DELIVRD")
            Cache::put('token', $this->getToken());
    }
}
