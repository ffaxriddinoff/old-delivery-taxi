<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model {
    use HasFactory;

    protected $fillable = [
        'driver_id', 'order_id', 'order_type', 'service_fee',
        'fare', 'minute', 'path', 'status', 'district_id'
    ];

    public function scopeByDistrict($query, $district_id) {
        if ($district_id != null) {
            return $query->where('district_id', $district_id);
        }
    }
}
