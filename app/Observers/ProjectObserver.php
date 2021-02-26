<?php

namespace App\Observers;

use App\Domains\Claim\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectObserver
{
    /**
     * Handle the Project "creating" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function creating(Project $project)
    {
        $project->created_by = Auth::user()->id;
    }

    /**
     * Handle the Project "updating" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function updating(Project $project)
    {
        $project->modified_by = Auth::user()->id;
    }

    /**
     * Handle the Project "deleting" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function deleting(Project $project)
    {
        $project->purged_by = Auth::user()->id;
        $project->save();
    }

}
