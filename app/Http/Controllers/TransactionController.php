<?php

namespace App\Http\Controllers;

use App\Models\DriverTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        return $this->success(
            DriverTransaction::with('user:id,name,surname', 'driver:id,name,surname')
                ->byDistrict($request->get('address'))
                ->get()
        );
    }
}
