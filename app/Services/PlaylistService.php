<?php


namespace App\Services;


use App\Http\Requests\playlist\AddVideoToPlaylistRequset;
use App\Http\Requests\Playlist\createPlaylistrequest;
use App\Http\Requests\Playlist\listPlaylistrequest;
use App\Http\Requests\playlist\showPlaylistRequest;
use App\Http\Requests\playlist\sortVideosRequest;
use App\Models\Playlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlaylistService extends BaseService
{
    public static function getAllPlaylist(listPlaylistrequest $request)
    {
        return Playlist::all();
    }

    public static function getMyPlayList(listPlaylistrequest $request)
    {
        return auth()->user()->playlists;
    }

    public static function showPlaylist(showPlaylistRequest $request)
    {
        return Playlist::with('videos')
            ->find($request->playlist->id);
    }

    public static function createPlayList(createPlaylistrequest $request)
    {
        try {
            $data = $request->validated();
            $playlist = auth()->user()->playlists()->create($data);

            return response($playlist,200);
        }catch (\Exception $e){
            Log::error($e);
            return response(['message'=>'خطای رخ داده است'],500);
        }
    }

    public static function addVideoToPlayList(AddVideoToPlaylistRequset $request)
    {
        DB::table('playlist_videos')
            ->where('video_id',$request->video->id)
            ->delete();
        $request->playlist->videos()->attach($request->video->id);
        return response(['message'=>'ویدیو با موفقیت به لیست پخش مورد نظر اضافه شده'],200);
    }

    public static function sortVideos(sortVideosRequest $request)
    {
        $request->playlist
            ->videos()
            ->detach($request->videos);

        $request->playlist
            ->videos()
            ->attach($request->videos);

        return response(['message'=>'لیست پخش با موفقیت مرتب سازی شد'],200);
    }


}
