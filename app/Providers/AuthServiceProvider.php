<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Video;
use App\Policies\CommentPolicy;
use App\Policies\UserPolicy;
use App\Policies\VideoPolicy;
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
        Video::class => VideoPolicy::class,
        User::class => UserPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerGates();
        //add passport routes
//        Passport::routes();
        //set expire time
        Passport::tokensExpireIn(now()->addMinute(config('auth.token_expiration.token')));
        Passport::refreshToken(now()->addMinute(config('auth.token_expiration.refresh_token')));

    }

    public function registerGates()
    {
        Gate::before(function ($user , $bility){
            if ($user->isAdmin()){
                return true;
            }
        });
    }
}
