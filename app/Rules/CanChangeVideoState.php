<?php

namespace App\Rules;

use App\Models\Video;
use Illuminate\Contracts\Validation\Rule;

class CanChangeVideoState implements Rule
{
    /**
     * @var Video
     */
    private $video;

    /**
     * Create a new rule instance.
     *
     * @param Video $video
     */
    public function __construct(Video $video)
    {
        //
        $this->video = $video;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value = null)
    {
        return !empty($this->video) &&
            (
                ( $this->video->state == Video::STATE_CONVERTED && in_array($value , [ Video::STATE_ACCEPTED , Video::STATE_BLOCKED ]) ) ||
                ( $this->video->state == Video::STATE_ACCEPTED && $value == Video::STATE_BLOCKED ) ||
                ( $this->video->state == Video::STATE_BLOCKED && $value == Video::STATE_ACCEPTED )
            );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
