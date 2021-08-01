<?php


namespace App\Services;


use App\Http\Requests\channel\UpdateChannelRequest;
use App\Http\Requests\channel\UpdateSocialsRequest;
use App\Http\Requests\channel\UploadBannerForChannelRequest;
use App\Http\Requests\video\createVideoRequest;
use App\Http\Requests\video\UploadVideoRequest;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VideoService  extends BaseService
{
    public static function UploadVideoService(UploadVideoRequest $request)
    {
        try {
            $video = $request->file('video');
            $fileName =  time() . Str::random(10);
            $path = public_path('videos/tmp');
            $video->move( $path , $fileName);

            return response([  'video'=> $fileName ],200);
        }catch (\Exception $e){
            return response(['message'=>'خطایی رخ داده است!'],500);
        }

    }

    public static function CreateVideoService(createVideoRequest $request)
    {
dd($request->all());
    }

}
