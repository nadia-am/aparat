<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoRepublish;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    public function changeState(User $user , Video $video=null)
    {
        return $user->isAdmin();
    }

    public function republish(User $user , Video $video=null)
    {
        return $video &&
            (
                //this video is'nt mine
                $video->user_id != $user->id &&
                //if this video didnt republished by me
                VideoRepublish::where([
                    'user_id'=> auth()->id() ,
                    'video_id'=>$video->id
                ])->count() < 1
            );
    }
}
