<?php

namespace App\Http\Controllers;

use App\Http\Requests\tag\CreateTagRequest;
use App\Http\Requests\tag\listTagsRequest;
use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(listTagsRequest $request)
    {
        return TagService::getAllTags($request);
    }

    public function create(CreateTagRequest $request)
    {
        return TagService::createTag($request);
    }

}
