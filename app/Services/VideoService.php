<?php


namespace App\Services;

use App\Http\Requests\video\createVideoRequest;
use App\Http\Requests\video\UploadVideoBannerRequest;
use App\Http\Requests\video\UploadVideoRequest;
use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            $duration = 0;//TODO get duration
            DB::beginTransaction();

            $video = Video::create([
                'user_id'=>auth()->id() ,
                'category_id'=>$request->category ,
                'channel_category_id'=>$request-> channel_category,
                'slug'=> ''  ,//create slug in update
                'title'=>$request->title ,
                'info'=>$request->info ,
                'duration'=>  $duration ,//TODO get duration
                'banner'=>null,
                'published_at'=>$request->published_at ,
            ]);
            $video->slug = uniq_id( auth()->id());
            $video->banner =  $video->slug . '-banner';
            $video->save();

            Storage::disk('videos')->move( '/tmp/'.$request->video_id , auth()->id(). '/' . $video->slug );
            if (!empty($request->banner )){
                $banner_name = $video->slug . '-banner';
                Storage::disk('videos')->move( '/tmp/'.$request->banner , auth()->id().'/'.$banner_name );
            }

            if (!empty($request->playList)){
                $playlist = Playlist::find($request->playList);
                $playlist->videos()->attach($video->id);
            }
            if (!empty($request->tags)){
                $video->tags()->attach($request->tags);
            }

            DB::commit();
            return response(['data'=>$video],200);
        }catch (\Exception $e){
            Log::error($e);
            DB::rollBack();
            return response(['message'=>'خطایی رخ داده است!'],500);
        }


    }

}
