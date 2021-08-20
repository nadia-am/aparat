<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Boolean;
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
     * @var Boolean
     */
    private $addWatermark;


    /**
     * Create a new job instance.
     *
     * @param Video $video
     * @param string $video_id
     * @param bool $addWatermark
     */
    public function __construct(Video $video , string $video_id , bool $addWatermark)
    {
        $this->video = $video;
        $this->video_id = $video_id;
        $this->user_id = auth()->id();
        $this->addWatermark = $addWatermark;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $uploadedVideoPath = '/tmp/' . $this->video_id; // video_id => uploaded video id
        if ($this->video->trashed() || !Video::where('id',$this->video_id)->count() ){
            Storage::disk('videos')
                ->delete($uploadedVideoPath);
            return ;
        }

        $videoUploaded = FFMpeg::fromDisk('videos')->open($uploadedVideoPath);
        $formate = new X264('libmp3lame');
        //add water mark
        if ($this->addWatermark){//TODO add water mark
            $filter = new CustomFilter("drawtext=text='my custom video':fontcolor=red: fontsize=24 ");
            $videoUploaded = $videoUploaded->addFilter($filter);
        }
        $videoFile = $videoUploaded->export()
                                   ->toDisk('videos')
                                   ->inFormat($formate);
        $videoFile->save($this->user_id . '/' . $this->video->slug . '.mp4');




        $this->video->duration = $videoFile->getDurationInSeconds();
        $this->video->state = Video::STATE_CONVERTED;
        $this->video->save();

        Storage::disk('videos')->delete('/tmp/'.$this->video_id);
    }
}
