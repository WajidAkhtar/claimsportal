<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ClaimMasterSheet implements FromView, WithTitle
{
    private $project;
    private $sheet_name;

    public function __construct($project, $sheet_name)
    {
        $this->project = $project;
        $this->sheet_name = $sheet_name;
    }

    /**
     * @return Builder
     */
    public function view() : View
    {
        return view('backend.claim.project.yearwise-master-claim', [
            'project' => $this->project,
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