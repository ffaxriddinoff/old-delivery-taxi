<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\TaxiOrder;
use App\Models\History;


class StatisticService extends CRUDService {

    public function income($district_id) {
        $earnings = History::query()->where('order_type', TaxiOrder::class)
            ->whereMonth('created_at', '=', now()->month)
            ->select(DB::raw('Day(created_at) as day'), DB::raw('sum(service_fee) as data'))
            ->groupBy('day')
            ->byDistrict($district_id)
            ->get();

        return $this->success($this->dataToMonthlyList($earnings));
    }

    public function clients($district_id) {
        $clients = History::query()->where('order_type', TaxiOrder::class)
            ->whereMonth('created_at', '=', now()->month)
            ->select(DB::raw('Day(created_at) as day'), DB::raw('count(*) as data'))
            ->groupBy('day')
            ->byDistrict($district_id)
            ->get();

        return $this->success($this->dataToMonthlyList($clients));
    }

    public function orders($district_id) {
        $orders = History::query()->where('order_type', Order::class)
            ->whereMonth('created_at', '=', now()->month)
            ->select(DB::raw('Day(created_at) as day'), DB::raw('count(*) as data'))
            ->groupBy('day')
            ->byDistrict($district_id)
            ->get();

        return $this->success($this->dataToMonthlyList($orders));
    }

    private function dataToMonthlyList($data) {
        // Days count of month
        $count = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        $res = array_fill(1, $count, 0);
        foreach ($data as $d) {
            $res[$d->day] = $d->data;
        }

        return $res;
    }
}
