<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ClaimMasterMainSheet implements FromView, WithTitle, WithDrawings, ShouldAutoSize
{
    private $project;
    private $data;
    private $partnerAdditionalInfo;
    private $yearwiseHtml;
    private $leadUser;
    private $leadUserPartner;
    private $sheet_name;

    public function __construct($project, $data, $partnerAdditionalInfo, $yearwiseHtml, $leadUser, $leadUserPartner, $sheet_name)
    {
        $this->project = $project;
        $this->data = $data;
        $this->partnerAdditionalInfo = $partnerAdditionalInfo;
        $this->yearwiseHtml = $yearwiseHtml;
        $this->leadUser = $leadUser;
        $this->leadUserPartner = $leadUserPartner;
        $this->sheet_name = $sheet_name;
    }

    /**
     * @return Builder
     */
    public function view() : View
    {
        return view('backend.claim.project.export-master-excel', [
            'project' => $this->project,
            'data' => $this->data,
            'partnerAdditionalInfo' => $this->partnerAdditionalInfo,
            'yearwiseHtml' => $this->yearwiseHtml,
            'leadUser' => $this->leadUser,
            'leadUserPartner' => $this->leadUserPartner
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->sheet_name;
    }

    /**
     * @return BaseDrawing|BaseDrawing[]
     */
    public function drawings()
    {
        $drawing1 = new Drawing();
        $drawing1->setPath(public_path('uploads/projects/logos/'.$this->project->logo));
        $drawing1->setWidth(225);
        $drawing1->setCoordinates('B1');

        $drawing2 = new Drawing();
        $drawing2->setPath(public_path('uploads/organisations/logos/'.optional($this->leadUserPartner->invoiceOrganisation)->logo));
        $drawing2->setWidth(225);
        $drawing2->setCoordinates('D1');

        $drawing3 = new Drawing();
        $drawing3->setPath(public_path(('uploads/organisations/logos/'.optional($this->project->funders()->first())->logo)));
        $drawing3->setWidth(225);
        $drawing3->setCoordinates('G1');

        return [$drawing1, $drawing2, $drawing3];
    }

}