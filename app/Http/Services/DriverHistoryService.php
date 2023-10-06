<?php


namespace App\Http\Services;


use App\Models\History;
use Illuminate\Support\Facades\DB;

class DriverHistoryService
{
    use Response;

    public function daily($driver) {
        $history = History::query()->where('driver_id', $driver)
            ->whereDay('created_at', '=', now()->day)
            ->orderByDesc('created_at')->count();

        return $this->success($history);
    }

    public function clients($driver) {
        $clients = History::query()->where('driver_id', $driver)->orderByDesc('created_at')->get();

        return $this->success($clients);
    }

    public function profit($driver) {
        return History::query()->where('driver_id', $driver)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('sum(fare) as benefit'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
    }
}
