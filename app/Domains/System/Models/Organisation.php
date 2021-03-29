<?php

namespace App\Domains\System\Models;

use App\Domains\Claim\Models\ProjectPartners;
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
        'county',
        'city',
        'postcode',
        'logo',
        'logo_high',
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

    protected $orderBy = 'organisation_name';
    protected $orderDirection = 'ASC';
    protected $orderBys = [
        [
            'organisation_name' => 'ASC'
        ]
    ];

    public function scopeOrdered($query) {
        if ($this->orderBy) {
            return $query->orderBy($this->orderBy, $this->orderDirection)->withTrashed();
        }
        return $query;
    }

    public static function organisationTypes() {
        return [
            'ACADEMIC' => 'ACADEMIC',
            'FUNDER' => 'FUNDER',
            'INDUSTRY' => 'INDUSTRY',
        ];
    }

    public static function organisationRoles() {
        return [
            'LEAD' => 'LEAD',
            'COLLABORATOR' => 'COLLABORATOR',
            'FUNDER' => 'FUNDER',
            'CO FUNDER' => 'CO FUNDER',
        ];
    }
    
    public function partner()
    {
        return $this->hasOne(ProjectPartners::class);
    }
}
