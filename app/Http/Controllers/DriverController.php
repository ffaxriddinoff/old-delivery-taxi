<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Services\DriverHistoryService;
use App\Http\Services\DriverOrderService;
use App\Http\Requests\DriverOrderRequest;
use App\Http\Requests\DriverRequest;
use App\Http\Requests\HistoryRequest;
use App\Http\Requests\LocationRequest;
use App\Http\Services\DriverService;
use App\Models\Car;
use App\Models\Driver;


class DriverController extends Controller {
    /**
     * @var DriverService
     */
    private DriverService $service;
    /**
     * @var DriverOrderService
     */
    private DriverOrderService $driverOrderService;
    /**
     * @var DriverHistoryService
     */
    private DriverHistoryService $driverHistoryService;

    public function __construct(DriverService $service, DriverOrderService $driverOrderService, DriverHistoryService $driverHistoryService) {
        $this->service = $service;
        $this->driverOrderService = $driverOrderService;
        $this->driverHistoryService = $driverHistoryService;
    }

    public function index(Request $request) {
        return $this->success(Driver::with('car')->byDistrict($request->get('address'))->get());
    }

    public function store(DriverRequest $request) {
        $data = $request->validated();
        $data['district_id'] = District::query()->where('name', $data['address'])->first()->id;
        return $this->service->store($data);
    }

    public function show($driver) {
        $driver = Driver::with('car')->find($driver);
        return $this->success(['driver' => $driver]);
    }

    public function getTariff($driver) {
        return Car::query()->where('driver_id', $driver)->firstOrFail()->tariff;
    }

    public function addSum(Request $request, Driver $driver) {
        return $this->service->addSum($request, $driver);
    }

    public function pay(Request $request, Driver $driver) {
        return $this->service->selfPay($request, $driver);
    }

    public function update(Request $request, Driver $driver) {
        return $this->service->update($driver, $this->validated($request));
    }

    public function destroy(Driver $driver) {
        return $this->service->destroy($driver);
    }

    public function activate(Request $request, Driver $driver) {
        return $this->service->activate($request->get('status'), $driver);
    }

    public function check(LocationRequest $request) {
        return $this->service->check($request->validated());
    }

    public function mergeOrder(DriverOrderRequest $request) {
        return $this->driverOrderService->mergeOrder($request);
    }

    public function history(HistoryRequest $request, Driver $driver) {
        return $this->driverOrderService->writeHistory($request, $driver);
    }

    public function orders() {
        return $this->driverOrderService->ordersInAir();
    }

    public function daily($driver) {
        return $this->driverHistoryService->daily($driver);
    }

    /* Full history of clients */
    public function clients($driver) {
        return $this->driverHistoryService->clients($driver);
    }

    public function profit($driver) {
        return $this->driverHistoryService->profit($driver);
    }

    public function setFirebaseToken(Request $request, Driver $driver)
    {
        $driver->update(['firebase_token' => $request->get('firebase_token')]);
        return $this->success($driver);
    }
}
