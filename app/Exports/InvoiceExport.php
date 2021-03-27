<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class InvoiceExport implements FromView/* , WithDrawings */
{
    protected $quarter;
    protected $quarterPartner;
    protected $invoiceItems;
    protected $invoiceTo;
    protected $invoiceToPartner;
    protected $invoiceFrom;
    protected $invoiceFromPartner;
    
    public function __construct($quarter, $quarterPartner, $invoiceItems, $invoiceTo, $invoiceToPartner, $invoiceFrom, $invoiceFromPartner)
    {
        $this->quarter = $quarter;
        $this->quarterPartner = $quarterPartner;
        $this->invoiceItems = $invoiceItems;
        $this->invoiceTo = $invoiceTo;
        $this->invoiceToPartner = $invoiceToPartner;
        $this->invoiceFrom = $invoiceFrom;
        $this->invoiceFromPartner = $invoiceFromPartner;
    }

    public function view(): View
    {
        return view('backend.claim.project.invoice', [
            'quarter' => $this->quarter,
            'quarterPartner' => $this->quarterPartner,
            'invoiceItems' => json_decode(json_encode($this->invoiceItems)),
            'invoiceTo' => $this->invoiceTo,
            'invoiceToPartner' => $this->invoiceToPartner,
            'invoiceFrom' => $this->invoiceFrom,
            'invoiceFromPartner' => $this->invoiceFromPartner,
        ]);
    }

    // public function drawings()
    // {
    //     if($this->invoiceFrom->logo && file_exists(public_path('uploads/organisations/logos/'.$this->invoiceFrom->logo))) {
    //         $drawing = new Drawing();
    //         $drawing->setName('Invoice Header Logo');
    //         $drawing->setDescription('Organisation Logo');
    //         $drawing->setPath(public_path('uploads/organisations/logos/'.$this->invoiceFrom->logo));
    //         $drawing->setHeight(160);
    //         $drawing->setCoordinates('B3');
    //         return $drawing;
    //     }

    // }
}