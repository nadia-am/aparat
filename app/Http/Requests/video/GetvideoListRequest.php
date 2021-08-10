<?php

namespace App\Http\Requests\video;

use Illuminate\Foundation\Http\FormRequest;

class GetvideoListRequest extends FormRequest
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
            'republished'=>'nullable|boolean'
        ];
    }
}
