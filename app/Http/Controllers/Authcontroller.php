<?php

namespace App\Http\Controllers;

use App\Exceptions\RegisterVerificationException;
use App\Exceptions\UserAlreadyRegisterException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

class Authcontroller extends Controller
{
    /**
     * Register User
     *
     * @param RegisterNewUserRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws UserAlreadyRegisterException
     */
    public function register(RegisterNewUserRequest $request)
    {
        $field = $request->getField();
        $value = $request->getFieldValue();

        $user = User::where($field,$value)->first();
        if (!$user){
            $code = rand(10000,99999);
            $user = User::create([
                $field => $value,
                'verify_code' => $code,
            ]);
        }else{
            if ($user->verified_at){
                throw new UserAlreadyRegisterException('شما قبلا ثبت نام کرده اید!');
            }
            $code = rand(10000,99999);
            $user->verify_code = $code;
            $user->save();
            return response(['message'=>'کد فعالسازی مجددا برای شما ارسال گردید.'],200);
        }

        // TODO:send email or sms to user
        Log::info('send-register-code-message-to-user',['code',$code]);
        return response(['message'=>'کاربر ثبت موقت شد، کد فعالسازی برای شما ارسال گردید.'],200);
    }

    /**
     * Send User verification code
     *
     * @param RegisterVerifyUserRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function registerVerify(RegisterVerifyUserRequest $request)
    {
        $field = $request->getField();
        $value = $request->getFieldValue();
//        $field = $request->has('email') ? 'email':'mobile';
//        $value = $request->input($field);
        $code = $request->code;

        $user = User::where([
            'verify_code'=> $code,
            $field => $value
        ])->first();
        if (empty($user)){
            throw  new  ModelNotFoundException('کاربری با کد مورد نظر یافت نشد!');
        }
        $user->verify_code = null;
        $user->verified_at = now();
        $user->save();
        return response($user,200);
    }

    public function resendVerificationCode()
    {

    }
}
