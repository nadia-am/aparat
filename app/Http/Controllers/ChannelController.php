<?php

namespace App\Http\Controllers;

use App\Http\Requests\channel\UpdateChannelRequest;
use App\Http\Requests\channel\UpdateSocialsRequest;
use App\Http\Requests\channel\UploadBannerForChannelRequest;
use App\Services\ChannelService;

class ChannelController extends Controller
{
    public function update(UpdateChannelRequest $request)
    {
        return ChannelService::UpdateChannelService($request);
    }

    public function uploadBanner(UploadBannerForChannelRequest $request)
    {
        return ChannelService::UploadBannerForChannelService($request);
    }

    public function updatesocials(UpdateSocialsRequest $request)
    {
        return ChannelService::UpdateSocials($request);
    }


}
