<?php
/**
 * add +98 to first of mobile number
 * @param string $mobile
 * @return string
 */
function to_valid_mobile_number($mobile){
    return '+98' . substr($mobile,-10,10);
}
