<?php

namespace App\Http\Requests\channel;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialsRequest extends FormRequest
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
            'facebook'=>'nullable|url',
            'twitter'=>'nullable|url',
            'instagram'=>'nullable|url',
            'telegram'=>'nullable|url',
        ];
    }
}
