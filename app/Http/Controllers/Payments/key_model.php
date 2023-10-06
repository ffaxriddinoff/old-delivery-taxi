<?php
    // $key -> merchant_trans_id
    $param = explode("_", $key);
    if ($param[0] == 'order') {
        return App\Models\Order::query()->find($param[1] ?? 0);
    } else {
        return App\Models\Driver::query()->find($param[1] ?? 0);
    }


