<?php
/**
 * add +98 to first of mobile number
 * @param string $mobile
 * @return string
 */

use Hashids\Hashids;

if (!function_exists('to_valid_mobile_number')){
    function to_valid_mobile_number($mobile){
        return '+98' . substr($mobile,-10,10);
    }
}
if (!function_exists('random_verification_code')){
    function random_verification_code(){
        return rand(10000,99999);
    }
}
if (!function_exists('uniq_id')){
    function uniq_id($value){
        $hashids = new Hashids(env('APP_KEY'), 10); // pad to length 10
        return $hashids->encode($value);
    }
}


