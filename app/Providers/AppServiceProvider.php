<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

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

        view()->composer('pages.user.*', function ($view) {
            $mostRecentProject = Cache::get('recent-projects') ? Cache::get('recent-projects')->first() : null;

            $view->with('mostRecentProject', $mostRecentProject);
        });
    }
}
