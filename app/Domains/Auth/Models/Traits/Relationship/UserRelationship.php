<?php

namespace App\Domains\Auth\Models\Traits\Relationship;

use App\Domains\Auth\Models\PasswordHistory;
use App\Domains\System\Models\Organisation;
use App\Domains\System\Models\Pool;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    /**
     * @return mixed
     */
    public function passwordHistories()
    {
        return $this->morphMany(PasswordHistory::class, 'model');
    }

    public function organisation() {
    	return $this->belongsTo(Organisation::class);
    }

    public function pools() {
    	return $this->belongsToMany(Pool::class, 'user_pools');
    }

}
