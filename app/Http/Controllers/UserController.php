<?php

namespace App\Http\Controllers;

use App\Http\Requests\user\ChangeEmailRequest;
use App\Http\Requests\user\ChangeEmailSubmitRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    const CACHE_EMAIL_KEY = 'change.email.for.user';

    public function changeEmail(ChangeEmailRequest $request)
    {
        try {
            $email = $request->email;
            $userId = auth()->id();
            $code = random_verification_code();
            $time = config('auth.token_expiration.token',1440);

            Cache::put(self::CACHE_EMAIL_KEY.$userId,compact('code','email'),$time);
            // TODO send email to user
            Log::info('SEND-CHANGE-EMAIL-CODE',compact('code'));
            return response(['message'=>'ایمیلی برای شما ارسال شد لطفا صندوق ورودی خود را چک کنید'],200);
        }catch (\Exception $e){
            Log::error($e);
            return response(['message'=>'خطایی رخ داده سرور قادر به ارسال کد فعالسازی نمی باشد'],500);
        }
    }

    public function changeEmailSubmit(ChangeEmailSubmitRequest $request)
    {
        $userId = auth()->id();
        $cashKey = self::CACHE_EMAIL_KEY.$userId;
        $cache = Cache::get($cashKey);
        if (empty($cache) || $cache['code'] != $request->code ){
            return response(['message'=>'درخواست نامعتبر است'],400);
        }

        $user = auth()->user();
        $user->email = $cache['email'];
        $user->save();
        Cache::forget($cashKey);
        return response(['message'=>'ایمیل با موفقعیت تغییر یافت'],200);
    }
}
