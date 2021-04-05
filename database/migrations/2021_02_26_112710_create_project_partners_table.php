<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_partners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('organisation_id')->nullable();
            $table->enum('is_master', [0, 1])->default(0);
            $table->bigInteger('invoice_organisation_id')->nullable();
            $table->string('organisation_type')->nullable();
            $table->string('organisation_role')->nullable();
            $table->string('office_team_name')->nullable();
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
            $table->string('vat')->nullable();
            $table->string('eori')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('sort_code')->nullable();
            $table->string('account_no')->nullable();
            $table->string('swift')->nullable();
            $table->string('iban')->nullable();
            $table->string('web_url')->nullable();
            $table->string('contact')->nullable();
            $table->string('customer_ref')->nullable();
            $table->bigInteger('funder_id')->nullable();
            $table->bigInteger('funder_office')->nullable();
            $table->bigInteger('funder_building_name')->nullable();
            $table->bigInteger('funder_address_line_1')->nullable();
            $table->bigInteger('funder_address_line_2')->nullable();
            $table->bigInteger('funder_city')->nullable();
            $table->bigInteger('funder_county')->nullable();
            $table->bigInteger('funder_post_code')->nullable();
            $table->bigInteger('funder_web_url')->nullable();
            $table->bigInteger('funder_contact')->nullable();
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
        Schema::table('project_partners', function (Blueprint $table) {
            Schema::dropIfExists('project_partners');
        });
    }
}
