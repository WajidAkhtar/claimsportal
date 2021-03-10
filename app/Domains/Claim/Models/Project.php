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
        'number_of_partners',
        'cost_items_order',
        'project_funder_ref',
        'organisation_id',
        'status',
        'active',
        'finance_email',
        'finance_tel',
        'finance_fax',
        'vat',
        'eori',
        'account_name',
        'bank_name',
        'bank_address',
        'sort_code',
        'account_no',
        'swift',
        'iban',
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
    
}
