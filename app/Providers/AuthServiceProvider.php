<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
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
        //add passport routes
//        Passport::routes();
        //set expire time
        Passport::tokensExpireIn(now()->addMinute(config('auth.token_expiration.token')));
        Passport::refreshToken(now()->addMinute(config('auth.token_expiration.refresh_token')));

        //
    }
}
