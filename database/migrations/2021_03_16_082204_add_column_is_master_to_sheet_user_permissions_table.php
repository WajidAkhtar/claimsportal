<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsMasterToSheetUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sheet_user_permissions', function (Blueprint $table) {
            $table->enum('is_master', [0, 1])->after('sheet_permission_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sheet_user_permissions', function (Blueprint $table) {
            $table->dropColumn('is_master');
        });
    }
}
