<?php


namespace App\Services;


use App\Http\Requests\Playlist\createPlaylistrequest;
use App\Http\Requests\Playlist\listPlaylistrequest;
use App\Models\Playlist;
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
}
