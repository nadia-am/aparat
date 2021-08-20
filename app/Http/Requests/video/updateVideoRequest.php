<?php

namespace App\Http\Requests\video;

use App\Rules\CategoryIdRequest;
use App\Rules\OwnPlayListId;
use App\Rules\UploadedBannerId;
use App\Rules\UploadedVideoId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class updateVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update',$this->video);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'info' => 'nullable|string',
            'tags' => 'nullable|array',
            'category' =>['required', new CategoryIdRequest(CategoryIdRequest::PUBLIC_CATEGORY)],
            'channel_category' => ['nullable', new CategoryIdRequest(CategoryIdRequest::PRIVATE_CATEGORY)],
            'tags.*' => 'nullable|exists:tags,id',
            'enable_comments'=>'boolean',
            'banner'  =>  ['nullable', new UploadedBannerId()]
        ];
    }
}
