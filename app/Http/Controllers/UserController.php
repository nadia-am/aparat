<?php

namespace App\Http\Controllers;

use App\Http\Requests\user\ChangeEmailRequest;
use App\Http\Requests\user\ChangeEmailSubmitRequest;
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
}
