<?php

namespace App\Http\Controllers;

use App\Http\Services\OrderService;
use App\Models\District;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Http\Requests\TaxiOrderRequest;
use App\Models\Order;
use App\Models\Client;
use App\Models\TaxiOrder;


class TaxiOrderController extends Controller {

    public function all(Request $request) {
        return $this->success([
            'orders' =>  TaxiOrder::query()
                ->with('client:id,phone', 'driver:id,name,surname', 'history')
                ->orderByDesc('id')
                ->byDistrict($request->get('district_id'))
                ->byDate($request->get('date'))
                ->get()
        ]);
    }

    public function getOrdersByStatus(Request $request) {
        return $this->success([
            'orders' => TaxiOrder::query()->with('client', 'driver')
                ->where('status', $request->status)
                ->orderByDesc('id')->paginate($request->get('perPage') ?? 10)
        ]);
    }

    public function getNewOrders() {
        return $this->success([
            'taxi_orders' => $this->addNumber(TaxiOrder::with('client')->newOrders()->get()),
            'delivery_orders' => $this->addNumber(Order::with('partner', 'client')->newOrders()->get())
        ]);
    }

    private function addNumber($orders) {
        foreach ($orders as $order) {
            $order->phone = $order->client->phone;
        }
        return $orders;
    }

    public function store(TaxiOrderRequest $request) {
        $data = $request->validated();
        $notification['topic_address'] = District::query()->find($data['district_id'])?->name ?? '';
        $client = Client::query()->firstOrCreate(
            ['phone' => $request->get('phone')],
            ['name' => $request->get('name')]
        );
        $data['client_id'] = $client->id;
        $order = TaxiOrder::query()->firstOrCreate(
            ['client_id' => $data['client_id'], 'status' => TaxiOrder::NEW], $data
        );
        $notification['order_id'] = $order->id;
        $notification['address'] = $order->address;
        $notification['latitude'] = $order->latitude;
        $notification['longitude'] = $order->longitude;

        if ($order->wasRecentlyCreated) {
            OrderService::sendFirebaseNotification($notification);
            return $this->success(['order' => $order]);
        }
        return $this->fail([], "Siz allaqachon buyurtma bergansiz");
    }

    public function share(Request $request) {
        $tokens = Driver::query()->whereIn('id', $request->get('drivers'))->pluck('firebase_token');
        foreach ($tokens as $token) {
            OrderService::shareOrder($token, $request->all());
        }
    }

    public function update(Request $request, TaxiOrder $order) {
        $order->update([
            'status' => $request->status,
            'driver_id' => 0
        ]);

        return $this->success($order);
    }
}
