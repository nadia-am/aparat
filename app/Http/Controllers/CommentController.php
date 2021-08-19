<?php

namespace App\Http\Controllers;

use App\Http\Requests\comment\ListCommentsRequest;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function index(ListCommentsRequest $request)
    {
        CommentService::getComments($request);
    }
}
