<?php
namespace App\Exports;

use App\Domains\System\Models\Organisation;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ClaimExport implements WithMultipleSheets
{
    use Exportable;

    protected $project;
    protected $partner;
    
    public function __construct($project, $partner)
    {
        $this->project = $project;
        $this->partner = $partner;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {        
        $startDate = clone $this->project->start_date;
        $fromDate = clone $this->project->start_date;
        $globalFromDate = clone $fromDate;
        $remainingQuarters = $this->project->length;
        $quarterNo = 1;
        
        $sheets[] = new ClaimMasterSheet($this->project, (!empty($this->partner) ? Organisation::find($this->partner)->organisation_name : 'Mastersheet'));

        for ($yearIndex = 0; $yearIndex < ceil($this->project->length/4); $yearIndex++) {
            $currentYearQuarters = $remainingQuarters > 4 ? 4 : $remainingQuarters;
            $sheets[] = new ClaimSheet($this->project, $yearIndex, $startDate, $fromDate, $globalFromDate, $remainingQuarters, $quarterNo, $currentYearQuarters, $this->partner);
            $remainingQuarters -= $currentYearQuarters;
            $globalFromDate = clone $startDate;
        }

        return $sheets;
    }
    
}