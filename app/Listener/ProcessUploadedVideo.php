<?php

namespace App\Listener;

use App\Events\UploadNewVideo;
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
//            $filter = new CustomFilter("drawtext=text='x y z'");
        $videoFile = FFMpeg::fromDisk('videos')
            ->open('/tmp/'.$event->getRequest()->video_id)
//                ->addFilter($filter)//TODO add filter
            ->export()
            ->toDisk('videos')
            ->inFormat(new \FFMpeg\Format\Video\X264());
        $videoFile->save(auth()->id() . '/' .$event->getVideo()->slug . '.mp4');

        $event->getVideo()->duration = $videoFile->getDurationInSeconds();
        $event->getVideo()->save();
    }
}
