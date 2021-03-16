<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOrganisationTypeToProjectPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_partners', function (Blueprint $table) {
            $table->string('organisation_type')->after('invoice_organisation_id')->nullable();
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
            $table->dropColumn('organisation_type');
        });
    }
}
