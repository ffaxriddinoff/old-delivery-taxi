<?php

namespace Database\Seeders;

use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call(CarTypeSeeder::class);
        $this->call(TariffSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PaymentSeeder::class);

        Cache::put('pay', 2000);
        $this->haversine();
    }

    private function haversine() {
        # DELIMITER $$
        try {
            DB::statement("
                CREATE FUNCTION haversine(lat1 FLOAT, long1 FLOAT, lat2 FLOAT, long2 FLOAT)
                    RETURNS FLOAT
                BEGIN
                    DECLARE dist FLOAT;
                    DECLARE r FLOAT DEFAULT 6371; # r - Earth radius (km)

                    SET dist =
                          r * acos(
                                cos(radians(lat1))
                                * cos(radians(lat2))
                                * cos(radians(long2) - radians(long1))
                                + sin(radians(lat1))
                                * sin(radians(lat2))
                          );

                    RETURN dist;
                END
            ");
        } catch (QueryException $e) {}
    }
}
