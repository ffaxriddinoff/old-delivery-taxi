<?php

namespace App\Http\Services;

trait Response {
    protected function success($data = [], $msg = 'Muvaffaqiyatli') {
        return response()->json([
            'success' => true,
            'data' => $data,
            'msg' => $msg
        ]);
    }

    protected function fail($errors = [], $msg = 'Muvaffaqiyatsiz') {
        return response()->json([
            'success' => false,
            'errors' => $errors,
            'msg' => $msg
        ]);
    }
}
