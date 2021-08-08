<?php

namespace App\Listener;

use App\Events\UploadNewVideo;
use App\Jobs\ConvertAndAddWaterMarkToUploadedVideoJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ProcessUploadedVideo
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
     * @param  UploadNewVideo  $event
     * @return void
     */
    public function handle(UploadNewVideo $event)
    {
        ConvertAndAddWaterMarkToUploadedVideoJob::dispatch($event->getVideo() , $event->getRequest()->video_id);
    }
}
