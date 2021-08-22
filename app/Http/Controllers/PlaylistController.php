<?php

namespace App\Http\Controllers;

use App\Http\Requests\playlist\AddVideoToPlaylistRequset;
use App\Http\Requests\Playlist\createPlaylistrequest;
use App\Http\Requests\Playlist\listPlaylistrequest;
use App\Http\Requests\playlist\showPlaylistRequest;
use App\Http\Requests\playlist\sortVideosRequest;
use App\Services\PlaylistService;

class PlaylistController extends Controller
{
    public function index(listPlaylistrequest $request)
    {
        return PlaylistService::getAllPlaylist($request);
    }

    public function my(listPlaylistrequest $request)
    {
        return PlaylistService::getMyPlayList($request);
    }

    public function show(showPlaylistRequest $request)
    {
        return PlaylistService::showPlaylist($request);
    }

    public function create(createPlaylistrequest $request){
        return PlaylistService::createPlayList($request);
    }

    public function add_video(AddVideoToPlaylistRequset $request)
    {
        return PlaylistService::addVideoToPlayList( $request);
    }

    public function sortVideos(sortVideosRequest $request)
    {
        return PlaylistService::sortVideos( $request);
    }
}
