<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model {
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'phone', 'name', 'surname', 'password', 'card_number', 'birth_date', 'token', 'img', 'lang'
    ];

    protected $hidden = [
        'password', 'token'
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
