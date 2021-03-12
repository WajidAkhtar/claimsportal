<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCorrespondenceAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_correspondence_address', function (Blueprint $table) {
            $table->id();
            $table->string('building_name_no')->nullable();
            $table->string('street')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('county')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('direct_dial')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
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
        Schema::dropIfExists('user_correspondence_address');
    }
}
