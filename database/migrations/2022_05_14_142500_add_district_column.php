<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistrictColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('district_id')->default(1)->after('role');
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->unsignedBigInteger('district_id')->default(1)->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('district_id');
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('district_id');
        });
    }
}
