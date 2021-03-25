<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFunderRelatedColumnsInProjectPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_partners', function (Blueprint $table) {
            $table->bigInteger('funder_id')->after('customer_ref')->nullable();
            $table->string('funder_office')->after('funder_id')->nullable();
            $table->string('funder_building_name')->after('funder_office')->nullable();
            $table->string('funder_address_line_1')->after('funder_building_name')->nullable();
            $table->string('funder_address_line_2')->after('funder_address_line_1')->nullable();
            $table->string('funder_city')->after('funder_address_line_2')->nullable();
            $table->string('funder_county')->after('funder_city')->nullable();
            $table->string('funder_post_code')->after('funder_county')->nullable();
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
            $table->dropColumn('funder_id');
            $table->dropColumn('funder_office');
            $table->dropColumn('funder_building_name');
            $table->dropColumn('funder_address_line_1');
            $table->dropColumn('funder_address_line_1');
            $table->dropColumn('funder_city');
            $table->dropColumn('funder_county');
            $table->dropColumn('funder_post_code');
        });
    }
}
