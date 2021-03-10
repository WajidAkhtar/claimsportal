<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganisationIdColumnInProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->bigInteger('organisation_id')->after('pool_id')->nullable()->unsigned();
            $table->foreign('organisation_id')->references('id')->on('organisations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('lists_organisation_id_foreign');
            $table->dropColumn('organisation_id');
        });
    }
}
