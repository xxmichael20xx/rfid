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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('home_owner_id');
            $table->foreign('home_owner_id')->references('id')->on('home_owners');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('token')->unique();
            $table->string('qr_image')->nullable();
            $table->timestamp('date_visited')->nullable();
            $table->text('capture')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitors');
    }
};
