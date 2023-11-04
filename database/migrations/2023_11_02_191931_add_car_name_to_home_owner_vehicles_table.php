<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_owner_vehicles', function (Blueprint $table) {
            $table->string('car_name')->default('Car Name')->after('car_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_owner_vehicles', function (Blueprint $table) {
            $table->dropColumn('car_name');
        });
    }
};
