<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organisation_id')->nullable();
            $table->string('organisation_role')->nullable();
            $table->string('office_name')->nullable();
            $table->string('building_name')->nullable();
            $table->string('street')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('county')->nullable();
            $table->string('post_code')->nullable();
            $table->string('finance_contact_name')->nullable();
            $table->string('finance_email')->nullable();
            $table->string('finance_tel')->nullable();
            $table->string('finance_fax')->nullable();
            $table->string('vat_no')->nullable();
            $table->string('eori_no')->nullable();
            $table->bigInteger('project_id')->nullable();
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
        Schema::dropIfExists('project_invoice_details');
    }
}
