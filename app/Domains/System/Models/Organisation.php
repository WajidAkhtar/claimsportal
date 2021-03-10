<?php

namespace App\Domains\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Organisation.
 */
class Organisation extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organisation_name',
        'organisation_type',
        'building_name_no',
        'street',
        'address_line_2',
        'country',
        'city',
        'postcode',
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
        
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
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
