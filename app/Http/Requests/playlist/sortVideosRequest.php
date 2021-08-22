<?php

namespace App\Http\Requests\playlist;

use App\Rules\sortablePlaylistVideosRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class sortVideosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('sortVideos', $this->playlist);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'videos'=>['required' , new sortablePlaylistVideosRule($this->playlist) ]
        ];
    }
}
