<?php

namespace App\Domains\Claim\Models\Traits\Attribute;

/**
 * Trait CostItemAttribute.
 */
trait CostItemAttribute
{
    /**
     * Covert Pivot json column to object
     */
    public function getClaimsDataAttribute()
    {
        return json_decode($this->pivot->claims_data);
    }
}
