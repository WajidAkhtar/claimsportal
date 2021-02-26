<?php

namespace App\Domains\Claim\Models\Traits\Relationship;

use App\Domains\Auth\Models\User;
use App\Domains\Claim\Models\CostItem;
use App\Domains\Claim\Models\ProjectCostItem;
use App\Domains\Claim\Models\ProjectPartners;

/**
 * Class ProjectRelationship.
 */
trait ProjectRelationship
{
    /**
     * @return mixed
     */
    public function funders()
    {
        return $this->belongsToMany(User::class, 'project_funders', 'project_id', 'user_id');
    }
    
    /**
     * @return mixed
     */
    public function costItems()
    {
        return $this->belongsToMany(CostItem::class, 'project_cost_items')->withPivot('claims_data')->withTimestamps();
    }

    /**
     * @return mixed
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * @return mixed
     */
    public function partners()
    {
        return $this->belongsToMany(User::class, 'project_partners', 'project_id', 'user_id');
    }

    /**
     * @return mixed
     */
    public function allpartners()
    {
        return $this->hasMany(ProjectPartners::class, 'project_id', 'id');
    }

}
