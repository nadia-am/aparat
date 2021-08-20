<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoFavourit;
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
        return $video &&  $video->isAccepted() &&
            (
                //this video is'nt mine
                $video->user_id != $user->id &&
                //if this video didn't republished by me
                VideoRepublish::where([
                    'user_id'=> auth()->id() ,
                    'video_id'=>$video->id
                ])->count() < 1
            );
    }

    public function like(User $user = null , Video $video=null)
    {
        if ($video && $video->isAccepted() ){
            $conditions = [
                'video_id'=>$video->id ,
                'user_id'=>$user ? $user->id : null
            ];
            if (empty($user)){
                $conditions['user_ip'] = client_ip();
            }
            return VideoFavourit::where($conditions)->count() == 0 ;
        }
        return false;
    }

    public function unlike(User $user = null , Video $video=null)
    {
        $conditions = [
            'video_id'=>$video->id ,
            'user_id'=>$user ? $user->id : null
        ];
        if (empty($user)){
            $conditions['user_ip'] = client_ip();
        }
        return VideoFavourit::where($conditions)->count() ;
    }

    public function getLikedList(User $user, Video $video=null)
    {
        return true;
    }

    public function delete(User $user, Video $video)
    {
        return $user->id == $video->user_id;
    }

    public function viewStatistics(User $user, Video $video)
    {

    }
}
