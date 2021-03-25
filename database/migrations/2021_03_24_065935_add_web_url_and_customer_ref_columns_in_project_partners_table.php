<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWebUrlAndCustomerRefColumnsInProjectPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_partners', function (Blueprint $table) {
            $table->string('web_url')->after('iban')->nullable();
            $table->string('customer_ref')->after('web_url')->nullable();
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
            $table->dropColumn('web_url');
            $table->dropColumn('customer_ref');
        });
    }
}
