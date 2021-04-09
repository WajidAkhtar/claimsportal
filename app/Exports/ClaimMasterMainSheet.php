<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ClaimMasterMainSheet implements FromView, WithTitle
{
    private $project;
    private $data;
    private $sheet_name;

    public function __construct($project, $data, $sheet_name)
    {
        $this->project = $project;
        $this->data = $data;
        $this->sheet_name = $sheet_name;
    }

    /**
     * @return Builder
     */
    public function view() : View
    {
        return view('backend.claim.project.yearwise-master-main-table-claim', [
            'project' => $this->project,
            'data' => $this->data
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->sheet_name;
    }
}