<?php

namespace App\Http\Controllers;

use App\Http\Requests\Playlist\createPlaylistrequest;
use App\Http\Requests\Playlist\listPlaylistrequest;
use App\Services\PlaylistService;
use Illuminate\Http\Request;

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

    public function create(createPlaylistrequest $request){
        return PlaylistService::createPlayList($request);
    }
}
