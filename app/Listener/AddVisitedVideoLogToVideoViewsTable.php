<?php

namespace App\Listener;

use App\Events\VisitVideo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddVisitedVideoLogToVideoViewsTable
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  VisitVideo  $event
     * @return void
     */
    public function handle(VisitVideo $event)
    {
        //
    }
}
