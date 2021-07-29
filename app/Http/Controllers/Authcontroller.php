<?php

namespace App\Http\Controllers;

use App\Exceptions\RegisterVerificationException;
use App\Exceptions\UserAlreadyRegisterException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Psy\Util\Str;

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
        try {
            DB::beginTransaction();
            $field = $request->getField();
            $value = $request->getFieldValue();

            $user = User::where($field,$value)->first();
            if (!$user){
                $code =  random_verification_code();
                $user = User::create([
                    $field => $value,
                    'verify_code' => $code,
                ]);
                $channelName = $field =='mobile'? Str::after($value, '+98'):Str::before($value, '@');
                $channel = $user->channel()->create(['name'=>$channelName]);

            }else{
                if ($user->verified_at){
                    throw new UserAlreadyRegisterException('شما قبلا ثبت نام کرده اید!');
                }
                $code =  random_verification_code();
                $user->verify_code = $code;
                $user->save();
                return response(['message'=>'کد فعالسازی مجددا برای شما ارسال گردید.'],200);
            }
            DB::commit();
            // TODO:send email or sms to user
            Log::info('send-register-code-message-to-user',['code',$code]);
            return response(['message'=>'کاربر ثبت موقت شد،'],200);
        }catch (\Exception $e){
            Log::error($e);
            DB::rollBack();
            response(['message'=>'خطایی رخ داده است'],500);
        }

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

    public function resendVerificationCode(ResendVerificationCodeRequest $request)
    {
        $field = $request->getField();
        $value = $request->getFieldValue();

        $user = User::where($field,$value)->whereNull('verified_at')->first();
        if (!empty($user)){
            $dateDiff = now()->diffInMinutes($user->updated_at);
            if ($dateDiff > config('auth.resend_verification_code_in_minuts',60)){
                $code = random_verification_code();
                $user->verify_code = $code;
                $user->save();
            }
            Log::info('resend-register-code-message-to-user',['code',$user->verify_code ]);
            return response([
                'message'=>'کد مجددا برای شما ارسال گردید.'
            ],200);
        }
        throw new ModelNotFoundException('کاربری با این مشخصات یافت نشد یا قبلا فعالسازی شده است!');
    }
}
