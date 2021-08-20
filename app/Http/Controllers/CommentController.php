<?php

namespace App\Http\Controllers;

use App\Http\Requests\comment\changeCommentStateRequest;
use App\Http\Requests\comment\createCommentsRequest;
use App\Http\Requests\comment\deleteCommentRequest;
use App\Http\Requests\comment\ListCommentsRequest;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function index(ListCommentsRequest $request)
    {
        return CommentService::getComments($request);
    }

    public function create(createCommentsRequest $request)
    {
        return CommentService::create($request);
    }

    public function changeState(changeCommentStateRequest $request)
    {
        return CommentService::changeState($request);
    }
    public function delete(deleteCommentRequest $request)
    {
        return CommentService::delete($request);
    }
}
