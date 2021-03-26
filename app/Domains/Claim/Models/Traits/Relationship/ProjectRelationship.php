<?php

namespace App\Domains\Claim\Models\Traits\Relationship;

use App\Domains\Auth\Models\User;
use App\Domains\System\Models\Pool;
use App\Domains\Claim\Models\CostItem;
use App\Domains\System\Models\UserPools;
use App\Domains\System\Models\Organisation;
use App\Domains\Claim\Models\ProjectQuarter;
use App\Domains\Claim\Models\ProjectCostItem;
use App\Domains\Claim\Models\ProjectPartners;
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
        // return $this->belongsToMany(User::class, 'project_partners', 'project_id', 'organisation_id');
        return $this->belongsToMany(Organisation::class, 'project_partners', 'project_id', 'organisation_id');
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
    public function usersInSamePool($belowCurrentUser = false) {
        $users = User::whereIn('id', UserPools::where('pool_id', $this->pool_id)->pluck('user_id'));
        if($belowCurrentUser) {
          if(current_user_role() == 'Super User') {
            $users = $users->whereHas('roles', function($q) {
                $q->whereIn('name', ['Finance Officer', 'Project Admin', 'Project Partner', 'Funder']);
            });
          } else if(current_user_role() == 'Finance Officer') {
              $users = $users->whereHas('roles', function($q) {
                  $q->whereIn('name', ['Project Admin', 'Project Partner', 'Funder']);
              });
          } else if(current_user_role() == 'Project Admin') {
              $users = $users->whereHas('roles', function($q) {
                  $q->whereIn('name', ['Project Partner', 'Funder']);
              });
          } else if(current_user_role() == 'Project Partner') {
              $users = $users->whereHas('roles', function($q) {
                  $q->whereIn('name', ['Funder']);
              });
          }
        }
        $users = $users->get();
        return $users;
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
    public function usersWithPermissions($partner_id = 0) {
        $permissions = $this->hasMany(SheetUserPermissions::class, 'project_id', 'id');
        if(!empty($partner_id)) {
          $permissions = $permissions->where('partner_id', $partner_id);
        }
        return $permissions;
    }

    /**
    * @return mixed
    */
    public function userHasFullAccessToProject() {
        if(auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super User')) {
          return true;
        }
        foreach($this->usersWithPermissions()->get() as $partner) {
          if(auth()->user()->id == $partner->user_id && $partner->partner_id == 0 && $partner->is_master == '1') {
            return true;
          }
        }
        return false;
    }

    /**
    * @return mixed
    */
    public function userHasPartialAccessToProject() {
        if(!auth()->user()->hasRole('Administrator') && !auth()->user()->hasRole('Super User') && !in_array(auth()->user()->id, $this->usersWithPermissions()->pluck('user_id')->toArray())) {
          return false;
        }
        return true;
    }
    
    /**
    * @return mixed
    */
    public function quarters() {
        return $this->hasMany(ProjectQuarter::class);
    }

}
