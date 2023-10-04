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
        Schema::table('rfid_monitorings', function (Blueprint $table) {
            $table->text('capture_in')->nullable()->after('time_out');
            $table->text('capture_out')->nullable()->after('capture_in');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfid_monitorings', function (Blueprint $table) {
            $table->dropColumn(['capture_in', 'capture_out']);
        });
    }
};
