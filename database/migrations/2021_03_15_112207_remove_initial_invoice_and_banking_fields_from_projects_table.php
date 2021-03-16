<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveInitialInvoiceAndBankingFieldsFromProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            //
        });
    }
}
