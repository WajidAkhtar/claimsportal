<?php

namespace App\Domains\Claim\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use App\Domains\Claim\Models\Project;
use App\Domains\System\Models\Organisation;

class ProjectPartners extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'organisation_id',
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
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'project_id' => 'integer',
        'claims_data' => 'integer',
    ];

    /**
     * @var array
     */
    protected $appends = [];

    /**
     * @var string[]
     */
    protected $with = ['project', 'organisation'];

    public function organisation() {
        return $this->belongsTo(Organisation::class);
    }

    public function invoiceOrganisation() {
        return $this->belongsTo(Organisation::class, 'invoice_organisation_id', 'id');
    }

    public function invoiceFunder() {
        return $this->belongsTo(Organisation::class, 'funder_id', 'id');
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function userPermissions() {
        return $this->belongsToMany(SheetPermission::class, 'user_id', 'partner_id');
    }

}
