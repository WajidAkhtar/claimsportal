<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\System\Models\Organisation;

class SetLogosToOrganisationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisations', function (Blueprint $table) {
            $table->string('logo_high')->after('logo')->nullable();
        });
        foreach (Organisation::all() as $organisation) {
            $organisation_name = $organisation->organisation_name;
            $organisation->update([
              'logo' => $organisation_name.' 72.jpg',
              'logo_high' => $organisation_name.' 300.jpg',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisations', function (Blueprint $table) {
            $table->dropColumn('logo_high');
        });
    }
}
