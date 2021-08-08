<?php


namespace App\Services;

use App\Http\Requests\video\createVideoRequest;
use App\Http\Requests\video\UploadVideoBannerRequest;
use App\Http\Requests\video\UploadVideoRequest;
use App\Models\Playlist;
use App\Models\Video;
use FFMpeg\Filters\Audio\CustomFilter;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Filesystem\Media;
use ProtoneMedia\LaravelFFMpeg\MediaOpener;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoService  extends BaseService
{
    public static function UploadVideoService(UploadVideoRequest $request)
    {
        try {
            $video = $request->file('video');
            $fileName =  time() . Str::random(10);
            Storage::disk('videos')->put( '/tmp/' . $fileName,$video->get() );

            return response([  'video'=> $fileName ],200);
        }catch (\Exception $e){
            return response(['message'=>'خطایی رخ داده است!'],500);
        }

    }

    public static function UploadBannerService(UploadVideoBannerRequest $request)
    {
        try {
            $banner = $request->file('banner');
            $fileName =  time() . Str::random(10) . '-banner';
            Storage::disk('videos')->put( '/tmp/' . $fileName,$banner->get() );

            return response([  'banner'=> $fileName ],200);
        }catch (\Exception $e){
            return response(['message'=>'خطایی رخ داده است!'],500);
        }
    }

    public static function CreateVideoService(createVideoRequest $request)
    {
        try {
//            $filter = new CustomFilter("drawtext=text='x y z'");
            $videoFile = FFMpeg::fromDisk('videos')
                ->open('/tmp/'.$request->video_id)
//                ->addFilter($filter)//TODO add filter
                ->export()
                ->toDisk('videos')
                ->inFormat(new \FFMpeg\Format\Video\X264());

            $duration = $videoFile->getDurationInSeconds();
            DB::beginTransaction();

            $video = Video::create([
                'user_id'=>auth()->id() ,
                'category_id'=>$request->category ,
                'channel_category_id'=>$request-> channel_category,
                'slug'=> ''  ,//create slug in update
                'title'=> $request->title ,
                'info'=> $request->info ,
                'duration'=>  $duration ,
                'banner'=> null,
                'enable_comments'=> $request->enable_comments,
                'published_at'=> $request->published_at ,
            ]);
            $video->slug = uniq_id( auth()->id());
            $video->banner =  $video->slug . '-banner';
            $video->save();

            $videoFile->save(auth()->id() . '/' .$video->slug . '.mp4');
            Storage::delete( public_path('videos/tmp/' . $request->video_id) );

//            $oldPath = public_path('videos/tmp/'.$request->video_id);
//            $newPath = public_path('videos/'.  auth()->id() . '/' .$video->slug);
//            File::move($oldPath, $newPath);
            if (!empty($request->banner )){
                $banner_name = $video->slug . '-banner';

                $oldBannerPath = public_path('videos/tmp/'.$request->banner);
                $newBannerPath = public_path('videos/'.  auth()->id() . '/' . $banner_name);
                File::move($oldBannerPath, $newBannerPath);
            }

            if (!empty($request->playList)){
                $playlist = Playlist::find($request->playList);
                $playlist->videos()->attach($video->id);
            }
            if (!empty($request->tags)){
                $video->tags()->attach($request->tags);
            }

            DB::commit();
            return response($video,200);
        }catch (\Exception $e){
            dd($e);
            Log::error($e);
            DB::rollBack();
            return response(['message'=>'خطایی رخ داده است!'],500);
        }


    }

}
