<?php

namespace App\Domains\Claim\Models\Traits\Method;

use Illuminate\Support\Collection;

/**
 * Trait ProjectMethod.
 */
trait ProjectMethod
{
    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
}
