<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('finance_email')->nullable();
            $table->string('finance_tel')->nullable();
            $table->string('finance_fax')->nullable();
            $table->string('vat')->nullable();
            $table->string('eori')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('sort_code')->nullable();
            $table->string('account_no')->nullable();
            $table->string('swift')->nullable();
            $table->string('iban')->nullable();
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
            $table->dropColumn('finance_email');
            $table->dropColumn('finance_tel');
            $table->dropColumn('finance_fax');
            $table->dropColumn('vat');
            $table->dropColumn('eori');
            $table->dropColumn('account_name');
            $table->dropColumn('bank_name');
            $table->dropColumn('bank_address');
            $table->dropColumn('sort_code');
            $table->dropColumn('account_no');
            $table->dropColumn('swift');
            $table->dropColumn('iban');
        });
    }
}
