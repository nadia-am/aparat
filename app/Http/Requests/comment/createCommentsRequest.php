<?php

namespace App\Http\Requests\comment;

use Illuminate\Foundation\Http\FormRequest;

class createCommentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;//TODO who can create comments
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'video_id'=>'required|exists:videos,id',
            'parent_id'=>'nullable|exists:comments,id',
            'body'=>'required|string|max:1000',

        ];
    }
}
