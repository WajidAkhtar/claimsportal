<?php

namespace App\Domains\Auth\Models\Traits\Relationship;

use App\Domains\Auth\Models\PasswordHistory;
use App\Domains\System\Models\Organisation;
use App\Domains\System\Models\Pool;
use App\Domains\System\Models\UserCorrespondenceAddress;

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

    public function correspondenceAddress() {
        return $this->hasOne(UserCorrespondenceAddress::class, 'user_id', 'id');
    }

}
