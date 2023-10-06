<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int payment_type
 */
class Order extends Model {
    use HasFactory;

    public const ACCEPT = 1;
    public const SENT = 3;
    public const PAID = 1;
    public const CANCELLED = -1;
    public const INVALID_PAYMENT_AMOUNT = -2;
    public const SELF_PAYMENT = 2;
    public const SELF_TAKE = 9;
    public const TYPE_CLIENT = 1;
    public const TYPE_ORDER = 2;

    protected $fillable = [
        'customer_id', 'driver_id', 'partner_id', 'total_price', 'item_count',
        'address', 'longitude', 'latitude', 'payment_type', 'status', 'paid', 'district_id', 'pay_check'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function client() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function driver() {
        return $this->belongsTo(Driver::class);
    }

    public function partner() {
        return $this->belongsTo(Partner::class);
    }

    public function scopeNewOrders($query) {
        $query->where('driver_id', Driver::FREE)->where('status', Order::SENT);
    }

    public function scopeByDistrict($query, $district_id) {
        if ($district_id != null) {
            return $query->where('district_id', $district_id);
        }
    }
}
