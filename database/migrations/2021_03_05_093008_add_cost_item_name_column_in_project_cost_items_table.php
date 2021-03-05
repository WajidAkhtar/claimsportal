<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostItemNameColumnInProjectCostItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_cost_items', function (Blueprint $table) {
            $table->string('cost_item_name')->nullable()->before('cost_item_description');
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
            $table->dropColumn('cost_item_name');
        });
    }
}
