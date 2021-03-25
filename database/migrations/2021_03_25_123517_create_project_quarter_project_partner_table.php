<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectQuarterProjectPartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_quarter_project_partner', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_quarter_id');
            $table->unsignedBigInteger('project_organisation_id');
            $table->string('status');
            $table->string('po_number')->nullable();
            $table->string('invoice_date')->nullable();
            $table->string('invoice_no')->nullable();
            $table->unsignedTinyInteger('claim_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_quarter_project_partner');
    }
}
