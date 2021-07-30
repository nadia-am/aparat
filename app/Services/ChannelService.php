<?php


namespace App\Services;


use App\Http\Requests\channel\UpdateChannelRequest;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChannelService  extends BaseService
{

    public static function UpdateChannelService(UpdateChannelRequest $request)
    {
        try {
            //TODO check if its admin to update others user channel
            if ( $channelId = $request->route('id')){
                if (auth()->user()->type != User::TYPES_ADMIN){
                    throw new AuthorizationException('شما به این بخش دسترسی ندارید!');
                }
                $channel = Channel::findOrFail($channelId);
                $user = $channel->user;
            }else{
                $user = auth()->user();
                $channel = $user->channel;
            }

            DB::beginTransaction();
            $channel->name = $request->name ;
            $channel->info = $request->info ;
            $channel->save();

            $user->website = $request->website;
            $user->save();
            DB::commit();
            return response(['message'=>'تغییرات کانال انجام شد.'],200);
        }catch (\Exception $e){
           DB::rollBack();
           if ($e instanceof AuthorizationException){
               throw $e;
           }
           Log::error($e);
           return response(['message'=>'خطایی رخ داده است!'],500);
        }

    }


}
