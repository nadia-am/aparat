<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function changeCommentState(User $user , Comment $comment ,$state = null)
    {
        return  (
                ( ( $comment->state == Comment::STATE_PENDING ) && ($state == Comment::STATE_READ || $state == Comment::STATE_ACCEPTED))
                ||
                (  ( $comment->state == Comment::STATE_READ ) && ($state == Comment::STATE_ACCEPTED))
            ) &&
            $user->channelVideos()->where('id', $comment->vido_id)->count();
    }

    public function deleteComment(User $user, Comment $comment)
    {
        return $user->channelVideos()->where('id', $comment->vido_id)->count();
    }
}
