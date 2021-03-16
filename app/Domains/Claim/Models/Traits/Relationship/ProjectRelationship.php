<?php

namespace App\Domains\Claim\Models\Traits\Relationship;

use App\Domains\Auth\Models\User;
use App\Domains\Claim\Models\CostItem;
use App\Domains\Claim\Models\ProjectCostItem;
use App\Domains\Claim\Models\ProjectPartners;
use App\Domains\System\Models\Pool;
use App\Domains\System\Models\Organisation;
use App\Domains\System\Models\UserPools;
use App\Domains\System\Models\SheetUserPermissions;

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
        return $this->belongsToMany(Organisation::class, 'project_funders', 'project_id', 'organisation_id');
    }
    
    /**
     * @return mixed
     */
    public function costItems()
    {
        return $this->belongsToMany(CostItem::class, 'project_cost_items')->withPivot('organisation_id', 'claims_data', 'cost_item_name', 'cost_item_description')->withTimestamps();
    }

    /**
     * @return mixed
     */
    public function innerData()
    {
        return $this->hasMany(ProjectCostItem::class, 'project_id', 'id');
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
        return $this->belongsToMany(User::class, 'project_partners', 'project_id', 'organisation_id');
    }

    /**
     * @return mixed
     */
    public function allpartners()
    {
        return $this->hasMany(ProjectPartners::class, 'project_id', 'id');
    }

    /**
     * @return mixed
     */
    public function isUserPartOfProject($user_id, $onlyCreator = false)
    {
        if($onlyCreator == true) {
            return $this->created_by == $user_id;
        }

        return $this->created_by == $user_id || in_array($user_id, $this->partners->pluck('id')->toArray());
    }

    /**
    * @return mixed
    */
    public function costItemOrderRaw() {
      return "FIELD (name, '".implode("','", explode(',', $this->cost_items_order))."')";  
    } 

    /**
     * @return mixed
     */
    public function pool()
    {
        return $this->belongsTo(Pool::class);
    }

    /**
     * @return mixed
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id', 'id');
    }

    /**
    * @return mixed
    */
    public function usersInSamePool() {
        return User::whereIn('id', UserPools::where('pool_id', $this->pool_id)->pluck('user_id'))->get();
    }

    /**
    * @return mixed
    */
    public function partnersInSamePool() {
        return User::whereHas('roles', function($q) {
          $q->where('name', 'Project Partner');
        })->whereIn('id', UserPools::where('pool_id', $this->pool_id)->pluck('user_id'))->get();
    }

    /**
    * @return mixed
    */
    public function usersWithPermissions() {
        return $this->hasMany(SheetUserPermissions::class, 'project_id', 'id');
    }

    /**
    * @return mixed
    */
    public function userHasFullAccessToProject() {
        if($this->created_by == auth()->user()->id || auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super User')) {
          return true;
        }
        return false;
    }

    /**
    * @return mixed
    */
    public function userHasPartialAccessToProject() {
        if($this->created_by != auth()->user()->id && !auth()->user()->hasRole('Administrator') && !auth()->user()->hasRole('Super User') && !in_array(auth()->user()->id, $this->usersWithPermissions()->pluck('user_id')->toArray())) {
          return false;
        }
        return true;
    }

}
