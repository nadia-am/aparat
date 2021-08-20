<?php

namespace App\Listener;

use App\Events\DeleteVideo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteVideoData
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
     * @param  DeleteVideo  $event
     * @return void
     */
    public function handle(DeleteVideo $event)
    {
        $video = $event->getVideo();
        Storage::disk('videos')
            ->delete(auth()->id() . '/' . $video->banner);
        Storage::disk('videos')
            ->delete(auth()->id() . '/' . $video->slug . '.mp4');
    }
}
