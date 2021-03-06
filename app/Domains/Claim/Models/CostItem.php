<?php

namespace App\Domains\Claim\Models;

use App\Domains\Claim\Models\Traits\Attribute\CostItemAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Claim\Models\Traits\Scope\CostItemScope;

/**
 * Class CostItem.
 */
class CostItem extends Model
{
    use SoftDeletes,
        CostItemScope,
        CostItemAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'active',
        'is_system_generated',
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
    protected $casts = [
        'active' => 'boolean',
        'is_system_generated' => 'boolean',
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
