<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemainingAdditionalColumnsToProjectPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_partners', function (Blueprint $table) {
            $table->string('organisation_role')->after('organisation_type')->nullable();
            $table->string('office_team_name')->after('organisation_role')->nullable();
            $table->string('building_name')->after('office_team_name')->nullable();
            $table->string('street')->after('building_name')->nullable();
            $table->string('address_line_2')->after('street')->nullable();
            $table->string('city')->after('address_line_2')->nullable();
            $table->string('county')->after('city')->nullable();
            $table->string('post_code')->after('county')->nullable();
            $table->string('finance_contact_name')->after('post_code')->nullable();
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
            $table->dropColumn('organisation_role');
            $table->dropColumn('office_team_name');
            $table->dropColumn('building_name');
            $table->dropColumn('street');
            $table->dropColumn('address_line_2');
            $table->dropColumn('city');
            $table->dropColumn('county');
            $table->dropColumn('post_code');
            $table->dropColumn('finance_contact_name');
        });
    }
}
