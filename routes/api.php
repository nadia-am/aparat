<?php

use Illuminate\Support\Facades\Route;

/**
 * Contain Auth routes
 *
 * */
Route::group([],function ($router){
    $router->group(['namespace'=>'\Laravel\Passport\Http\Controllers'],function ($router){
        $router->post('login',[
            'as'=>'auth.login',
            'middleware'=>['throttle'],
            'uses'=>'AccessTokenController@issueToken'
        ]);
    });
    $router->post('register',[
        'as'=>'auth.register',
        'uses'=>'App\Http\Controllers\Authcontroller@register',
    ]);
    $router->post('register-verify',[
        'as'=>'auth.register.verify',
        'uses'=>'App\Http\Controllers\Authcontroller@registerVerify',
    ]);
    $router->post('resend-verification-code',[
        'as'=>'auth.register.resnd.verification.code',
        'uses'=>'App\Http\Controllers\Authcontroller@resendVerificationCode',
    ]);
});
/**
 * User's Route
 * */
Route::group(['middleware'=>['auth:api']],function ($router){
    $router->post('change-email',[
        'as'=>'change.email',
        'uses'=>'App\Http\Controllers\UserController@changeEmail',
    ]);
    $router->post('change-email-submit',[
        'as'=>'change.email',
        'uses'=>'App\Http\Controllers\UserController@changeEmailSubmit'
    ]);
});

Route::group(['middleware'=>['auth:api'],'prefix'=>'/channel'],function ($router){
    $router->put('/{id?}',[
        'as'=>'channel.update',
        'uses'=>'App\Http\Controllers\ChannelController@update'
    ]);
    $router->post('/',[
        'as'=>'channel.upload.banner',
        'uses'=>'App\Http\Controllers\ChannelController@uploadBanner'
    ]);
    $router->match(['post','put'],'/socials',[
        'as'=>'channel.update.socials',
        'uses'=>'App\Http\Controllers\ChannelController@updatesocials'
    ]);
});
