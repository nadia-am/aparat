<?php


namespace App\Services;

use App\Http\Requests\comment\ListCommentsRequest;
use App\Models\Comment;

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
}
