<?php

namespace App\Http\Requests\channel;

use Illuminate\Foundation\Http\FormRequest;

class ChannelStatisticRequest extends FormRequest
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
            'last_n_days'=>' nullable|in:7,14,30,60'
        ];
    }
}
