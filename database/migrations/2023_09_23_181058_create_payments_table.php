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
            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('payment_types');
            $table->string('mode')->nullable()->default('Cash');
            $table->float('amount', 20);
            $table->timestamp('transaction_date')->nullable();
            $table->timestamp('date_paid')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->string('reference')->nullable();
            $table->boolean('is_recurring')->default(0)->nullable();
            $table->string('recurring_date')->nullable();
            $table->string('status')->default('pending')->nullable();
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
