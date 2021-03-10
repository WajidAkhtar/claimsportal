<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePoolColumnTypeInProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            if(!Schema::hasColumn('projects', 'pool_id')) {
                Schema::table('projects', function (Blueprint $table) {
                    $table->bigInteger('pool_id')->after('project_funder_ref')->nullable()->unsigned();
                    $table->foreign('pool_id')->references('id')->on('pools');
                });           
            }
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
            $table->dropForeign('lists_pool_id_foreign');
            $table->dropColumn('pool_id');
        });
    }
}
