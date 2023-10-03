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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('home_owner_id');
            $table->foreign('home_owner_id')->references('id')->on('home_owners');
            $table->string('type');
            $table->string('mode');
            $table->float('amount', 20);
            $table->string('transaction_date');
            $table->string('reference')->nullable();
            $table->string('status')->default('pending')->nullable();
            $table->timestamp('paid_on')->nullable();
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
        Schema::dropIfExists('payments');
    }
};
