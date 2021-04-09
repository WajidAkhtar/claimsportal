<?php
namespace App\Exports;

use App\Domains\System\Models\Organisation;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ClaimMasterExport implements WithMultipleSheets
{
    use Exportable;

    protected $project;
    protected $data;
    
    public function __construct($project, $data)
    {
        $this->project = $project;
        $this->data = $data;
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
        
        $sheets[] = new ClaimMasterMainSheet($this->project, $this->data, 'Mastersheet');

        for ($yearIndex = 0; $yearIndex < ceil($this->project->length/4); $yearIndex++) {
            $currentYearQuarters = $remainingQuarters > 4 ? 4 : $remainingQuarters;
            $sheets[] = new ClaimMasterChildSheet($this->project, $yearIndex, $startDate, $fromDate, $globalFromDate, $remainingQuarters, (($yearIndex * 4) + 1), $currentYearQuarters, $this->data);
            $remainingQuarters -= $currentYearQuarters;
            $globalFromDate = clone $startDate;
        }

        return $sheets;
    }
    
}