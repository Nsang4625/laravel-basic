<?php
namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use App\Services\Counter;
use App\View\Components\badge;
use App\View\Components\updated;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
        // Blade::component( 'badge', badge::class);
        // Blade::component('updated', updated::class);
        // Blade::component('components.card', 'card');
        view()->composer(['posts.index', 'posts.show'], ActivityComposer::class);
        // $this->app->bind(Counter::class, function($app) {
        //     return new Counter(5);
        //     });
        // this will register a class/interface to Container
        $this->app->singleton(Counter::class, function($app) {
            return new Counter(5);
            });
        // this also register like bind method above but when resolve, always returns the same instance instance
    }
}
