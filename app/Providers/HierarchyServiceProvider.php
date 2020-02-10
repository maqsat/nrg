<?php

namespace App\Providers;

use App;
use Illuminate\Support\ServiceProvider;

class HierarchyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('hierarchy', function()
        {
            return new \App\Helpers\Hierarchy;

        });
    }
}
