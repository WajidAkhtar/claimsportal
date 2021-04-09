<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ClaimMasterChildSheet implements FromView, WithTitle
{
    private $project;
    protected $partner;
    private $yearIndex;
    private $startDate;
    private $fromDate;
    private $globalFromDate;
    private $remainingQuarters;
    private $quarterNo;
    private $currentYearQuarters;
    private $data;

    public function __construct($project, $yearIndex, $startDate, $fromDate, $globalFromDate, $remainingQuarters, $quarterNo, $currentYearQuarters, $data)
    {
        $this->project = $project;
        $this->yearIndex = $yearIndex;
        $this->startDate = $startDate;
        $this->fromDate = $fromDate;
        $this->globalFromDate = $globalFromDate;
        $this->remainingQuarters = $remainingQuarters;
        $this->quarterNo = $quarterNo;
        $this->currentYearQuarters = $currentYearQuarters;
        $this->data = $data;
    }

    /**
     * @return Builder
     */
    public function view() : View
    {
        return view('backend.claim.project.yearwise-master-child-table-claim', [
            'project' => $this->project,
            'yearIndex' => $this->yearIndex,
            'startDate' => $this->startDate,
            'fromDate' => $this->fromDate,
            'globalFromDate' => $this->globalFromDate,
            'remainingQuarters' => $this->remainingQuarters,
            'quarterNo' => $this->quarterNo,
            'currentYearQuarters' => $this->currentYearQuarters,
            'data' => $this->data,
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Year ' . ($this->yearIndex+1).' Finance';
    }
}