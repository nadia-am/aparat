<?php

namespace App\Http\Controllers;

use App\Http\Requests\channel\UpdateChannelRequest;
use App\Services\ChannelService;

class ChannelController extends Controller
{
    public function update(UpdateChannelRequest $request)
    {
        return ChannelService::UpdateChannelService($request);
    }
}
