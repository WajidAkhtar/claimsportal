<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Domains\System\Models\Organisation;

class ClaimChildSheet implements FromView, WithTitle, WithDrawings, WithStyles, WithEvents
{
    use RegistersEventListeners;

    private $project;
    private $data;
    private $partnerAdditionalInfo;
    private $yearwiseHtml;
    private $leadUser;
    private $leadUserPartner;
    private $partner;
    private $costItems;
    private $sheet_name;

    public function __construct($project, $data, $partnerAdditionalInfo, $yearwiseHtml, $leadUser, $leadUserPartner, $partner, $sheet_name, $costItems)
    {
        $this->project = $project;
        $this->data = $data;
        $this->partnerAdditionalInfo = $partnerAdditionalInfo;
        $this->yearwiseHtml = $yearwiseHtml;
        $this->leadUser = $leadUser;
        $this->leadUserPartner = $leadUserPartner;
        $this->partner = $partner;
        $this->costItems = $costItems;
        $this->sheet_name = $sheet_name;
    }

    /**
     * @return Builder
     */
    public function view() : View
    {
        return view('backend.claim.project.export-child-excel', [
            'project' => $this->project,
            'data' => $this->data,
            'partnerAdditionalInfo' => $this->partnerAdditionalInfo,
            'yearwiseHtml' => $this->yearwiseHtml,
            'leadUser' => $this->leadUser,
            'leadUserPartner' => $this->leadUserPartner,
            'costItems' => $this->costItems,
            'partner' => $this->partner,
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
        $project_logo_path = '';
        if(!empty($this->project->logo) && file_exists(public_path('uploads/projects/logos/'.$this->project->logo))) {
            $project_logo_path = public_path('uploads/projects/logos/'.$this->project->logo);
        } else {
            $project_logo_path = public_path('uploads/projects/logos/default-logo.png');
        }
        $drawing1 = new Drawing();
        $drawing1->setPath($project_logo_path);
        $drawing1->setWidth(225);
        $drawing1->setHeight(108);
        $drawing1->setOffsetX(5);
        $drawing1->setOffsetY(5);
        $drawing1->setCoordinates('B1');

        $drawing2 = new Drawing();
        $drawing2->setPath(public_path('uploads/organisations/logos/'.optional($this->leadUserPartner->invoiceOrganisation)->logo));
        $drawing2->setWidth(225);
        $drawing2->setCoordinates('E1');

        $drawing3 = new Drawing();
        $drawing3->setPath(public_path('uploads/organisations/logos/'.optional(Organisation::find($this->partner))->logo));
        $drawing3->setWidth(225);
        $drawing3->setCoordinates('H1');

        $drawing4 = new Drawing();
        $drawing4->setPath(public_path(('uploads/organisations/logos/'.optional($this->project->funders()->first())->logo)));
        $drawing4->setWidth(225);
        $drawing4->setCoordinates('K1');

        return [$drawing1, $drawing2, $drawing3, $drawing4];
    }

    public static function afterSheet(AfterSheet $event)
    {
        $defaultStyle = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => [
                    'rgb' => 'ffffff'
                ]
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => 'ffffff']
                ],
            ]
        ];

        $active_sheet = $event->sheet->getDelegate();
        $active_sheet->getParent()->getDefaultStyle()->applyFromArray($defaultStyle);

        for ($i = 22; $i <= $active_sheet->getHighestRow(); $i++) {
          $active_sheet->getRowDimension($i)->setRowHeight(20);
        }

        $active_sheet->getStyle('B22:'.$active_sheet->getHighestColumn().$active_sheet->getHighestRow())->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $active_sheet->getStyle('B22:'.$active_sheet->getHighestColumn().$active_sheet->getHighestRow())->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $active_sheet->getStyle('D22:'.'D'.$active_sheet->getHighestRow())->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $active_sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

}