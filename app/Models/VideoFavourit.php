<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoFavourit extends Model
{
    use HasFactory;
    protected $table = 'video_favourites';
    protected $fillable = [ 'user_id', 'video_id' ];
}
