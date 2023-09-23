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
        Schema::create('home_owners', function (Blueprint $table) {
            $table->id();
            $table->text('first_name');
            $table->text('last_name');
            $table->text('middle_name')->nullable();
            $table->unsignedBigInteger('block');
            $table->foreign('block')->references('id')->on('blocks');
            $table->unsignedBigInteger('lot');
            $table->foreign('lot')->references('id')->on('lots');
            $table->string('contact_no')->unique();
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
        Schema::dropIfExists('home_owners');
    }
};
