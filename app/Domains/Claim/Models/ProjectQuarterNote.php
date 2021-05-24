<?php

namespace App\Domains\Claim\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use App\Domains\System\Models\Organisation;

class ProjectQuarterNote extends Model
{
    use HasFactory;

    protected $table = 'project_quarter_notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'quarter_id',
        'note',
        'created_by',
        'organisation_id'
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
    protected $casts = [];

    /**
     * @var array
     */
    protected $appends = [];

    public function getCreatedAtAttribute($date) {
    	return date('d-m-Y H:i:s', strtotime($date));
    }

    public function getUpdatedAtAttribute($date) {
    	return date('d-m-Y H:i:s', strtotime($date));
    }

    public function user() {
    	return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function organisation() {
    	return $this->belongsTo(Organisation::class, 'organisation_id', 'id');
    }

}
