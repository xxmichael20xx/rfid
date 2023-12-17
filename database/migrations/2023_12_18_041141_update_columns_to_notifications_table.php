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
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('sent_by');
            $table->foreign('sent_by')->references('id')->on('users');
            $table->string('type');
            $table->boolean('is_visitor_request')->default(0)->nullable();
            $table->string('visitor_request_status')->default('pending')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['sent_by']);
            $table->dropColumn(['sent_by', 'type', 'is_visitor_request', 'visitor_request_status']);
        });
    }
};
