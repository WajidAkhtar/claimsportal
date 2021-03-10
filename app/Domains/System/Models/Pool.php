<?php

namespace App\Domains\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Pool.
 */
class Pool extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name'
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

    public function getFullNameAttribute() {
        return $this->code.'-'.$this->name;
    }
    
}
