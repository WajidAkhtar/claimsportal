<?php

namespace App\Domains\Claim\Models\Traits\Relationship;

use App\Domains\Claim\Models\Project;
use App\Domains\System\Models\Organisation;
use App\Domains\Claim\Models\ProjectQuarterUser;
use App\Domains\Claim\Models\ProjectQuarterNote;

/**
 * Class ProjectQuarterRelationship.
 */
trait ProjectQuarterRelationship
{
    /**
     * @return mixed
     */
    public function partners()
    {
        return $this->belongsToMany(Organisation::class, 'project_quarter_project_partner', 'project_quarter_id', 'project_organisation_id')
            ->withPivot('status', 'po_number', 'invoice_date', 'invoice_no', 'claim_status');
    }
    
    /**
     * @return mixed
     */
    public function user()
    {
        return $this->hasOne(ProjectQuarterUser::class);
    }
    
    /**
     * @return mixed
     */
    public function project()
    {
        return $this->belongsTo(Project::class)->withTrashed();
    }

    public function notes() {
        return ProjectQuarterNote::where(function($q) {
            /**
             * @var Builder $q
             */
            $q->where('project_id', $this->project_id)
                ->where('quarter_id', $this->id);
        });
    }

    public function getNotesAttribute() {
        return $this->notes()->get();
    }

}
