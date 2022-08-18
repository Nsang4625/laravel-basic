<?php
namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use App\View\Components\badge;
use App\View\Components\updated;
use Illuminate\Support\Facades\Blade;
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
        // Blade::component( 'badge', badge::class);
        // Blade::component('updated', updated::class);
        // Blade::component('components.card', 'card');
        view()->composer(['posts.index', 'posts.show'], ActivityComposer::class);
    }
}
