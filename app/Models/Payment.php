<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Payment extends Model {
    use HasFactory;

    public static function authHeader() {
        return env('MERCHANT_USER_ID') . ":" . (new static)->digest() . ":" . now()->getTimestamp();
    }

    public function digest() {
        return sha1(now()->getTimestamp() . env('SECRET_KEY'));
    }
}
