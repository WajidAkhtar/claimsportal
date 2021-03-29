<?php

namespace App\Domains\Claim\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectQuarterUser.
 */
class ProjectQuarterUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_quarter_id',
        'status',
        'po_number',
        'invoice_date',
        'invoice_no'
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
    protected $dates = [];

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
    
    public $timestamps = false;
}
