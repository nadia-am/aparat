<?php

namespace App\Providers;

use App\Events\ActveUnregisteredUser;
use App\Events\DeleteVideo;
use App\Events\UploadNewVideo;
use App\Events\VisitVideo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Events\AccessTokenCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UploadNewVideo::class =>[
            'App\Listener\ProcessUploadedVideo'
        ],
        VisitVideo::class =>[
            'App\Listener\AddVisitedVideoLogToVideoViewsTable'
        ],
        AccessTokenCreated::class =>[
            'App\Listener\ActiveUnregisterUserAfterLogin'
        ],
        ActveUnregisteredUser::class =>[
            //TODO after unregister usered loged in
        ],
        DeleteVideo::class=>[
            'App\Listener\DeleteVideoData'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
