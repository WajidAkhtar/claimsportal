<?php

namespace App\Providers;

use Schema;
use App\Observers\ProjectObserver;
use Illuminate\Support\Facades\App;
use Illuminate\Pagination\Paginator;
use App\Domains\Claim\Models\Project;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Project::observe(ProjectObserver::class);
        
        if(App::environment('local')) {
            Schema::defaultStringLength(191);
        }
    }
}
