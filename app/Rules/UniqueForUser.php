<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueForUser implements Rule
{
    private $tableName;
    private $columnName  ;
    /**
     * @var null
     */
    private $userId  ;
    /**
     * @var string
     */
    private $userIdField;

    /**
     * Create a new rule instance.
     *
     * @param $tableName
     * @param $columnName
     * @param null $userId
     * @param string $userIdField
     */
    public function __construct($tableName ,$columnName , $userId = null , $userIdField = 'user_id' )
    {
        //
        $this->tableName = $tableName;
        $this->columnName = $columnName;
        $this->userId = $userId == null ?  auth()->id() : $userId;
        $this->userIdField = $userIdField;
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
        $field = !empty($this->columnName) ? $this->columnName : $attribute;
        $count = DB::table($this->tableName)
            ->where( $field,$value )
            ->where( $this->userIdField , $this->userId )
            ->count();

        return $count === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'this value already exist!';
    }
}
