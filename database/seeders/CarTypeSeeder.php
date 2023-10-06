<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarType;


class CarTypeSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        CarType::query()->firstOrCreate(['brand' => 'Chevrolet', 'model' => 'Matiz']);
        CarType::query()->firstOrCreate(['brand' => 'Chevrolet', 'model' => 'Spark']);
        CarType::query()->firstOrCreate(['brand' => 'Chevrolet', 'model' => 'Nexia 2']);
        CarType::query()->firstOrCreate(['brand' => 'Chevrolet', 'model' => 'Nexia 3']);
        CarType::query()->firstOrCreate(['brand' => 'Chevrolet', 'model' => 'Lacetti']);
    }
}
