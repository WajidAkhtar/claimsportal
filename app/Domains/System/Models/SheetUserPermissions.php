<?php

namespace App\Domains\System\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;

/**
 * Class SheetUserPermissions.
 */
class SheetUserPermissions extends Model
{

    protected $table = 'sheet_user_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'partner_id',
        'user_id',
        'sheet_permission_id',
        'is_master',
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
    
    public function sheetPermissions()
    {
        return $this->belongsTo(SheetPermission::class, 'sheet_permission_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
