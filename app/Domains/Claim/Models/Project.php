<?php

namespace App\Domains\Claim\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Claim\Models\Traits\Scope\ProjectScope;
use App\Domains\Claim\Models\Traits\Relationship\ProjectRelationship;

/**
 * Class Project.
 */
class Project extends Model
{
    use SoftDeletes,
        ProjectScope,
        ProjectRelationship;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'number',
        'pool_id',
        'start_date',
        'length',
        'cost_items_order',
        'project_funder_ref',
        'status',
        'active',
        'logo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @var array
     */
    protected $dates = [
        'start_date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * @var array
     */
    protected $appends = [];

    /**
     * @var string[]
     */
    protected $with = ['organisation', 'pool'];

    public static function statuses() {
        return [
            'Active' => 'Active',
            'Pending' => 'Pending',
            'Closed' => 'Closed',
        ];
    }

    public function getStartDateMonthAttribute() {
        $start_date = explode('-', $this->start_date);
        return $start_date[1];
    }

    public function getStartDateYearAttribute() {
        $start_date = explode('-', $this->start_date);
        return $start_date[0];
    }
    
}
