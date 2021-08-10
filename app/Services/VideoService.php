<?php


namespace App\Services;

use App\Events\UploadNewVideo;
use App\Http\Requests\video\ChangeStateVideoRequest;
use App\Http\Requests\video\createVideoRequest;
use App\Http\Requests\video\GetvideoListRequest;
use App\Http\Requests\video\RepublishVideoRequest;
use App\Http\Requests\video\UploadVideoBannerRequest;
use App\Http\Requests\video\UploadVideoRequest;
use App\Models\Playlist;
use App\Models\Video;
use App\Models\VideoRepublish;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoService  extends BaseService
{
    public static function GetVideoListService(GetvideoListRequest $request)
    {
        $user = auth()->user();
        if ($request->has('republished')){
            $videos = $request->republished ? $user->republishVideos() : $user->channelVideos();
        }else{
            $videos = $user->videos();
        }
        return $videos->paginate(10);
    }

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
            DB::beginTransaction();

            //save video in db
            $video = Video::create([
                'user_id'               =>auth()->id() ,
                'category_id'           =>$request->category ,
                'channel_category_id'   =>$request-> channel_category,
                'slug'                  => ''  ,//create slug in update
                'title'                 => $request->title ,
                'info'                  => $request->info ,
                'duration'              =>  0 ,
                'banner'                => null,
                'enable_comments'       => $request->enable_comments,
                'enable_watermark'      => $request->enable_watermark,
                'published_at'          => $request->published_at ,
                'state'                 => Video::STATE_PENDING
            ]);
            //add slug and banner to created video
            $video->slug = uniqe_id( auth()->id());
            $video->banner =  $video->slug . '-banner';
            $video->save();

            //save video & banner in public folder
            event(new UploadNewVideo($video , $request));
            if (!empty($request->banner )){
                $banner_name = $video->slug . '-banner';
                $oldBannerPath = public_path('videos/tmp/'.$request->banner);
                $newBannerPath = public_path('videos/'.  auth()->id() . '/' . $banner_name);
                File::move($oldBannerPath, $newBannerPath);
            }
            //add playlist to video
            if (!empty($request->playList)){
                $playlist = Playlist::find($request->playList);
                $playlist->videos()->attach($video->id);
            }
            //add tags to video
            if (!empty($request->tags)){
                $video->tags()->attach($request->tags);
            }

            DB::commit();
            return response($video,200);
        }catch (\Exception $e){
            Log::error($e);
            DB::rollBack();
            return response(['message'=>'خطایی رخ داده است!'],500);
        }


    }

    public static function ChangeStateVideoService(ChangeStateVideoRequest $request)
    {

        $video = $request->video;
        $video->state = $request->state;
        $video->save();
        return response($video);

    }

    public static function RepublishVideoService(RepublishVideoRequest $request)
    {
        try {
            $user = auth()->user();
            VideoRepublish::create([
                'user_id' => auth()->id(),
                'video_id' => $request->video->id,
            ]);
            return response(['message'=>'باز نشر با موفقعیت انجام شد' ],200);
        }catch (\Exception $e){
           Log::error($e);
            return response(['message'=>'بازنشر انجام نشد! لطفا مجددا تلاش کنید!'],500);
        }
    }


}
