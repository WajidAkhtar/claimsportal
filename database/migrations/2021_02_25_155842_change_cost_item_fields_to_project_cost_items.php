<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCostItemFieldsToProjectCostItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_cost_items', function (Blueprint $table) {
            $table->dropColumn(['name', 'description']);
            $table->unsignedBigInteger('cost_item_id')->after('project_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_cost_items', function (Blueprint $table) {
            $table->string('name')->after('project_id');
            $table->string('description')->nullable()->after('name');
            $table->dropColumn('cost_item_id');
        });
    }
}
