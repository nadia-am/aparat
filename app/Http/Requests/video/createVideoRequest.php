<?php

namespace App\Http\Requests\video;

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
            'video_id' => 'required',//TODO video_id validation
            'title' => 'required|string|max:255',
            'category' => 'nullable|exists:categories,id',
            'info' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|exists:tags,id',
            'playList' => 'nullable|exists:playlist,id',
            'channel_category' => 'nullable|',//TODO channel category
            'banner'  =>  'nullable|',//TODO banner must be uploaded before create video
            'published_at' => 'nullable|date'
        ];
    }
}
