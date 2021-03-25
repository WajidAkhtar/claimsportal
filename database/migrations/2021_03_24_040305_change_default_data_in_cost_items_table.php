<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Claim\Models\CostItem;

class ChangeDefaultDataInCostItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cost_items', function (Blueprint $table) {
            //
        });
        $costItems = [
            'A1' => 'Directly Incurred: Staff',
            'A2' => 'Directly Incurred: Travel & Subsistence',
            'A3' => 'Directly Incurred: Equipment',
            'A4' => 'Directly Incurred: Consumables',
            'A5' => 'Directly Allocated: Investigators',
            'A6' => 'Directly Allocated: Estates',
            'A7' => 'Directly Allocated: Other Costs',
            'A8' => 'Indirect Costs',
            'A9' => 'Exceptions: Staff',
            'A10' => 'Exceptions: Travel & Subsistence',
            'A11' => 'Exceptions: Other',
            'A12' => 'Exceptions: Student Internships',
            'A13' => 'Exceptions:'
        ];
        foreach($costItems as $name => $description) {
            CostItem::where('name', $name)->where('is_system_generated', 1)->update([
                'description' => $description
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
        Schema::table('cost_items', function (Blueprint $table) {
            //
        });
    }
}
