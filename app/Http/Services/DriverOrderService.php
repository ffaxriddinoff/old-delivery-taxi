<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Cache;
use App\Jobs\OrderMessageJob;
use App\Models\Driver;
use App\Models\Order;
use App\Models\TaxiOrder;


class DriverOrderService {
    use Response;

    private MessageService $service;

    public function __construct(MessageService $service) {
        $this->service = $service;
    }

    public function mergeOrder($request) {
        $data = $request->validated();
        if ($data['type'] == 1) {
            $order = TaxiOrder::query();
            $key = "client";
        } else {
            $order = Order::query();
            $key = "customer";
        }

        if (!Driver::query()->find($data['driver_id'])) return $this->fail();

        $order = $order->where('id', $data['order_id'])->where('driver_id', 0)->firstOrFail();
        $order->fill($data);
        $order->update();
        $order['phone'] = $order->$key->phone;

        $this->sendReceiveMessage($order['phone'], $data['driver_id']);
        return $this->success(['order' => $order]);
    }

    public function sendReceiveMessage($phone, $driver_id) {
        $car = Driver::query()->findOrFail($driver_id)->car;
        $car->color = strtolower($car->color);
        $model = strtoupper($car->type->model);
        $message = "Taxi 1420! Hurmatli mijoz sizga $car->number raqamli $car->color, $model mashinasi yuborildi";
        dispatch(new OrderMessageJob($phone, $message));
    }

    public function writeHistory($request, $driver) {
        $car = $driver->car;
        $tariff = $car->tariff;
        $residue = $driver->sum - ($driver->vip ? 0 : $tariff->client);
        $driver->update(['status' => Driver::FREE, 'sum' => $residue]);

        $status = 0;
        $data = $request->validated();
        $data['driver_id'] = $driver->id;
        if ($data['order_type'] == Order::TYPE_ORDER) {
            $data['order_type'] = Order::class;
            $query = Order::query();
            if ($data['status'] == Order::ACCEPT) $status = 4;
        } else {
            $data['order_type'] = TaxiOrder::class;
            $query = TaxiOrder::query();
            if ($data['status'] == 1) $status = 2;
        }
        if ($data['status'] == 0) {
            $status = TaxiOrder::CANCELLED;
        }

        if ($data['order_id']) {
            $order = $query->findOrFail($data['order_id']);
            $order->update(['status' => $status]);

            if ($data['order_type'] == TaxiOrder::class)
                $this->sendFinishMessage($order->client->phone, $data['fare'], $data['luggage']);
        }

        $data['service_fee'] = $tariff->client;
        return $this->success($driver->writeHistory($data));
    }

    private function sendFinishMessage($phone, $fare, $hasLuggage) {
        $message = "Lider Taxi 1420! Yo\u{2018}l haqi $fare so\u{2018}m.\nLider Group xizmatidan foydalanganingizdan xursandmiz. Taklif va shikoyatlaringiz 1420 raqamida qabul qilinadi!";
        if ($hasLuggage) {
            $pay = Cache::get('pay');
            $message .= "\nYukingiz uchun $pay so\u{2018}m qo\u{2018}shildi.";
        }
        $this->service->sendMessage($phone, $message);
    }

    public function ordersInAir() {
        return TaxiOrder::query()->where('driver_id', Driver::FREE)->where('status', Driver::FREE)->get();
    }
}
