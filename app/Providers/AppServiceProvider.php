<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Ramsey\Uuid\Type\Integer;

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
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        
        Paginator::useBootstrapFive();
        
        Blade::directive('blog_date', function (string $expression) {            
            return "<?php echo ($expression)->format('M jS \'y'); ?>";
        });

        Blade::directive('blog_url', function (array $expression) {
            list($category, $slug, $post_id) = $expression;
            return "<?php echo env('POST_URL_PREFIX').$category; ?>";
        });
    }
}
