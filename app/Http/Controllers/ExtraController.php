<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class ExtraController extends Controller {

    public function index() {
        return $this->success(
            Cache::get('pay') ?? 2000
        );
    }

    public function update(Request $request) {
        $request->validate([
            'pay' => 'required'
        ]);

        Cache::put('pay', $request->get('pay'));
        return $this->success();
    }
}
