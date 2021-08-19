<?php

namespace App\Http\Requests\comment;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\In;

class changeCommentStateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('changeCommentState',$this->comment);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'state'=> [ 'required', New In([Comment::STATE_READ,Comment::STATE_ACCEPTED])]
        ];
    }
}
