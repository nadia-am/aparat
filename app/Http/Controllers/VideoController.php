<?php

namespace App\Http\Controllers;

use App\Http\Requests\video\ChangeStateVideoRequest;
use App\Http\Requests\video\createVideoRequest;
use App\Http\Requests\video\GetvideoListRequest;
use App\Http\Requests\video\RepublishVideoRequest;
use App\Http\Requests\video\UploadVideoBannerRequest;
use App\Http\Requests\video\UploadVideoRequest;
use App\Services\VideoService;

class VideoController extends Controller
{
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

    public function getList(GetvideoListRequest $request)
    {
        return VideoService::GetVideoListService($request);
    }

    public function republish(RepublishVideoRequest $request)
    {
        return VideoService::RepublishVideoService($request);
    }
}
