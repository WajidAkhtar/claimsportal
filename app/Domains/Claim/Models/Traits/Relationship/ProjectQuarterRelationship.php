<?php

namespace App\Domains\Claim\Models\Traits\Relationship;

use App\Domains\System\Models\Organisation;

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

}
