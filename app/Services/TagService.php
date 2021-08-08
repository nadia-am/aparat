<?php


namespace App\Services;


use App\Http\Requests\tag\CreateTagRequest;
use App\Http\Requests\tag\listTagsRequest;
use App\Models\Tag;

class TagService extends BaseService
{

    public static function getAllTags(listTagsRequest $request)
    {
        return Tag::all();
    }

    public static function createTag(CreateTagRequest $request)
    {
        $data = $request->validated();
        $tag = Tag::create($data);
        return $tag;
    }
}
