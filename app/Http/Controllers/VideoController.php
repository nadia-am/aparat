<?php

namespace App\Http\Controllers;

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
use App\Services\VideoService;

class VideoController extends Controller
{
    public function getList(GetvideoListRequest $request)
    {
        return VideoService::GetVideoListService($request);
    }

    public function show(showVideoRequest $request)
    {
        return VideoService::ShowVideoService($request);
    }

    public function upload( UploadVideoRequest $request )
    {
        return VideoService::UploadVideoService($request);
    }

    public function uploadBanner( UploadVideoBannerRequest $request )
    {
        return VideoService::UploadBannerService($request);
    }

    public function create(createVideoRequest $request)
    {
        return VideoService::CreateVideoService($request);
    }

    public function changeState(ChangeStateVideoRequest $request)
    {
        return VideoService::ChangeStateVideoService($request);
    }

    public function republish(RepublishVideoRequest $request)
    {
        return VideoService::RepublishVideoService($request);
    }

    public function like(LikeVideoRequest $request)
    {
        return VideoService::LikeVideoService($request);
    }

    public function unlike(unLikeVideoRequest $request)
    {
        return VideoService::unLikeVideoService($request);
    }

    public function likedByCurrentUser(LikedByCurrentUserRequest $request)
    {
        return VideoService::LikeedByCurrentUserService($request);
    }

    public function delete(videoDeleteRequest $request)
    {
        return VideoService::deleteVideoService($request);
    }

    public function statistics(videoStatisticsRequset $request)
    {
        return VideoService::videoStatisticsService($request);
    }


}
