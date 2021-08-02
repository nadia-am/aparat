<?php

namespace App\Http\Requests\video;

use App\Rules\CategoryIdRequest;
use App\Rules\UploadedBannerId;
use App\Rules\UploadedVideoId;
use Illuminate\Foundation\Http\FormRequest;

class createVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'video_id' => ['required', new UploadedVideoId()],
            'title' => 'required|string|max:255',
            'category' =>['required', new CategoryIdRequest(CategoryIdRequest::PUBLIC_CATEGORY)],
            'info' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|exists:tags,id',
            'playList' => 'nullable|exists:playlist,id',//TODO select user's playlist
            'channel_category' => ['nullable', new CategoryIdRequest(CategoryIdRequest::PRIVATE_CATEGORY)],
            'banner'  =>  ['nullable', new UploadedBannerId()],
            'published_at' => 'nullable|date_format:Y-m-d H:i:s|after:now'
        ];
    }
}
