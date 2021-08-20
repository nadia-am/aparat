<?php

namespace App\Listener;

use App\Events\ActveUnregisteredUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Events\AccessTokenCreated;

class ActiveUnregisterUserAfterLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param AccessTokenCreated $event
     * @return void
     * @throws Exception
     */
    public function handle(AccessTokenCreated $event)
    {

        $user = User::withTrashed()->find($event->userId);
        if ($user->trashed()){
            try {
                DB::beginTransaction();
                $user->restore();
                event(new ActveUnregisteredUser($user));
                Log::info('Active unregister user',['user_id'=>$user->id]);
                DB::commit();
            }catch (Exception $e){
                DB::rollBack();
                Log::error($e);
                throw $e;
            }
        }
    }
}
