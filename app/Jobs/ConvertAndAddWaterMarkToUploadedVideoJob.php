<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Math\PrimeField\Integer;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertAndAddWaterMarkToUploadedVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Video
     */
    private $video;
    /**
     * @var Integer
     */
    private $video_id;
    /**
     * @var int|string|null
     */
    private $user_id;


    /**
     * Create a new job instance.
     *
     * @param Video $video
     * @param string $video_id
     */
    public function __construct(Video $video , string $video_id)
    {
        $this->video = $video;
        $this->video_id = $video_id;
        $this->user_id = auth()->id();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//            $filter = new CustomFilter("drawtext=text='x y z'");
        $videoFile = FFMpeg::fromDisk('videos')
            ->open('/tmp/'.$this->video_id)
//                ->addFilter($filter)//TODO add filter
            ->export()
            ->toDisk('videos')
            ->inFormat(new \FFMpeg\Format\Video\X264());
        $videoFile->save($this->user_id . '/' .$this->video->slug . '.mp4');

//        Storage::delete( public_path('videos/tmp/' . $this->video_id) );
        Storage::disk('videos')->delete('/tmp/'.$this->video_id);

        $this->video->duration = $videoFile->getDurationInSeconds();
        $this->video->save();
    }
}
