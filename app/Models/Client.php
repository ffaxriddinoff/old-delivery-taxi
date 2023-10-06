<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property TaxiOrder $history
 */
class Client extends Model {
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'phone',
        'password'
    ];

    public function history() {
        return $this->hasMany(TaxiOrder::class);
    }
}
