<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactUrlFieldsToProjectPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_partners', function (Blueprint $table) {
            $table->string('contact')->after('web_url')->nullable();
            $table->string('funder_web_url')->after('funder_post_code')->nullable();
            $table->string('funder_contact')->after('funder_web_url')->nullable();
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
            $table->dropColumn('contact');
            $table->dropColumn('funder_web_url');
            $table->dropColumn('funder_contact');
        });
    }
}
