<?php

namespace App\Http\Controllers;

use App\Models\TaxiOrder;
use App\Models\Client;

class HistoryController extends Controller {
    
    public function show($phone) {
        $client_id = Client::query()->where('phone', $phone)->first();
        
        $orders = TaxiOrder::query()
            ->where('client_id', $client_id)
            ->orderByDesc('id')
            ->get();
        
        return success($orders);
    }
}
