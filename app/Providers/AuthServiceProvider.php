<?php

namespace App\Providers;

use App\Models\Check;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\CheckPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Check::class => CheckPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function ($user) {
            return $user->role === 'administrator';
        });

        Gate::define('accept-check', function ($user, $check) {
            return $user->role === 'administrator';
        });

        Gate::define('reject-check', function ($user, $check) {
            return $user->role === 'administrator';
        });
    }
}
