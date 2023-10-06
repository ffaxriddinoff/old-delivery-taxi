<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxiOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver_id', 'client_id', 'address', 'longitude', 'latitude', 'payment_type', 'status',
        'district_id', 'finish_address', 'finish_latitude', 'finish_longitude', 'payment', 'distance'
    ];

    public const CANCELLED = -1;
    public const NEW = 0;

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function driver() {
        return $this->belongsTo(Driver::class);
    }

    public function history() {
        return $this->hasOne(History::class, 'order_id', 'id');
    }

    public function scopeNewOrders($query) {
        $query->where('driver_id', Driver::FREE)->where('status', TaxiOrder::NEW);
    }

    public function scopeByDistrict($query, $district_id) {
        if ($district_id != null) {
            return $query->where('district_id', $district_id);
        }
    }
    
    public function scopeByDate($query, $date) {
        if ($date != null) {
            return $query->whereDate('created_at', $date);
        }
    }
}
