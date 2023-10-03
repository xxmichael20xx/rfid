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
        Schema::create('rfid_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('rfid');
            $table->foreign('rfid')->references('rfid')->on('rfids');
            $table->string('date');
            $table->string('time_in');
            $table->string('time_out')->default('N/A')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rfid_monitorings');
    }
};
