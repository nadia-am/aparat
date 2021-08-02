<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UploadedBannerId implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $bannerIdExist = file_exists( public_path('videos/tmp/'.$value));
        return $bannerIdExist;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid Banner Id';
    }
}
