<?php

namespace App\Http\Controllers;

use App\Http\Requests\user\followingUserRequest;
use App\Http\Requests\user\FollowUserChannelRequest;
use App\Http\Requests\user\unFollowUserChannelRequest;
use App\Http\Requests\user\ChangeEmailRequest;
use App\Http\Requests\user\ChangeEmailSubmitRequest;
use App\Http\Requests\user\ChangePasswordRequest;
use App\Http\Requests\user\unregisterUserRequest;
use App\Services\UserService;

class UserController extends Controller
{

    /**
     * change user's email
     * @param ChangeEmailRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeEmail(ChangeEmailRequest $request)
    {
        return UserService::changeEmail($request);
    }
    /**
     * confirm change user's email
     * @param ChangeEmailSubmitRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeEmailSubmit(ChangeEmailSubmitRequest $request)
    {
        return UserService::changeEmailSubmit($request);
    }
    public function changePassword(ChangePasswordRequest $request)
    {
        return UserService::changePassword($request);
    }
    public function follow(FollowUserChannelRequest $request)
    {
        return UserService::FollowService($request);
    }
    public function unfollow(unFollowUserChannelRequest $request)
    {
        return UserService::unFollowService($request);
    }
    public function followings(followingUserRequest $request)
    {
        return UserService::userFollowingService($request);

    }
    public function followers(followingUserRequest $request)
    {
        return UserService::userFollowersService($request);

    }
    public function unregister(unregisterUserRequest $request)
    {
        return UserService::unregisterService($request);
    }
}
