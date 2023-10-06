<?php

use App\Models\Driver;
use App\Models\Order;

if ($transaction->transactionable_type == Order::class) {
    $model = Order::query()->find($transaction->transactionable_id ?? 0);
    if ($model) {
        $model->update(['status' => Order::PAID]);
    }
} else {
    $model = Driver::query()->find($transaction->transactionable_id ?? 0);
    if ($model) {
        $model->increment('sum', (int)$transaction->amount);
    }
}
