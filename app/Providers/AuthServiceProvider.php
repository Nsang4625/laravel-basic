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
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('update-post', function($user, $post){ // lar authentication will automatically add $user
            return $user -> id == $post -> user_id;
        });
        Gate::define('delete-post', function($user, $post){ // lar authentication will automatically add $user
            return $user -> id == $post -> user_id;
        });
        Gate::before(function($user, $ability){
            if($user -> is_admin && in_array($ability, ['update-post'])){ // inside this array is ability for admin
                // if we don't use in_array() it will provide all abilities
                return true;// and it will run 2 upon gate check 
            }
        });
    }
}
