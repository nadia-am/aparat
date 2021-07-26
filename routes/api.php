<?php

use App\Http\Controllers\Authcontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['namespace'=>'\Laravel\Passport\Http\Controllers'],function ($router){
    $router->post('login',[
        'as'=>'auth.login',
        'middleware'=>['throttle'],
        'uses'=>'AccessTokenController@issueToken'
    ]);
});
Route::post('register',[
    'as'=>'auth.register',
    'uses'=>'App\Http\Controllers\Authcontroller@register',
]);
Route::post('register-verify',[
    'as'=>'auth.register.verify',
    'uses'=>'App\Http\Controllers\Authcontroller@registerVerify',
]);
Route::post('resend-verification-code',[
    'as'=>'auth.register.resnd.verification.code',
    'uses'=>'App\Http\Controllers\Authcontroller@resendVerificationCode',
]);


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
