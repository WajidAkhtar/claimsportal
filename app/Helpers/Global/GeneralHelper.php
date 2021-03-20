<?php

use Carbon\Carbon;
use App\Domains\System\Models\Pool;

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
                return 'admin.dashboard';
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
        if(current_user_role() == 'Administrator') {
            return Pool::all();
        }
        return auth()->user()->pools()->get();
    }
}

if(! function_exists('is_under_current_user')) {
    /**
    * Check if user is under user
    *
    * @return boolean
    */
    function is_under_current_user($user_id = '') {
        if(current_user_role() == 'Administrator' || current_user_role() == 'Super User') {
            return true;
        }
        $isUnderCurrentUser = false;
        $role = User::find($user_id)->roles()->first()->name;
        if(!empty($role)) {
            switch (current_user_role()) {
                case 'Finance Officer':
                    if(in_array($role, ['Project Admin', 'Project Partner', 'Funder'])) {
                        $isUnderCurrentUser = true;
                    }
                    break;
                case 'Project Admin':
                    if(in_array($role, ['Project Partner', 'Funder'])) {
                        $isUnderCurrentUser = true;
                    }
                    break;
                case 'Project Partner':
                    if(in_array($role, ['Funder'])) {
                        $isUnderCurrentUser = true;
                    }
                    break;
                default:
                    $isUnderCurrentUser = false;
                    break;
            }    
        }
        
        return $isUnderCurrentUser;
    }
}
