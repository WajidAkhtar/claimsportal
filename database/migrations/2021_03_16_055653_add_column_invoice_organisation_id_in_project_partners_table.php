<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInvoiceOrganisationIdInProjectPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_partners', function (Blueprint $table) {
            $table->bigInteger('invoice_organisation_id')->after('organisation_id')->nullable();
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
            $table->dropColumn('invoice_organisation_id');
        });
    }
}
