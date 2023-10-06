<?php

namespace App\Http\Services;

use App\Jobs\VipLimitJob;
use App\Models\Driver;
use App\Models\DriverTransaction;


class DriverService extends CRUDService {

    public function __construct(Driver $model = null) {
        parent::__construct($model ?? new Driver());
    }

    public function get() {
        return $this->success(Driver::with('car')->get());
    }

    public function addSum($request, $driver) {
        $driver->sum += $request->sum;
        $tariff = $driver->car->tariff;
        if ($driver->sum >= $tariff->vip && $driver->vip == 0) {
            $driver->vip = 1;
            $driver->sum -= $tariff->vip;

            dispatch((new VipLimitJob($driver->id))->delay(now()->addMonth()));
        }

        $driver->update();

        try {
            DriverTransaction::query()->create([
                'user_id' => $request->user_id,
                'driver_id' => $driver->id,
                'amount' => $request->sum,
                'address' => $driver->address
            ]);
        } catch (\Exception $exception) {
            return $this->success([], $exception->getMessage());
        }

        return $this->success();
    }

    public function selfPay($request, $driver) {
        $success = PaymentService::payByCard($driver->card_token, $request->sum, 'driver', $driver->id);
        if ($success) {
            return $this->addSum($request, $driver);
        } else {
            return $this->fail();
        }
    }

    public function check($data) {
        list($latitude, $longitude, $tariff_id, $scope) = array_values($data);

        $drivers = Driver::query()->whereHas('car', function($query) use($tariff_id) {
            $query->where('status', 1);

            if ($tariff_id) {
                $query->where('tariff_id', $tariff_id);
            }
        })
            ->online()->statusActive()->active()
            ->whereRaw("haversine(latitude, longitude, $latitude, $longitude) <= $scope")
//                ->select("*", DB::raw("haversine(latitude, longitude, $latitude, $longitude) as distance"))
//                ->orderBy('distance')
            ->limit(15)
            ->get();

        $drivers->push(new Driver([
            'name' => "VIP"
        ]));

        return $this->success([
            'drivers' => $drivers
        ]);
    }

    public function activate($status, $driver) {
        $driver->car->update(['status' => $status]);
        return $this->success($driver);
    }
}
