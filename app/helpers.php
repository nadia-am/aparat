<?php
/**
 * add +98 to first of mobile number
 * @param string $mobile
 * @return string
 */

use Hashids\Hashids;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

if (!function_exists('uniqe_id')){
    function uniqe_id($value){
        $hashids = new Hashids(env('APP_KEY'), 10); // pad to length 10
        return $hashids->encode($value) . Str::random(5);
    }
}

if (!function_exists('clear_storage')) {
    function clear_storage($storageName)
    {
        try {
            Storage::disk($storageName)->delete(Storage::disk($storageName)->allFiles());
            foreach (Storage::disk($storageName)->allDirectories() as $disk){
                Storage::disk($storageName)->deleteDirectory($disk);
            }
            return true;
        }catch (Exception $e){
          Log::error($e);
          return false;
        }
    }
}
if (!function_exists('client_ip')) {
    function client_ip($date = false)
    {
        $ip = $_SERVER['HTTP_USER_AGENT'] . '-' . md5($_SERVER['REMOTE_ADDR']);
        if ($date){
            $ip .= '-'. now()->toDateString();
        }
        return $ip;
    }
}

if (!function_exists('sort_comments')) {
    function sort_comments($comments , $parent_id = null)
    {
        $result =[];
        foreach ($comments as $comment){
            if ($comment->parent_id == $parent_id ){
                $data = $comment->toArray();
                $data['childeren'] = sort_comments($comments , $comment->id );
                $result[] = $data;
            }
        }
        return $result;
    }
}




