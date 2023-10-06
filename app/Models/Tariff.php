<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $vip
 */
class Tariff extends Model {
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'client', 'minute', 'km', 'min_pay', 'min_km', 'out_city', 'vip', 'img', 'district_id'
    ];

    public function scopeByDistrict($query, $district_id) {
        if ($district_id != null) {
            return $query->where('district_id', $district_id);
        }
    }
}
