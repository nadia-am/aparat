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
    $router->post('/change-password',[
        'as'=>'password.change',
        'uses'=>'App\Http\Controllers\UserController@changePassword'
    ]);
});
/**
 * channel's Route
 * */
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
/**
 * video's Route
 * */
Route::group(['middleware'=>['auth:api'],'prefix'=>'/video'],function ($router){
    $router->post('/upload',[
        'as'=>'video.upload',
        'uses'=>'App\Http\Controllers\VideoController@upload'
    ]);
    $router->post('/upload-banner',[
        'as'=>'video.upload.banner',
        'uses'=>'App\Http\Controllers\VideoController@uploadBanner'
    ]);
    $router->post('/',[
        'as'=>'video.create',
        'uses'=>'App\Http\Controllers\VideoController@create'
    ]);
});
/**
 * category's Route
 * */
Route::group(['middleware'=>['auth:api'],'prefix'=>'/category'],function ($router){
    $router->get('/',[
        'as'=>'category.all',
        'uses'=>'App\Http\Controllers\CategoryController@index'
    ]);
    $router->get('/my',[
        'as'=>'category.all',
        'uses'=>'App\Http\Controllers\CategoryController@my'
    ]);
    $router->post('/',[
        'as'=>'category.create',
        'uses'=>'App\Http\Controllers\CategoryController@create'
    ]);
    $router->post('/upload-banner',[
        'as'=>'category.upload.banner',
        'uses'=>'App\Http\Controllers\CategoryController@uploadBanner'
    ]);

});
/**
 * playlist's Route
 * */
Route::group(['middleware'=>['auth:api'],'prefix'=>'/playlist'],function ($router){
    $router->get('/',[
        'as'=>'playlist.all',
        'uses'=>'App\Http\Controllers\PlaylistController@index'
    ]);
    $router->get('/my',[
        'as'=>'playlist.all',
        'uses'=>'App\Http\Controllers\PlaylistController@my'
    ]);
    $router->post('/',[
        'as'=>'playlist.create',
        'uses'=>'App\Http\Controllers\PlaylistController@create'
    ]);


});
/**
 * tag's Route
 * */
Route::group(['middleware'=>['auth:api'],'prefix'=>'/tag'],function ($router){
    $router->get('/',[
        'as'=>'tag.all',
        'uses'=>'App\Http\Controllers\TagController@index'
    ]);
    $router->post('/',[
        'as'=>'tag.create',
        'uses'=>'App\Http\Controllers\TagController@create'
    ]);


});
