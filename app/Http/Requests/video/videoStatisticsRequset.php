<?php

namespace App\Http\Requests\video;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class videoStatisticsRequset extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('viewStatistics',$this->video);
//        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'from_date'=>'required|date'
            'last_n_days'=>' nullable|in:7,14,30,60'
        ];
    }
}
