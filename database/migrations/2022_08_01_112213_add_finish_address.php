<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinishAddress extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('taxi_orders', function(Blueprint $table) {
            $table->string('finish_address')->after('address')->nullable();
            $table->double('finish_latitude')->after('latitude')->nullable();
            $table->double('finish_longitude')->after('finish_latitude')->nullable();
            $table->double('payment')->after('finish_longitude')->default(0);
            $table->double('distance')->after('payment')->default(0);
        });
        
        Schema::table('partners', function(Blueprint $table) {
            $table->boolean('open')->after('end_time')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('taxi_orders', function(Blueprint $table) {
            $table->dropColumn('finish_address');
            $table->dropColumn('finish_latitude');
            $table->dropColumn('finish_longitude');
            $table->dropColumn('payment');
            $table->dropColumn('distance');
        });
        
        Schema::table('partners', function(Blueprint $table) {
            $table->dropColumn('open');
        });
    }
}
