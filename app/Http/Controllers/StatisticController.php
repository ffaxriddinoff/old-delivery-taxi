<?php

namespace App\Http\Controllers;

use App\Http\Services\StatisticService;
use Illuminate\Http\Request;


class StatisticController extends Controller {

    private StatisticService $service;

    public function __construct(StatisticService $service) {
        $this->service = $service;
    }

    public function income(Request $request) {
        return $this->service->income($request->get('district_id'));
    }

    public function clients(Request $request) {
        return $this->service->clients($request->get('district_id'));
    }

    public function orders(Request $request) {
        return $this->service->orders($request->get('district_id'));
    }
}
