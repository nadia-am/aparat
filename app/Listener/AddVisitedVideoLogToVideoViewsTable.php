<?php

namespace App\Listener;

use App\Events\VisitVideo;
use App\Models\VideoView;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

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
        try {
            $conditions = [
                'user_id'=> auth('api')->id(),
                'video_id'=> $event->getVideo()->id,
                ['created_at','>',now()->subDays(1)],
            ];
            if (!auth('api')->check()){
                $conditions['user_ip'] = client_ip();
            }
            if (! VideoView::where($conditions)->count()){
                $data = [
                    'user_id'=> auth('api')->id(),
                    'video_id'=> $event->getVideo()->id,
                    'user_ip'=> client_ip()
                ];
                VideoView::create($data);
            }
        }catch (\Exception $e){
            Log::error($e);
        }
    }
}
