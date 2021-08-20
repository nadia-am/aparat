<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;

class CategoryIdRequest implements Rule
{
    const PUBLIC_CATEGORY = 'public';
    const PRIVATE_CATEGORY = 'private';
    const ALL_CATEGORY = 'all';

    private $category_type;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($category_type = self::ALL_CATEGORY)
    {
        $this->category_type = $category_type;
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
        if ($this->category_type == self::PUBLIC_CATEGORY){
            return Category::where('id', $value)->whereNull('user_id')->count();
        }
        if ($this->category_type == self::PRIVATE_CATEGORY){
            return Category::where('id', $value)->where('user_id', auth()->id())->count();
        }
        if ($this->category_type == self::ALL_CATEGORY){
            return Category::where('id', $value)->count();
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid Category Id for '.$this->category_type;
    }
}
