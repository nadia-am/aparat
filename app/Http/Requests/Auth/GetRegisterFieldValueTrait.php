<?php


namespace App\Http\Requests\Auth;


trait GetRegisterFieldValueTrait
{
    public function getField()
    {
        return $this->has('email') ? 'email':'mobile';
    }

    public function getFieldValue()
    {
        $field = $this->getField();
        $value = $this->input($this->getField($field));
        if ($field == "mobile"){
            $value = to_valid_mobile_number($value);
        }
        return $value;
    }
}
