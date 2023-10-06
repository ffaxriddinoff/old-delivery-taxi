<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Goodoneuz\PayUz\Models\PaymentSystem;
use Goodoneuz\PayUz\Models\PaymentSystemParam;


class PaymentSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        PaymentSystem::query()->firstOrCreate([
            'name' => "Click",
            'system' => "click",
            'status' => "active"
        ]);

        // System params
        PaymentSystemParam::query()->firstOrCreate([
           'system' => 'click',
           'label' => 'Xizmat ID',
           'name' => 'service_id',
           'value' => env('SERVICE_ID')
        ]);
        PaymentSystemParam::query()->firstOrCreate([
           'system' => 'click',
           'label' => 'Sovdagar ID',
           'name' => 'merchant_user_id',
           'value' => env('MERCHANT_USER_ID')
        ]);
        PaymentSystemParam::query()->firstOrCreate([
           'system' => 'click',
           'label' => 'Yashirin kalit',
           'name' => 'secret_key',
           'value' => env('SECRET_KEY')
        ]);
    }
}
