<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'driver_id', 'amount', 'address'];

    public function scopeByDistrict($query, $address)
    {
        if ($address != null) {
            return $query->where('address', $address);
        }
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function driver() {
        return $this->belongsTo(Driver::class);
    }
}
