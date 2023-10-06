<?php
    // $model, $amount
    if ($model instanceof \App\Models\Order) {
        return $amount == $model->total_price && $model->paid == 0;
    } else {
        return true;
    }
