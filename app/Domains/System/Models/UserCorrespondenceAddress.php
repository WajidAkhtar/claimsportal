<?php

namespace App\Domains\System\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserCorrespondenceAddress.
 */
class UserCorrespondenceAddress extends Model
{

    protected $table = 'user_correspondence_address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'building_name_no',
        'street',
        'address_line_2',
        'county',
        'city',
        'postcode',
        'email',
        'mobile',
        'direct_dial',
        'user_id',
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
