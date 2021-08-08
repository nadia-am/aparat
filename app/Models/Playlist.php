<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;

    //region model config
    protected $table = 'playlists';
    protected $fillable = ['user_id','title'];
    //endregion

    //region videos
    public function videos()
    {
        return $this->belongsToMany(Video::class,'playlist_videos');
    }
    //endregion

    //region user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //endregion
}
