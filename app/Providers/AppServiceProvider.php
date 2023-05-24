<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Ramsey\Uuid\Type\Integer;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        /* Model::preventLazyLoading(App::environment('local'));
        Model::preventSilentlyDiscardingAttributes();
        Model::preventAccessingMissingAttributes();
        Model::unguard();

        Model::shouldBeStrict( App::environment('local') ); */

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        
        Paginator::useBootstrapFive();
    }
}
