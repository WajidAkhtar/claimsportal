<?php

namespace App\Domains\Claim\Models\Traits\Method;

use Illuminate\Support\Collection;

/**
 * Trait ProjectQuarterMethod.
 */
trait ProjectQuarterMethod
{
    /**
     * @return mixed
     */
    public function partner($organisationId = null)
    {
        return $this->partners()->whereProjectOrganisationId($organisationId ?? auth()->user()->organisation->id)->first();
    }
}
