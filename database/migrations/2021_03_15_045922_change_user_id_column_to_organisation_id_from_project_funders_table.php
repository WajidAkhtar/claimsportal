<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUserIdColumnToOrganisationIdFromProjectFundersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_funders', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->bigInteger('organisation_id')->after('project_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_funders', function (Blueprint $table) {
            $table->dropColumn('organisation_id');
        });
    }
}
