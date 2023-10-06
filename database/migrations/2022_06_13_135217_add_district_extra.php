<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistrictExtra extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('tariffs', function (Blueprint $table) {
            $table->unsignedBigInteger('district_id')->default(1)->after('img');
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->unsignedBigInteger('district_id')->default(1)->after('latitude');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('district_id')->default(1)->after('paid');
        });

        Schema::table('taxi_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('district_id')->default(1)->after('status');
            $table->boolean('is_mobile')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('tariffs', function (Blueprint $table) {
            $table->dropColumn('district_id');
        });
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('district_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('district_id');
        });
        Schema::table('taxi_orders', function (Blueprint $table) {
            $table->dropColumn('district_id');
            $table->dropColumn('is_mobile');
        });
    }
}
