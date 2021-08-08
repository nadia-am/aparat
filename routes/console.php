<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('aparat:clear', function () {

    clear_storage('videos');
    clear_storage('category');
    clear_storage('channel');


    $this->info('Clear uloaded video files');
    $this->info('Clear uloaded category files');
    $this->info('Clear uloaded channel files');
})->purpose('clear all temporary files,...');
