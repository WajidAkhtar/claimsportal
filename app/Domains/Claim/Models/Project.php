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
        'pool',
        'start_date',
        'length',
        'number_of_partners',
        'cost_items_order',
        'status',
        'active',
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
    protected $with = [];
    
}
