<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToProjectPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_partners', function (Blueprint $table) {
            $table->smallInteger('organisation_id')->after('user_id')->nullable();
            $table->string('finance_email')->after('organisation_id')->nullable();
            $table->string('finance_tel')->after('finance_email')->nullable();
            $table->string('finance_fax')->after('finance_tel')->nullable();
            $table->string('vat')->after('finance_fax')->nullable();
            $table->string('eori')->after('vat')->nullable();
            $table->string('account_name')->after('eori')->nullable();
            $table->string('bank_name')->after('account_name')->nullable();
            $table->string('bank_address')->after('bank_name')->nullable();
            $table->string('sort_code')->after('bank_address')->nullable();
            $table->string('account_no')->after('sort_code')->nullable();
            $table->string('swift')->after('account_no')->nullable();
            $table->string('iban')->after('swift')->nullable();
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
            $table->dropColumn('organisation_id');
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
