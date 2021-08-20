<?php


namespace App\Services;

use App\Events\DeleteVideo;
use App\Events\UploadNewVideo;
use App\Events\VisitVideo;
use App\Http\Requests\video\ChangeStateVideoRequest;
use App\Http\Requests\video\createVideoRequest;
use App\Http\Requests\video\GetvideoListRequest;
use App\Http\Requests\video\LikedByCurrentUserRequest;
use App\Http\Requests\video\LikeVideoRequest;
use App\Http\Requests\video\RepublishVideoRequest;
use App\Http\Requests\video\showVideoRequest;
use App\Http\Requests\video\unLikeVideoRequest;
use App\Http\Requests\video\UploadVideoBannerRequest;
use App\Http\Requests\video\UploadVideoRequest;
use App\Http\Requests\video\videoDeleteRequest;
use App\Http\Requests\video\videoStatisticsRequset;
use App\Models\Playlist;
use App\Models\Video;
use App\Models\VideoFavourit;
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
        $user = auth('api')->user();
        if ($request->has('republished')){
            if ($user){
                $videos = $request->republished ? $user->republishVideos() : $user->channelVideos();
            }else{
                $videos = $request->republished ? Video::whereRepublished() : Video::whereNotRepublished() ;
            }

        }else{
            $videos = $user ? $user->videos() : Video::query() ;
        }

        return $videos
                    ->orderBy('id')
                    ->paginate();
    }

    public static function UploadVideoService(UploadVideoRequest $request)
    {
        try {
            $video = $request->file('video');
            $fileName =  time() . Str::random(10);
            Storage::disk('videos')->put( '/tmp/' . $fileName , $video->get() );

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
//                Storage::move($oldBannerPath, $newBannerPath);
                if(!File::isDirectory( public_path('videos/'.  auth()->id() . '/'))){
                    File::makeDirectory( public_path('videos/'.  auth()->id() . '/'), 0777, true, true);
                }
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
            dd($e);
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

    public static function LikeVideoService(LikeVideoRequest $request)
    {
        VideoFavourit::create([
            'user_id'=> auth('api')->id(),
            'user_ip'=> client_ip(),
            'video_id'=> $request->video->id
        ]);
        return response(['با موفقعیت ثبت شد'],200);
    }

    public static function unLikeVideoService(unLikeVideoRequest $request)
    {
        $user = auth('api')->user();
        $conditions = [
            'video_id'=>$request->video->id ,
            'user_id'=>$user ? $user->id : null
        ];
        if (empty($user)){
            $conditions['user_ip'] = client_ip();
        }
        VideoFavourit::where($conditions)->delete() ;
        return response(['با موفقعیت ثبت شد'],200);
    }

    public static function LikeedByCurrentUserService(LikedByCurrentUserRequest $request)
    {
        $user = auth()->user();
        $videos = $user->favouritVideos()
        ->paginate();
        return $videos;
    }

    public static function ShowVideoService(showVideoRequest $request)
    {
        event(new VisitVideo($request->video));
        return $request->video;
    }

    public static function deleteVideoService(videoDeleteRequest $request)
    {
        try {
            DB::beginTransaction();
            $request->video->forceDelete();
            event(new DeleteVideo($request->video));
            DB::commit();
            return response(['message'=>'حذف با موفقیت انجام شد'],200);
        }catch (\Exception $e){
            DB::rollBack();
            Log::error($e);
            return response(['message'=>'حذف انجام نشد'],500);
        }
    }

    public static function videoStatisticsService(videoStatisticsRequset $request)
    {
        dd('statistics');
    }


}
