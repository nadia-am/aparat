<?php


namespace App\Services;

use App\Http\Requests\comment\createCommentsRequest;
use App\Http\Requests\comment\ListCommentsRequest;
use App\Models\Comment;
use App\Models\Video;

class CommentService  extends BaseService
{
    public static function getComments(ListCommentsRequest $request)
    {
        $comments = Comment::channelComments($request->user()->id);
        if ($request->has('state')){
            $comments = $comments->where('comments.state',$request->state);
        }
        return $comments->get();
    }

    public static function create(createCommentsRequest $request)
    {

        $user_id = auth()->id();
        $video = Video::find($request->video_id);
        $comment = $request->user()->comments()->create([
            'video_id'=>$request->video_id,
            'parent_id'=>$request->parent_id,
            'body'=>$request->body,
            'state'=> $video->user_id == $user_id ?
                Comment::STATE_accepted :
                Comment::STATE_PENDING
        ]);
        return $comment;
    }

}
