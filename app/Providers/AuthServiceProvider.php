<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-users', function ($user) {
            return ($user->authorized && ($user->name === 'ylo' || $user->name === 'mdschwan' || $user->name === 'mollybabel'))
                  ? Response::allow()
                  : Response::deny('You must be authorized to manage users.');
        });

        Gate::define('manage-data', function ($user) {
            return $user->authorized
                  ? Response::allow()
                  : Response::deny('You must be authorized to manage data.');
        });

    }
}
