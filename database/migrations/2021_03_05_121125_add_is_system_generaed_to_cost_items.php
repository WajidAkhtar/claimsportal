<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSystemGeneraedToCostItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cost_items', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_system_generated')->default(0)->after('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cost_items', function (Blueprint $table) {
            $table->dropColumn('is_system_generated');
        });
    }
}
