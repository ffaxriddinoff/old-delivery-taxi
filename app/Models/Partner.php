<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $categories
 * @property  $orders
 */
class Partner extends Model {
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name', 'username', 'password', 'start_time', 'end_time', 'open',
        'address', 'phone', 'img', 'longitude', 'latitude', 'district_id', 'card'
    ];

    protected $hidden = [
        'password'
    ];

    public function categories() {
        return $this->hasMany(Category::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function scopeByDistrict($query, $district_id) {
        if ($district_id != null) {
            return $query->where('district_id', $district_id);
        }
    }
}
