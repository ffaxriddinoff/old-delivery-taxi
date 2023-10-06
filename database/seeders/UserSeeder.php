<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder {

    public function run() {
        User::query()->firstOrCreate(['username' => 'admin'], [
            'name' => 'Admin',
            'surname' => 'Admin',
            'password' => bcrypt("admin"),
            'phone' => '991111111',
            'role' => 1
        ]);

        User::query()->firstOrCreate(['username' => 'operator'], [
            'name' => 'operator',
            'password' => bcrypt('operator'),
            'phone' => '991234567'
        ]);
    }
}
