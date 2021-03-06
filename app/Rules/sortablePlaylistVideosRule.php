<?php

namespace App\Rules;

use App\Models\Playlist;
use Illuminate\Contracts\Validation\Rule;

class sortablePlaylistVideosRule implements Rule
{
    /**
     * @var Playlist
     */
    private $playlist;

    /**
     * Create a new rule instance.
     *
     * @param Playlist $playlist
     */
    public function __construct(Playlist $playlist)
    {
        //
        $this->playlist = $playlist;
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
        if (is_array($value)){
            $videos_id = $this->playlist->videos()->pluck('videos.id')->toArray();
            sort($videos_id);
            $value = array_map('intval',$value);
            sort($value);
            return $value== $videos_id;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'video list is not valid for this playlist.';
    }
}
