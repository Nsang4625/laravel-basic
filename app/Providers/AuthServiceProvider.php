<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\BlogPost' => 'App\Policies\BlogPostPolicy' 
        // map model to its policy 
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies(); // you can't remove this code because it tells laravel
        // which policy to use for which model

        // Gate::define('update-post', function($user, $post){ // lar authentication will automatically add $user
        //     return $user -> id == $post -> user_id;
        // });
        // Gate::define('delete-post', function($user, $post){ // lar authentication will automatically add $user
        //     return $user -> id == $post -> user_id;
        // });
        // Gate::define('posts.update', 'App\Policies\BlogPostPolicy@update');
        // Gate::define('posts.delete', 'App\Policies\BlogPostPolicy@delete');
            // like map a name as 'posts.update' to a method after @ 
        // Gate::resource('posts', 'App\Policies\BlogPostPolicy');// have all definition in policy file
        Gate::before(function ($user, $ability) {
            if ($user->is_admin && in_array($ability, ['delete', 'update'])) { // inside this array is ability for admin
                // if we don't use in_array() it will provide all abilities
                return true; // and it will run 2 upon gate check 
            }
        });
        Gate::define('home.secret', function($user){
            return $user -> is_admin;
        });
    }
}
