<?php

namespace App\Http\Services;

use App\Models\DriverTransaction;

class TransactionService extends CRUDService
{
    public function __construct(DriverTransaction $model = null) {
        parent::__construct($model ?? new DriverTransaction());
    }
}
