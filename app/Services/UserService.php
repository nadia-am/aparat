<?php


namespace App\Services;


use App\Exceptions\UserAlreadyRegisterException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Http\Requests\user\followingUserRequest;
use App\Http\Requests\user\FollowUserChannelRequest;
use App\Http\Requests\user\unFollowUserChannelRequest;
use App\Http\Requests\user\ChangeEmailRequest;
use App\Http\Requests\user\ChangeEmailSubmitRequest;
use App\Http\Requests\user\ChangePasswordRequest;
use App\Http\Requests\user\unregisterUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService extends BaseService
{

    const CACHE_EMAIL_KEY = 'change.email.for.user';

    public static function registerNewUser(RegisterNewUserRequest $request)
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
        }
        catch (\Exception $e){
            DB::rollBack();
            if ($e instanceof UserAlreadyRegisterException ){
                throw $e;
            }
            Log::error($e);
            response(['message'=>'خطایی رخ داده است'],500);
        }
    }

    public static function registerVerify(RegisterVerifyUserRequest $request)
    {
        $field = $request->getField();
        $value = $request->getFieldValue();
        $code = $request->code;
        $user = User::where([
            'verify_code'=> $code,
            $field => $value
        ])->first();
        if (empty($user)){
            throw  new  ModelNotFoundException('کاربری با اطلاعات مورد نظر یافت نشد!');
        }
        $user->verify_code = null;
        $user->verified_at = now();
        $user->save();
        return response($user,200);
    }

    public static function ResendVerificationCodeToUser(ResendVerificationCodeRequest $request)
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

    public static function changeEmail(ChangeEmailRequest $request)
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

    public static function changeEmailSubmit(ChangeEmailSubmitRequest $request)
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

    public static function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = auth()->user();
            if (! Hash::check($request->old_password,$user->password)){
                return response(['message'=>'گذرواژه وارد شده اشتباه است.'],400);
            }
            $user->update([ 'password' => bcrypt($request->password) ]);
            return response(['message'=>'تغییر گذرواژه با موفقعیت انجام شد.'],200);
        }catch (\Exception $e){
            Log::error($e);
            return response(['message'=>'خطایی رخ داد است!'],500);
        }
    }

    public static function FollowService(FollowUserChannelRequest $request)
    {
        $user = $request->user();
        $user->follow($request->channel->user);
        return response(['message'=>'با موفقعیت انجام شد.'],200);
    }

    public static function unFollowService(unFollowUserChannelRequest $request)
    {
        $user = $request->user();
        $user->unfollow($request->channel->user);
        return response(['message'=>'با موفقعیت انجام شد.'],200);
    }

    public static function userFollowingService(followingUserRequest $request)
    {
        return $request->user()->followings()->paginate();
    }

    public static function userFollowersService(followingUserRequest $request)
    {
        return $request->user()->followers()->paginate();
    }

    public static function unregisterService(unregisterUserRequest $request)
    {
        try{
            DB::beginTransaction();
            $request->user()->delete();
            DB::commit();
            return response(['message'=>'کاربر با موفقیت غیر فعال شد، برای فعالسازی مجددا یکبار در سیستم ورود کنید'],200);
        }catch (\Exception $e){
            DB::rollBack();
            Log::error($e);
            return response(['message'=>'حذف امکان پذیر نشد، مجددا تلاش کنید'],500);
        }

    }
}
