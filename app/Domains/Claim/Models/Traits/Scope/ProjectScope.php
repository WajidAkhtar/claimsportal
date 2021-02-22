<?php

namespace App\Domains\Claim\Models\Traits\Scope;

/**
 * Class ProjectScope.
 */
trait ProjectScope
{
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlyDeactivated($query)
    {
        return $query->whereActive(false);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeOnlyActive($query)
    {
        return $query->whereActive(true);
    }
}
