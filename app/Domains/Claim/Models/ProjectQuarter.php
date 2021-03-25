<?php

namespace App\Domains\Claim\Models;

use App\Domains\Claim\Models\Traits\Method\ProjectQuarterMethod;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Claim\Models\Traits\Relationship\ProjectQuarterRelationship;

/**
 * Class ProjectQuarter.
 */
class ProjectQuarter extends Model
{
    use ProjectQuarterMethod,
        ProjectQuarterRelationship;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'name',
        'length',
        'start_timestamp',
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
    protected $casts = [];

    /**
     * @var array
     */
    protected $appends = [];

    /**
     * @var string[]
     */
    protected $with = [];
    
}
