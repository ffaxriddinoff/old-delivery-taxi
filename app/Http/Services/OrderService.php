<?php

namespace App\Http\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class OrderService extends CRUDService
{
    public function __construct(Order $model = null)
    {
        parent::__construct($model ?? new Order());
    }

    public function changeStatus(Request $request, Order $order)
    {
        if ($request->status) {
            if ($request->status == Order::ACCEPT) {
                return $this->payOrder($order);
            }

            if ($order->payment_type == Order::SELF_PAYMENT) {
                $order->update(['status' => Order::SELF_TAKE]);
            } else {
                $order->update(['status' => $request->status]);
            }

            return $this->success($order);
        }

        return $this->fail([], 'Invalid status');
    }

    private function payOrder(Order $order)
    {
        $token = $order->customer->token;
        $success = PaymentService::payByCard($token, $order->total_price, 'order', $order->id);
        if ($success) {
            $order->update(['status' => Order::ACCEPT, 'paid' => Order::PAID]);
            return $this->success();
        }

        $order->update(['status' => Order::INVALID_PAYMENT_AMOUNT]);
        return $this->fail();
    }

    public static function sendFirebaseNotification($notification)
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . env('FCM_SERVER_KEY')
        ])->post('https://fcm.googleapis.com/fcm/send',
            [
                "to" => '/topics/' . $notification['topic_address'],
                "data" => $notification
            ]
        )->body();
    }

    public static function shareOrder($firebase_token, $data)
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . env('FCM_SERVER_KEY')
        ])->post('https://fcm.googleapis.com/fcm/send',
            [
                "to" => $firebase_token,
                "data" => $data
            ]
        )->body();
    }
}
