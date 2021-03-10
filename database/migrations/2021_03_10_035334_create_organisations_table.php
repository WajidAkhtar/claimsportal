<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganisationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisations', function (Blueprint $table) {
            $table->id();
            $table->string('organisation_name')->nullable();
            $table->enum('organisation_type', ['ACADEMIC', 'INDUSTRY', 'FUNDER'])->default('INDUSTRY');
            $table->string('building_name_no')->nullable();
            $table->string('street')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('county')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('logo')->default(asset('backend/images/default_organisation_img.png'));
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
        Schema::dropIfExists('organisations');
    }
}
