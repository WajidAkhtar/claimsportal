<?php

use Carbon\Carbon;
use App\Domains\System\Models\Pool;
use App\Domains\System\Models\SheetUserPermissions;

if (! function_exists('appName')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function appName()
    {
        return config('app.name', 'Laravel Boilerplate');
    }
}

if (! function_exists('carbon')) {
    /**
     * Create a new Carbon instance from a time.
     *
     * @param $time
     *
     * @return Carbon
     * @throws Exception
     */
    function carbon($time)
    {
        return new Carbon($time);
    }
}

if (! function_exists('homeRoute')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function homeRoute()
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return 'admin.claim.project.index';
            }

            if (auth()->user()->isUser()) {
                return 'frontend.user.dashboard';
            }
        }

        return 'frontend.index';
    }
}

if(! function_exists('current_user_role')) {
    /**
    * Return the role name of logged in user
    *
    * @return string
    */
    function current_user_role() {
        return auth()->user()->roles()->first()->name;
    }
}

if(! function_exists('current_user_pools')) {
    /**
    * Return the pools of logged in user
    *
    * @return string
    */
    function current_user_pools() {
        if(current_user_role() == 'Developer' || current_user_role() == 'Administrator' || current_user_role() == 'Super User') {
            return Pool::all();
        }
        return auth()->user()->pools()->get();
    }
}

if(! function_exists('get_months_list')) {
    /**
    * Return the months
    *
    * @return array
    */
    function months() {
        $months= [];
        for ($month = 1; $month <= 12; $month++) {
            $months[str_pad($month, 2, '0', STR_PAD_LEFT)] = str_pad($month, 2, '0', STR_PAD_LEFT);
        }
        return $months;
    }
}

if(! function_exists('years')) {
    /**
    * Return the months
    *
    * @return array
    */
    function years() {
        $years= [];
        for ($year = (date('Y') - 15); $year <= (date('Y') + 20); $year++) {
            $years[$year] = $year;
        }
        return $years;
    }
}

if(! function_exists('projectLead')) {
    /**
    * @return mixed
    */
    function projectLead($project) {
        $lead = optional(SheetUserPermissions::where('project_id', $project->id)->where('is_master', '1')->whereHas('sheetPermissions', function($q){
            return $q->wherePermission('LEAD_USER');
        })->first())->user;
        return $lead;
    }
}
