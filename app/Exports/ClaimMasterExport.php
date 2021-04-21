<?php
namespace App\Exports;

use App\Domains\System\Models\Organisation;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\View;
use App\Domains\Claim\Models\ProjectPartners;

class ClaimMasterExport implements WithMultipleSheets
{
    use Exportable;

    protected $project;
    protected $data;
    protected $partnerAdditionalInfo;
    protected $yearwiseHtml;
    protected $leadUser;
    protected $leadUserPartner;
    
    public function __construct($project, $data, $partnerAdditionalInfo, $yearwiseHtml, $leadUser, $leadUserPartner)
    {
        $this->project = $project;
        $this->data = $data;
        $this->partnerAdditionalInfo = $partnerAdditionalInfo;
        $this->yearwiseHtml = $yearwiseHtml;
        $this->leadUser = $leadUser;
        $this->leadUserPartner = $leadUserPartner;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {      
        $sheets[] = new ClaimMasterMainSheet(
            $this->project, 
            $this->data,
            $this->partnerAdditionalInfo,
            $this->yearwiseHtml,
            $this->leadUser,
            $this->leadUserPartner,
            'Mastersheet'
        );

        foreach ($this->project->partners as $key => $partner) {
            
            $costItems = $this->project->costItems()->where('organisation_id', $partner->id)->whereNull('project_cost_items.deleted_at')->groupBy('cost_item_id')->orderByRaw($this->project->costItemOrderRaw())->get();
            $partnerAdditionalInfo = ProjectPartners::where('project_id', $this->project->id)->where('organisation_id', $partner->id)->first();
            $yearwiseHtml = View::make('backend.claim.project.export-child-yearly-excel', [
                'project' => $this->project, 
                'partner' => $partner->id,
                'costItems' => $costItems
            ])->render();

            $sheets[] = new ClaimChildSheet(
                $this->project,
                $this->data,
                $partnerAdditionalInfo,
                $yearwiseHtml,
                $this->leadUser,
                $this->leadUserPartner,
                $partner->id,
                $partner->organisation_name,
                $costItems,
            );
        }

        return $sheets;
    }
    
}